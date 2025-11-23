import pandas as pd
from sqlalchemy import create_engine

# ======================
# 1. Conexão via SQLAlchemy (SEM WARNINGS)
# ======================
engine = create_engine("postgresql+psycopg2://postgres:postgres@localhost:5432/projetointegrado")

# ======================
# 2. Query: somente exames numéricos
# ======================

query_numeric = r"""
SELECT *
FROM resultados_exames
WHERE valor_absoluto ~ '^\s*[0-9]';
"""

df = pd.read_sql(query_numeric, engine)

# ======================
# 3. Separar valor numérico e unidade
# ======================
df[['valor_num', 'unidade']] = df['valor_absoluto'].str.extract(
    r'([0-9]+(?:\.[0-9]+)?)\s*([a-zA-Z\/]+)?'
)
df['valor_num'] = df['valor_num'].astype(float)

# ======================
# 4. Padronizar datas
# ======================
df['data_hora_exame'] = (
    pd.to_datetime(df['data_hora_exame'])
      .dt.tz_localize(None)
)

df['data_cadastro'] = (
    pd.to_datetime(df['data_cadastro'])
      .dt.tz_localize(None)
)

df['data_exame_fmt'] = df['data_hora_exame'].dt.strftime("%d-%m-%Y %H:%M")
df['data_cadastro_fmt'] = df['data_cadastro'].dt.strftime("%d-%m-%Y %H:%M")

# ======================
# 5. Corrigir nomes das colunas
# ======================
df = df.rename(columns={
    "patient_id_fk": "paciente_id",
    "exam_name": "nome_exame",
    "exam_type": "tipo_exame"
})

# ======================
# 6. Criar PIVOT correto (usando SOMENTE valor_num)
# ======================
pivot = df.pivot_table(
    index="paciente_id_fk",
    columns="nome_exame",
    values="valor_num",
    aggfunc="max"
).reset_index()

print("\n=== DATAFRAME FINAL (LIMPO, NUMÉRICO) ===")
print(df.head())

print("\n=== DATASET PIVOT PRONTO PARA MACHINE LEARNING ===")
print(pivot.head())

print("\nConcluído.")
