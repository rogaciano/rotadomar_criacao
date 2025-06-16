import datetime as dt
import mysql.connector
from mysql.connector import errorcode

# --------------------------------------------
# CONFIGURAÇÕES GERAIS
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
BATCH_SIZE = 100  # Reduzido para lidar com consultas auxiliares

# Tabelas a serem limpas antes da migração
TABLES_TO_CLEAN = ["produtos", "tecidos", "estilistas", "grupos", "status", "produto_tecido"]
MIN_ID_TO_DELETE_MARCAS = 5  # Preservar marcas com ID <= 5

# --------------------------------------------
# FUNÇÕES AUXILIARES GERAIS
# --------------------------------------------
def clean_description(description: str | None) -> str:
    """Limpa a descrição, removendo caracteres não-ASCII e preenchendo vazias."""
    if not description:
        return "Sem descrição"
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
# FUNÇÕES AUXILIARES PARA TABELAS DE APOIO (PRODUTOS)
# --------------------------------------------
def get_or_create_estilista(cursor, nome):
    """Busca ou cria estilista, retorna ID"""
    if not nome or nome.strip() == "":
        nome = "Sem estilista"
    nome = nome.strip()[:255]  # Limita tamanho
    cursor.execute(f"SELECT id FROM {TARGET_SCHEMA}.estilistas WHERE nome_estilista = %s", (nome,))
    result = cursor.fetchone()
    if result:
        return result[0]
    cursor.execute(f"INSERT INTO {TARGET_SCHEMA}.estilistas (nome_estilista) VALUES (%s)", (nome,))
    return cursor.lastrowid

def get_or_create_status(cursor, descricao):
    """Busca ou cria status, retorna ID"""
    if not descricao or descricao.strip() == "":
        descricao = "Sem status"
    descricao = descricao.strip()[:255]  # Limita tamanho
    cursor.execute(f"SELECT id FROM {TARGET_SCHEMA}.status WHERE descricao = %s", (descricao,))
    result = cursor.fetchone()
    if result:
        return result[0]
    cursor.execute(f"INSERT INTO {TARGET_SCHEMA}.status (descricao) VALUES (%s)", (descricao,))
    return cursor.lastrowid

def get_or_create_grupo(cursor, descricao):
    """Busca ou cria grupo, retorna ID"""
    if not descricao or descricao.strip() == "":
        descricao = "Sem grupo"
    descricao = descricao.strip()[:255]  # Limita tamanho
    cursor.execute(f"SELECT id FROM {TARGET_SCHEMA}.grupos WHERE descricao = %s", (descricao,))
    result = cursor.fetchone()
    if result:
        return result[0]
    cursor.execute(f"INSERT INTO {TARGET_SCHEMA}.grupos (descricao) VALUES (%s)", (descricao,))
    return cursor.lastrowid

def get_or_create_marca(cursor, nome_marca):
    """Busca ou cria marca, retorna ID"""
    if not nome_marca or nome_marca.strip() == "":
        nome_marca = "Sem marca"
    nome_marca = nome_marca.strip()[:255]  # Limita tamanho
    cursor.execute(f"SELECT id FROM {TARGET_SCHEMA}.marcas WHERE nome_marca = %s", (nome_marca,))
    result = cursor.fetchone()
    if result:
        return result[0]
    cursor.execute(f"INSERT INTO {TARGET_SCHEMA}.marcas (nome_marca) VALUES (%s)", (nome_marca,))
    return cursor.lastrowid


# --------------------------------------------
# FUNÇÕES AUXILIARES PARA MOVIMENTAÇÕES
# --------------------------------------------
def get_or_create_produto_id(cursor, referencia):
    """Busca produto pela referência, retorna ID ou None se não encontrar"""
    if not referencia or referencia.strip() == "":
        return None
    
    referencia = referencia.strip()[:255]  # Limita tamanho
    cursor.execute(f"SELECT id FROM {TARGET_SCHEMA}.produtos WHERE referencia = %s LIMIT 1", (referencia,))
    result = cursor.fetchone()
    
    if result:
        return result[0]
    return None  # Não criamos produtos automaticamente


def get_or_create_localizacao_id(cursor, nome_localizacao):
    """Busca ou cria localização, retorna ID"""
    if not nome_localizacao or nome_localizacao.strip() == "":
        nome_localizacao = "Sem localização"
    
    nome_localizacao = nome_localizacao.strip()[:255]  # Limita tamanho
    cursor.execute(f"SELECT id FROM {TARGET_SCHEMA}.localizacoes WHERE nome_localizacao = %s LIMIT 1", (nome_localizacao,))
    result = cursor.fetchone()
    
    if result:
        return result[0]
    
    # Se não encontrar, criar nova localização
    cursor.execute(
        f"INSERT INTO {TARGET_SCHEMA}.localizacoes (nome_localizacao, created_at, updated_at) VALUES (%s, NOW(), NOW())",
        (nome_localizacao,)
    )
    new_id = cursor.lastrowid
    print(f"✅ Nova localização criada: {nome_localizacao} (ID: {new_id})")
    return new_id


def get_or_create_tipo_id(cursor, descricao):
    """Busca ou cria tipo, retorna ID"""
    # Se for um inteiro, buscar pelo ID diretamente
    if isinstance(descricao, int):
        cursor.execute(f"SELECT id FROM {TARGET_SCHEMA}.tipos WHERE id = %s LIMIT 1", (descricao,))
        result = cursor.fetchone()
        if result:
            return result[0]
        # Se não encontrar pelo ID, criar um novo com descrição padrão
        descricao_str = f"Tipo {descricao}"
    else:
        # Se for string, tratar normalmente
        if not descricao or str(descricao).strip() == "":
            descricao_str = "Sem tipo"
        else:
            descricao_str = str(descricao).strip()[:255]  # Limita tamanho
    
    # Buscar pelo texto
    cursor.execute(f"SELECT id FROM {TARGET_SCHEMA}.tipos WHERE descricao = %s LIMIT 1", (descricao_str,))
    result = cursor.fetchone()
    
    if result:
        return result[0]
    
    # Se não encontrar, criar novo tipo
    cursor.execute(
        f"INSERT INTO {TARGET_SCHEMA}.tipos (descricao, created_at, updated_at) VALUES (%s, NOW(), NOW())",
        (descricao_str,)
    )
    new_id = cursor.lastrowid
    print(f"✅ Novo tipo criado: {descricao_str} (ID: {new_id})")
    return new_id


def get_or_create_situacao_id(cursor, descricao):
    """Busca ou cria situação, retorna ID"""
    # Se for um inteiro, buscar pelo ID diretamente
    if isinstance(descricao, int):
        cursor.execute(f"SELECT id FROM {TARGET_SCHEMA}.situacoes WHERE id = %s LIMIT 1", (descricao,))
        result = cursor.fetchone()
        if result:
            return result[0]
        # Se não encontrar pelo ID, criar um novo com descrição padrão
        descricao_str = f"Situação {descricao}"
    else:
        # Se for string, tratar normalmente
        if not descricao or str(descricao).strip() == "":
            descricao_str = "Sem situação"
        else:
            descricao_str = str(descricao).strip()[:255]  # Limita tamanho
    
    # Buscar pelo texto
    cursor.execute(f"SELECT id FROM {TARGET_SCHEMA}.situacoes WHERE descricao = %s LIMIT 1", (descricao_str,))
    result = cursor.fetchone()
    
    if result:
        return result[0]
    
    # Se não encontrar, criar nova situação
    cursor.execute(
        f"INSERT INTO {TARGET_SCHEMA}.situacoes (descricao, created_at, updated_at) VALUES (%s, NOW(), NOW())",
        (descricao_str,)
    )
    new_id = cursor.lastrowid
    print(f"✅ Nova situação criada: {descricao_str} (ID: {new_id})")
    return new_id


# --------------------------------------------
# FUNÇÃO PARA EXCLUIR DADOS
# --------------------------------------------
def clean_tables():
    try:
        cx = mysql.connector.connect(**DB_CONFIG)
        cursor = cx.cursor()

        print("\n--- LIMPANDO TABELAS ---")
        
        # Primeiro, executar os DELETEs
        delete_statements = [
            f"DELETE FROM {TARGET_SCHEMA}.produtos;",
            f"DELETE FROM {TARGET_SCHEMA}.grupos;",
            f"DELETE FROM {TARGET_SCHEMA}.marcas;",
            f"DELETE FROM {TARGET_SCHEMA}.`status`;",
            f"DELETE FROM {TARGET_SCHEMA}.estilistas;",
            f"DELETE FROM {TARGET_SCHEMA}.produto_tecido;"
        ]
        
        for stmt in delete_statements:
            try:
                cursor.execute(stmt)
                cx.commit()
                print(f"✅ Executado: {stmt}")
            except mysql.connector.Error as err:
                print(f"❌ Erro ao executar: {stmt} - {err}")
                cx.rollback()
        
        # Depois, desativar verificação de chaves estrangeiras e fazer TRUNCATE
        try:
            cursor.execute("SET FOREIGN_KEY_CHECKS = 0;")
            print("✅ Desativada verificação de chaves estrangeiras")
            
            truncate_statements = [
                f"TRUNCATE TABLE {TARGET_SCHEMA}.produtos;",
                f"TRUNCATE TABLE {TARGET_SCHEMA}.grupos;",
                f"TRUNCATE TABLE {TARGET_SCHEMA}.marcas;",
                f"TRUNCATE TABLE {TARGET_SCHEMA}.`status`;",
                f"TRUNCATE TABLE {TARGET_SCHEMA}.estilistas;",
                f"TRUNCATE TABLE {TARGET_SCHEMA}.produto_tecido;"
            ]
            
            for stmt in truncate_statements:
                try:
                    cursor.execute(stmt)
                    cx.commit()
                    print(f"✅ Executado: {stmt}")
                except mysql.connector.Error as err:
                    print(f"❌ Erro ao executar: {stmt} - {err}")
                    cx.rollback()
                    
            # Reativar verificação de chaves estrangeiras
            cursor.execute("SET FOREIGN_KEY_CHECKS = 1;")
            print("✅ Reativada verificação de chaves estrangeiras")
            
        except mysql.connector.Error as err:
            print(f"❌ Erro ao manipular chaves estrangeiras: {err}")
            cx.rollback()

        print("\n🎉 Limpeza concluída!")

    except mysql.connector.Error as err:
        print("Erro de conexão:", err)
    finally:
        if 'cursor' in locals() and cursor:
            cursor.close()
        if 'cx' in locals() and cx.is_connected():
            cx.close()

# -------------------

# --------------------------------------------
# MIGRAÇÃO DE TECIDOS
# --------------------------------------------
def migrate_tecidos(cx):
    SOURCE_TABLE = "tecidos"
    TARGET_TABLE = "tecidos"
    ID_FILE = "ids_tecidos_destino.txt"
    
    try:
        src_cur = cx.cursor(dictionary=True)
        tgt_cur = cx.cursor()

        # 1. Carregar IDs do arquivo (opcional)
        try:
            with open(ID_FILE, "r") as f:
                ids_destino = set(int(line.strip()) for line in f)
        except FileNotFoundError:
            print(f"Arquivo {ID_FILE} não encontrado. Assumindo destino vazio.")
            ids_destino = set()

        # Conta total para feedback
        src_cur.execute(f"SELECT COUNT(*) AS total FROM {SOURCE_SCHEMA}.{SOURCE_TABLE}")
        total_rows = src_cur.fetchone()["total"]
        print(f"Total de tecidos a migrar: {total_rows}")

        offset = 0
        migrados = 0
        ignorados = 0
        erros = 0

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

            # 3. Processar cada registro individualmente
            for r in rows:
                try:
                    id_origem = r["idtecidos"]
                    ativo_val = 1 if r["status"] == 'Ativo' else 0
                    descricao = clean_description(r["descricao"])

                    # Pular se já existe no controle de IDs
                    if id_origem in ids_destino:
                        print(f"Tecido ID {id_origem} já existe no controle. Ignorando.")
                        ignorados += 1
                        continue

                    # 4. Inserir no destino
                    insert_sql = f"""
                        INSERT INTO {TARGET_SCHEMA}.{TARGET_TABLE}
                            (id, descricao, referencia, ativo, created_at, updated_at, deleted_at)
                        VALUES
                            (%s, %s, %s, %s, %s, %s, %s)
                    """

                    dados = (
                        id_origem,          # id
                        descricao,          # descricao
                        None,               # referencia
                        ativo_val,          # ativo
                        r["data_cadastro"], # created_at
                        r["data_cadastro"], # updated_at
                        None                # deleted_at
                    )

                    tgt_cur.execute(insert_sql, dados)
                    cx.commit()
                    migrados += 1

                    if migrados % 50 == 0:
                        print(f"✔ Migrados: {migrados} tecidos")

                except mysql.connector.IntegrityError as err:
                    # Tratamento específico para erro de chave primária
                    if "Duplicate entry" in str(err) and "PRIMARY" in str(err):
                        print(f"⚠️ Tecido ID {id_origem} já existe na tabela. Ignorando e continuando...")
                        ignorados += 1
                        continue
                    else:
                        print(f"❌ Erro de integridade no tecido ID {id_origem}: {err}")
                        erros += 1
                        continue

                except mysql.connector.Error as err:
                    print(f"❌ Erro MySQL no tecido ID {id_origem}: {err}")
                    cx.rollback()
                    erros += 1
                    continue

                except Exception as e:
                    print(f"❌ Erro geral no tecido ID {id_origem}: {e}")
                    erros += 1
                    continue

            offset += len(rows)
            print(f"✔ Processados {offset}/{total_rows} tecidos | Migrados: {migrados} | Ignorados: {ignorados} | Erros: {erros}")

        print(f"\n🎉 Migração de tecidos concluída!")
        print(f"📊 Total processado: {total_rows}")
        print(f"✅ Migrados com sucesso: {migrados}")
        print(f"⚠️ Ignorados (duplicados): {ignorados}")
        print(f"❌ Erros: {erros}")

    except mysql.connector.Error as err:
        print("Erro na migração de tecidos:", err)
        cx.rollback()
    finally:
        if 'src_cur' in locals() and src_cur:
            src_cur.close()
        if 'tgt_cur' in locals() and tgt_cur:
            tgt_cur.close()
# --------------------------------------------
# MIGRAÇÃO DE PRODUTOS
# --------------------------------------------
def migrate_produtos(cx):
    SOURCE_TABLE = "criacao_referencias"
    TARGET_TABLE = "produtos"
    ID_FILE = "ids_produtos_destino.txt"

    try:
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
        print(f"Total de produtos a migrar: {total_rows}")

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
                        print(f"Produto ID {id_origem} já existe no destino. Ignorando.")
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
                        print(f"✔ Migrados: {migrados} produtos")

                except mysql.connector.Error as err:
                    print(f"Erro ao inserir produto ID {r['idreferencias']}: {err}")
                    print(f"Dados: {r}")
                    cx.rollback()
                    erros += 1
                except Exception as e:
                    print(f"Erro geral no produto ID {r['idreferencias']}: {e}")
                    erros += 1

            offset += len(rows)
            print(f"✔ Processados {offset}/{total_rows} produtos | Migrados: {migrados} | Erros: {erros}")

        print(f"\n🎉 Migração de produtos concluída!")
        print(f"📊 Total processado: {total_rows}")
        print(f"✅ Migrados com sucesso: {migrados}")
        print(f"❌ Erros: {erros}")

        # --------------------------------------------
        # AJUSTES FINAIS (UPDATEs)
        # --------------------------------------------
        print("\n⚙️ Executando ajustes finais de marca em produtos...")
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
        print("Erro na migração de produtos:", err)
        cx.rollback()
    finally:
        if 'src_cur' in locals() and src_cur:
            src_cur.close()
        if 'tgt_cur' in locals() and tgt_cur:
            tgt_cur.close()

# --------------------------------------------
# MIGRAÇÃO DE PRODUTO_TECIDO
# --------------------------------------------
def migrate_produto_tecido(cx):
    try:
        src_cur = cx.cursor(dictionary=True)
        tgt_cur = cx.cursor()

        print("\n--- INICIANDO MIGRAÇÃO DE PRODUTO_TECIDO ---")

        # Primeiro, vamos verificar a estrutura da tabela produto_tecido
        tgt_cur.execute(f"DESCRIBE {TARGET_SCHEMA}.produto_tecido")
        columns = tgt_cur.fetchall()
        print("Estrutura da tabela produto_tecido:")
        for col in columns:
            print(f"  - {col}")

        # Consulta para obter os relacionamentos entre produtos e tecidos da tabela de origem
        src_cur.execute(
            f"""
            SELECT idreferencias, idtecido
            FROM {SOURCE_SCHEMA}.criacao_referencias
            WHERE idtecido IS NOT NULL AND idtecido > 0
            """
        )
        rows = src_cur.fetchall()
        total_rows = len(rows)
        print(f"Total de relacionamentos produto-tecido a migrar: {total_rows}")

        # Processar cada relacionamento
        migrados = 0
        erros = 0

        for r in rows:
            try:
                produto_id = r["idreferencias"]
                tecido_id = r["idtecido"]

                # Verificar se o produto e o tecido existem nas tabelas de destino
                tgt_cur.execute(f"SELECT id FROM {TARGET_SCHEMA}.produtos WHERE id = %s", (produto_id,))
                produto_existe = tgt_cur.fetchone()

                tgt_cur.execute(f"SELECT id FROM {TARGET_SCHEMA}.tecidos WHERE id = %s", (tecido_id,))
                tecido_existe = tgt_cur.fetchone()

                if produto_existe and tecido_existe:
                    # Verificar se o relacionamento já existe
                    tgt_cur.execute(
                        f"SELECT * FROM {TARGET_SCHEMA}.produto_tecido WHERE produto_id = %s AND tecido_id = %s",
                        (produto_id, tecido_id)
                    )
                    if not tgt_cur.fetchone():
                        # Inserir o relacionamento COM o campo consumo
                        tgt_cur.execute(
                            f"""INSERT INTO {TARGET_SCHEMA}.produto_tecido 
                               (produto_id, tecido_id, consumo) 
                               VALUES (%s, %s, %s)""",
                            (produto_id, tecido_id, 0.0)  # Valor padrão para consumo
                        )
                        cx.commit()
                        migrados += 1
                    else:
                        print(f"Relacionamento Produto {produto_id} - Tecido {tecido_id} já existe. Ignorando.")
                else:
                    if not produto_existe:
                        print(f"⚠️ Produto ID {produto_id} não encontrado no destino. Ignorando.")
                    if not tecido_existe:
                        print(f"⚠️ Tecido ID {tecido_id} não encontrado no destino. Ignorando.")

                if migrados % 50 == 0 and migrados > 0:
                    print(f"✔ Migrados: {migrados} relacionamentos produto-tecido")

            except mysql.connector.IntegrityError as err:
                if "Duplicate entry" in str(err):
                    print(f"⚠️ Relacionamento duplicado (Produto: {produto_id}, Tecido: {tecido_id}). Ignorando...")
                    continue
                else:
                    print(f"❌ Erro de integridade no relacionamento (Produto: {produto_id}, Tecido: {tecido_id}): {err}")
                    erros += 1
                    continue

            except mysql.connector.Error as err:
                print(f"❌ Erro MySQL no relacionamento (Produto: {produto_id}, Tecido: {tecido_id}): {err}")
                cx.rollback()
                erros += 1
                continue

            except Exception as e:
                print(f"❌ Erro geral no relacionamento (Produto: {produto_id}, Tecido: {tecido_id}): {e}")
                erros += 1
                continue

        print(f"\n🎉 Migração de produto_tecido concluída!")
        print(f"📊 Total processado: {total_rows}")
        print(f"✅ Migrados com sucesso: {migrados}")
        print(f"❌ Erros: {erros}")

    except mysql.connector.Error as err:
        print("Erro na migração de produto_tecido:", err)
        cx.rollback()
    finally:
        if 'src_cur' in locals() and src_cur:
            src_cur.close()
        if 'tgt_cur' in locals() and tgt_cur:
            tgt_cur.close()

# --------------------------------------------
# MIGRAÇÃO DE MOVIMENTAÇÕES
# --------------------------------------------
def migrate_movimentacoes(cx):
    SOURCE_TABLE = "ppcp_mov_piloto"
    TARGET_TABLE = "movimentacoes"
    ID_FILE = "ids_movimentacoes_destino.txt"
    
    try:
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
        print(f"Total de movimentações a migrar: {total_rows}")

        offset = 0
        migrados = 0
        erros = 0
        ignorados = 0

        while offset < total_rows:
            # 2. Ler lote de origem
            src_cur.execute(
                f"""
                SELECT 
                    idppcp_mov_piloto, referencia, segmento, localizacao, marca,
                    comprometido, tipo, situacao, entrada, saida, observacao
                FROM {SOURCE_SCHEMA}.{SOURCE_TABLE}
                ORDER BY idppcp_mov_piloto
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
                    id_origem = r["idppcp_mov_piloto"]
                    
                    # Pular se já existe no destino
                    if id_origem in ids_destino:
                        print(f"Movimentação ID {id_origem} já existe no destino. Ignorando.")
                        ignorados += 1
                        continue

                    # Usar funções auxiliares para buscar IDs
                    produto_id = get_or_create_produto_id(tgt_cur, r["referencia"])
                    if not produto_id:
                        print(f"⚠️ Produto com referência '{r['referencia']}' não encontrado. Ignorando movimentação ID {id_origem}.")
                        ignorados += 1
                        continue
                    
                    # Buscar ou criar IDs para localização, tipo e situação
                    try:
                        localizacao_id = get_or_create_localizacao_id(tgt_cur, r["localizacao"])
                        tipo_id = get_or_create_tipo_id(tgt_cur, r["tipo"])
                        situacao_id = get_or_create_situacao_id(tgt_cur, r["situacao"])
                    except Exception as e:
                        print(f"❌ Erro ao buscar/criar IDs para movimentação ID {id_origem}: {e}")
                        print(f"Dados: {r}")
                        erros += 1
                        continue

                    # Converter valores de comprometido
                    comprometido = 1 if r["comprometido"] and r["comprometido"].lower() in ['sim', 'yes', '1', 'true'] else 0

                    # 4. Inserir no destino
                    insert_sql = f"""
                        INSERT INTO {TARGET_SCHEMA}.{TARGET_TABLE}
                            (id, produto_id, localizacao_id, tipo_id, situacao_id, 
                             data_entrada, data_saida, observacao, comprometido,
                             created_at, updated_at, deleted_at)
                        VALUES
                            (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
                    """

                    # Preparar datas
                    now = dt.datetime.now()
                    
                    dados = (
                        id_origem,                    # id
                        produto_id,                   # produto_id
                        localizacao_id,               # localizacao_id
                        tipo_id,                      # tipo_id
                        situacao_id,                  # situacao_id
                        r["entrada"] or now,          # data_entrada
                        r["saida"],                   # data_saida
                        r["observacao"],              # observacao
                        comprometido,                 # comprometido
                        now,                          # created_at
                        now,                          # updated_at
                        None                          # deleted_at
                    )

                    tgt_cur.execute(insert_sql, dados)
                    cx.commit()
                    migrados += 1

                    if migrados % 50 == 0:
                        print(f"✔ Migrados: {migrados} movimentações")

                except mysql.connector.IntegrityError as err:
                    # Tratamento específico para erro de chave primária
                    if "Duplicate entry" in str(err) and "PRIMARY" in str(err):
                        print(f"⚠️ Movimentação ID {id_origem} já existe na tabela. Ignorando e continuando...")
                        ignorados += 1
                        continue
                    else:
                        print(f"❌ Erro de integridade na movimentação ID {id_origem}: {err}")
                        erros += 1
                        continue

                except mysql.connector.Error as err:
                    print(f"❌ Erro MySQL na movimentação ID {id_origem}: {err}")
                    cx.rollback()
                    erros += 1
                    continue

                except Exception as e:
                    print(f"❌ Erro geral na movimentação ID {id_origem}: {e}")
                    erros += 1
                    continue

            offset += len(rows)
            print(f"✔ Processados {offset}/{total_rows} movimentações | Migrados: {migrados} | Ignorados: {ignorados} | Erros: {erros}")

            # se passar de 100 erros sair da funçao ou ignrados maior que 100 sair da funçao\
            if erros > 100:
                print(f"❌ Erros: {erros}")
                break

        print(f"\n🎉 Migração de movimentações concluída!")
        print(f"📊 Total processado: {total_rows}")
        print(f"✅ Migrados com sucesso: {migrados}")
        print(f"⚠️ Ignorados (duplicados ou sem correspondência): {ignorados}")
        print(f"❌ Erros: {erros}")

    except mysql.connector.Error as err:
        print("Erro na migração de movimentações:", err)
        cx.rollback()
    finally:
        if 'src_cur' in locals() and src_cur:
            src_cur.close()
        if 'tgt_cur' in locals() and tgt_cur:
            tgt_cur.close()

# --------------------------------------------
# FUNÇÃO PRINCIPAL (EXECUÇÃO)
# --------------------------------------------
def main():
    try:
        print("\n=== INICIANDO MIGRAÇÃO DE DADOS ===\n")
        
        # Conectar ao banco de dados
        print("Conectando ao banco de dados...")
        cx = mysql.connector.connect(**DB_CONFIG)
        print("✅ Conexão estabelecida!")
        
        
        # Limpar tabelas antes da migração
        print("\n--- LIMPANDO TABELAS ---")
        
        # clean_tables()
        
        # Reconectar ao banco de dados específico após limpeza
        cx = mysql.connector.connect(**DB_CONFIG, database=TARGET_SCHEMA)
        
        # Executar migrações
        # print("\n--- INICIANDO MIGRAÇÃO DE TECIDOS ---")
        # migrate_tecidos(cx)
        
        # print("\n--- INICIANDO MIGRAÇÃO DE PRODUTOS ---")
        # migrate_produtos(cx)
        
        # print("\n--- INICIANDO MIGRAÇÃO DE PRODUTO_TECIDO ---")
        # migrate_produto_tecido(cx)
        
        print("\n--- INICIANDO MIGRAÇÃO DE MOVIMENTAÇÕES ---")
        migrate_movimentacoes(cx)  # Nova função de migração
        
        print("\n🎉 MIGRAÇÃO CONCLUÍDA COM SUCESSO! 🎉\n")
        
    except mysql.connector.Error as err:
        if err.errno == errorcode.ER_ACCESS_DENIED_ERROR:
            print("❌ Erro: usuário ou senha incorretos")
        elif err.errno == errorcode.ER_BAD_DB_ERROR:
            print(f"❌ Erro: banco de dados {TARGET_SCHEMA} não existe")
        else:
            print(f"❌ Erro: {err}")
    finally:
        if 'cx' in locals() and cx.is_connected():
            cx.close()
            print("Conexão fechada.")

if __name__ == "__main__":
    main()