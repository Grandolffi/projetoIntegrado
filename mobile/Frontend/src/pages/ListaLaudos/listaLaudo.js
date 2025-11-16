import React, { useState, useEffect } from 'react';
import { View, StyleSheet, Text, ScrollView, TouchableOpacity, TextInput, SafeAreaView } from "react-native";
import { Feather } from '@expo/vector-icons';
import Header from "../../components/Header";
import User from "../../components/User";
import PageAtual from "../../components/PageAtual";
import { LoadLaudosFromAPI } from '../../API/Laudos';



export default function ListaLaudos({navigation}){ // Adicione 'navigation' se usar o React Navigation
    const [searchText, setSearchText] = useState('');
    const [laudo, setLaudo] = useState([]);

    useEffect(()=>{
         getListaLaudo();
    }, []);

    const getListaLaudo = async () => {
        const laudos = await LoadLaudosFromAPI();
        if (laudos){
            setLaudo(laudos)
        }else{
            setLaudo([])
        }
    }

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
        console.log("olha", item),
        // Linha da Tabela
        <View key={String(item.id_laudo)} style={Estilo.linhaTabela}>
            
            {/* ID do Laudo (FIXO à esquerda) */}
            <View style={[Estilo.celulaPrincipal, { width: LARGURA_ID_LAUDO }]}>
                <Text style={Estilo.celulaTextoBold}>{item.id_laudo}</Text>
                <Text style={Estilo.celulaSubTexto}>Paciente: {item.paciente_id}</Text>
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
                        {/*item.nomeExame*/}
                    </Text>
                    {/* Data de Realização */}
                    <Text style={[Estilo.celulaTexto, { width: LARGURA_COLUNA }]}>{item.data_finalizacao}</Text>
                    {/* Valor Absoluto / Resultado */}
                    <Text style={[Estilo.celulaTexto, { width: LARGURA_COLUNA }]}>{item.observacoes}</Text>
                    {/* Status */}
                    <Text style={[Estilo.celulaTexto, { width: LARGURA_COLUNA }]}>{item.responsavel_tecnico}</Text>
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
                            placeholder="Pesquisar ID paciente"
                            placeholderTextColor="#999"
                            value={searchText}
                            onChangeText={setSearchText}
                        />
                        <TouchableOpacity style={Estilo.searchButton}>
                            <Feather name="search" size={20} color="#0A212F" />
                        </TouchableOpacity>
                    </View>
                    {/* Botão para solicitar novo exame (Navega para a tela do seu parceiro) */}
                    <TouchableOpacity style={Estilo.novoExameButton} onPress={() => navigation.navigate("SolicitarNovoExame")}>
                         <Text style={Estilo.novoExameText}>+ Novo Exame</Text>
                    </TouchableOpacity>
                </View>


                {/* 2. CARD DA TABELA */}
                <View style={Estilo.listaCard}>
    
                    <ScrollView horizontal showsHorizontalScrollIndicator={false}>
                        
                        <View>
                            {/* CABEÇALHO */}
                            <View style={Estilo.cabecalhoTabela}>
                                <View style={[Estilo.cabecalhoTextoContainer, { width: LARGURA_ID_LAUDO }]}>
                                    <Text style={Estilo.cabecalhoTexto}>ID Laudo</Text>
                                </View>

                                <Text style={[Estilo.cabecalhoTexto, { width: LARGURA_COLUNA }]}>Realização</Text>
                                <Text style={[Estilo.cabecalhoTexto, { width: LARGURA_COLUNA }]}>Observações</Text>
                                <Text style={[Estilo.cabecalhoTexto, { width: LARGURA_COLUNA }]}>Responsável</Text>

                                <View style={{ width: LARGURA_ACAO }} />
                            </View>

                            {/* LINHAS */}
                            {laudo.map(item => (
                                <View key={item.id_laudo} style={Estilo.linhaTabela}>
                                    
                                    <View style={[Estilo.celulaPrincipal, { width: LARGURA_ID_LAUDO }]}>
                                        <Text style={Estilo.celulaTextoBold}>{item.id_laudo}</Text>
                                        <Text style={Estilo.celulaSubTexto}>Paciente: {item.paciente_id}</Text>
                                    </View>

                                    <Text style={[Estilo.celulaTexto, { width: LARGURA_COLUNA }]}> {item.responsavel_tecnico} </Text>
                                    <Text style={[Estilo.celulaTexto, { width: LARGURA_COLUNA }]}> {item.observacoes} </Text>
                                    <Text style={[Estilo.celulaTexto, { width: LARGURA_COLUNA }]}> {new Date(item.data_finalizacao).toLocaleDateString('pt-BR')} </Text>
                                    

                                    <TouchableOpacity 
                                        style={[Estilo.botaoAcoes, { width: LARGURA_ACAO }]}
                                        onPress={() => handleViewDetails(item)}
                                    >
                                        <Feather name="eye" size={20} color="#0A212F" />
                                    </TouchableOpacity>

                                </View>
                            ))}

                        </View>

    </ScrollView>

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
        borderRadius: 14,
        shadowColor: "#000",
        shadowOffset: { width: 0, height: 3 },
        shadowOpacity: 0.1,
        shadowRadius: 4.65,
        elevation: 6,
        overflow: 'hidden',
    },

    /* ---------------- CABEÇALHO DA TABELA ---------------- */
    cabecalhoTabela: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#E5EBF1',
        paddingVertical: 12,
        paddingHorizontal: 10,
        borderBottomWidth: 1,
        borderBottomColor: '#D0D8E0',
    },

    cabecalhoTextoContainer: {
        paddingLeft: 20, 
    },

    cabecalhoTexto: {
        fontWeight: '700',
        fontSize: 13,
        color: '#0A212F',
        textAlign: 'center',
    },

    /* ---------------- LINHAS ---------------- */
    linhaTabela: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#FFFFFF',
        paddingVertical: 4,
        borderBottomWidth: 1,
        borderBottomColor: '#E9EEF2',
        minHeight: 55,
    },

    celulaPrincipal: {
        paddingVertical: 10,
        paddingLeft: 20,
        justifyContent: 'center',
    },

    celulaTexto: {
        fontSize: 14,
        color: '#444',
        paddingVertical: 10,
        textAlign: 'center',
    },

    celulaTextoBold: {
        fontSize: 15,
        fontWeight: '700',
        color: '#0A212F',
    },

    celulaSubTexto: {
        fontSize: 12,
        color: '#77838F',
        marginTop: 2,
    },

    dadosSecundarios: {
        flexDirection: 'row',
        alignItems: 'center',
        paddingRight: 8,
    },

    scrollHorizontalContent: {
        paddingRight: 10, 
    },

    /* ---------------- BOTÃO AÇÕES ---------------- */
    botaoAcoes: {
        alignItems: 'center',
        justifyContent: 'center',
        height: '100%',
        paddingRight: 10,
        paddingLeft: 5,
    },
});