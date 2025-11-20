import React, { useState, useEffect } from "react";
import { View, StyleSheet, Text, TextInput, TouchableOpacity, ScrollView, Alert, Platform } from "react-native";

import Header from "../../components/Header";
import User from "../../components/User";
import PageAtual from "../../components/PageAtual";

// --- CORES PADRONIZADAS ---
const COR_DESTAQUE = "#1ABC9C";
const COR_FUNDO_ESCURO = "#0A212F";

// --- DADOS DE EXAMES ---
const EXAMES_POR_CATEGORIA = {
  Microbiologia: [
    {
      id: 20,
      nome_exame: "Urocultura com antibiograma",
      tipo_exame_categoria: "Microbiologia",
    },
    {
      id: 21,
      nome_exame: "Swab ocular",
      tipo_exame_categoria: "Microbiologia",
    },
    {
      id: 22,
      nome_exame: "Escarro para Micobacterium tuberculosis",
      tipo_exame_categoria: "Microbiologia",
    },
  ],
  Parasitologia: [
    {
      id: 27,
      nome_exame: "Exame de fezes",
      tipo_exame_categoria: "Parasitologia",
    },
    {
      id: 28,
      nome_exame: "Pesquisa de hematozoários",
      tipo_exame_categoria: "Parasitologia",
    },
    {
      id: 29,
      nome_exame: "Pesquisa de protozoários intestinais",
      tipo_exame_categoria: "Parasitologia",
    },
  ],
  Hematologia: [
    {
      id: 23,
      nome_exame: "Hemograma Completo",
      tipo_exame_categoria: "Hematologia",
    },
    {
      id: 24,
      nome_exame: "Reticulócitos",
      tipo_exame_categoria: "Hematologia",
    },
    {
      id: 25,
      nome_exame: "Tempo de sangramento",
      tipo_exame_categoria: "Hematologia",
    },
    {
      id: 26,
      nome_exame: "Tempo de coagulação",
      tipo_exame_categoria: "Hematologia",
    },
  ],
  Bioquimica: [
    { id: 1, nome_exame: "Ácido úrico", tipo_exame_categoria: "Bioquímica" },
    { id: 2, nome_exame: "Alfa Amilase", tipo_exame_categoria: "Bioquímica" },
    {
      id: 3,
      nome_exame: "Bilirrubina Total",
      tipo_exame_categoria: "Bioquímica",
    },
    {
      id: 4,
      nome_exame: "Bilirrubina Direta",
      tipo_exame_categoria: "Bioquímica",
    },
    { id: 5, nome_exame: "Cálcio", tipo_exame_categoria: "Bioquímica" },
    {
      id: 6,
      nome_exame: "Colesterol Total",
      tipo_exame_categoria: "Bioquímica",
    },
    { id: 7, nome_exame: "HDL", tipo_exame_categoria: "Bioquímica" },
    { id: 8, nome_exame: "Creatinina", tipo_exame_categoria: "Bioquímica" },
    { id: 9, nome_exame: "Ferro Ferene", tipo_exame_categoria: "Bioquímica" },
    {
      id: 10,
      nome_exame: "Fosfatase Alcalina",
      tipo_exame_categoria: "Bioquímica",
    },
    { id: 11, nome_exame: "Fosfato", tipo_exame_categoria: "Bioquímica" },
    { id: 12, nome_exame: "Gama GT", tipo_exame_categoria: "Bioquímica" },
    { id: 13, nome_exame: "Glicose", tipo_exame_categoria: "Bioquímica" },
    { id: 14, nome_exame: "GOT (AST)", tipo_exame_categoria: "Bioquímica" },
    { id: 15, nome_exame: "GTP (ALT)", tipo_exame_categoria: "Bioquímica" },
    { id: 16, nome_exame: "Magnésio", tipo_exame_categoria: "Bioquímica" },
    {
      id: 17,
      nome_exame: "Proteína total",
      tipo_exame_categoria: "Bioquímica",
    },
    {
      id: 18,
      nome_exame: "Triglicerídeos",
      tipo_exame_categoria: "Bioquímica",
    },
    { id: 19, nome_exame: "Uréia", tipo_exame_categoria: "Bioquímica" },
  ],
  Urinálise: [
    { id: 30, nome_exame: "Exame físico", tipo_exame_categoria: "Urinálise" },
    { id: 31, nome_exame: "Exame químico", tipo_exame_categoria: "Urinálise" },
    {
      id: 32,
      nome_exame: "Exame microscópico",
      tipo_exame_categoria: "Urinálise",
    },
  ],
};

export default function NovoExame({ navigation }) {
  // Campos do paciente / formulário
  const [nomePaciente, setNomePaciente] = useState("");
  const [pacienteId, setPacienteId] = useState("");
  const [dataHora, setDataHora] = useState("");

  // Exames selecionados (objeto por id para facilitar inclusão/remoção)
  const [examesSelecionados, setExamesSelecionados] = useState({});

  // Aba ativa (categoria)
  const [abaAtiva, setAbaAtiva] = useState(null);

  // Toggle para abrir/fechar lista da categoria (mantém comportamento)
  const toggleAba = (aba) => {
    setAbaAtiva((prev) => (prev === aba ? null : aba));
  };

  // Selecionar / desselecionar exame
  const toggleExame = (exame) => {
    setExamesSelecionados((prev) => {
      const newState = { ...prev };
      if (newState[exame.id]) {
        delete newState[exame.id];
      } else {
        newState[exame.id] = exame;
      }
      return newState;
    });
  };

  // Envio via fetch
  const handleSolicitarExame = async () => {
    const idsExames = Object.keys(examesSelecionados);

    if (!pacienteId || idsExames.length === 0 || !dataHora) {
      Alert.alert(
        "Atenção",
        "Preencha o ID do paciente, a data/hora e selecione pelo menos um exame."
      );
      return;
    }

    // Monta o corpo no formato que o backend espera
    const solicitacaoData = {
      paciente_id: pacienteId,
      data_prevista_realizacao: dataHora,
      solicitante_nome: "Fernanda",
      observacoes: "",
      exames: Object.values(examesSelecionados).map((ex) => ({
        nome_exame: ex.nome_exame,
        tipo_exame_categoria: ex.tipo_exame_categoria,
        valor_referencia_solicitacao: "",
      })),
    };

    try {
      const res = await fetch("http://localhost:3000/solicitacoes", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(solicitacaoData),
      });

      if (!res.ok) {
        // tenta pegar mensagem do servidor
        const text = await res.text();
        throw new Error(text || "Erro na requisição");
      }

      const json = await res.json();

      // supondo que o backend retorne { success: true, ... }
      if (json && json.success) {
        Alert.alert("Sucesso", "Solicitação de Exame enviada!");
        // limpa campos
        setNomePaciente("");
        setPacienteId("");
        setDataHora("");
        setExamesSelecionados({});
      } else {
        Alert.alert("Erro", "Resposta inesperada do servidor.");
      }
    } catch (error) {
      console.error("Erro ao solicitar exame:", error);
      Alert.alert(
        "Erro",
        `Falha ao solicitar exame: ${error.message || error}`
      );
    }
  };

  // Componente auxiliar que renderiza a lista de exames de uma categoria
  const ExamesCheckboxList = ({ categoria }) => (
    <View style={Estilo.exameListContainer}>
      <Text style={Estilo.secaoTitulo}>Exames de {categoria}:</Text>

      {EXAMES_POR_CATEGORIA[categoria]?.map((exame) => (
        <TouchableOpacity
          key={exame.id}
          style={Estilo.checkboxItem}
          onPress={() => toggleExame(exame)}
        >
          <View style={Estilo.checkboxOuter}>
            {examesSelecionados[exame.id] && (
              <View style={Estilo.checkboxInner} />
            )}
          </View>
          <Text style={Estilo.checkboxLabel} numberOfLines={2}>
            {exame.nome_exame}
          </Text>
        </TouchableOpacity>
      ))}
    </View>
  );

  return (
    <View style={Estilo.container}>
      <Header />
      <User nomeUsuario="Fernanda" />
      <PageAtual Pageatual="Solicitar Novo Exame" />

      <ScrollView
        contentContainerStyle={Estilo.formContent}
        keyboardShouldPersistTaps="handled"
      >
        <View style={Estilo.campoContainer}>
          {/* CAMPOS BÁSICOS DO PACIENTE */}
          <Text style={Estilo.label}>Nome do Paciente</Text>
          <TextInput
            style={Estilo.input}
            placeholder="Digite o nome..."
            placeholderTextColor="#999"
            value={nomePaciente}
            onChangeText={setNomePaciente}
          />

          <Text style={Estilo.label}>Id do Paciente</Text>
          <TextInput
            style={Estilo.input}
            placeholder="Digite o ID do paciente..."
            placeholderTextColor="#999"
            keyboardType="numeric"
            value={pacienteId}
            onChangeText={setPacienteId}
          />

          <Text style={Estilo.label}>Data e hora do Exame</Text>
          <TextInput
            style={Estilo.input}
            placeholder="dd/mm/aaaa --:--"
            placeholderTextColor="#999"
            value={dataHora}
            onChangeText={setDataHora}
          />

          {/* TABS HORIZONTAIS (Categorias) */}
          <Text style={[Estilo.label, { marginTop: 10 }]}>
            Laboratórios Solicitados
          </Text>
          <ScrollView
            horizontal
            showsHorizontalScrollIndicator={false}
            contentContainerStyle={Estilo.tabsContainer}
            style={{ marginBottom: 8 }}
          >
            {Object.keys(EXAMES_POR_CATEGORIA).map((aba) => (
              <TouchableOpacity
                key={aba}
                style={[
                  Estilo.tabButton,
                  abaAtiva === aba && Estilo.tabButtonActive,
                ]}
                onPress={() => toggleAba(aba)}
                activeOpacity={0.8}
              >
                <Text
                  style={[
                    Estilo.tabText,
                    abaAtiva === aba && Estilo.tabTextActive,
                  ]}
                >
                  {aba}
                </Text>
              </TouchableOpacity>
            ))}
          </ScrollView>

          {/* LISTA DE EXAMES DA ABA ATIVA */}
          {abaAtiva && <ExamesCheckboxList categoria={abaAtiva} />}

          {/* VISUALIZAÇÃO DOS EXAMES SELECIONADOS */}
          <Text style={Estilo.selecaoTitulo}>
            Exames Selecionados ({Object.keys(examesSelecionados).length})
          </Text>
          {Object.values(examesSelecionados).length === 0 ? (
            <Text style={Estilo.selecaoVazia}>Nenhum exame selecionado.</Text>
          ) : (
            Object.values(examesSelecionados).map((ex) => (
              <Text key={ex.id} style={Estilo.selecaoItem}>
                • {ex.nome_exame}
              </Text>
            ))
          )}
        </View>

        {/* espaço extra para o botão fixo não sobrepor o conteúdo */}
        <View style={{ height: 120 }} />
      </ScrollView>

      {/* BOTÃO FIXO NO RODAPÉ */}
      <View style={Estilo.botaoFixoContainer}>
        <TouchableOpacity
          style={Estilo.botao}
          activeOpacity={0.8}
          onPress={handleSolicitarExame}
        >
          <Text style={Estilo.textoBotao}>Marcar Exame</Text>
        </TouchableOpacity>
      </View>
    </View>
  );
}

const Estilo = StyleSheet.create({
  container: { flex: 1, backgroundColor: "#f0f0f0" },
  formContent: {
    paddingHorizontal: 20,
    paddingBottom: Platform.OS === "ios" ? 140 : 120,
  },

  // Card do formulário
  campoContainer: {
    backgroundColor: "#fff",
    padding: 20,
    borderRadius: 10,
    marginTop: 10,
    paddingBottom: 10,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 3.84,
    elevation: 5,
  },

  // inputs
  label: { fontSize: 16, color: "#333", marginBottom: 5, fontWeight: "bold" },
  input: {
    height: 50,
    borderBottomWidth: 2,
    borderBottomColor: "#ccc",
    borderRadius: 0,
    paddingTop: 5,
    paddingBottom: 5,
    paddingHorizontal: 4,
    backgroundColor: "#fff",
    marginBottom: 12,
    fontSize: 17,
  },

  // tabs
  tabsContainer: {
    flexDirection: "row",
    paddingVertical: 4,
  },
  tabButton: {
    paddingHorizontal: 14,
    paddingVertical: 8,
    marginRight: 10,
    borderRadius: 8,
    backgroundColor: "#f1f1f1",
  },
  tabButtonActive: {
    backgroundColor: COR_DESTAQUE,
  },
  tabText: {
    fontSize: 14,
    color: "#666",
    fontWeight: "600",
  },
  tabTextActive: {
    color: "#fff",
    fontWeight: "700",
  },

  // checkbox list
  exameListContainer: {
    marginBottom: 10,
    marginTop: 6,
  },
  secaoTitulo: {
    fontSize: 16,
    fontWeight: "bold",
    color: COR_FUNDO_ESCURO,
    marginBottom: 8,
  },
  checkboxItem: {
    flexDirection: "row",
    alignItems: "center",
    paddingVertical: 8,
  },
  checkboxLabel: {
    fontSize: 16,
    color: "#333",
    marginLeft: 10,
    flex: 1,
    flexWrap: "wrap",
  },
  checkboxOuter: {
    height: 20,
    width: 20,
    borderWidth: 2,
    borderColor: COR_FUNDO_ESCURO,
    alignItems: "center",
    justifyContent: "center",
    marginRight: 8,
  },
  checkboxInner: {
    height: 12,
    width: 12,
    backgroundColor: COR_DESTAQUE,
  },

  // selecionados
  selecaoTitulo: {
    fontSize: 14,
    fontWeight: "bold",
    color: COR_FUNDO_ESCURO,
    marginTop: 12,
    marginBottom: 6,
  },
  selecaoVazia: { fontSize: 13, color: "#888" },
  selecaoItem: {
    fontSize: 13,
    color: "#555",
    marginBottom: 4,
  },

  // botão fixo
  botaoFixoContainer: {
    position: "absolute",
    bottom: 0,
    left: 0,
    right: 0,
    paddingHorizontal: 20,
    paddingVertical: 15,
    alignItems: "center",
    backgroundColor: "#f0f0f0",
    borderTopWidth: 1,
    borderTopColor: "#eee",
    zIndex: 10,
  },
  botao: {
    backgroundColor: COR_DESTAQUE,
    borderRadius: 12,
    width: "100%",
    paddingVertical: 16,
    alignItems: "center",
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 5.46,
    elevation: 9,
  },
  textoBotao: { color: "#fff", fontSize: 18, fontWeight: "bold" },
});
