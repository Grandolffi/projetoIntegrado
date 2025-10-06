import React, { useState } from 'react';
import { View, StyleSheet, Text, ScrollView, TouchableOpacity, TextInput, SafeAreaView } from "react-native";
import { Feather } from '@expo/vector-icons';
import Header from "../../components/Header";
import User from "../../components/User";
import PageAtual from "../../components/PageAtual";

// --- DADOS DE TESTE (Baseado em lista_solicitacoes_pendentes.php) ---
const DADOS_SOLICITACOES = [
    { 
        idSolicitacao: 'S-001', 
        idPaciente: 10, 
        dataSolicitacao: "2025-10-06", 
        dataPrevista: "2025-10-08", 
        solicitante: "Dr. André",
        status: "Pendente" 
    },
    { 
        idSolicitacao: 'S-002', 
        idPaciente: 25, 
        dataSolicitacao: "2025-10-05", 
        dataPrevista: "2025-10-07", 
        solicitante: "Dra. Elisa",
        status: "Pendente" 
    },
    { 
        idSolicitacao: 'S-003', 
        idPaciente: 33, 
        dataSolicitacao: "2025-10-04", 
        dataPrevista: "2025-10-06", 
        solicitante: "Enf. Carla",
        status: "Coletado" 
    },
];

export default function ListaSolicitacoes({ navigation }){ 
    const [searchText, setSearchText] = useState('');
    const [solicitacoesFiltradas, setSolicitacoesFiltradas] = useState(DADOS_SOLICITACOES);

    // Função de ação: Leva para a tela de preenchimento (cadastroExames.php no seu backend)
    const handlePreencherResultados = (solicitacao) => {
        // Exemplo: navigation.navigate('CadastroExames', { solicitacaoId: solicitacao.idSolicitacao });
        alert(`Abrir formulário de resultados para Solicitação ID: ${solicitacao.idSolicitacao}`);
    };

    // Definições de Largura para a Tabela Responsiva
    const LARGURA_ID_SOL = 80;
    const LARGURA_PACIENTE_ID = 80;
    const LARGURA_COLUNA_DATA = 120;
    const LARGURA_SOLICITANTE = 120;
    const LARGURA_STATUS = 100;
    const LARGURA_ACAO = 140;


    // --- RENDERIZAÇÃO DA TABELA (Uma linha) ---
    const renderRow = (item) => (
        // Linha da Tabela
        <View key={item.idSolicitacao} style={Estilo.linhaTabela}>
            
            {/* ID da Solicitação e Paciente (FIXO à esquerda) */}
            <View style={[Estilo.celulaPrincipal, { width: LARGURA_ID_SOL + LARGURA_PACIENTE_ID }]}>
                <Text style={Estilo.celulaTextoBold}>ID Sol.: {item.idSolicitacao}</Text>
                <Text style={Estilo.celulaSubTexto}>Paciente: #{item.idPaciente}</Text>
            </View>

            {/* O CONTEÚDO SCROLLÁVEL HORIZONTALMENTE */}
            <ScrollView 
                horizontal={true} 
                showsHorizontalScrollIndicator={false} 
                contentContainerStyle={Estilo.scrollHorizontalContent}
            >
                <View style={Estilo.dadosSecundarios}>
                    {/* Data Solicitação */}
                    <Text style={[Estilo.celulaTexto, { width: LARGURA_COLUNA_DATA }]}>
                        {item.dataSolicitacao}
                    </Text>
                    {/* Data Prevista */}
                    <Text style={[Estilo.celulaTexto, { width: LARGURA_COLUNA_DATA }]}>{item.dataPrevista}</Text>
                    {/* Solicitante */}
                    <Text style={[Estilo.celulaTexto, { width: LARGURA_SOLICITANTE }]}>{item.solicitante}</Text>
                    {/* Status */}
                    <Text style={[Estilo.celulaTexto, { width: LARGURA_STATUS }]}>{item.status}</Text>
                </View>
            </ScrollView>

            {/* Botão de Ações (Fixo à direita) */}
            <TouchableOpacity 
                style={[Estilo.botaoAcoes, { width: LARGURA_ACAO }]}
                onPress={() => handlePreencherResultados(item)}
            >
                 <Text style={Estilo.textoBotaoAcao}>Preencher Resultados</Text>
            </TouchableOpacity>

        </View>
    );

    return(
        <SafeAreaView style={Estilo.container}>
            <Header />
            <User nomeUsuario="Fernanda" />
            <PageAtual Pageatual="Solicitações Pendentes" />

            <ScrollView contentContainerStyle={Estilo.scrollVerticalContent}>
                
                {/* 1. BARRA DE PESQUISA */}
                <View style={Estilo.headerLista}>
                    <View style={Estilo.searchContainer}>
                        <TextInput
                            style={Estilo.searchInput}
                            placeholder="Pesquisar ID, Paciente, Solicitante..."
                            placeholderTextColor="#999"
                            value={searchText}
                            onChangeText={setSearchText}
                        />
                        <TouchableOpacity style={Estilo.searchButton}>
                            <Feather name="search" size={20} color="#0A212F" />
                        </TouchableOpacity>
                    </View>
                </View>


                {/* 2. CARD DA TABELA */}
                <View style={Estilo.listaCard}> 

                    {/* Títulos da Tabela (Fixo no topo da lista) */}
                    <View style={Estilo.cabecalhoTabela}>
                        <View style={[Estilo.cabecalhoTextoContainer, { width: LARGURA_ID_SOL + LARGURA_PACIENTE_ID }]}>
                            <Text style={Estilo.cabecalhoTexto}>Solicitação/Paciente</Text>
                        </View>
                        
                        <ScrollView horizontal={true} showsHorizontalScrollIndicator={false} contentContainerStyle={Estilo.scrollHorizontalContent}>
                            <View style={Estilo.dadosSecundarios}>
                                <Text style={[Estilo.cabecalhoTexto, { width: LARGURA_COLUNA_DATA }]}>Dt. Solicitação</Text>
                                <Text style={[Estilo.cabecalhoTexto, { width: LARGURA_COLUNA_DATA }]}>Previsto para</Text>
                                <Text style={[Estilo.cabecalhoTexto, { width: LARGURA_SOLICITANTE }]}>Solicitante</Text>
                                <Text style={[Estilo.cabecalhoTexto, { width: LARGURA_STATUS }]}>Status</Text>
                            </View>
                        </ScrollView>
                        <View style={{ width: LARGURA_ACAO }} /> {/* Espaço para o botão de Ações */}
                    </View>

                    {/* Renderiza as Linhas das Solicitações */}
                    <View style={Estilo.listaConteudo}>
                        {solicitacoesFiltradas.map(renderRow)}
                    </View>

                </View>

            </ScrollView>
        </SafeAreaView>
    );
}

const Estilo = StyleSheet.create({
    container: {
        flex: 1, 
        backgroundColor: '#f0f0f0'
    },
    scrollVerticalContent: {
        paddingHorizontal: 20,
        paddingBottom: 20, 
    },
    
    // --- ESTILOS DE HEADER (Pesquisa) ---
    headerLista: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        marginTop: 10,
        marginBottom: 10,
    },
    searchContainer: {
        flexDirection: 'row',
        flex: 1,
        backgroundColor: '#fff',
        borderRadius: 8,
        borderWidth: 1,
        borderColor: '#ccc',
        overflow: 'hidden',
        height: 45,
    },
    searchInput: {
        flex: 1,
        paddingHorizontal: 15,
        fontSize: 15,
    },
    searchButton: {
        paddingHorizontal: 12,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#EAEAEA',
    },

    // --- ESTILOS DA TABELA (Copiados dos outros componentes) ---
    listaCard: {
        backgroundColor: '#fff',
        borderRadius: 10,
        shadowColor: "#000",
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 3.84,
        elevation: 5,
        overflow: 'hidden',
    },
    cabecalhoTabela: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'space-between',
        backgroundColor: '#EAEAEA',
        paddingVertical: 10,
    },
    cabecalhoTextoContainer: {
        paddingLeft: 20, 
    },
    cabecalhoTexto: {
        fontWeight: 'bold',
        fontSize: 12,
        color: '#333',
        textAlign: 'center', 
    },
    linhaTabela: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#fff',
        borderBottomWidth: 1,
        borderBottomColor: '#eee',
    },
    celulaPrincipal: {
        paddingVertical: 12,
        paddingLeft: 20,
        // Garante que a célula principal e a sub-célula fiquem juntas
        justifyContent: 'center', 
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
    scrollHorizontalContent: {
        paddingRight: 10, 
    },
    dadosSecundarios: {
        flexDirection: 'row',
        alignItems: 'center',
    },
    botaoAcoes: {
        alignItems: 'center',
        justifyContent: 'center',
        backgroundColor: '#0A212F', // Cor escura para destaque
        paddingHorizontal: 5,
        height: '100%',
    },
    textoBotaoAcao: {
        color: '#fff',
        fontWeight: 'bold',
        fontSize: 12,
        textAlign: 'center',
    }
});