import React, { useState } from 'react';
import { View, StyleSheet, Text, TextInput, TouchableOpacity, ScrollView, Alert, ActivityIndicator, Dimensions } from "react-native";
import { Feather } from '@expo/vector-icons';
import Header from "../../components/Header";
import User from "../../components/User";
import PageAtual from "../../components/PageAtual";
import CreateResultadoFromAPI from '../../API/ResultadoExames'; // <-- API para SALVAR

const COR_DESTAQUE = '#1ABC9C';
const COR_FUNDO_ESCURO = '#0A212F';

// DADO SIMULADO FOI REMOVIDO
// const SIMULACAO_SOLICITACAO = { ... };

export default function CadastrarResultadoExame() {
    const [solicitacaoId, setSolicitacaoId] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const [solicitacaoAtual, setSolicitacaoAtual] = useState(null);
    const [valorAbsoluto, setValorAbsoluto] = useState('');
    const [exameSelecionado, setExameSelecionado] = useState(null);

    /**
     * ATUALIZADO: Esta função agora busca na sua API Node.js
     */
    const buscarSolicitacao = async (id) => {
        if (!id) return;

        console.log(`Buscando solicitação com ID: ${id}`);
        setIsLoading(true);
        setSolicitacaoAtual(null);
        setExameSelecionado(null);

        try {
            // ⚠️ ATENÇÃO AQUI:
            // Se estiver testando em emulador, 'localhost' funciona.
            // Se estiver testando em celular físico, use o IP da sua máquina:
            // Ex: http://192.168.1.10:3000/solicitacoes/${id}
            const response = await fetch(`http://localhost:3000/solicitacoes/${id}`);

            if (!response.ok) {
                if (response.status === 404) {
                    Alert.alert("Erro", "Solicitação não encontrada.");
                } else {
                    throw new Error("Falha na resposta do servidor");
                }
            } else {
                const data = await response.json();
                // O DAO que criamos no backend (solicitacaoDAO.js)
                // já formata o JSON como o frontend precisa.
                setSolicitacaoAtual(data);
            }

        } catch (error) {
            console.error("Falha ao buscar solicitação:", error);
            Alert.alert("Erro", "Não foi possível buscar a solicitação.");
        } finally {
            setIsLoading(false);
        }
    };

    /**
     * CORRIGIDO: Esta função envia os dados para salvar
     * no formato 'snake_case' que o backend espera.
     */
    const handleSalvarResultado = async () => {
        if (!exameSelecionado || !valorAbsoluto) {
            Alert.alert("Atenção", "Selecione um exame e preencha o valor.");
            return;
        }

        try {
            Alert.alert("Enviado!", `Resultado: ${valorAbsoluto}`);

            const resultado = {
                // Campos OBRIGATÓRIOS (NOT NULL)
                paciente_id_fk: solicitacaoAtual.paciente_id, // Use o nome da API
                nome_exame: exameSelecionado.nome_exame,
                tipo_exame: exameSelecionado.tipo_exame_categoria, // Use o nome da API
                data_hora_exame: new Date().toISOString(),

                // Campos Opcionais (Nullable)
                valor_absoluto: valorAbsoluto,
                valor_referencia: exameSelecionado.valor_referencia_solicitacao, // Use o nome da API
                laudo_id: null,
                paciente_registro: null
            };

            const response = await CreateResultadoFromAPI(resultado);

            // Se a sua API 'CreateResultadoFromAPI' estiver correta,
            // ela deve "pegar" o erro 400 do backend e
            // fazer 'response.success' ser 'false'.
            if (response.success === false) {
                throw new Error("Falha no servidor ao salvar");
            }

            // Se chegou aqui, salvou E vai limpar o form.
            setValorAbsoluto("");
            setExameSelecionado(null);

        } catch (e) {
            console.error("Erro ao salvar:", e.message);
            Alert.alert("Erro", "Não foi possível enviar o resultado. Verifique os campos.");
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
                            placeholder="Ex: 20"
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
                                        key={item.id} // 👈 MUDADO (ex: de 'idSolicitacaoItem' para 'id')
                                        style={Estilo.checkboxItem}
                                        onPress={() => {
                                            setExameSelecionado(item);
                                            setValorAbsoluto('');
                                        }}
                                    >
                                        <View style={Estilo.radioOuter}>
                                            {exameSelecionado?.id === item.id && ( // 👈 MUDADO AQUI TAMBÉM
                                                <View style={Estilo.radioInner} />
                                            )}
                                        </View>

                                        <View>
                                            <Text style={Estilo.checkboxLabel}>{item.nome_exame}</Text>
                                            <Text style={Estilo.ref}>{item.valor_referencia_solicitacao}</Text>
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