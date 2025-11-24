import os #manipulação de arquivos e strings
import re #manipulação de arquivos e strings
import pandas as pd #manipulação e análise de dados.
import numpy as np #manipulação e análise de dados.
from sqlalchemy import create_engine #conexão com PostgreSQL.
import psycopg2 #conexão com PostgreSQL.
from sklearn.model_selection import train_test_split #machine learning.
from sklearn.preprocessing import StandardScaler #machine learning.
from sklearn.linear_model import LogisticRegression #machine learning.
from sklearn.metrics import accuracy_score, confusion_matrix, classification_report #machine learning.
import matplotlib.pyplot as plt #graficos
import random #gerar dados fictícios
from datetime import datetime, timedelta #gerar dados fictícios



# 1) CONFIGURAÇÕES INICIAIS

OUT_DIR = "outputs"

def sanitize_filename(name):
    # Substitui caracteres que não podem em nomes de arquivos por "_"
    return re.sub(r'[\\/*?:"<>| ]', '_', name)  

os.makedirs(OUT_DIR, exist_ok=True) # cria pasta de saída se não existir

# Conexão com o banco
engine = create_engine("postgresql+psycopg2://postgres:postgres@localhost:5432/projetointegrado")


# 2) GERAR DADOS FICTÍCIOS
np.random.seed(42)

num_pacientes = 100 #Define 100 pacientes
percent_risco = 0.5 #sendo metade com risco
num_risco = int(num_pacientes * percent_risco)

ids_risco = np.random.choice(range(1, num_pacientes+1), size=num_risco, replace=False) #contém aleatoriamente os IDs dos pacientes que terão risco

pacientes = [] #Cria uma lista de pacientes, cada um com os dados que esta em paciente append
for i in range(1, num_pacientes + 1):
    idade = random.randint(1, 80)
    genero = random.choice(["Masculino", "Feminino"])
    tem_risco = i in ids_risco
    pacientes.append({"paciente_id_fk": i, "idade": idade, "genero": genero, "tem_risco": tem_risco})

#Define os limites normais de cada exame dependendo do exame, idade e gênero (foi passo de acordo com dados reais passado pela keyuser)
def faixa_referencia(exame, idade, genero):
    genero = genero.lower()

    referencias = {
        # -------------------- BIOQUÍMICA --------------------
        "Bilirrubina Total": (0.2, 1.2),
        "Bilirrubina Direta": (0.0, 0.2),
        "Proteína Total": (6.0, 8.3),
        "TGO - Transaminase Glutâmico Oxalacética": (5, 34),
        "TGP - Transaminase Glutâmico Pirúvica":
            (21, 72) if genero in ["masculino", "masc"] else (9, 52),
        "Gama GT - Glutamiltransferase":
            (15, 73) if genero in ["masculino", "masc"] else (12, 43),
        "Fosfatase Alcalina": (38, 126),
        "Creatinina":
            (0.70, 1.25) if genero in ["masculino", "masc"] else (0.57, 1.11),
        "Glicose": (75, 99),
        "Colesterol Total": (0, 190) if idade >= 20 else (0, 170),
        "Triglicerídeos": (
            (0, 150) if idade >= 20 else
            (0, 75) if idade <= 9 else
            (0, 90)     # idade 10–19
        ),
        # Exames sem referência oficial fixa → você deixou como null
        # Colocarei valores clássicos usados em diagnóstico
        "Uréia": (10, 50),
        "Ácido Úrico":
            (3.4, 7.0) if genero in ["masculino", "masc"] else (2.4, 6.0),
        "PCR - Proteína C Reativa": (0, 1.0),
        "Cálcio": (8.6, 10.2),
        "LDH": (140, 280),
        "Ferro":
            (65, 175) if genero in ["masculino", "masc"] else (50, 170),

        # -------------------- HEMATOLOGIA --------------------
        "Hemácia": (3.9, 5.0),
        "Hemoglobina": (12.0, 15.5),
        "Hematócrito": (35, 45),
        "VCM": (82, 98),
        "HCM": (26, 34),
        "CHCM": (31, 36),
        "RDW": (11.5, 16.5),

        "Leucócitos": (3500, 10500),
        "Neutrófilos": (1700, 8000),
        "Bastonetes": (0, 840),
        "Segmentados": (1700, 8000),
        "Basófilos": (0, 100),
        "Eosinófilos": (50, 500),
        "Linfócitos": (900, 2900),
        "Monócitos": (300, 900),
        "Plaquetas": (150000, 450000),

        # -------------------- URINÁLISE --------------------
        "Densidade": (1.015, 1.035),
        "pH": (5.5, 6.5),
        "Células Epiteliais": (0, 31000),
        "Leucócitos (Urina)": (0, 25000),
        "Hemácias (Urina)": (0, 23000),
    }

    return referencias.get(exame, (0, 1))


exames = [
    # -------------------- BIOQUÍMICA --------------------
    "Bilirrubina Total",
    "Bilirrubina Direta",
    "Proteína Total",
    "TGO - Transaminase Glutâmico Oxalacética",
    "TGP - Transaminase Glutâmico Pirúvica",
    "Gama GT - Glutamiltransferase",
    "Fosfatase Alcalina",
    "Creatinina",
    "Glicose",
    "Colesterol Total",
    "Triglicerídeos",
    "Uréia",
    "Ácido Úrico",
    "PCR - Proteína C Reativa",
    "Cálcio",
    "LDH",
    "Ferro",

    # -------------------- HEMATOLOGIA --------------------
    "Hemácia",
    "Hemoglobina",
    "Hematócrito",
    "VCM",
    "HCM",
    "CHCM",
    "RDW",
    "Leucócitos",
    "Neutrófilos",
    "Bastonetes",
    "Segmentados",
    "Basófilos",
    "Eosinófilos",
    "Linfócitos",
    "Monócitos",
    "Plaquetas",

    # -------------------- URINÁLISE --------------------
    "Densidade",
    "pH",
    "Células Epiteliais",
    "Leucócitos (Urina)",
    "Hemácias (Urina)"
]


#Para cada paciente, gera valores aleatórios para cada exame, considerando a faixa de referência. Se o paciente tiver risco, alguns valores podem ser fora da faixa (Anormal).
registros = []
for paciente in pacientes:
    idade, genero, pid, tem_risco = paciente["idade"], paciente["genero"], paciente["paciente_id_fk"], paciente["tem_risco"]  #Itera sobre cada paciente da lista pacientes
    for exame in exames: #Para cada paciente, itera sobre todos os exames da lista exames
        ref_min, ref_max = faixa_referencia(exame, idade, genero) #Chama a função que retorna os valores de referência mínimo e máximo para aquele exame, idade e gênero.
        if tem_risco: # linha 45 // Se o paciente tem risco, ele tem 30% de chance de sair do intervalo normal
            valor = np.random.uniform(ref_min, ref_max) if np.random.rand() < 0.7 else np.random.uniform(ref_min*0.5, ref_min*0.99) if np.random.rand()<0.5 else np.random.uniform(ref_max*1.01, ref_max*2.0)
        else:
            valor = np.random.uniform(ref_min, ref_max) #Se não tem risco, o valor é sempre dentro da faixa normal (ref_min a ref_max)
        status = "Normal" if ref_min <= valor <= ref_max else "Anormal" #Define se o exame está dentro ou fora da faixa de referência.
        registros.append({ #Adiciona um dicionário à lista registros
            "paciente_id_fk": pid,
            "idade": idade,
            "genero": genero,
            "exame": exame,
            "valor_num": round(valor,2),
            "ref_min": ref_min,
            "ref_max": ref_max,
            "status": status,
            "data_hora_exame": datetime.now() - timedelta(days=random.randint(0,30))
        })

df = pd.DataFrame(registros) #Converte tudo em um DataFrame pandas para manipulação.

# Status de risco por paciente 
df_risco = df.groupby("paciente_id_fk").apply(lambda x: 1 if (x["status"]=="Anormal").any() else 0).reset_index() #Verifica se algum exame esta fora da faixa, se tiver ele marca como anormal em status_risco //Essa linha resume os exames de cada paciente em um único indicador de risco (1 ou 0).
df_risco.columns = ["paciente_id_fk", "Status_Risco"] #salva id do paciente e seu status
print(df_risco["Status_Risco"].value_counts())

# Padronizar datas
df['data_hora_exame'] = pd.to_datetime(df['data_hora_exame']).dt.tz_localize(None)

# Pivot table // Converte o DataFrame de formato longo para formato largo antes cada exame era uma linha, agora cada paciente é uma linha e cada coluna um exame
pivot = df.pivot_table(
    index="paciente_id_fk",
    columns="exame",
    values="valor_num",
    aggfunc="max"
).reset_index()
pivot.columns.name = None

# Criar alvo para ML
pivot_ml = pivot.copy() #Cria uma cópia do pivot table (paciente_id_fk x exames) para criar o alvo da ML
pivot_ml["Status_Risco"] = 0 #Inicializa a coluna de risco com zero
for exame in df["exame"].unique(): #Itera sobre todos os exames existentes no DataFrame original
    if exame in pivot_ml.columns:
        mediana = df.loc[df["exame"] == exame, "valor_num"].median() #Calcula a mediana do exame em toda a base
        desvio = df.loc[df["exame"] == exame, "valor_num"].std() #Calcula o desvio padrão do exame
        pivot_ml["Status_Risco"] = np.maximum( #Atualiza o Status_Risco do paciente se qualquer exame estiver acima de mediana + desvio
            pivot_ml["Status_Risco"], (pivot_ml[exame] > mediana + desvio).astype(int) #cria 1 se valor acima do limite, 0 caso contrário.
        )
print(pivot_ml["Status_Risco"].value_counts())

# Preparar dados para modelo
feature_cols = [c for c in pivot_ml.columns if c not in ["paciente_id_fk", "Status_Risco"]] #Cria uma lista de colunas que serão usadas como features (todos os exames, sem ID nem alvo).

#feature significa uma característica do dado que será usada para prever alguma coisa. ==========Nesse caso as variaveis X e target o alvo seria as Y =========

X = pivot_ml[feature_cols].apply(pd.to_numeric, errors="coerce").fillna(0) #Converte os valores das features para números (por segurança). // errors="coerce" valores inválidos viram NaN
y = pivot_ml["Status_Risco"]

# Treino/teste
#=============x_train é todas as features, exceto o que queremos prever. ================
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42, stratify=y) #Divide dados em treino (80%) e teste (20%). 
scaler = StandardScaler() #Cria objeto que padroniza features (média=0, desvio=1). /// Scaler = ferramenta que padroniza os valores, deixando-os na mesma escala.
X_train_scaled = scaler.fit_transform(X_train) #fit_transform() → calcula a média e o desvio padrão e aplica a transformação no treino
X_test_scaled  = scaler.transform(X_test) #transform() → usa a mesma transformação no teste

# Modelo
model = LogisticRegression(solver="liblinear") #Logistic Regression = modelo de classificação (Normal / Anormal) // solver="liblinear" = algoritmo interno usado para encontrar a melhor solução
model.fit(X_train_scaled, y_train) #O modelo aprende a relação entre X_train_scaled → dados de entrada (features escaladas) e y_train → rótulos (Normal / Anormal)
y_pred = model.predict(X_test_scaled) #Modelo olha para os dados novos e diz: normal ou anormal 

# Métricas // 
acc = accuracy_score(y_test, y_pred)
cm  = confusion_matrix(y_test, y_pred)
report = classification_report(y_test, y_pred, zero_division=0)

print("\nAcurácia:", acc)
print("\nMatriz de confusão:\n", cm)
print("\nRelatório:\n", report)

with open(os.path.join(OUT_DIR, "metricas_modelo.txt"), "w", encoding="utf-8") as f:
    f.write(f"Acurácia: {acc}\nMatriz:\n{cm}\nRelatório:\n{report}\n")

# Plot matriz de confusão
cm = cm  # variável do seu código
plt.figure(figsize=(5,5))
plt.imshow(cm, cmap="Blues")
plt.title("Matriz de Confusão")
plt.colorbar()
plt.xticks([0,1], ["Normal", "Risco"])
plt.yticks([0,1], ["Normal", "Risco"])
for i in range(cm.shape[0]):
    for j in range(cm.shape[1]):
        plt.text(j, i, cm[i,j], ha="center", va="center")
plt.savefig(os.path.join(OUT_DIR, "matriz_confusao.png"))
plt.close()
print("\nArquivo salvo: outputs/matriz_confusao.png")


# ==================14) PREVISÃO DE NOVOS EXAMES DO BANCO=====================

print("\nBuscando novos exames no banco para previsão...")

# Buscar todos os exames que tenham algum valor numérico
query_novos = """
SELECT *
FROM resultados_exames
WHERE valor_absoluto ~ '[0-9]'
AND data_cadastro > NOW() - INTERVAL '5 minutes';
"""
df_novos = pd.read_sql(query_novos, engine)

if df_novos.empty:
    print("Nenhum exame novo encontrado.")
else:
    # Mostrar todos os dados que vieram do banco
    print("\n=== DADOS BRUTOS DO BANCO ===")
    print(df_novos)
    print(f"Total de linhas: {len(df_novos)}")

    # Extrair valor numérico e unidade mesmo com <, >, vírgula ou ponto
    df_novos[['valor_num', 'unidade']] = df_novos['valor_absoluto'].str.extract(
        r'[<>]?\s*([0-9]+(?:[.,][0-9]+)?)\s*([a-zA-Z/]+)?'
    )
    df_novos['valor_num'] = df_novos['valor_num'].str.replace(',', '.').astype(float)

    # Padronizar datas
    df_novos['data_hora_exame'] = pd.to_datetime(df_novos['data_hora_exame']).dt.tz_localize(None)

    # Pivot usando nome_exame // Converte o DataFrame de formato longo para formato largo antes cada exame era uma linha, agora cada paciente é uma linha e cada coluna um exame
    pivot_novos = df_novos.pivot_table(
        index="paciente_id_fk",
        columns="nome_exame",
        values="valor_num",
        aggfunc="max"
    ).reset_index()
    pivot_novos.columns.name = None

    # Mostrar pivot com todos os exames
    print("\n=== PIVOT DE EXAMES ===")
    print(pivot_novos)
    print(f"Total de pacientes no pivot: {len(pivot_novos)}")

    # Garantir que todas as features do modelo existam
    for c in feature_cols:
        if c not in pivot_novos.columns:
            pivot_novos[c] = 0

    # Selecionar apenas as colunas/features do modelo
    X_novos = pivot_novos[feature_cols].apply(pd.to_numeric, errors="coerce").fillna(0)

    # Escalar com o scaler treinado
    X_novos_scaled = scaler.transform(X_novos) #Aplica mesmo scaler nos novos exames para manter consistência do modelo

    # Prever risco
    pivot_novos['Status_Risco'] = model.predict(X_novos_scaled) #Usa o modelo treinado para prever risco (0 ou 1) para cada paciente.

    # Mapear para texto
    pivot_novos['Status_Risco_Texto'] = pivot_novos['Status_Risco'].map({0: "Normal", 1: "Anormal"}) #Converte os valores binários em texto para visualização e gráficos.

    # Mostrar resultado final com previsão
    print("\n=== PREDIÇÃO DE RISCO ===")
    print(pivot_novos[['paciente_id_fk', 'Status_Risco_Texto']])

    # Salvar em CSV
    pivot_novos.to_csv(os.path.join(OUT_DIR, "novos_exames_previstos.csv"), index=False)
    print("Arquivo salvo: outputs/novos_exames_previstos.csv")

    print("\n=== GERANDO GRÁFICOS POR PACIENTE ===")

    PAC_DIR = os.path.join(OUT_DIR, "graficos_pacientes")
    os.makedirs(PAC_DIR, exist_ok=True)

    pids = [int(x) for x in pivot_novos["paciente_id_fk"].unique()] #Lista de IDs dos pacientes que vamos gerar gráficos.

    # Query para trazer dados dos pacientes
    query_pacientes = """
    SELECT id, dtnasc, genero
    FROM pacientes
    WHERE id = ANY(%(pids)s);
    """

    # Trazer dados do banco
    df_pacientes = pd.read_sql(query_pacientes, engine, params={"pids": pids})

    # Merge para juntar os exames com dados dos pacientes
    df_novos_merge = pd.merge(df_novos, df_pacientes, left_on="paciente_id_fk", right_on="id", how="left")

    # Calcular idade
    df_novos_merge['idade'] = df_novos_merge['dtnasc'].apply(lambda x: (datetime.now() - pd.to_datetime(x)).days // 365)

    # Gerar gráficos por paciente
    for pid in pids:
        paciente_data = df_novos_merge[df_novos_merge["paciente_id_fk"] == pid].copy()
        if paciente_data.empty: #Para cada paciente, filtra os exames dele.
            continue

        # Criar colunas de referência mínima e máxima
        paciente_data["ref_min"] = paciente_data.apply(
            lambda x: faixa_referencia(x["nome_exame"], x["idade"], x["genero"])[0], axis=1 #Calcula faixas de referência para cada exame do paciente.
        )
        paciente_data["ref_max"] = paciente_data.apply(
            lambda x: faixa_referencia(x["nome_exame"], x["idade"], x["genero"])[1], axis=1 #Calcula faixas de referência para cada exame do paciente.
        )

        # Criar coluna de status: Normal se valor_num está dentro da faixa, Anormal caso contrário
        paciente_data['status'] = paciente_data.apply( #Determina se cada exame está normal ou anormal. 
            lambda x: "Normal" if x['ref_min'] <= x['valor_num'] <= x['ref_max'] else "Anormal", axis=1 #Lambda = uma “função rápida”, criada na hora // o pandas passa cada linha como um objeto x
        )

        # Criar labels com faixa de referência no eixo Y
        labels_exames = []
        for nome, vmin, vmax in zip(paciente_data["nome_exame"], paciente_data["ref_min"], paciente_data["ref_max"]):
            if pd.isna(vmin) and pd.isna(vmax):
                label = f"{nome}"
            elif pd.isna(vmin):
                label = f"{nome} (< {vmax})"
            elif pd.isna(vmax):
                label = f"{nome} (> {vmin})"
            else:
                label = f"{nome} ({vmin} - {vmax})"
            labels_exames.append(label)

        #Prepara valores, status e cores para plotagem
        valores = paciente_data["valor_num"].tolist()
        status = paciente_data["status"].tolist()
        # Cor: verde se normal, vermelho se anormal
        cores = ["green" if s=="Normal" else "red" for s in status]

        plt.figure(figsize=(10,6))
        barras = plt.barh(labels_exames, valores, color=cores, alpha=0.7)

        # Adicionar valor numérico na barra
        for bar, valor in zip(barras, valores):
            plt.text(bar.get_width(), bar.get_y() + bar.get_height()/2,
                    f"{valor}", va="center", ha="left", fontsize=9)

        plt.xlabel("Valor do Exame")
        plt.ylabel("Exame")
        plt.title(f"Exames do Paciente {pid}")

        plt.tight_layout()
        plt.savefig(os.path.join(PAC_DIR, f"paciente_{pid}.png"))  # SALVA ANTES
        plt.close()


    print(f"\nGráficos por paciente gerados em {PAC_DIR}/")

