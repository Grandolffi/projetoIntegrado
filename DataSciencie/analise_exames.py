import pandas as pd
from db_connection import get_connection

# 1. Criar conexão com o banco
conn = get_connection()
if conn is None:
    exit()

# 2. Definir as querys 


# Query 1 — tabela completa (dados brutos)
query_all = "SELECT * FROM resultados_exames;"

# Query 2 — somente valores numéricos
query_numeric = """
SELECT *
FROM resultados_exames
WHERE absolute_value ~ '^[0-9]';
"""

# Query 3 — histórico de um paciente específico (ex: paciente 5)
query_paciente = """
SELECT *
FROM resultados_exames
WHERE patient_id_fk = 5
ORDER BY exam_date_time;
"""

# Query 4 — pivot (transformar vários exames em 1 linha por paciente)
query_pivot = """
SELECT 
    patient_id_fk AS patient_id,
    MAX(CASE WHEN exam_name ILIKE '%glucose%' THEN absolute_value END) AS glicose,
    MAX(CASE WHEN exam_name ILIKE '%cholesterol%' THEN absolute_value END) AS colesterol_total,
    MAX(CASE WHEN exam_name ILIKE '%triglycer%' THEN absolute_value END) AS triglicerideos,
    MAX(CASE WHEN exam_name ILIKE '%urea%' THEN absolute_value END) AS ureia,
    MAX(CASE WHEN exam_name ILIKE '%creatinine%' THEN absolute_value END) AS creatinina,
    MAX(CASE WHEN exam_name ILIKE '%tsh%' THEN absolute_value END) AS tsh
FROM resultados_exames
GROUP BY patient_id_fk
ORDER BY patient_id_fk;
"""


# 3. Ler dados para testar tudo


print("\n=== Teste: lendo dados brutos ===")
df_all = pd.read_sql(query_all, conn)
print(df_all.head())

print("\n=== Teste: lendo apenas exames numéricos ===")
df_num = pd.read_sql(query_numeric, conn)
print(df_num.head())

print("\n=== Teste: lendo histórico do paciente 5 ===")
df_pac = pd.read_sql(query_paciente, conn)
print(df_pac.head())

print("\n=== Teste: lendo PIVOT (dataset ideal para DS) ===")
df_pivot = pd.read_sql(query_pivot, conn)
print(df_pivot.head())

# --------------------------------------------------------------
# 4. Fechar conexão
# --------------------------------------------------------------
conn.close()
print("\nConexão encerrada com sucesso.")
