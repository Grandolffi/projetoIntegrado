import psycopg2

def get_connection():
    try:
        conn = psycopg2.connect(
            dbname="projetointegrado",
            user="postgres",
            password="postgres",
            host="localhost",
            port=5432
        )
        print("Conexão com PostgreSQL estabelecida com sucesso.")
        return conn
    except Exception as e:
        print("Erro ao conectar ao PostgreSQL:", e)
        return None
