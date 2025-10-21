import React, { useState } from 'react';
import { View, StyleSheet, Text, ScrollView, TouchableOpacity, TextInput, SafeAreaView } from "react-native";
import { Feather } from '@expo/vector-icons';
import Header from "../../components/Header";
import User from "../../components/User";
import PageAtual from "../../components/PageAtual";

// --- DADOS DE TESTE (Simulando uma lista de Laudos) ---
const DADOS_LAUDOS = [
    { 
        idLaudo: 'LDR-001', 
        idPaciente: 1, 
        nomeExame: "Hemograma Completo", 
        dataRealizacao: "2025-10-01", 
        valorAbsoluto: "Normal", 
        status: "Concluído" 
    },
    { 
        idLaudo: 'LDR-002', 
        idPaciente: 2, 
        nomeExame: "Glicemia de Jejum", 
        dataRealizacao: "2025-09-28", 
        valorAbsoluto: "85 mg/dL", 
        status: "Concluído" 
    },
    { 
        idLaudo: 'LDR-003', 
        idPaciente: 3, 
        nomeExame: "Urocultura", 
        dataRealizacao: "2025-10-05", 
        valorAbsoluto: "Pendente", 
        status: "Em Análise" 
    },
];

export default function ListaLaudos({ navigation }){ // Adicione 'navigation' se usar o React Navigation
    const [searchText, setSearchText] = useState('');
    const [laudosFiltrados, setLaudosFiltrados] = useState(DADOS_LAUDOS);

    // Função que seria chamada ao clicar na linha
    const handleViewDetails = (laudo) => {
        // Exemplo: navigation.navigate('ResultadoExame', { laudoId: laudo.idLaudo });
        alert(`Abrir detalhes do Laudo ID: ${laudo.idLaudo}`);
    };

    // Definições de Largura para a Tabela Responsiva
    const LARGURA_ID_LAUDO = 80;
    const LARGURA_NOME_EXAME = 150;
    const LARGURA_COLUNA = 100;
    const LARGURA_ACAO = 50;


    // --- RENDERIZAÇÃO DA TABELA (Uma linha) ---
    const renderRow = (item) => (
        // Linha da Tabela
        <View key={item.idLaudo} style={Estilo.linhaTabela}>
            
            {/* ID do Laudo (FIXO à esquerda) */}
            <View style={[Estilo.celulaPrincipal, { width: LARGURA_ID_LAUDO }]}>
                <Text style={Estilo.celulaTextoBold}>{item.idLaudo}</Text>
                <Text style={Estilo.celulaSubTexto}>Paciente: {item.idPaciente}</Text>
            </View>

            {/* O CONTEÚDO SCROLLÁVEL HORIZONTALMENTE */}
            <ScrollView 
                horizontal={true} 
                showsHorizontalScrollIndicator={false} 
                contentContainerStyle={Estilo.scrollHorizontalContent}
            >
                <View style={Estilo.dadosSecundarios}>
                    {/* Nome do Exame */}
                    <Text style={[Estilo.celulaTexto, { width: LARGURA_NOME_EXAME, textAlign: 'left' }]}>
                        {item.nomeExame}
                    </Text>
                    {/* Data de Realização */}
                    <Text style={[Estilo.celulaTexto, { width: LARGURA_COLUNA }]}>{item.dataRealizacao}</Text>
                    {/* Valor Absoluto / Resultado */}
                    <Text style={[Estilo.celulaTexto, { width: LARGURA_COLUNA }]}>{item.valorAbsoluto}</Text>
                    {/* Status */}
                    <Text style={[Estilo.celulaTexto, { width: LARGURA_COLUNA }]}>{item.status}</Text>
                </View>
            </ScrollView>

            {/* Botão de Ações / Visualização (Fixo à direita) */}
            <TouchableOpacity 
                style={[Estilo.botaoAcoes, { width: LARGURA_ACAO }]}
                onPress={() => handleViewDetails(item)}
            >
                <Feather name="eye" size={20} color="#0A212F" />
            </TouchableOpacity>

        </View>
    );

    return(
        <SafeAreaView style={Estilo.container}>
            <Header />
            <User nomeUsuario="Fernanda" />
            <PageAtual Pageatual="Listagem de Laudos" />

            <ScrollView contentContainerStyle={Estilo.scrollVerticalContent}>
                
                {/* 1. BARRA DE PESQUISA E BOTÃO DE NOVO EXAME (Baseado em lista_de_exames.php) */}
                <View style={Estilo.headerLista}>
                    <View style={Estilo.searchContainer}>
                        <TextInput
                            style={Estilo.searchInput}
                            placeholder="Pesquisar ID, Paciente, Exame..."
                            placeholderTextColor="#999"
                            value={searchText}
                            onChangeText={setSearchText}
                        />
                        <TouchableOpacity style={Estilo.searchButton}>
                            <Feather name="search" size={20} color="#0A212F" />
                        </TouchableOpacity>
                    </View>
                    {/* Botão para solicitar novo exame (Navega para a tela do seu parceiro) */}
                    <TouchableOpacity style={Estilo.novoExameButton} onPress={() => alert("Navegar para NovoExame")}>
                         <Text style={Estilo.novoExameText}>+ Novo Exame</Text>
                    </TouchableOpacity>
                </View>


                {/* 2. CARD DA TABELA */}
                <View style={Estilo.listaCard}> 

                    {/* Títulos da Tabela (Fixo no topo da lista) */}
                    <View style={Estilo.cabecalhoTabela}>
                        <View style={[Estilo.cabecalhoTextoContainer, { width: LARGURA_ID_LAUDO }]}>
                            <Text style={Estilo.cabecalhoTexto}>ID Laudo</Text>
                        </View>
                        
                        <ScrollView horizontal={true} showsHorizontalScrollIndicator={false} contentContainerStyle={Estilo.scrollHorizontalContent}>
                            <View style={Estilo.dadosSecundarios}>
                                <Text style={[Estilo.cabecalhoTexto, { width: LARGURA_NOME_EXAME, textAlign: 'left' }]}>Exame</Text>
                                <Text style={[Estilo.cabecalhoTexto, { width: LARGURA_COLUNA }]}>Realização</Text>
                                <Text style={[Estilo.cabecalhoTexto, { width: LARGURA_COLUNA }]}>Resultado</Text>
                                <Text style={[Estilo.cabecalhoTexto, { width: LARGURA_COLUNA }]}>Status</Text>
                            </View>
                        </ScrollView>
                        <View style={{ width: LARGURA_ACAO }} /> {/* Espaço para o ícone de Ações */}
                    </View>

                    {/* Renderiza as Linhas dos Laudos */}
                    <View style={Estilo.listaConteudo}>
                        {laudosFiltrados.map(renderRow)}
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
    
    // --- ESTILOS DE HEADER (Pesquisa e Botão) ---
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
        marginRight: 10,
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
    novoExameButton: {
        backgroundColor: '#0A212F',
        borderRadius: 8,
        paddingHorizontal: 12,
        height: 45,
        justifyContent: 'center',
    },
    novoExameText: {
        color: '#fff',
        fontWeight: 'bold',
        fontSize: 14,
    },

    // --- ESTILOS DA TABELA (Copiados de ListaPacientes) ---
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
        paddingRight: 10,
        height: '100%',
    },
});