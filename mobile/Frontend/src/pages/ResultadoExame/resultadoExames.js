import React from 'react';
import { View, StyleSheet, Text, ScrollView, SafeAreaView, TouchableOpacity } from "react-native";
import { Feather } from '@expo/vector-icons';
import Header from "../../components/Header";
import User from "../../components/User";
import PageAtual from "../../components/PageAtual";

// --- DADOS DE TESTE (Baseado na sua classe PHP ResultadoExames) ---
const DADOS_EXAME = {
    idExame: 105,
    nomeExame: "Glicemia de Jejum",
    tipoExame: "Bioquímica",
    valorAbsoluto: "85",
    valorReferencia: "70 - 99",
    pacienteNome: "João Silva", // Adicionado para exibição
    cpf: "123.456.789-01", // Adicionado para exibição
    dataHora: "2025-10-05 09:30",
    laudoId: "LAUDO-2025-001",
    // Observação: Adicionar o campo "Unidade de Medida" (ex: mg/dL) seria útil na vida real.
};

// Componente auxiliar para exibir um item de detalhe
const DetalheItem = ({ label, valor, isDestaque = false }) => (
    <View style={Estilo.detalheLinha}>
        <Text style={Estilo.detalheLabel}>{label}:</Text>
        <Text style={[Estilo.detalheValor, isDestaque && Estilo.detalheDestaque]}>
            {valor}
        </Text>
    </View>
);

export default function ResultadoExame() {

    const handleImprimir = () => {
        alert('Funcionalidade de Impressão (PDF) em desenvolvimento.');
    };

    return (
        <SafeAreaView style={Estilo.container}>
            <Header />
            <User nomeUsuario="Fernanda" />
            <PageAtual Pageatual="Detalhes do Exame" />

            <ScrollView contentContainerStyle={Estilo.scrollVerticalContent}>
                
                {/* 1. CARD DE INFORMAÇÕES BÁSICAS */}
                <View style={Estilo.infoCard}>
                    <Text style={Estilo.cardTitulo}>{DADOS_EXAME.nomeExame}</Text>
                    
                    <DetalheItem label="ID do Laudo" valor={DADOS_EXAME.laudoId} isDestaque={true} />
                    <DetalheItem label="Data e Hora" valor={DADOS_EXAME.dataHora.replace(' ', ' às ')} />
                    <DetalheItem label="Tipo de Exame" valor={DADOS_EXAME.tipoExame} />
                </View>

                {/* 2. CARD DO PACIENTE */}
                <View style={Estilo.infoCard}>
                    <Text style={Estilo.cardTitulo}>Informações do Paciente</Text>
                    <DetalheItem label="Nome" valor={DADOS_EXAME.pacienteNome} />
                    <DetalheItem label="CPF" valor={DADOS_EXAME.cpf} />
                    <DetalheItem label="Registro Interno" valor={`#${DADOS_EXAME.idExame - 100}`} />
                </View>


                {/* 3. CARD DO RESULTADO */}
                <View style={[Estilo.infoCard, Estilo.resultadoCard]}>
                    <Text style={Estilo.cardTitulo}>Resultado Encontrado</Text>
                    
                    <View style={Estilo.resultadoValores}>
                        <View style={Estilo.valorContainer}>
                            <Text style={Estilo.valorLabel}>VALOR ABSOLUTO</Text>
                            {/* Destaque para o valor principal */}
                            <Text style={Estilo.valorAbsoluto}>{DADOS_EXAME.valorAbsoluto}</Text> 
                        </View>
                        
                        <View style={Estilo.referenciaContainer}>
                            <Text style={Estilo.referenciaLabel}>VALOR DE REFERÊNCIA</Text>
                            <Text style={Estilo.referenciaValor}>{DADOS_EXAME.valorReferencia}</Text>
                        </View>
                    </View>
                </View>
            </ScrollView>

            {/* Botão Fixo no Rodapé: Imprimir */}
            <View style={Estilo.botaoFixoContainer}>
                <TouchableOpacity 
                    style={Estilo.botao} 
                    activeOpacity={0.8} 
                    onPress={handleImprimir}
                >
                    <Feather name="printer" size={24} color="#fff" style={{ marginRight: 10 }} />
                    <Text style={Estilo.textoBotao}>Imprimir Laudo</Text>
                </TouchableOpacity>
            </View>
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
        paddingBottom: 100, // Espaço para não cobrir o botão fixo
    },
    
    // --- ESTILO CARD PRINCIPAL (baseado em listaCard/campoContainer) ---
    infoCard: {
        backgroundColor: '#fff',
        padding: 20, 
        borderRadius: 10,
        marginTop: 15, 
        shadowColor: "#000",
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 3.84,
        elevation: 5,
    },
    cardTitulo: {
        fontSize: 18,
        fontWeight: 'bold',
        color: '#0A212F',
        marginBottom: 15,
        borderBottomWidth: 1,
        borderBottomColor: '#eee',
        paddingBottom: 10,
    },

    // --- ESTILO DETALHE ITEM (Linhas de Informação) ---
    detalheLinha: {
        flexDirection: 'row',
        marginBottom: 10,
    },
    detalheLabel: {
        fontSize: 15,
        color: '#555',
        fontWeight: '600',
        width: 140, // Largura fixa para alinhar os valores
    },
    detalheValor: {
        flex: 1,
        fontSize: 15,
        color: '#333',
    },
    detalheDestaque: {
        fontWeight: 'bold',
        color: '#0A212F',
    },

    // --- CARD DE RESULTADO (Destaque) ---
    resultadoCard: {
        borderLeftWidth: 5, // Borda lateral para dar um visual de destaque
        borderLeftColor: '#0A212F',
    },
    resultadoValores: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        marginTop: 10,
    },
    valorContainer: {
        flex: 1,
        alignItems: 'center',
        borderRightWidth: 1,
        borderRightColor: '#eee',
        paddingRight: 10,
    },
    referenciaContainer: {
        flex: 1,
        alignItems: 'center',
        paddingLeft: 10,
    },
    valorLabel: {
        fontSize: 14,
        color: '#999',
        marginBottom: 5,
    },
    valorAbsoluto: {
        fontSize: 36,
        fontWeight: 'bold',
        color: '#28A745', // Verde para indicar resultado normal (simulação)
        // Você usaria lógica aqui para mudar para vermelho (ex: '#DC3545') se fosse anormal
    },
    referenciaLabel: {
        fontSize: 14,
        color: '#999',
        marginBottom: 5,
    },
    referenciaValor: {
        fontSize: 16,
        color: '#333',
        fontWeight: 'bold',
    },

    // --- BOTÃO FIXO (BOTTOM BAR - Copiado de CadastroPaciente) ---
    botaoFixoContainer: {
        position: 'absolute',
        bottom: 0,
        left: 0,
        right: 0,
        paddingHorizontal: 20,
        paddingVertical: 15,
        alignItems: 'center',
        backgroundColor: '#f0f0f0',
        borderTopWidth: 1,
        borderTopColor: '#eee',
        zIndex: 10,
    },
    botao: {
        flexDirection: 'row',
        backgroundColor: '#0A212F',
        borderRadius: 12,
        width: '100%',
        paddingVertical: 18,
        alignItems: 'center',
        justifyContent: 'center', // Centraliza o texto e o ícone
        shadowColor: "#000",
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.3,
        shadowRadius: 5.46,
        elevation: 9,
    }, 
    textoBotao: {
        color: '#fff',
        fontSize: 18,
        fontWeight: 'bold',
    }
});