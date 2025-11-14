import React, { useState } from 'react';
import { View, StyleSheet, Text, TextInput, TouchableOpacity, ScrollView, Alert, ActivityIndicator, Dimensions } from "react-native";
import { Feather } from '@expo/vector-icons';
import Header from "../../components/Header";
import User from "../../components/User";
import PageAtual from "../../components/PageAtual";
import { CreateResultadoFromAPI } from '../../API/ResultadoExames';

const COR_DESTAQUE = '#1ABC9C';
const COR_FUNDO_ESCURO = '#0A212F';

const SIMULACAO_SOLICITACAO = {
    idSolicitacao: '12345',
    pacienteId: 99,
    solicitanteNome: "Dr. André",
    exames: [
        { idSolicitacaoItem: 1, nomeExame: "Glicemia de Jejum", valorReferencia: "70 - 99 mg/dL" },
        { idSolicitacaoItem: 2, nomeExame: "Colesterol Total", valorReferencia: "< 190 mg/dL" },
        { idSolicitacaoItem: 3, nomeExame: "Creatinina", valorReferencia: "0.5 - 1.2 mg/dL" },
    ],
};

export default function ResultadoExame() {
    const [solicitacaoId, setSolicitacaoId] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const [solicitacaoAtual, setSolicitacaoAtual] = useState(null);
    const [valorAbsoluto, setValorAbsoluto] = useState('');
    const [exameSelecionado, setExameSelecionado] = useState(null);

    const buscarSolicitacao = async (id) => {
        if (!id) return;

        setIsLoading(true);
        setSolicitacaoAtual(null);
        setExameSelecionado(null);

        try {
            if (id === '12345' || id === '15' || id === '20') {
                setSolicitacaoAtual(SIMULACAO_SOLICITACAO);
            } else {
                Alert.alert("Erro", "Solicitação não encontrada.");
            }
        } catch {
            Alert.alert("Erro", "Falha ao buscar solicitação.");
        } finally {
            setIsLoading(false);
        }
    };

    const handleSalvarResultado = async () => {
        if (!exameSelecionado || !valorAbsoluto) {
            Alert.alert("Atenção", "Selecione um exame e preencha o valor.");
            return;
        }

        try {
            Alert.alert("Enviado!", `Resultado: ${valorAbsoluto}`);

            const resultado = {
                idSolicitacao: solicitacaoAtual.idSolicitacao,
                pacienteId: solicitacaoAtual.pacienteId,
                idSolicitacaoItem: exameSelecionado.idSolicitacaoItem,
                nomeExame: exameSelecionado.nomeExame,
                valorAbsoluto,
                valorReferencia: exameSelecionado.valorReferencia,
                dataHora: new Date().toISOString(),
            };

            const response = await CreateResultadoFromAPI(resultado);

            if (response.success === false) throw new Error("Falha no servidor");

            setValorAbsoluto("");
            setExameSelecionado(null);

        } catch (e) {
            Alert.alert("Erro", "Não foi possível enviar o resultado.");
        }
    };

    return (
        <View style={Estilo.container}>
            <Header />
            <User nomeUsuario="Fernanda" />
            <PageAtual Pageatual="Preencher Resultado" />

            <ScrollView contentContainerStyle={Estilo.formContent}>

                <View style={Estilo.campoContainer}>
                    {/* BUSCA */}
                    <Text style={Estilo.label}>ID da Solicitação</Text>
                    <View style={Estilo.inputComBotao}>
                        <TextInput
                            style={[Estilo.input, Estilo.inputBusca]}
                            placeholder="Ex: 12345"
                            placeholderTextColor="#999"
                            keyboardType="numeric"
                            value={solicitacaoId}
                            onChangeText={setSolicitacaoId}
                        />
                        <TouchableOpacity
                            style={Estilo.botaoBusca}
                            onPress={() => buscarSolicitacao(solicitacaoId)}
                        >
                            {isLoading ? <ActivityIndicator color="#fff" /> : <Feather name="search" size={20} color="#fff" />}
                        </TouchableOpacity>
                    </View>

                    {/* SOLICITAÇÃO */}
                    {solicitacaoAtual && (
                        <>
                            <View style={Estilo.infoBox}>
                                <Text style={Estilo.infoText}>Solicitação Nº: {solicitacaoAtual.idSolicitacao}</Text>
                                <Text style={Estilo.infoText}>Solicitante: {solicitacaoAtual.solicitanteNome}</Text>
                            </View>

                            <Text style={Estilo.label}>Exames Pendentes</Text>
                            <View style={Estilo.checkboxList}>
                                {solicitacaoAtual.exames.map(item => (
                                    <TouchableOpacity
                                        key={item.idSolicitacaoItem}
                                        style={Estilo.checkboxItem}
                                        onPress={() => {
                                            setExameSelecionado(item);
                                            setValorAbsoluto('');
                                        }}
                                    >
                                        <View style={Estilo.radioOuter}>
                                            {exameSelecionado?.idSolicitacaoItem === item.idSolicitacaoItem && (
                                                <View style={Estilo.radioInner} />
                                            )}
                                        </View>

                                        <View>
                                            <Text style={Estilo.checkboxLabel}>{item.nomeExame}</Text>
                                            <Text style={Estilo.ref}>{item.valorReferencia}</Text>
                                        </View>
                                    </TouchableOpacity>
                                ))}
                            </View>
                        </>
                    )}

                    {exameSelecionado && (
                        <View>
                            <View style={Estilo.linha} />
                            <Text style={[Estilo.label, Estilo.labelDestaque]}>
                                Valor de Referência – {exameSelecionado.nomeExame}
                            </Text>
                            <Text style={Estilo.subLabelRef}>
                                {exameSelecionado.valorReferencia}
                            </Text>
                            <TextInput
                                style={[Estilo.input, Estilo.inputValor]}
                                placeholder="Digite o valor..."
                                keyboardType="numeric"
                                value={valorAbsoluto}
                                onChangeText={setValorAbsoluto}
                            />
                        </View>
                    )}
                </View>
            </ScrollView>

            <View style={Estilo.botaoFixoContainer}>
                <TouchableOpacity
                    style={Estilo.botao}
                    onPress={handleSalvarResultado}
                >
                    <Text style={Estilo.textoBotao}>Salvar Resultado</Text>
                </TouchableOpacity>
            </View>
        </View>
    );
}

const Estilo = StyleSheet.create({
    container: { flex: 1, backgroundColor: "#f5f5f5" },
    formContent: { padding: 20, paddingBottom: 120 },

    campoContainer: {
        backgroundColor: "#fff",
        padding: 20,
        borderRadius: 10,
        elevation: 3
    },
    label: {
        fontSize: 15,
        fontWeight: "bold",
        marginBottom: 8,
        color: "#333"
    },
    inputComBotao: {
        flexDirection: "row",
        borderWidth: 1,
        borderColor: "#bbb",
        borderRadius: 6,
        overflow: "hidden",
        marginBottom: 20,
        height: 40
    },
    inputBusca: {
        flex: 1,
        paddingHorizontal: 10,
        fontSize: 14,
        borderBottomWidth: 0,
        marginBottom: 0
    },
    botaoBusca: {
        width: 45,
        backgroundColor: COR_FUNDO_ESCURO,
        justifyContent: "center",
        alignItems: "center"
    },
    infoBox: {
        marginTop: 10,
        backgroundColor: "#f1f1f1",
        padding: 12,
        borderRadius: 6
    },
    infoText: { fontSize: 14, color: "#444" },
    checkboxList: { marginTop: 10 },
    checkboxItem: {
        flexDirection: "row",
        alignItems: "flex-start",
        paddingVertical: 10
    },
    radioOuter: {
        height: 16,
        width: 16,
        borderRadius: 8,
        borderWidth: 2,
        borderColor: COR_FUNDO_ESCURO,
        marginRight: 10,
        marginTop: 4,
        justifyContent: "center",
        alignItems: "center"
    },
    radioInner: {
        height: 8,
        width: 8,
        borderRadius: 4,
        backgroundColor: COR_DESTAQUE,
    },
    checkboxLabel: { fontSize: 15, color: "#333" },
    ref: { fontSize: 13, color: COR_DESTAQUE },
    linha: {
        height: 1,
        backgroundColor: "#ddd",
        marginVertical: 15
    },
    labelValor: { fontSize: 16, fontWeight: "bold", color: "#000" },
    inputValor: {
        fontSize: 14,
        fontWeight: "600",
        borderColor: COR_DESTAQUE,
        height: 40,
        paddingHorizontal: 8
    },
    input: {
        height: 40,
        borderBottomWidth: 1,
        borderColor: "#ccc",
        fontSize: 14,
        marginBottom: 16
    },
    botaoFixoContainer: {
        position: "absolute",
        bottom: 0,
        left: 0,
        right: 0,
        padding: 15,
        backgroundColor: "#fff",
        elevation: 15
    },
    botao: {
        backgroundColor: COR_DESTAQUE,
        paddingVertical: 15,
        borderRadius: 10,
        alignItems: "center"
    },
    textoBotao: { color: "#fff", fontSize: 17, fontWeight: "bold" },
});
