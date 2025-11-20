import React, { useState } from 'react';
import { View, StyleSheet, Text, TextInput, TouchableOpacity, ScrollView, ActivityIndicator } from "react-native";
import { Feather } from '@expo/vector-icons';
import Header from "../../components/Header";
import User from "../../components/User";
import PageAtual from "../../components/PageAtual";
import Toast from 'react-native-toast-message';

// Importação da API
import { CreateResultadoExamesFromAPI } from '../../API/ResultadoExames'; 

const COR_DESTAQUE = '#1ABC9C';
const COR_FUNDO_ESCURO = '#0A212F';

export default function CadastrarResultadoExame() {
    const [solicitacaoId, setSolicitacaoId] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const [solicitacaoAtual, setSolicitacaoAtual] = useState(null);
    const [valorAbsoluto, setValorAbsoluto] = useState('');
    const [exameSelecionado, setExameSelecionado] = useState(null);

    // --- FUNÇÃO DE BUSCA ---
    const buscarSolicitacao = async (id) => {
        if (!id) return;
        
        console.log(`Buscando solicitação com ID: ${id}`);
        setIsLoading(true);
        setSolicitacaoAtual(null);
        setExameSelecionado(null);

        try {
            // Ajuste o IP se necessário (localhost para emulador, IP para físico)
            const response = await fetch(`http://localhost:3000/solicitacoes/${id}`);

            if (!response.ok) {
                if (response.status === 404) {
                    Toast.show({
                        type: "error",
                        text1: "Não encontrado",
                        text2: "Solicitação não encontrada."
                    });
                } else {
                    throw new Error("Falha na resposta do servidor");
                }
            } else {
                const data = await response.json();
                setSolicitacaoAtual(data);
            }

        } catch (error) {
            console.error("Erro busca:", error);
            Toast.show({
                type: "error",
                text1: "Erro na busca",
                text2: "Não foi possível buscar a solicitação."
            });
        } finally {
            setIsLoading(false);
        }
    };

    // --- FUNÇÃO DE SALVAR ---
    const handleSalvarResultado = async () => {
        if (!exameSelecionado || !valorAbsoluto) {
            Toast.show({
                type: "info",
                text1: "Atenção",
                text2: "Selecione um exame e preencha o valor."
            });
            return;
        }

        try {
            const resultado = {
                paciente_id_fk: solicitacaoAtual.paciente_id, 
                nome_exame: exameSelecionado.nome_exame,
                tipo_exame: exameSelecionado.tipo_exame_categoria, 
                data_hora_exame: new Date().toISOString(),
                valor_absoluto: valorAbsoluto,
                valor_referencia: exameSelecionado.valor_referencia_solicitacao, 
                laudo_id: null, 
                paciente_registro: String(solicitacaoAtual.paciente_id)
            };

            const response = await CreateResultadoExamesFromAPI(resultado);

            if (response === false || (response.success === false)) {
                throw new Error(response.message || "Falha ao salvar");
            }

            // SUCESSO: Toast
            Toast.show({
                type: "success",
                text1: "Sucesso!",
                text2: `Resultado de ${exameSelecionado.nome_exame} salvo.`
            });

            // ATUALIZA A LISTA LOCAL (Remove o exame salvo da tela)
            const novaLista = { ...solicitacaoAtual };
            novaLista.exames = novaLista.exames.filter(item => item.id !== exameSelecionado.id);
            
            setSolicitacaoAtual(novaLista);
            setValorAbsoluto("");
            setExameSelecionado(null);

        } catch (e) {
            console.error("Erro save:", e);
            Toast.show({
                type: "error",
                text1: "Erro ao salvar",
                text2: e.message || "Tente novamente."
            });
        }
    };

    return (
        <View style={Estilo.container}>
            <Header />
            <User nomeUsuario="Técnico Lab" /> 
            <PageAtual Pageatual="Lançamento de Resultados" />

            <ScrollView contentContainerStyle={Estilo.formContent}>

                <View style={Estilo.campoContainer}>
                    {/* BUSCA */}
                    <Text style={Estilo.label}>Número da Solicitação</Text>
                    <View style={Estilo.inputComBotao}>
                        <TextInput
                            style={[Estilo.input, Estilo.inputBusca]}
                            placeholder="Digite o nº..."
                            placeholderTextColor="#999"
                            keyboardType="numeric"
                            value={solicitacaoId}
                            onChangeText={setSolicitacaoId}
                        />
                        <TouchableOpacity style={Estilo.botaoBusca} onPress={() => buscarSolicitacao(solicitacaoId)}>
                            {isLoading ? <ActivityIndicator color="#fff" /> : <Feather name="search" size={20} color="#fff" />}
                        </TouchableOpacity>
                    </View>

                    {solicitacaoAtual && (
                        <>
                            {/* DADOS DA SOLICITAÇÃO */}
                            <View style={Estilo.infoBoxAnonimo}>
                                <View style={Estilo.linhaInfo}>
                                    <Feather name="file-text" size={18} color="#555" />
                                    <Text style={Estilo.infoTextDestaque}> Solicitação: #{solicitacaoAtual.idSolicitacao}</Text>
                                </View>
                                <View style={Estilo.linhaInfo}>
                                    <Feather name="user" size={18} color="#555" />
                                    <Text style={Estilo.infoText}> ID do Paciente: {solicitacaoAtual.paciente_id}</Text>
                                </View>
                            </View>

                            {/* LISTA DE EXAMES OU TELA DE CONCLUÍDO */}
                            {solicitacaoAtual.exames.length > 0 ? (
                                <>
                                    <Text style={Estilo.label}>Selecione o Exame para Preencher:</Text>
                                    <View style={Estilo.checkboxList}>
                                        {solicitacaoAtual.exames.map(item => (
                                            <TouchableOpacity
                                                key={item.id} 
                                                style={[
                                                    Estilo.checkboxItem, 
                                                    exameSelecionado?.id === item.id && Estilo.checkboxItemSelected
                                                ]}
                                                onPress={() => {
                                                    setExameSelecionado(item);
                                                    setValorAbsoluto('');
                                                }}
                                            >
                                                <View style={Estilo.radioOuter}>
                                                    {exameSelecionado?.id === item.id && <View style={Estilo.radioInner} />}
                                                </View>
                                                <View style={{flex: 1}}>
                                                    <Text style={Estilo.checkboxLabel}>{item.nome_exame}</Text>
                                                    <Text style={Estilo.ref}>{item.tipo_exame_categoria}</Text>
                                                </View>
                                            </TouchableOpacity>
                                        ))}
                                    </View>

                                    {/* INPUT DE VALOR */}
                                    {exameSelecionado && (
                                        <View style={Estilo.areaPreenchimento}>
                                            <Text style={Estilo.tituloExameSelecionado}>{exameSelecionado.nome_exame}</Text>
                                            <Text style={Estilo.labelInput}>Resultado Encontrado:</Text>
                                            <TextInput
                                                style={[Estilo.input, Estilo.inputValor]}
                                                placeholder="Digite o resultado..."
                                                value={valorAbsoluto}
                                                onChangeText={setValorAbsoluto}
                                            />
                                        </View>
                                    )}
                                </>
                            ) : (
                                /* TELA DE CONCLUÍDO */
                                <View style={Estilo.emptyStateContainer}>
                                    <Feather name="check-circle" size={60} color={COR_DESTAQUE} />
                                    <Text style={Estilo.emptyStateTitle}>Concluído!</Text>
                                    <Text style={Estilo.emptyStateText}>Não há exames pendentes nesta solicitação.</Text>
                                </View>
                            )}
                        </>
                    )}
                </View>
            </ScrollView>

            {/* BOTÃO FIXO (Só aparece se tiver exames) */}
            {solicitacaoAtual && solicitacaoAtual.exames.length > 0 && (
                <View style={Estilo.botaoFixoContainer}>
                    <TouchableOpacity
                        style={[Estilo.botao, (!exameSelecionado || !valorAbsoluto) && Estilo.botaoDesabilitado]}
                        onPress={handleSalvarResultado}
                        disabled={!exameSelecionado || !valorAbsoluto}
                    >
                        <Text style={Estilo.textoBotao}>Confirmar Resultado</Text>
                    </TouchableOpacity>
                </View>
            )}
        </View>
    );
}

// Daqui para baixo vem o const Estilo = StyleSheet.create({ ...

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
        color: "#333",
        marginTop: 15
    },
    labelInput: {
        fontSize: 14,
        fontWeight: "600",
        color: "#333",
        marginBottom: 5
    },
    inputComBotao: {
        flexDirection: "row",
        borderWidth: 1,
        borderColor: "#bbb",
        borderRadius: 6,
        overflow: "hidden",
        height: 45
    },
    inputBusca: {
        flex: 1,
        paddingHorizontal: 10,
        fontSize: 16,
        borderBottomWidth: 0,
        marginBottom: 0
    },
    botaoBusca: {
        width: 50,
        backgroundColor: COR_FUNDO_ESCURO,
        justifyContent: "center",
        alignItems: "center"
    },

    // Estilos para o box anônimo
    infoBoxAnonimo: {
        marginTop: 10,
        backgroundColor: "#E3F2FD", // Azul bem clarinho
        padding: 15,
        borderRadius: 8,
        borderLeftWidth: 5,
        borderLeftColor: COR_FUNDO_ESCURO
    },
    linhaInfo: {
        flexDirection: "row",
        alignItems: "center",
        marginBottom: 5
    },
    infoTextDestaque: { fontSize: 16, fontWeight: "bold", color: "#0A212F", marginLeft: 8 },
    infoText: { fontSize: 15, color: "#444", marginLeft: 8 },
    avisoAnonimo: { fontSize: 12, color: "#888", fontStyle: "italic", marginTop: 5, textAlign: "right" },

    checkboxList: { marginTop: 10 },
    checkboxItem: {
        flexDirection: "row",
        alignItems: "center",
        paddingVertical: 12,
        paddingHorizontal: 10,
        borderBottomWidth: 1,
        borderBottomColor: "#eee"
    },
    checkboxItemSelected: {
        backgroundColor: "#F0FDF4", // Verde claro quando selecionado
        borderRadius: 6,
        borderBottomWidth: 0
    },
    radioOuter: {
        height: 20,
        width: 20,
        borderRadius: 10,
        borderWidth: 2,
        borderColor: "#999",
        marginRight: 15,
        justifyContent: "center",
        alignItems: "center"
    },
    radioInner: {
        height: 10,
        width: 10,
        borderRadius: 5,
        backgroundColor: COR_DESTAQUE,
    },
    checkboxLabel: { fontSize: 16, color: "#333", fontWeight: 'bold' },
    ref: { fontSize: 13, color: "#666" },
    status: { fontSize: 12, color: "#999" },

    // Área de preenchimento
    areaPreenchimento: {
        marginTop: 20,
        padding: 15,
        backgroundColor: "#fafafa",
        borderRadius: 8,
        borderWidth: 1,
        borderColor: "#eee"
    },
    cabecalhoExame: {
        marginBottom: 15,
        borderBottomWidth: 1,
        borderBottomColor: "#ddd",
        paddingBottom: 10
    },
    tituloExameSelecionado: {
        fontSize: 18,
        fontWeight: "bold",
        color: COR_DESTAQUE
    },
    subLabelRef: {
        fontSize: 13,
        color: '#555',
        marginTop: 4
    },
    inputValor: {
        fontSize: 16,
        fontWeight: "600",
        borderColor: COR_DESTAQUE,
        height: 50,
        paddingHorizontal: 10,
        borderWidth: 1,
        borderRadius: 5,
        backgroundColor: "#fff"
    },
    input: {
        marginBottom: 0
    },

    botaoFixoContainer: {
        position: "absolute",
        bottom: 0,
        left: 0,
        right: 0,
        padding: 15,
        backgroundColor: "#fff",
        elevation: 15,
        borderTopWidth: 1,
        borderTopColor: "#eee"
    },
    botao: {
        backgroundColor: COR_DESTAQUE,
        paddingVertical: 15,
        borderRadius: 10,
        alignItems: "center"
    },
    botaoDesabilitado: {
        backgroundColor: "#ccc"
    },
    textoBotao: { color: "#fff", fontSize: 17, fontWeight: "bold" },
});