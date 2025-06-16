import datetime as dt
import mysql.connector
from mysql.connector import errorcode

# --------------------------------------------
# CONFIGURAÇÕES
# --------------------------------------------
DB_CONFIG = {
    "user":     "root",
    "password": "",  # Senha vazia
    "host":     "127.0.0.1",
    "raise_on_warnings": True,
    "autocommit": False,        # habilite transações
}

SOURCE_SCHEMA = "remessafaccoes"
TARGET_SCHEMA = "rotadomar_produtos"
SOURCE_TABLE = "tecidos"
TARGET_TABLE = "tecidos"
BATCH_SIZE = 1000              # lê e grava em lotes
ID_FILE = "ids_destino.txt"    # Arquivo com IDs do destino

# --------------------------------------------
# CONVERSÕES UTILITÁRIAS
# --------------------------------------------
def status_to_ativo(status_val: str | int | None) -> int | None:
    """
    Converte o campo STATUS da origem para o campo ativo da tabela destino.
    Personalize para a regra de negócio real.
    """
    if status_val is None:
        return None
    if isinstance(status_val, str):
        return 1 if status_val.upper().startswith(("A", "1", "T")) else 0
    return int(bool(status_val))

def clean_description(description: str | None) -> str:
    """
    Limpa a descrição, removendo caracteres não-ASCII e preenchendo vazias.
    """
    if not description:
        return "Sem descrição"
    # Remove caracteres não-ASCII
    return ''.join(c for c in description if ord(c) < 128)

# --------------------------------------------
# MIGRAÇÃO
# --------------------------------------------
def migrate():
    try:
        cx = mysql.connector.connect(**DB_CONFIG)
        src_cur = cx.cursor(dictionary=True)
        tgt_cur = cx.cursor()

        # 1. Carregar IDs do arquivo
        try:
            with open(ID_FILE, "r") as f:
                ids_destino = set(int(line.strip()) for line in f)
        except FileNotFoundError:
            print(f"Arquivo {ID_FILE} não encontrado. Assumindo destino vazio.")
            ids_destino = set()

        # Conta total para feedback
        src_cur.execute(f"SELECT COUNT(*) AS total FROM {SOURCE_SCHEMA}.{SOURCE_TABLE}")
        total_rows = src_cur.fetchone()["total"]
        print(f"Total de registros a migrar: {total_rows}")

        offset = 0
        while offset < total_rows:
            # 2. Ler lote de origem
            src_cur.execute(
                f"""
                SELECT idtecidos, descricao, data_cadastro, STATUS
                FROM {SOURCE_SCHEMA}.{SOURCE_TABLE}
                ORDER BY idtecidos
                LIMIT %s OFFSET %s
                """,
                (BATCH_SIZE, offset),
            )
            rows = src_cur.fetchall()
            if not rows:
                break

            # 3. Preparar dados para inserção
            payload = []
            ids_faltantes = []
            for r in rows:
                id_origem = r["idtecidos"]
                ativo_val = 1 if r["STATUS"] == 'ativo' else 0  # Conversão direta
                descricao = clean_description(r["descricao"])

                if id_origem not in ids_destino:
                    payload.append((
                        id_origem,          # id   (ou None se auto-incremento)
                        descricao,
                        None,                    # referencia (ajuste se tiver regra)
                        ativo_val,
                        r["data_cadastro"],      # created_at
                        r["data_cadastro"],      # updated_at (inicialmente igual)
                        None                     # deleted_at
                    ))
                    ids_faltantes.append(id_origem)
                else:
                    print(f"ID {id_origem} já existe no destino. Ignorando.")

            # 4. Inserir no destino
            insert_sql = f"""
                INSERT INTO {TARGET_SCHEMA}.{TARGET_TABLE}
                    (id, descricao, referencia, ativo, created_at, updated_at, deleted_at)
                VALUES
                    (%s, %s, %s, %s, %s, %s, %s)
            """

            try:
                tgt_cur.executemany(insert_sql, payload)
                cx.commit()
                print(f"Inseridos {len(payload)} novos registros.")
            except mysql.connector.Error as err:
                print(f"Erro ao inserir lote: {err}")
                cx.rollback()
                # Imprimir os dados que causaram o erro
                for data in payload:
                    try:
                        tgt_cur.execute(insert_sql, (data,))
                        cx.commit()
                    except mysql.connector.Error as e:
                        print(f"Erro ao inserir registro individual: {e} - Dados: {data}")
                break  # Parar após o primeiro erro

            offset += len(rows)
            print(f"✔  Processados {offset}/{total_rows}")

            # Logar IDs faltantes (opcional)
            if ids_faltantes:
                print(f"IDs faltantes no destino (lote atual): {ids_faltantes}")

        print("Migração concluída com sucesso!")

    except mysql.connector.Error as err:
        print("Erro:", err)
        cx.rollback()
    finally:
        for cur in ("src_cur", "tgt_cur"):
            if cur in locals() and locals()[cur]:
                locals()[cur].close()
        if "cx" in locals() and cx.is_connected():
            cx.close()

if __name__ == "__main__":
    migrate()