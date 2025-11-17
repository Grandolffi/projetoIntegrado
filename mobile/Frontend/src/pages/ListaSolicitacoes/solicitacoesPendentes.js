import React, { useState, useEffect } from 'react';
import { View, StyleSheet, Text, ScrollView, TouchableOpacity, Modal, SafeAreaView } from "react-native";
import { Feather } from '@expo/vector-icons';
import Header from "../../components/Header";
import User from "../../components/User";
import PageAtual from "../../components/PageAtual";
import { LoadSolicitacoesFromAPI } from '../../API/Solicitacoes';  // API que carrega as solicitações pendentes

export default function ListaSolicitacoes(){
    const [solicitacoes, setSolicitacoes] = useState([]);
    const [modalVisible, setModalVisible] = useState(false);
    const [solicitacaoSelecionada, setSolicitacaoSelecionada] = useState(null);

    useEffect(()=>{
        getSolicitacoes();
    }, []);

    const getSolicitacoes = async () => {
        const solicitacoes = await LoadSolicitacoesFromAPI(); // Carregar dados da API
        if (solicitacoes){
            setSolicitacoes(solicitacoes);
        } else {
            setSolicitacoes([]);
        }
    };

    const openModal = (solicitacao) => {
        setSolicitacaoSelecionada(solicitacao);
        setModalVisible(true);
    };

    const handleAcao = (acao) => {
        alert(`${acao} a solicitação ID: ${solicitacaoSelecionada.idSolicitacao}`);
        setModalVisible(false);
    };

    // Definições de largura
    const LARGURA_COLUNA = 120;
    const LARGURA_ID_SOLICITACAO = 120;
    const LARGURA_SOLICITANTE = 120;
    const LARGURA_STATUS = 100;
    const LARGURA_ACAO = 50;

    // Renderização da Tabela
    const renderRow = ({ item, index }) => (
        <View key={index} style={Estilo.linhaTabela}>
            <View style={[Estilo.celula, { width: LARGURA_ID_SOLICITACAO }]}>
                <Text style={Estilo.textoLinha}>{item.idSolicitacao}</Text>
            </View>

            <ScrollView 
                horizontal
                showsHorizontalScrollIndicator={false}
                contentContainerStyle={Estilo.scrollHorizontalContent}
            >
                <View style={Estilo.dadosSecundarios}>
                    <Text style={[Estilo.textoLinha, { width: LARGURA_COLUNA }]}>{item.dataSolicitacao}</Text>
                    <Text style={[Estilo.textoLinha, { width: LARGURA_SOLICITANTE }]}>{item.solicitante}</Text>
                    <Text style={[Estilo.textoLinha, { width: LARGURA_STATUS }]}>{item.status}</Text>
                </View>
            </ScrollView>

            <TouchableOpacity 
                style={Estilo.celulaAcoes}
                onPress={() => openModal(item)}
            >
                <Feather name="more-vertical" size={20} color="#666" />
            </TouchableOpacity>
        </View>
    );

    return(
        <SafeAreaView style={Estilo.container}>
            <Header />
            <User nomeUsuario="Fernanda" />
            <PageAtual Pageatual="Solicitações Pendentes" />

            <View style={[Estilo.listaCard, { flex: 1 }]}>
                <ScrollView showsVerticalScrollIndicator={false}>
                    <ScrollView horizontal showsHorizontalScrollIndicator={false} contentContainerStyle={{ paddingHorizontal: 16 }}>
                        <View>
                            {/* Cabeçalho da Tabela */}
                            <View style={Estilo.cabecalhoTabela}>
                                <Text style={[Estilo.cabecalhoTexto, { width: LARGURA_ID_SOLICITACAO }]}>ID Solicitação</Text>
                                <Text style={[Estilo.cabecalhoTexto, { width: LARGURA_COLUNA }]}>Data Solicitação</Text>
                                <Text style={[Estilo.cabecalhoTexto, { width: LARGURA_SOLICITANTE }]}>Solicitante</Text>
                                <Text style={[Estilo.cabecalhoTexto, { width: LARGURA_STATUS }]}>Status</Text>
                                <View style={{ width: LARGURA_ACAO }} />
                            </View>

                            {/* Linhas de Dados */}
                            {solicitacoes.map((item, index) => renderRow({ item, index }))}
                        </View>
                    </ScrollView>
                </ScrollView>
            </View>

            {/* Modal de Ações */}
            <Modal
                animationType="fade"
                transparent={true}
                visible={modalVisible}
                onRequestClose={() => setModalVisible(false)}
            >
                <TouchableOpacity 
                    style={Estilo.modalOverlay}
                    activeOpacity={1}
                    onPressOut={() => setModalVisible(false)}
                >
                    <View style={Estilo.modalView}>
                        <TouchableOpacity style={Estilo.modalOption} onPress={() => handleAcao('Preencher Resultados')}>
                            <Text style={Estilo.modalText}>Preencher Resultados</Text>
                        </TouchableOpacity>
                        <TouchableOpacity style={Estilo.modalOption} onPress={() => handleAcao('Cancelar Solicitação')}>
                            <Text style={[Estilo.modalText, { color: '#dc3545' }]}>Cancelar Solicitação</Text>
                        </TouchableOpacity>
                    </View>
                </TouchableOpacity>
            </Modal>
        </SafeAreaView>
    );
}

const Estilo = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#f3f3f3',
    },
    listaCard: {
        backgroundColor: '#fff',
        marginTop: 12,
        borderRadius: 12,
        paddingBottom: 8,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.08,
        shadowRadius: 6,
        elevation: 4,
    },
    cabecalhoTabela: {
        flexDirection: 'row',
        backgroundColor: '#f7f7f7',
        borderBottomWidth: 1,
        borderBottomColor: '#e5e5e5',
        paddingVertical: 12,
        elevation: 2,
    },
    cabecalhoTexto: {
        fontSize: 13,
        fontWeight: '700',
        color: '#333',
        letterSpacing: 0.3,
    },
    linhaTabela: {
        flexDirection: 'row',
        backgroundColor: '#fff',
        minHeight: 52,
        borderBottomWidth: 1,
        borderBottomColor: '#f0f0f0',
    },
    celula: {
        justifyContent: 'center',
        paddingLeft: 16,
    },
    textoLinha: {
        fontSize: 14,
        color: '#444',
        fontWeight: '500',
    },
    dadosSecundarios: {
        flexDirection: 'row',
        alignItems: 'center',
    },
    scrollHorizontalContent: {
        paddingRight: 10,
    },
    celulaAcoes: {
        width: 45,
        alignItems: 'center',
        justifyContent: 'center',
    },
    modalOverlay: {
        flex: 1,
        backgroundColor: 'rgba(0,0,0,0.5)',
        alignItems: 'flex-end',
        justifyContent: 'flex-start',
        paddingTop: 150,
        paddingRight: 20,
    },
    modalView: {
        width: 150,
        backgroundColor: '#fff',
        borderRadius: 10,
        paddingVertical: 8,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 6 },
        shadowOpacity: 0.2,
        shadowRadius: 6,
        elevation: 7,
    },
    modalOption: {
        paddingVertical: 12,
        paddingHorizontal: 15,
    },
    modalText: {
        fontSize: 16,
        color: '#333',
    },
});
