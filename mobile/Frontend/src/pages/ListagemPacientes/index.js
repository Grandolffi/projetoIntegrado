import React, { useState } from 'react';
import { View, StyleSheet, Text, ScrollView, TouchableOpacity, Modal, SafeAreaView } from "react-native";
import { Feather } from '@expo/vector-icons';
import Header from "../../components/Header";
import User from "../../components/User";
import PageAtual from "../../components/PageAtual";

// --- DADOS DE TESTE (Para simular a API) ---
const DADOS_PACIENTES = [
    { 
        id: 1, 
        nome: "João Silva", 
        cpf: "12345678901", 
        nasc: "1990-03-12", 
        email: "joao.silva@teste.com",
        nomeMae: "Maria Silva", 
        telefone: "11987654321", 
        genero: "Masculino" 
    },
    { 
        id: 2, 
        nome: "Ana Costa", 
        cpf: "98765432109", 
        nasc: "1995-06-15", 
        email: "ana.costa@teste.com",
        nomeMae: "Helena Costa", 
        telefone: "21999887766", 
        genero: "Feminino" 
    },
];

export default function ListaPacientes(){
    const [modalVisible, setModalVisible] = useState(false);
    const [pacienteSelecionado, setPacienteSelecionado] = useState(null);

    const openModal = (paciente) => {
        setPacienteSelecionado(paciente);
        setModalVisible(true);
    };

    const handleAcao = (acao) => {
        alert(`${acao} o paciente ID: ${pacienteSelecionado.id}`);
        setModalVisible(false);
    };

    // Definições de Largura para COERÊNCIA
    const LARGURA_COLUNA = 100;
    const LARGURA_NOME = 120;
    const LARGURA_EMAIL = 150;
    const LARGURA_ACAO = 40;

    // --- RENDERIZAÇÃO DA TABELA (Uma linha) ---
    const renderRow = (item) => (
        // Linha da Tabela
        <View key={item.id} style={Estilo.linhaTabela}>
            
            {/* O Cartão de Dados principal (Nome e CPF) - FIXO */}
            <View style={[Estilo.celulaPrincipal, { width: LARGURA_NOME }]}>
                <Text style={Estilo.celulaTextoBold}>{item.nome}</Text>
                <Text style={Estilo.celulaSubTexto}>CPF: {item.cpf}</Text>
            </View>

            {/* O CONTEÚDO SCROLLÁVEL HORIZONTALMENTE */}
            <ScrollView 
                horizontal={true} 
                showsHorizontalScrollIndicator={false} 
                contentContainerStyle={Estilo.scrollHorizontalContent}
            >
                <View style={Estilo.dadosSecundarios}>
                    <Text style={[Estilo.celulaTexto, { width: LARGURA_COLUNA }]}>{item.nasc}</Text>
                    <Text style={[Estilo.celulaTexto, { width: LARGURA_EMAIL }]}>{item.email}</Text>
                    <Text style={[Estilo.celulaTexto, { width: LARGURA_COLUNA }]}>{item.nomeMae}</Text>
                    <Text style={[Estilo.celulaTexto, { width: LARGURA_COLUNA }]}>{item.telefone}</Text>
                    <Text style={[Estilo.celulaTexto, { width: LARGURA_COLUNA }]}>{item.genero}</Text>
                </View>
            </ScrollView>

            {/* Botão de Ações (Fixo à direita) */}
            <TouchableOpacity 
                style={[Estilo.botaoAcoes, { width: LARGURA_ACAO }]}
                onPress={() => openModal(item)}
            >
                <Feather name="more-vertical" size={20} color="#0A212F" />
            </TouchableOpacity>

        </View>
    );

    return(
        <SafeAreaView style={Estilo.container}>
            <Header />
            <User nomeUsuario="Fernanda" />
            <PageAtual Pageatual="Listagem de Pacientes" />

            {/* ScrollView Principal (Vertical) para todo o conteúdo abaixo do PageAtual */}
            <ScrollView contentContainerStyle={Estilo.scrollVerticalContent}>
                
                <View style={Estilo.listaCard}> {/* ESTE É O NOVO CARD QUE REPLICA O CAMPO CONTAINER */}

                    {/* Títulos da Tabela (Fixo no topo da lista) */}
                    <View style={Estilo.cabecalhoTabela}>
                        <View style={[Estilo.cabecalhoTextoContainer, { width: LARGURA_NOME }]}>
                            <Text style={Estilo.cabecalhoTexto}>Paciente</Text>
                        </View>
                        
                        <ScrollView horizontal={true} showsHorizontalScrollIndicator={false} contentContainerStyle={Estilo.scrollHorizontalContent}>
                            <View style={Estilo.dadosSecundarios}>
                                <Text style={[Estilo.cabecalhoTexto, { width: LARGURA_COLUNA }]}>Nascimento</Text>
                                <Text style={[Estilo.cabecalhoTexto, { width: LARGURA_EMAIL }]}>Email</Text>
                                <Text style={[Estilo.cabecalhoTexto, { width: LARGURA_COLUNA }]}>Mãe</Text>
                                <Text style={[Estilo.cabecalhoTexto, { width: LARGURA_COLUNA }]}>Telefone</Text>
                                <Text style={[Estilo.cabecalhoTexto, { width: LARGURA_COLUNA }]}>Gênero</Text>
                            </View>
                        </ScrollView>
                        <View style={{ width: LARGURA_ACAO }} /> {/* Espaço para o ícone de Ações */}
                    </View>

                    {/* Renderiza as Linhas de Pacientes */}
                    <View style={Estilo.listaConteudo}>
                        {DADOS_PACIENTES.map(renderRow)}
                    </View>

                </View>

            </ScrollView>


            {/* Modal de Ações (Editar/Excluir) */}
            <Modal
                animationType="fade"
                transparent={true}
                visible={modalVisible}
                onRequestClose={() => setModalVisible(false)}
            >
                {/* ... (código do Modal mantido) ... */}
                <TouchableOpacity 
                    style={Estilo.modalOverlay}
                    activeOpacity={1}
                    onPressOut={() => setModalVisible(false)}
                >
                    <View style={Estilo.modalView}>
                        <TouchableOpacity style={Estilo.modalOption} onPress={() => handleAcao('Editar')}>
                            <Text style={Estilo.modalText}>Editar</Text>
                        </TouchableOpacity>
                        <TouchableOpacity style={Estilo.modalOption} onPress={() => handleAcao('Excluir')}>
                            <Text style={[Estilo.modalText, { color: '#dc3545' }]}>Excluir</Text>
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
        backgroundColor: '#f0f0f0'
    },

    // 💡 NOVO: Container do ScrollView Vertical
    scrollVerticalContent: {
        paddingHorizontal: 20,
        paddingBottom: 20, 
    },
    
    // 💡 NOVO: ESTILO DO CARD PRINCIPAL (REPLICAÇÃO do campoContainer)
    listaCard: {
        backgroundColor: '#fff',
        borderRadius: 10,
        // 💡 Margem de 10px para separar do PageAtual
        marginTop: 10, 
        shadowColor: "#000",
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 3.84,
        elevation: 5,
        overflow: 'hidden', // Importante para as bordas arredondadas funcionarem
    },
    listaConteudo: {
        // Conteúdo da lista após o cabeçalho
    },

    // --- CABEÇALHO DA TABELA (Títulos das Colunas) ---
    cabecalhoTabela: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'space-between',
        backgroundColor: '#EAEAEA', // Cor mais clara para o cabeçalho da tabela
        paddingVertical: 10,
    },
    cabecalhoTextoContainer: {
        paddingLeft: 20, // Padding da célula principal
    },
    cabecalhoTexto: {
        fontWeight: 'bold',
        fontSize: 12,
        color: '#333',
        textAlign: 'center', 
    },

    // --- LINHA DA TABELA (Dados do Paciente) ---
    linhaTabela: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#fff',
        borderBottomWidth: 1,
        borderBottomColor: '#eee',
        // Removendo o paddingHorizontal
    },
    // Removendo paddingVertical da linha e colocando nas células
    celulaPrincipal: {
        paddingVertical: 12,
        paddingLeft: 20, // Padding para alinhar com o cabeçalho
    },
    celulaTexto: {
        fontSize: 14,
        color: '#555',
        paddingVertical: 12,
        textAlign: 'center',
        
    },
    celulaTextoBold: {
        fontSize: 14,
        fontWeight: 'bold',
        color: '#0A212F',
        textAlign: 'left',
    },
    celulaSubTexto: {
        fontSize: 12,
        color: '#999',
        textAlign: 'left',
    },
    
    // --- SCROLL HORIZONTAL (Dados Secundários) ---
    scrollHorizontalContent: {
        paddingRight: 10, // Espaço extra para o scroll horizontal não encostar na borda
    },
    dadosSecundarios: {
        flexDirection: 'row',
        alignItems: 'center',
    },

    // --- BOTÃO DE AÇÕES ---
    botaoAcoes: {
        alignItems: 'center',
        justifyContent: 'center',
        paddingRight: 10, // Espaço à direita do ícone
    },

    // --- MODAL (Menu de Ações) ---
    modalOverlay: {
        flex: 1,
        backgroundColor: 'rgba(0, 0, 0, 0.5)',
        alignItems: 'flex-end',
        justifyContent: 'flex-start',
        paddingTop: 150, 
        paddingRight: 20,
    },
    modalView: {
        width: 150,
        backgroundColor: 'white',
        borderRadius: 8,
        paddingVertical: 5,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.25,
        shadowRadius: 4,
        elevation: 5,
    },
    modalOption: {
        padding: 12,
    },
    modalText: {
        fontSize: 16,
        color: '#333',
    },
});