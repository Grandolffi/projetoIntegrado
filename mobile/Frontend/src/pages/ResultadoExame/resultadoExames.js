import React, { useState, useCallback } from 'react';
import { View, StyleSheet, Text, TextInput, TouchableOpacity, ScrollView, ActivityIndicator } from "react-native";
import { Feather } from '@expo/vector-icons';
import { useFocusEffect } from '@react-navigation/native'; // <--- IMPORT NOVO PARA LIMPAR A TELA
import Header from "../../components/Header";
import User from "../../components/User";
import PageAtual from "../../components/PageAtual";
import Toast from 'react-native-toast-message';
import AsyncStorage from '@react-native-async-storage/async-storage';

import { CreateResultadoExamesFromAPI } from '../../API/ResultadoExames'; 

const COR_DESTAQUE = '#1ABC9C';
const COR_FUNDO_ESCURO = '#0A212F';

export default function CadastrarResultadoExame() {
    const [solicitacaoId, setSolicitacaoId] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const [isSaving, setIsSaving] = useState(false); 
    const [solicitacaoAtual, setSolicitacaoAtual] = useState(null);
    const [valorAbsoluto, setValorAbsoluto] = useState('');
    const [exameSelecionado, setExameSelecionado] = useState(null);

    // --- MÁGICA: LIMPA A TELA QUANDO VOCÊ SAI OU VOLTA PELO MENU ---
    useFocusEffect(
        useCallback(() => {
            // O retorno dessa função executa quando a tela perde o foco (você sai dela)
            return () => {
                setSolicitacaoId('');
                setSolicitacaoAtual(null);
                setExameSelecionado(null);
                setValorAbsoluto('');
                setIsLoading(false);
                setIsSaving(false);
            };
        }, [])
    );

    // --- FILTRO MEMÓRIA ---
    const filtrarExamesConcluidos = async (solicitacaoData) => {
        try {
            const key = `concluidos_${solicitacaoData.id_solicitacao}`;
            const jsonValue = await AsyncStorage.getItem(key);
            const idsConcluidos = jsonValue != null ? JSON.parse(jsonValue) : [];

            const examesPendentes = solicitacaoData.exames.filter(
                exame => !idsConcluidos.includes(exame.id)
            );

            return { ...solicitacaoData, exames: examesPendentes };
        } catch(e) {
            return solicitacaoData;
        }
    };

    // --- GRAVAR MEMÓRIA ---
    const marcarExameComoConcluido = async (solicId, exameId) => {
        try {
            const key = `concluidos_${solicId}`;
            const jsonValue = await AsyncStorage.getItem(key);
            let idsConcluidos = jsonValue != null ? JSON.parse(jsonValue) : [];
            
            if (!idsConcluidos.includes(exameId)) {
                idsConcluidos.push(exameId);
                await AsyncStorage.setItem(key, JSON.stringify(idsConcluidos));
            }
        } catch (e) { console.error(e); }
    };

    const buscarSolicitacao = async (id) => {
        if (!id || id.trim() === '') {
            Toast.show({ type: "info", text1: "Campo Vazio", text2: "Digite o ID da solicitação." });
            setSolicitacaoAtual(null);
            return;
        }
        
        setIsLoading(true);
        setSolicitacaoAtual(null); 
        setExameSelecionado(null);
        setValorAbsoluto('');

        try {
            const response = await fetch(`http://localhost:3000/solicitacoes/${id}`);

            if (!response.ok) {
                if (response.status === 404) {
                    Toast.show({ type: "error", text1: "Não Encontrado", text2: `Solicitação nº ${id} não existe.` });
                } else {
                    throw new Error("Falha na resposta do servidor");
                }
                return; 
            } 

            const data = await response.json();
            
            const dadosFiltrados = await filtrarExamesConcluidos(data);
            setSolicitacaoAtual(dadosFiltrados);

        } catch (error) {
            Toast.show({ type: "error", text1: "Erro na busca", text2: "Verifique conexão." });
        } finally {
            setIsLoading(false);
        }
    };

    const handleSalvarResultado = async () => {
        if (!exameSelecionado || !valorAbsoluto) {
            Toast.show({ type: "info", text1: "Atenção", text2: "Preencha o valor." });
            return;
        }

        // TRAVA DE SEGURANÇA: Se já estiver salvando, não faz nada (evita duplo clique)
        if (isSaving) return;

        setIsSaving(true); // <--- ATIVA O BLOQUEIO

        try {
            const resultado = {
                paciente_id_fk: solicitacaoAtual.paciente_id, 
                nome_exame: exameSelecionado.nome_exame,
                tipo_exame: exameSelecionado.tipo_exame_categoria, 
                data_hora_exame: new Date().toISOString(),
                valor_absoluto: valorAbsoluto,
                valor_referencia: exameSelecionado.valor_referencia_solicitacao, 
                laudo_id: null, 
                paciente_registro: String(solicitacaoAtual.paciente_id),
                solicitacao_id_origem: solicitacaoAtual.id_solicitacao
            };

            const response = await CreateResultadoExamesFromAPI(resultado);

            if (response === false || (response.success === false)) {
                throw new Error(response.message || "Falha ao salvar");
            }

            Toast.show({ type: "success", text1: "Sucesso!", text2: `Resultado salvo.` });

            await marcarExameComoConcluido(solicitacaoAtual.id_solicitacao, exameSelecionado.id);

            const novaLista = { ...solicitacaoAtual };
            novaLista.exames = novaLista.exames.filter(item => item.id !== exameSelecionado.id);
            
            setSolicitacaoAtual(novaLista);
            setValorAbsoluto("");
            setExameSelecionado(null);

        } catch (e) {
            Toast.show({ type: "error", text1: "Erro ao salvar", text2: "Tente novamente." });
        } finally {
            setIsSaving(false); // <--- LIBERA O BOTÃO
        }
    };

    // Função para limpar a busca manualmente (botão "Nova Busca")
    const novaBusca = () => {
        setSolicitacaoAtual(null);
        setSolicitacaoId('');
        setExameSelecionado(null);
        setValorAbsoluto('');
    }

    return (
        <View style={Estilo.container}>
            <Header />
            <User nomeUsuario="Técnico Lab" /> 
            <PageAtual Pageatual="Lançamento de Resultados" />

            <ScrollView contentContainerStyle={Estilo.formContent}>

                <View style={Estilo.campoContainer}>
                    <Text style={Estilo.label}>Número da Solicitação</Text>
                    <View style={Estilo.inputComBotao}>
                        <TextInput
                            style={[Estilo.input, Estilo.inputBusca]}
                            placeholder="Digite o Nº da Solicitação (ex: 22)"
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
                            <View style={Estilo.infoBoxAnonimo}>
                                <View style={Estilo.linhaInfo}>
                                    <Feather name="file-text" size={18} color="#555" />
                                    <Text style={Estilo.infoTextDestaque}> Solicitação: #{solicitacaoAtual.id_solicitacao}</Text>
                                </View>
                                <View style={Estilo.linhaInfo}>
                                    <Feather name="user" size={18} color="#555" />
                                    <Text style={Estilo.infoText}> ID do Paciente: {solicitacaoAtual.paciente_id}</Text>
                                </View>
                            </View>

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
                                <View style={Estilo.emptyStateContainer}>
                                    <Feather name="check-circle" size={60} color={COR_DESTAQUE} />
                                    <Text style={Estilo.emptyStateTitle}>Concluído!</Text>
                                    <Text style={Estilo.emptyStateText}>Não há exames pendentes nesta solicitação.</Text>
                                    
                                    <TouchableOpacity style={Estilo.btnNovaBusca} onPress={novaBusca}>
                                        <Text style={Estilo.btnNovaBuscaText}>Nova Busca</Text>
                                    </TouchableOpacity>
                                </View>
                            )}
                        </>
                    )}
                </View>
            </ScrollView>

            {/* O BOTÃO AGORA SÓ APARECE SE TIVER EXAMES E SE NÃO ESTIVER CARREGANDO */}
            {solicitacaoAtual && solicitacaoAtual.exames.length > 0 && (
                <View style={Estilo.botaoFixoContainer}>
                    <TouchableOpacity
                        // ESTILO: Muda de cor se estiver desabilitado
                        style={[Estilo.botao, (isSaving || !exameSelecionado || !valorAbsoluto) && Estilo.botaoDesabilitado]}
                        onPress={handleSalvarResultado}
                        // LOGICA: Desabilita se estiver salvando OU se não tiver preenchido
                        disabled={isSaving || !exameSelecionado || !valorAbsoluto}
                    >
                        {isSaving ? (
                            <ActivityIndicator color="#FFF" />
                        ) : (
                            <Text style={Estilo.textoBotao}>Confirmar Resultado</Text>
                        )}
                    </TouchableOpacity>
                </View>
            )}
        </View>
    );
}

const Estilo = StyleSheet.create({
    container: { flex: 1, backgroundColor: "#f5f5f5" },
    formContent: { padding: 20, paddingBottom: 120 },
    campoContainer: { backgroundColor: "#fff", padding: 20, borderRadius: 10, elevation: 3 },
    
    label: { fontSize: 15, fontWeight: "bold", marginBottom: 8, color: "#333", marginTop: 15 },
    labelInput: { fontSize: 14, fontWeight: "600", color: "#333", marginBottom: 5 },
    
    inputComBotao: { flexDirection: "row", borderWidth: 1, borderColor: "#bbb", borderRadius: 6, height: 45, overflow: 'hidden' },
    inputBusca: { flex: 1, paddingHorizontal: 10, fontSize: 16, marginBottom: 0 },
    botaoBusca: { width: 50, backgroundColor: COR_FUNDO_ESCURO, justifyContent: "center", alignItems: "center" },

    infoBoxAnonimo: { marginTop: 10, backgroundColor: "#E3F2FD", padding: 15, borderRadius: 8, borderLeftWidth: 5, borderLeftColor: COR_FUNDO_ESCURO },
    linhaInfo: { flexDirection: "row", alignItems: "center", marginBottom: 5 },
    infoTextDestaque: { fontSize: 16, fontWeight: "bold", color: "#0A212F", marginLeft: 8 },
    infoText: { fontSize: 15, color: "#444", marginLeft: 8 },

    checkboxList: { marginTop: 10 },
    checkboxItem: { flexDirection: "row", alignItems: "center", paddingVertical: 12, paddingHorizontal: 10, borderBottomWidth: 1, borderBottomColor: "#eee" },
    checkboxItemSelected: { backgroundColor: "#F0FDF4", borderRadius: 6, borderBottomWidth: 0 },
    radioOuter: { height: 20, width: 20, borderRadius: 10, borderWidth: 2, borderColor: "#999", marginRight: 15, justifyContent: "center", alignItems: "center" },
    radioInner: { height: 10, width: 10, borderRadius: 5, backgroundColor: COR_DESTAQUE },
    checkboxLabel: { fontSize: 16, color: "#333", fontWeight: 'bold' },
    ref: { fontSize: 13, color: "#666" },

    areaPreenchimento: { marginTop: 20, padding: 15, backgroundColor: "#fafafa", borderRadius: 8, borderWidth: 1, borderColor: "#eee" },
    tituloExameSelecionado: { fontSize: 18, fontWeight: "bold", color: COR_DESTAQUE, marginBottom: 10 },
    inputValor: { fontSize: 16, fontWeight: "600", borderColor: COR_DESTAQUE, height: 50, paddingHorizontal: 10, borderWidth: 1, borderRadius: 5, backgroundColor: "#fff", marginBottom: 0 },
    input: { marginBottom: 0 },

    emptyStateContainer: { alignItems: 'center', justifyContent: 'center', paddingVertical: 60 },
    emptyStateTitle: { fontSize: 22, fontWeight: 'bold', color: COR_DESTAQUE, marginTop: 15 },
    emptyStateText: { fontSize: 15, color: '#666', textAlign: 'center', marginTop: 8, paddingHorizontal: 20 },
    
    // Botão Nova Busca (aparece quando acaba)
    btnNovaBusca: { marginTop: 20, paddingVertical: 10, paddingHorizontal: 20, backgroundColor: '#eee', borderRadius: 20 },
    btnNovaBuscaText: { color: '#333', fontWeight: 'bold' },

    botaoFixoContainer: { position: "absolute", bottom: 0, left: 0, right: 0, padding: 15, backgroundColor: "#fff", elevation: 15, borderTopWidth: 1, borderTopColor: "#eee" },
    botao: { backgroundColor: COR_DESTAQUE, paddingVertical: 15, borderRadius: 10, alignItems: "center" },
    botaoDesabilitado: { backgroundColor: "#ccc" },
    textoBotao: { color: "#fff", fontSize: 17, fontWeight: "bold" },
});