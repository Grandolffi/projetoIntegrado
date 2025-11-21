import React, { useState, useCallback } from 'react';
import { View, StyleSheet, Text, ScrollView, TouchableOpacity, Modal, TextInput, ActivityIndicator, RefreshControl, Keyboard } from "react-native";
import { Feather } from '@expo/vector-icons';
import Toast from 'react-native-toast-message';
import { useFocusEffect } from '@react-navigation/native';

import Header from "../../components/Header";
import User from "../../components/User";
import PageAtual from "../../components/PageAtual";

// Import API
import { 
    LoadResultadoExamesFromAPI, 
    DeleteResultadoExamesFromAPI,
    UpdateResultadoExamesFromAPI 
} from '../../API/ResultadoExames';

const COR_DESTAQUE = '#1ABC9C';
const COR_FUNDO_ESCURO = '#0A212F';

export default function ListaResultados() {
    const [listaExames, setListaExames] = useState([]); // Dados brutos (todos)
    const [listaFiltrada, setListaFiltrada] = useState([]); // Dados visíveis (filtrados)
    const [loading, setLoading] = useState(false);
    const [busca, setBusca] = useState('');
    const [refreshing, setRefreshing] = useState(false);

    // Estados Modal Edição
    const [modalVisible, setModalVisible] = useState(false);
    const [exameEdit, setExameEdit] = useState(null);
    const [novoValor, setNovoValor] = useState('');

    // Carregar dados (mas não exibir ainda)
    useFocusEffect(
        useCallback(() => {
            carregarDados();
        }, [])
    );

    const carregarDados = async () => {
        if (!busca) setLoading(true); // Só mostra loading se não tiver buscando ainda
        
        const dados = await LoadResultadoExamesFromAPI();
        if (dados && Array.isArray(dados)) {
            // Ordena por ID (mais novos primeiro)
            const dadosOrdenados = dados.sort((a, b) => b.id_exame - a.id_exame);
            setListaExames(dadosOrdenados);
            
            // MUDANÇA: Se tiver busca ativa, atualiza a lista filtrada. Se não, deixa vazia.
            if (busca.trim() !== '') {
                aplicarFiltro(busca, dadosOrdenados);
            }
        }
        setLoading(false);
        setRefreshing(false);
    };

    const onRefresh = () => {
        setRefreshing(true);
        carregarDados();
    };

    // Função separada para filtrar
    const aplicarFiltro = (texto, dadosOrigem) => {
        if (texto.trim() === '') {
            setListaFiltrada([]); // Limpa a lista se a busca estiver vazia
        } else {
            const filtro = dadosOrigem.filter(item => 
                String(item.paciente_id_fk).includes(texto) || 
                item.nome_exame.toLowerCase().includes(texto.toLowerCase())
            );
            setListaFiltrada(filtro);
        }
    };

    // Handler do Input
    const handleBusca = (texto) => {
        setBusca(texto);
        aplicarFiltro(texto, listaExames);
    };

    // --- AÇÕES ---
    const abrirEdicao = (item) => {
        setExameEdit(item);
        setNovoValor(item.valor_absoluto);
        setModalVisible(true);
    };

    const salvarEdicao = async () => {
        if (!novoValor) return;
        const exameAtualizado = { ...exameEdit, valor_absoluto: novoValor };
        
        const res = await UpdateResultadoExamesFromAPI(exameEdit.id_exame, exameAtualizado);
        if (res) {
            Toast.show({ type: 'success', text1: 'Sucesso', text2: 'Resultado atualizado.' });
            setModalVisible(false);
            carregarDados();
        } else {
            Toast.show({ type: 'error', text1: 'Erro', text2: 'Falha ao atualizar.' });
        }
    };

    const excluirExame = async (id) => {
        const res = await DeleteResultadoExamesFromAPI(id);
        if (res && res.success !== false) {
            Toast.show({ type: 'success', text1: 'Excluído', text2: 'Item removido.' });
            carregarDados();
        } else {
            Toast.show({ type: 'error', text1: 'Erro', text2: 'Falha ao excluir.' });
        }
    };

    return (
        <View style={Estilo.container}>
            <Header />
            <User nomeUsuario="Técnico Lab" />
            <PageAtual Pageatual="Exames Concluídos" />

            <View style={Estilo.content}>
                {/* BARRA DE BUSCA */}
                <View style={Estilo.searchContainer}>
                    <Feather name="search" size={20} color="#666" style={{ marginRight: 10 }} />
                    <TextInput 
                        style={Estilo.searchInput}
                        placeholder="Pesquise por ID do Paciente ou Exame..."
                        value={busca}
                        onChangeText={handleBusca}
                        keyboardType="default"
                    />
                    {busca.length > 0 && (
                        <TouchableOpacity onPress={() => handleBusca('')}>
                            <Feather name="x" size={18} color="#999" />
                        </TouchableOpacity>
                    )}
                </View>

                {/* CONTEÚDO DA TELA */}
                {loading && !refreshing ? (
                    <ActivityIndicator size="large" color={COR_DESTAQUE} style={{ marginTop: 50 }} />
                ) : (
                    <>
                        {/* CASO 1: BUSCA VAZIA (Mostra instrução) */}
                        {busca.trim() === '' ? (
                            <View style={Estilo.emptySearchContainer}>
                                <View style={Estilo.iconCircle}>
                                    <Feather name="search" size={40} color={COR_DESTAQUE} />
                                </View>
                                <Text style={Estilo.emptySearchTitle}>Comece a pesquisar</Text>
                                <Text style={Estilo.emptySearchText}>
                                    Digite o ID do paciente ou o nome do exame para ver o histórico.
                                </Text>
                            </View>
                        ) : (
                            /* CASO 2: TEM BUSCA (Mostra lista ou "não encontrado") */
                            <ScrollView 
                                showsVerticalScrollIndicator={false}
                                refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}
                            >
                                {listaFiltrada.length === 0 ? (
                                    <Text style={Estilo.emptyText}>Nenhum resultado encontrado para "{busca}".</Text>
                                ) : (
                                    listaFiltrada.map((item) => (
                                        <View key={item.id_exame} style={Estilo.card}>
                                            
                                            <View style={Estilo.cardHeader}>
                                                <View style={Estilo.badgeId}>
                                                    <Feather name="user" size={12} color="#004D40" style={{marginRight: 4}} />
                                                    <Text style={Estilo.textId}>ID Paciente: {item.paciente_id_fk}</Text>
                                                </View>
                                                <Text style={Estilo.data}>
                                                    {new Date(item.data_hora_exame).toLocaleDateString('pt-BR')}
                                                </Text>
                                            </View>

                                            <View style={Estilo.cardBody}>
                                                <Text style={Estilo.nomeExame}>{item.nome_exame}</Text>
                                                <Text style={Estilo.tipo}>{item.tipo_exame}</Text>
                                                
                                                <View style={Estilo.rowValor}>
                                                    <Text style={Estilo.labelValor}>Resultado:</Text>
                                                    <Text style={Estilo.valor}>{item.valor_absoluto}</Text>
                                                </View>
                                            </View>

                                            <View style={Estilo.cardActions}>
                                                <TouchableOpacity style={Estilo.btnEdit} onPress={() => abrirEdicao(item)}>
                                                    <Feather name="edit-2" size={16} color="#FFF" />
                                                    <Text style={Estilo.btnText}>Corrigir</Text>
                                                </TouchableOpacity>
                                                
                                                <TouchableOpacity style={Estilo.btnDelete} onPress={() => excluirExame(item.id_exame)}>
                                                    <Feather name="trash-2" size={18} color="#dc3545" />
                                                </TouchableOpacity>
                                            </View>
                                        </View>
                                    ))
                                )}
                                <View style={{ height: 50 }} />
                            </ScrollView>
                        )}
                    </>
                )}
            </View>

            {/* MODAL DE EDIÇÃO */}
            <Modal transparent={true} visible={modalVisible} animationType="fade" onRequestClose={() => setModalVisible(false)}>
                <View style={Estilo.modalOverlay}>
                    <View style={Estilo.modalContent}>
                        <Text style={Estilo.modalTitle}>Corrigir Valor</Text>
                        {exameEdit && (
                            <Text style={Estilo.modalSub}>
                                {exameEdit.nome_exame} (Paciente ID: {exameEdit.paciente_id_fk})
                            </Text>
                        )}
                        
                        <TextInput 
                            style={Estilo.inputModal}
                            value={novoValor}
                            onChangeText={setNovoValor}
                            placeholder="Digite o valor correto"
                            autoFocus
                        />

                        <View style={Estilo.modalButtons}>
                            <TouchableOpacity style={Estilo.btnCancel} onPress={() => setModalVisible(false)}>
                                <Text style={Estilo.btnCancelText}>Cancelar</Text>
                            </TouchableOpacity>
                            <TouchableOpacity style={Estilo.btnSave} onPress={salvarEdicao}>
                                <Text style={Estilo.btnSaveText}>Salvar Alteração</Text>
                            </TouchableOpacity>
                        </View>
                    </View>
                </View>
            </Modal>
        </View>
    );
}

const Estilo = StyleSheet.create({
    container: { flex: 1, backgroundColor: '#f5f5f5' },
    content: { padding: 20, flex: 1 },
    
    searchContainer: {
        flexDirection: 'row',
        backgroundColor: '#FFF',
        borderRadius: 8,
        paddingHorizontal: 15,
        height: 50,
        alignItems: 'center',
        marginBottom: 10,
        elevation: 2
    },
    searchInput: { flex: 1, fontSize: 16 },
    emptyText: { textAlign: 'center', color: '#888', marginTop: 50, fontSize: 16 },

    // ESTADO VAZIO INICIAL
    emptySearchContainer: { alignItems: 'center', justifyContent: 'center', marginTop: 80 },
    iconCircle: {
        width: 80, height: 80, borderRadius: 40,
        backgroundColor: '#E0F2F1', // Verde bem clarinho
        alignItems: 'center', justifyContent: 'center', marginBottom: 15
    },
    emptySearchTitle: { fontSize: 18, fontWeight: 'bold', color: COR_FUNDO_ESCURO },
    emptySearchText: { fontSize: 14, color: '#666', textAlign: 'center', width: '70%', marginTop: 5 },

    // CARD
    card: {
        backgroundColor: '#FFF',
        borderRadius: 12,
        padding: 15,
        marginBottom: 15,
        elevation: 3,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 4,
        borderLeftWidth: 5,
        borderLeftColor: COR_DESTAQUE
    },
    cardHeader: { 
        flexDirection: 'row', 
        justifyContent: 'space-between', 
        alignItems: 'center',
        marginBottom: 12,
        borderBottomWidth: 1,
        borderBottomColor: '#f0f0f0',
        paddingBottom: 8
    },
    badgeId: { 
        flexDirection: 'row',
        backgroundColor: '#B2DFDB', 
        paddingHorizontal: 8, 
        paddingVertical: 4, 
        borderRadius: 6,
        alignItems: 'center'
    },
    textId: { color: '#00695C', fontWeight: 'bold', fontSize: 13 },
    data: { color: '#888', fontSize: 12 },

    cardBody: { marginBottom: 15 },
    nomeExame: { fontSize: 18, fontWeight: 'bold', color: '#333' },
    tipo: { fontSize: 13, color: '#777', marginBottom: 8 },
    
    rowValor: { 
        flexDirection: 'row', 
        alignItems: 'center', 
        backgroundColor: '#F9F9F9', 
        padding: 10, 
        borderRadius: 8 
    },
    labelValor: { fontSize: 14, color: '#555', marginRight: 8 },
    valor: { fontSize: 18, fontWeight: 'bold', color: COR_FUNDO_ESCURO },

    cardActions: { flexDirection: 'row', justifyContent: 'flex-end', alignItems: 'center' },
    btnEdit: { 
        flexDirection: 'row', 
        backgroundColor: COR_FUNDO_ESCURO, 
        paddingHorizontal: 15, 
        paddingVertical: 8, 
        borderRadius: 6, 
        alignItems: 'center', 
        marginRight: 12 
    },
    btnText: { color: '#FFF', marginLeft: 6, fontWeight: 'bold', fontSize: 13 },
    btnDelete: { padding: 8, backgroundColor: '#ffebee', borderRadius: 6 },

    // MODAL
    modalOverlay: { flex: 1, backgroundColor: 'rgba(0,0,0,0.6)', justifyContent: 'center', alignItems: 'center' },
    modalContent: { width: '85%', backgroundColor: '#FFF', borderRadius: 12, padding: 20, elevation: 10 },
    modalTitle: { fontSize: 20, fontWeight: 'bold', color: COR_FUNDO_ESCURO, marginBottom: 5 },
    modalSub: { fontSize: 14, color: '#666', marginBottom: 20 },
    inputModal: { borderWidth: 1, borderColor: '#ccc', borderRadius: 8, padding: 12, fontSize: 16, marginBottom: 20 },
    modalButtons: { flexDirection: 'row', justifyContent: 'flex-end' },
    btnCancel: { padding: 12, marginRight: 15 },
    btnCancelText: { color: '#666', fontWeight: '600' },
    btnSave: { backgroundColor: COR_DESTAQUE, paddingVertical: 12, paddingHorizontal: 20, borderRadius: 8 },
    btnSaveText: { color: '#FFF', fontWeight: 'bold' }
});