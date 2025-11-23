import React, { useState, useEffect } from "react";
import {
  View, StyleSheet, Text, TextInput, TouchableOpacity,
  ScrollView, Platform, Modal, FlatList, ActivityIndicator
} from "react-native";

import Header from "../../components/Header";
import User from "../../components/User";
import PageAtual from "../../components/PageAtual";
import Toast from 'react-native-toast-message';
import { Feather } from '@expo/vector-icons';

// APIs
import { CreateSolicitacaoFromAPI } from '../../API/Solicitacoes';
import { LoadPacientesFromAPI } from '../../API/Pacientes'; // <--- IMPORT NOVO

const COR_DESTAQUE = "#1ABC9C";
const COR_FUNDO_ESCURO = "#0A212F";

// --- DADOS DE EXAMES (Mantidos) ---
const EXAMES_POR_CATEGORIA = {
  Hematologia: [ //Util para data science pois temos referencia
    { id: 23, nome_exame: "Hemograma Completo", tipo_exame_categoria: "Hematologia",  },
    { id: 24, nome_exame: "Reticulócitos", tipo_exame_categoria: "Hematologia" },
    { id: 25, nome_exame: "Tempo de sangramento", tipo_exame_categoria: "Hematologia" },
    { id: 26, nome_exame: "Tempo de coagulação", tipo_exame_categoria: "Hematologia", valorReferenciaSolicitacao: null },
    { id: 31, nome_exame: "Bastonetes", tipo_exame_categoria: "Hematologia", valorReferenciaSolicitacao: "< 840/µL" },
  ],
  Bioquimica: [ // Verificar pois muda de masculino para feminino (fica por ultimo a se fazer)
    { id: 1, nome_exame: "Ácido úrico", tipo_exame_categoria: "Bioquímica" },
    { id: 2, nome_exame: "Alfa Amilase", tipo_exame_categoria: "Bioquímica" },
    { id: 3, nome_exame: "Bilirrubina Total", tipo_exame_categoria: "Bioquímica" },
    { id: 4, nome_exame: "Bilirrubina Direta", tipo_exame_categoria: "Bioquímica" },
    { id: 6, nome_exame: "Colesterol Total", tipo_exame_categoria: "Bioquímica" },
    { id: 8, nome_exame: "Creatinina", tipo_exame_categoria: "Bioquímica" },
    { id: 13, nome_exame: "Glicose", tipo_exame_categoria: "Bioquímica" },
    { id: 18, nome_exame: "Triglicerídeos", tipo_exame_categoria: "Bioquímica" },
    { id: 19, nome_exame: "Uréia", tipo_exame_categoria: "Bioquímica" },
  ],
  Urinálise: [ //Util para data science pois temos referencia
    { id: 30, nome_exame: "Exame físico", tipo_exame_categoria: "Urinálise" },
    { id: 31, nome_exame: "Exame químico", tipo_exame_categoria: "Urinálise" },
    { id: 32, nome_exame: "Exame microscópico", tipo_exame_categoria: "Urinálise" },
  ],
};

export default function NovoExame({ navigation }) {
  // --- ESTADOS ---
  const [nomePaciente, setNomePaciente] = useState("");
  const [pacienteId, setPacienteId] = useState("");
  const [medicoSolicitante, setMedicoSolicitante] = useState("");
  const [observacoes, setObservacoes] = useState("");
  const [examesSelecionados, setExamesSelecionados] = useState({});
  const [abaAtiva, setAbaAtiva] = useState(null);
  const [genero, setGeneroPaciente] = useState("");

  // --- ESTADOS PARA O MODAL DE PACIENTES ---
  const [modalVisible, setModalVisible] = useState(false);
  const [listaPacientes, setListaPacientes] = useState([]);
  const [listaFiltrada, setListaFiltrada] = useState([]);
  const [buscaModal, setBuscaModal] = useState("");
  const [loadingPacientes, setLoadingPacientes] = useState(false);

  // Carregar pacientes ao abrir a tela
  useEffect(() => {
    carregarPacientes();
  }, []);

  const carregarPacientes = async () => {
    setLoadingPacientes(true);
    const pacientes = await LoadPacientesFromAPI();
    if (pacientes) {
      setListaPacientes(pacientes);
      setListaFiltrada(pacientes);
    }
    setLoadingPacientes(false);
  };

  // Filtro no Modal
  const filtrarPacientes = (texto) => {
    setBuscaModal(texto);
    const filtro = listaPacientes.filter(p =>
      p.nome.toLowerCase().includes(texto.toLowerCase()) ||
      String(p.id).includes(texto)
    );
    setListaFiltrada(filtro);
  };

  // Selecionar Paciente da Lista
  const selecionarPaciente = (paciente) => {
    setNomePaciente(paciente.nome);
    setPacienteId(String(paciente.id));
    setModalVisible(false);
    setGeneroPaciente(paciente.genero)
    setBuscaModal(""); // Limpa busca
    setListaFiltrada(listaPacientes); // Reseta lista
  };

  // Toggle Abas
  const toggleAba = (aba) => {
    setAbaAtiva((prev) => (prev === aba ? null : aba));
  };

  // Toggle Exames
  const toggleExame = (exame) => {
    setExamesSelecionados((prev) => {
      const newState = { ...prev };
      if (newState[exame.id]) delete newState[exame.id];
      else newState[exame.id] = exame;
      return newState;
    });
  };

  // --- SALVAR SOLICITAÇÃO ---
  const handleSolicitarExame = async () => {
    const listaExamesParaEnviar = Object.values(examesSelecionados);

    if (!pacienteId || pacienteId.trim() === "") {
      Toast.show({ type: "info", text1: "Selecione um Paciente", text2: "Use a lupa para buscar." });
      return;
    }

    if (listaExamesParaEnviar.length === 0) {
      Toast.show({ type: "info", text1: "Carrinho Vazio", text2: "Adicione exames." });
      return;
    }

    try {
      // Tradução para CamelCase (Padrão do Desktop)
      const examesFormatados = listaExamesParaEnviar.map(item => ({
        nomeExame: item.nome_exame,
        tipoExameCategoria: item.tipo_exame_categoria,
        valorReferenciaSolicitacao: item.valorReferenciaSolicitacao,
        statusItem: "Pendente"
      }));

      const novaSolicitacao = {
        pacienteId: parseInt(pacienteId),
        dataSolicitacao: new Date().toISOString(),
        dataPrevistaRealizacao: new Date(Date.now() + 3 * 24 * 60 * 60 * 1000).toISOString(),
        solicitanteNome: medicoSolicitante || "Não Informado",
        status: "Pendente",
        observacoes: observacoes || "",
        exames: examesFormatados
      };

      console.log("Enviando:", JSON.stringify(novaSolicitacao));

      const response = await CreateSolicitacaoFromAPI(novaSolicitacao);

      if (response.success) {
        Toast.show({ type: "success", text1: "Sucesso", text2: `Solicitação #${response.idSolicitacao} criada!` });

        // Limpeza
        setExamesSelecionados({});
        setMedicoSolicitante("");
        setObservacoes("");
        setNomePaciente("");
        setPacienteId("");

      } else {
        throw new Error(response.message || "Falha ao inserir.");
      }

    } catch (error) {
      console.error("Erro:", error);
      Toast.show({ type: "error", text1: "Erro", text2: "Falha ao criar solicitação." });
    }
  };

  // Componente de Lista de Exames
  const ExamesCheckboxList = ({ categoria }) => (
    <View style={Estilo.exameListContainer}>
      <Text style={Estilo.secaoTitulo}>Exames de {categoria}:</Text>
      {EXAMES_POR_CATEGORIA[categoria]?.map((exame) => (
        <TouchableOpacity key={exame.id} style={Estilo.checkboxItem} onPress={() => toggleExame(exame)}>
          <View style={Estilo.checkboxOuter}>
            {examesSelecionados[exame.id] && <View style={Estilo.checkboxInner} />}
          </View>
          <Text style={Estilo.checkboxLabel}>{exame.nome_exame}</Text>
        </TouchableOpacity>
      ))}
    </View>
  );

  return (
    <View style={Estilo.container}>
      <Header />
      <User nomeUsuario="Fernanda" />
      <PageAtual Pageatual="Solicitar Novo Exame" />

      <ScrollView contentContainerStyle={Estilo.formContent} keyboardShouldPersistTaps="handled">
        <View style={Estilo.campoContainer}>

          {/* --- SELEÇÃO DE PACIENTE --- */}
          <Text style={Estilo.label}>Selecione o Paciente</Text>
          <TouchableOpacity
            style={Estilo.selectButton}
            onPress={() => setModalVisible(true)}
          >
            <Text style={nomePaciente ? Estilo.selectTextSelected : Estilo.selectTextPlaceholder}>
              {nomePaciente || "Toque para buscar paciente..."}
            </Text>
            <Feather name="search" size={20} color="#666" />
          </TouchableOpacity>

          {/* ID (Read Only - Preenchido automático) */}
          <View style={Estilo.row}>
            <View style={{ flex: 1 }}>
              <Text style={Estilo.label}>ID (Automático)</Text>
              <TextInput
                style={[Estilo.input, { backgroundColor: '#f0f0f0', color: '#666' }]}
                value={pacienteId}
                editable={false} // Bloqueado para edição manual
              />
            </View>
          </View>

          <Text style={Estilo.label}>Médico Solicitante</Text>
          <TextInput
            style={Estilo.input}
            placeholder="Nome do médico (opcional)"
            placeholderTextColor="#999"
            value={medicoSolicitante}
            onChangeText={setMedicoSolicitante}
          />

          <Text style={Estilo.label}>Observações</Text>
          <TextInput
            style={Estilo.input}
            placeholder="Observações adicionais..."
            placeholderTextColor="#999"
            value={observacoes}
            onChangeText={setObservacoes}
          />

          {/* ABAS DE CATEGORIA */}
          <Text style={[Estilo.label, { marginTop: 10 }]}>Laboratórios Solicitados</Text>
          <ScrollView horizontal showsHorizontalScrollIndicator={false} contentContainerStyle={Estilo.tabsContainer}>
            {Object.keys(EXAMES_POR_CATEGORIA).map((aba) => (
              <TouchableOpacity
                key={aba}
                style={[Estilo.tabButton, abaAtiva === aba && Estilo.tabButtonActive]}
                onPress={() => toggleAba(aba)}
              >
                <Text style={[Estilo.tabText, abaAtiva === aba && Estilo.tabTextActive]}>{aba}</Text>
              </TouchableOpacity>
            ))}
          </ScrollView>

          {/* LISTA DE EXAMES */}
          {abaAtiva && <ExamesCheckboxList categoria={abaAtiva} />}

          {/* RESUMO */}
          <Text style={Estilo.selecaoTitulo}>Exames Selecionados ({Object.keys(examesSelecionados).length})</Text>
          {Object.values(examesSelecionados).length === 0 ? (
            <Text style={Estilo.selecaoVazia}>Nenhum exame selecionado.</Text>
          ) : (
            Object.values(examesSelecionados).map((ex) => (
              <Text key={ex.id} style={Estilo.selecaoItem}>• {ex.nome_exame}</Text>
            ))
          )}
        </View>
        <View style={{ height: 120 }} />
      </ScrollView>

      {/* BOTÃO SALVAR */}
      <View style={Estilo.botaoFixoContainer}>
        <TouchableOpacity style={Estilo.botao} activeOpacity={0.8} onPress={handleSolicitarExame}>
          <Text style={Estilo.textoBotao}>Marcar Exame</Text>
        </TouchableOpacity>
      </View>

      {/* --- MODAL DE SELEÇÃO DE PACIENTE --- */}
      <Modal animationType="slide" transparent={true} visible={modalVisible} onRequestClose={() => setModalVisible(false)}>
        <View style={Estilo.modalOverlay}>
          <View style={Estilo.modalContent}>
            <View style={Estilo.modalHeader}>
              <Text style={Estilo.modalTitle}>Selecionar Paciente</Text>
              <TouchableOpacity onPress={() => setModalVisible(false)}>
                <Feather name="x" size={24} color="#333" />
              </TouchableOpacity>
            </View>

            <View style={Estilo.searchBox}>
              <Feather name="search" size={20} color="#666" style={{ marginRight: 8 }} />
              <TextInput
                style={Estilo.searchInput}
                placeholder="Buscar por nome ou ID..."
                value={buscaModal}
                onChangeText={filtrarPacientes}
                autoFocus
              />
            </View>

            {loadingPacientes ? (
              <ActivityIndicator size="large" color={COR_DESTAQUE} style={{ marginTop: 20 }} />
            ) : (
              <FlatList
                data={listaFiltrada}
                keyExtractor={item => String(item.id)}
                style={{ maxHeight: 400 }}
                renderItem={({ item }) => (
                  <TouchableOpacity style={Estilo.itemPaciente} onPress={() => selecionarPaciente(item)}>
                    <View style={Estilo.avatar}>
                      <Text style={Estilo.avatarText}>{item.nome.charAt(0).toUpperCase()}</Text>
                    </View>
                    <View>
                      <Text style={Estilo.itemNome}>{item.nome}</Text>
                      {/* AQUI ESTÁ A MUDANÇA: Removi o CPF, deixei só o ID */}
                      <Text style={Estilo.itemInfo}>ID: {item.id}</Text>
                    </View>
                  </TouchableOpacity>
                )}
                ListEmptyComponent={<Text style={Estilo.emptyText}>Nenhum paciente encontrado.</Text>}
              />
            )}
          </View>
        </View>
      </Modal>

    </View>
  );
}

const Estilo = StyleSheet.create({
  container: { flex: 1, backgroundColor: "#f0f0f0" },
  formContent: { paddingHorizontal: 20, paddingBottom: 120 },
  campoContainer: { backgroundColor: "#fff", padding: 20, borderRadius: 10, marginTop: 10, elevation: 3 },

  label: { fontSize: 16, color: "#333", marginBottom: 6, fontWeight: "bold", marginTop: 10 },
  input: { height: 50, borderBottomWidth: 1, borderBottomColor: "#ccc", fontSize: 16, paddingHorizontal: 5, marginBottom: 5 },
  row: { flexDirection: 'row' },

  // SELECT BUTTON (Imita um input mas clicável)
  selectButton: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    borderBottomWidth: 1,
    borderBottomColor: '#ccc',
    height: 50,
    paddingHorizontal: 5,
    marginBottom: 5
  },
  selectTextPlaceholder: { color: '#999', fontSize: 16 },
  selectTextSelected: { color: '#333', fontSize: 16, fontWeight: '500' },

  // TABS
  tabsContainer: { flexDirection: "row", paddingVertical: 8 },
  tabButton: { paddingHorizontal: 14, paddingVertical: 8, marginRight: 10, borderRadius: 20, backgroundColor: "#e0e0e0" },
  tabButtonActive: { backgroundColor: COR_DESTAQUE },
  tabText: { fontSize: 14, color: "#666", fontWeight: "600" },
  tabTextActive: { color: "#fff" },

  // CHECKBOX LIST
  exameListContainer: { marginTop: 10 },
  secaoTitulo: { fontSize: 16, fontWeight: "bold", color: COR_FUNDO_ESCURO, marginBottom: 10 },
  checkboxItem: { flexDirection: "row", alignItems: "center", paddingVertical: 10, borderBottomWidth: 1, borderBottomColor: '#eee' },
  checkboxLabel: { fontSize: 15, color: "#333", marginLeft: 10, flex: 1 },
  checkboxOuter: { height: 22, width: 22, borderWidth: 2, borderColor: COR_FUNDO_ESCURO, alignItems: "center", justifyContent: "center", borderRadius: 4 },
  checkboxInner: { height: 12, width: 12, backgroundColor: COR_DESTAQUE },

  // SELEÇÃO
  selecaoTitulo: { fontSize: 15, fontWeight: "bold", color: COR_FUNDO_ESCURO, marginTop: 20, marginBottom: 5 },
  selecaoVazia: { fontSize: 13, color: "#888", fontStyle: 'italic' },
  selecaoItem: { fontSize: 14, color: "#555", marginBottom: 2 },

  // BOTÃO
  botaoFixoContainer: { position: "absolute", bottom: 0, left: 0, right: 0, padding: 20, backgroundColor: "#f0f0f0", elevation: 10 },
  botao: { backgroundColor: COR_DESTAQUE, borderRadius: 10, paddingVertical: 15, alignItems: "center" },
  textoBotao: { color: "#fff", fontSize: 18, fontWeight: "bold" },

  // --- MODAL STYLES ---
  modalOverlay: { flex: 1, backgroundColor: 'rgba(0,0,0,0.5)', justifyContent: 'center', alignItems: 'center' },
  modalContent: { width: '90%', height: '70%', backgroundColor: '#fff', borderRadius: 15, padding: 20, elevation: 10 },
  modalHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 15 },
  modalTitle: { fontSize: 20, fontWeight: 'bold', color: COR_FUNDO_ESCURO },

  searchBox: { flexDirection: 'row', alignItems: 'center', backgroundColor: '#f5f5f5', borderRadius: 8, paddingHorizontal: 10, height: 45, marginBottom: 10 },
  searchInput: { flex: 1, fontSize: 16 },

  itemPaciente: { flexDirection: 'row', alignItems: 'center', paddingVertical: 12, borderBottomWidth: 1, borderBottomColor: '#eee' },
  avatar: { width: 40, height: 40, borderRadius: 20, backgroundColor: COR_DESTAQUE, alignItems: 'center', justifyContent: 'center', marginRight: 12 },
  avatarText: { color: '#fff', fontWeight: 'bold', fontSize: 18 },
  itemNome: { fontSize: 16, fontWeight: 'bold', color: '#333' },
  itemInfo: { fontSize: 14, color: '#777' },
  emptyText: { textAlign: 'center', marginTop: 20, color: '#888', fontSize: 16 }
});