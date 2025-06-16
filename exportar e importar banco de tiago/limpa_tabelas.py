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

TARGET_SCHEMA = "rotadomar_produtos"
TABLES_TO_CLEAN = [
    "produtos",
    "tecidos",
    "estilistas",
    "status",
    "grupos",
    "marcas",
]
MIN_ID_TO_DELETE_MARCAS = 6  # Excluir IDs maiores que 5 (apenas para marcas)

# --------------------------------------------
# FUNÇÃO PARA EXCLUIR DADOS
# --------------------------------------------
def clean_tables():
    try:
        cx = mysql.connector.connect(**DB_CONFIG)
        cursor = cx.cursor()

        for table in TABLES_TO_CLEAN:
            try:
                # Excluir dados com ID maior que MIN_ID_TO_DELETE (apenas para marcas)
                if table == "marcas":
                    delete_sql = f"DELETE FROM {TARGET_SCHEMA}.{table} WHERE id > %s"
                    cursor.execute(delete_sql, (MIN_ID_TO_DELETE_MARCAS,))
                else:
                    delete_sql = f"DELETE FROM {TARGET_SCHEMA}.{table}"
                    cursor.execute(delete_sql)
                cx.commit()
                print(f"✅ Dados da tabela {table} excluídos com sucesso!")
            except mysql.connector.Error as err:
                print(f"❌ Erro ao excluir dados da tabela {table}: {err}")
                cx.rollback()

        print("\n🎉 Limpeza concluída!")

    except mysql.connector.Error as err:
        print("Erro de conexão:", err)
    finally:
        if 'cursor' in locals() and cursor:
            cursor.close()
        if 'cx' in locals() and cx.is_connected():
            cx.close()

# --------------------------------------------
# EXECUÇÃO
# --------------------------------------------
if __name__ == "__main__":
    clean_tables()