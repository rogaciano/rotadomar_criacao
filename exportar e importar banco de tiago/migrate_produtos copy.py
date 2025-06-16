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
SOURCE_TABLE = "criacao_referencias"
TARGET_TABLE = "produtos"
BATCH_SIZE = 100               # Reduzido devido às consultas auxiliares
ID_FILE = "ids_produtos_destino.txt"    # Arquivo com IDs do destino

# --------------------------------------------
# FUNÇÕES AUXILIARES PARA TABELAS DE APOIO
# --------------------------------------------
def get_or_create_estilista(cursor, nome):
    """Busca ou cria estilista, retorna ID"""
    if not nome or nome.strip() == "":
        nome = "Sem estilista"
    
    nome = nome.strip()[:255]  # Limita tamanho
    
    # Buscar existente
    cursor.execute(f"SELECT id FROM {TARGET_SCHEMA}.estilistas WHERE nome = %s", (nome,))
    result = cursor.fetchone()
    if result:
        return result[0]
    
    # Criar novo
    cursor.execute(f"INSERT INTO {TARGET_SCHEMA}.estilistas (nome) VALUES (%s)", (nome,))
    return cursor.lastrowid

def get_or_create_status(cursor, descricao):
    """Busca ou cria status, retorna ID"""
    if not descricao or descricao.strip() == "":
        descricao = "Sem status"
    
    descricao = descricao.strip()[:255]  # Limita tamanho
    
    # Buscar existente
    cursor.execute(f"SELECT id FROM {TARGET_SCHEMA}.status WHERE descricao = %s", (descricao,))
    result = cursor.fetchone()
    if result:
        return result[0]
    
    # Criar novo
    cursor.execute(f"INSERT INTO {TARGET_SCHEMA}.status (descricao) VALUES (%s)", (descricao,))
    return cursor.lastrowid

def get_or_create_grupo(cursor, descricao):
    """Busca ou cria grupo, retorna ID"""
    if not descricao or descricao.strip() == "":
        descricao = "Sem grupo"
    
    descricao = descricao.strip()[:255]  # Limita tamanho
    
    # Buscar existente
    cursor.execute(f"SELECT id FROM {TARGET_SCHEMA}.grupos WHERE descricao = %s", (descricao,))
    result = cursor.fetchone()
    if result:
        return result[0]
    
    # Criar novo
    cursor.execute(f"INSERT INTO {TARGET_SCHEMA}.grupos (descricao) VALUES (%s)", (descricao,))
    return cursor.lastrowid

def get_or_create_marca(cursor, nome_marca):
    """Busca ou cria marca, retorna ID"""
    if not nome_marca or nome_marca.strip() == "":
        nome_marca = "Sem marca"
    
    nome_marca = nome_marca.strip()[:255]  # Limita tamanho
    
    # Buscar existente
    cursor.execute(f"SELECT id FROM {TARGET_SCHEMA}.marcas WHERE nome_marca = %s", (nome_marca,))
    result = cursor.fetchone()
    if result:
        return result[0]
    
    # Criar novo
    cursor.execute(f"INSERT INTO {TARGET_SCHEMA}.marcas (nome_marca) VALUES (%s)", (nome_marca,))
    return cursor.lastrowid

def clean_description(description: str | None) -> str:
    """Limpa a descrição, removendo caracteres não-ASCII e preenchendo vazias."""
    if not description:
        return "Sem descrição"
    # Remove caracteres não-ASCII e limita tamanho
    cleaned = ''.join(c for c in description if ord(c) < 128)
    return cleaned[:500] if cleaned else "Sem descrição"

def safe_float(value):
    """Converte valor para float, retorna 0.0 se inválido"""
    if value is None:
        return 0.0
    try:
        return float(value)
    except (ValueError, TypeError):
        return 0.0

def safe_int(value):
    """Converte valor para int, retorna 0 se inválido"""
    if value is None:
        return 0
    try:
        return int(value)
    except (ValueError, TypeError):
        return 0

# --------------------------------------------
# MIGRAÇÃO
# --------------------------------------------
def migrate():
    try:
        cx = mysql.connector.connect(**DB_CONFIG)
        src_cur = cx.cursor(dictionary=True)
        tgt_cur = cx.cursor()

        # 1. Carregar IDs do arquivo (opcional)
        try:
            with open(ID_FILE, "r") as f:
                ids_destino = set(int(line.strip()) for line in f if line.strip())
        except FileNotFoundError:
            print(f"Arquivo {ID_FILE} não encontrado. Assumindo destino vazio.")
            ids_destino = set()

        # Conta total para feedback
        src_cur.execute(f"SELECT COUNT(*) AS total FROM {SOURCE_SCHEMA}.{SOURCE_TABLE}")
        total_rows = src_cur.fetchone()["total"]
        print(f"Total de registros a migrar: {total_rows}")

        offset = 0
        migrados = 0
        erros = 0

        while offset < total_rows:
            # 2. Ler lote de origem
            src_cur.execute(
                f"""
                SELECT 
                    idreferencias, referencia, Descricao, data_cadastro, idtecido,
                    estilista, status, quant, caminho_imagem, caminho_img_catalogo,
                    grupo, atacado, varejo, marca, idgrade
                FROM {SOURCE_SCHEMA}.{SOURCE_TABLE}
                ORDER BY idreferencias
                LIMIT %s OFFSET %s
                """,
                (BATCH_SIZE, offset),
            )
            rows = src_cur.fetchall()
            if not rows:
                break

            # 3. Processar cada registro
            for r in rows:
                try:
                    id_origem = r["idreferencias"]
                    
                    # Pular se já existe no destino
                    if id_origem in ids_destino:
                        print(f"ID {id_origem} já existe no destino. Ignorando.")
                        continue

                    # Obter IDs das tabelas auxiliares
                    estilista_id = get_or_create_estilista(tgt_cur, r["estilista"])
                    status_id = get_or_create_status(tgt_cur, r["status"])
                    grupo_id = get_or_create_grupo(tgt_cur, r["grupo"])
                    marca_id = get_or_create_marca(tgt_cur, r["marca"])

                    # Preparar dados para inserção
                    descricao = clean_description(r["Descricao"])
                    preco_atacado = safe_float(r["atacado"])
                    preco_varejo = safe_float(r["varejo"])
                    quantidade = safe_int(r["quant"])
                    tecido_id = safe_int(r["idtecido"]) if r["idtecido"] else None

                    # 4. Inserir no destino
                    insert_sql = f"""
                        INSERT INTO {TARGET_SCHEMA}.{TARGET_TABLE}
                            (id, referencia, descricao, preco_atacado, preco_varejo, 
                             data_cadastro, anexo_ficha_producao, anexo_catalogo_vendas,
                             marca_id, tecido_id, estilista_id, grupo_id, status_id,
                             created_at, updated_at, deleted_at, quantidade)
                        VALUES
                            (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
                    """

                    dados = (
                        id_origem,                    # id
                        r["referencia"],              # referencia
                        descricao,                    # descricao
                        preco_atacado,                # preco_atacado
                        preco_varejo,                 # preco_varejo
                        r["data_cadastro"],           # data_cadastro
                        r["caminho_imagem"],          # anexo_ficha_producao
                        r["caminho_img_catalogo"],    # anexo_catalogo_vendas
                        marca_id,                     # marca_id
                        tecido_id,                    # tecido_id
                        estilista_id,                 # estilista_id
                        grupo_id,                     # grupo_id
                        status_id,                    # status_id
                        r["data_cadastro"],           # created_at
                        r["data_cadastro"],           # updated_at
                        None,                         # deleted_at
                        quantidade                    # quantidade
                    )

                    tgt_cur.execute(insert_sql, dados)
                    cx.commit()
                    migrados += 1
                    
                    if migrados % 10 == 0:
                        print(f"✔ Migrados: {migrados}")

                except mysql.connector.Error as err:
                    print(f"Erro ao inserir registro ID {r['idreferencias']}: {err}")
                    print(f"Dados: {r}")
                    cx.rollback()
                    erros += 1
                except Exception as e:
                    print(f"Erro geral no registro ID {r['idreferencias']}: {e}")
                    erros += 1

            offset += len(rows)
            print(f"✔ Processados {offset}/{total_rows} | Migrados: {migrados} | Erros: {erros}")

        print(f"\n🎉 Migração concluída!")
        print(f"📊 Total processado: {total_rows}")
        print(f"✅ Migrados com sucesso: {migrados}")
        print(f"❌ Erros: {erros}")

        # --------------------------------------------
        # AJUSTES FINAIS (UPDATEs)
        # --------------------------------------------
        print("\n⚙️ Executando ajustes finais (UPDATEs)...")
        try:
            updates = [
                "UPDATE produtos SET marca_id = 1 WHERE marca_id IN ( 15 )",  # Rota do mar
                "UPDATE produtos SET marca_id = 2 WHERE marca_id IN ( 29, 19, 26  )",  # HAUS
                "UPDATE produtos SET marca_id = 3 WHERE marca_id IN ( 6 )",  # Mitch's
                "UPDATE produtos SET marca_id = 4 WHERE marca_id IN (10, 12, 13, 14, 16, 17, 22, 23, 24, 27, 28, 30 )",  # private label
                "UPDATE produtos SET marca_id = 5 WHERE marca_id IN (7 )",
                "UPDATE produtos SET marca_id = 18 WHERE marca_id IN (20,21,25 )",
                "DELETE FROM marcas WHERE NOT EXISTS ( SELECT id FROM produtos WHERE produtos.marca_id = marcas.id )",

            ]
            for update_sql in updates:
                tgt_cur.execute(update_sql)
                cx.commit()
                print(f"✔ Executado: {update_sql}")
            print("✅ Ajustes finais concluídos!")
        except mysql.connector.Error as err:
            print(f"❌ Erro ao executar updates: {err}")
            cx.rollback()

    except mysql.connector.Error as err:
        print("Erro de conexão:", err)
        if 'cx' in locals():
            cx.rollback()
    finally:
        for cur in ("src_cur", "tgt_cur"):
            if cur in locals() and locals()[cur]:
                locals()[cur].close()
        if "cx" in locals() and cx.is_connected():
            cx.close()

if __name__ == "__main__":
    migrate()