import React, { useState } from 'react';
import { View, StyleSheet, Text, TextInput, TouchableOpacity, ScrollView, Alert } from "react-native";
import Header from "../../components/Header";
import User from "../../components/User";
import PageAtual from "../../components/PageAtual";
import { CreateSolicitacaoFromAPI } from '../../API/Solicitacoes';

// --- CORES PADRONIZADAS ---
const COR_DESTAQUE = '#1ABC9C';
const COR_FUNDO_ESCURO = '#0A212F';

// --- DADOS DE EXAMES ---
const EXAMES_POR_CATEGORIA = {
    Microbiologia: [
        { id: 20, nome: 'Urocultura com antibiograma', tipo: 'Microbiologia' },
        { id: 21, nome: 'Swab ocular', tipo: 'Microbiologia' },
        { id: 22, nome: 'Escarro para Micobacterium tuberculosis', tipo: 'Microbiologia' },
    ],
    Parasitologia: [
        { id: 27, nome: 'Exame de fezes', tipo: 'Parasitologia' },
        { id: 28, nome: 'Pesquisa de hematozoários', tipo: 'Parasitologia' },
        { id: 29, nome: 'Pesquisa de protozoários intestinais', tipo: 'Parasitologia' },
    ],
    Hematologia: [
        { id: 23, nome: 'Hemograma Completo', tipo: 'Hematologia' },
        { id: 24, nome: 'Reticulócitos', tipo: 'Hematologia' },
        { id: 25, nome: 'Tempo de sangramento', tipo: 'Hematologia' },
        { id: 26, nome: 'Tempo de coagulação', tipo: 'Hematologia' },
    ],
    Bioquimica: [
        { id: 1, nome: 'Ácido úrico', tipo: 'Bioquímica' },
        { id: 2, nome: 'Alfa Amilase', tipo: 'Bioquímica' },
        { id: 3, nome: 'Bilirrubina Total', tipo: 'Bioquímica' },
        { id: 4, nome: 'Bilirrubina Direta', tipo: 'Bioquímica' },
        { id: 5, nome: 'Cálcio', tipo: 'Bioquímica' },
        { id: 6, nome: 'Colesterol Total', tipo: 'Bioquímica' },
        { id: 7, nome: 'HDL', tipo: 'Bioquímica' },
        { id: 8, nome: 'Creatinina', tipo: 'Bioquímica' },
        { id: 9, nome: 'Ferro Ferene', tipo: 'Bioquímica' },
        { id: 10, nome: 'Fosfatase Alcalina', tipo: 'Bioquímica' },
        { id: 11, nome: 'Fosfato', tipo: 'Bioquímica' },
        { id: 12, nome: 'Gama GT', tipo: 'Bioquímica' },
        { id: 13, nome: 'Glicose', tipo: 'Bioquímica' },
        { id: 14, nome: 'GOT (AST)', tipo: 'Bioquímica' },
        { id: 15, nome: 'GTP (ALT)', tipo: 'Bioquímica' },
        { id: 16, nome: 'Magnésio', tipo: 'Bioquímica' },
        { id: 17, nome: 'Proteína total', tipo: 'Bioquímica' },
        { id: 18, nome: 'Triglicerídeos', tipo: 'Bioquímica' },
        { id: 19, nome: 'Uréia', tipo: 'Bioquímica' },
    ],
    Urinálise: [
        { id: 30, nome: 'Exame físico', tipo: 'Urinálise' },
        { id: 31, nome: 'Exame químico', tipo: 'Urinálise' },
        { id: 32, nome: 'Exame microscópico', tipo: 'Urinálise' },
    ],
};

export default function NovoExame() {

    const [nomePaciente, setNomePaciente] = useState('');
    const [pacienteId, setPacienteId] = useState('');
    const [dataHora, setDataHora] = useState('');
    const [examesSelecionados, setExamesSelecionados] = useState({});
    const [abaAtiva, setAbaAtiva] = useState(null);

    const toggleExame = (exame) => {
        setExamesSelecionados(prev => {
            const newState = { ...prev };
            if (newState[exame.id]) {
                delete newState[exame.id];
            } else {
                newState[exame.id] = exame;
            }
            return newState;
        });
    };

    const handleSolicitarExame = async () => {
        const idsExames = Object.keys(examesSelecionados);

        if (!pacienteId || idsExames.length === 0 || !dataHora) {
            Alert.alert("Atenção", "Preencha o ID do paciente, a data/hora e selecione pelo menos um exame.");
            return;
        }

        const solicitacaoData = {
            pacienteId: pacienteId,
            dataPrevista: dataHora,
            solicitanteNome: 'Fernanda',
            exames: Object.values(examesSelecionados).map(ex => ({
                nome: ex.nome,
                categoria: ex.tipo,
            }))
        };

        try {
            const response = await CreateSolicitacaoFromAPI(solicitacaoData);

            if (response.success) {
                Alert.alert("Sucesso", "Solicitação de Exame enviada!");
                setNomePaciente('');
                setPacienteId('');
                setDataHora('');
                setExamesSelecionados({});
            } else {
                throw new Error(response.message);
            }
        } catch (error) {
            Alert.alert("Erro", `Falha ao solicitar exame: ${error.message}`);
        }
    };

    const ExamesCheckboxList = ({ categoria }) => (
        <View style={Estilo.exameListContainer}>
            <Text style={Estilo.secaoTitulo}>Exames de {categoria}:</Text>

            {EXAMES_POR_CATEGORIA[categoria]?.map(exame => (
                <TouchableOpacity
                    key={exame.id}
                    style={Estilo.checkboxItem}
                    onPress={() => toggleExame(exame)}
                >
                    <View style={Estilo.checkboxOuter}>
                        {examesSelecionados[exame.id] && <View style={Estilo.checkboxInner} />}
                    </View>
                    <Text style={Estilo.checkboxLabel}>{exame.nome}</Text>
                </TouchableOpacity>
            ))}
        </View>
    );

    return (
        <View style={Estilo.container}>
            <Header />
            <User nomeUsuario="Fernanda" />
            <PageAtual Pageatual="Solicitar Novo Exame" />

            <ScrollView contentContainerStyle={Estilo.formContent}>
                <View style={Estilo.campoContainer}>

                    {/* Inputs */}
                    <Text style={Estilo.label}>Nome do Paciente</Text>
                    <TextInput style={Estilo.input} placeholder="Digite o nome..." value={nomePaciente} onChangeText={setNomePaciente} />

                    <Text style={Estilo.label}>Id do Paciente</Text>
                    <TextInput style={Estilo.input} placeholder="Digite o ID do paciente..." keyboardType="numeric" value={pacienteId} onChangeText={setPacienteId} />

                    <Text style={Estilo.label}>Data e hora do Exame</Text>
                    <TextInput style={Estilo.input} placeholder="dd/mm/aaaa --:--" value={dataHora} onChangeText={setDataHora} />

                    {/* Tabs */}
                    <Text style={[Estilo.label, { marginTop: 10 }]}>Laboratórios Solicitados</Text>

                    <ScrollView
                        horizontal
                        showsHorizontalScrollIndicator={false}
                        contentContainerStyle={Estilo.tabsContainer}
                    >
                        {Object.keys(EXAMES_POR_CATEGORIA).map(aba => (
                            <TouchableOpacity
                                key={aba}
                                style={[
                                    Estilo.tabButton,
                                    abaAtiva === aba && Estilo.tabButtonActive
                                ]}
                                onPress={() => setAbaAtiva(aba)}
                            >
                                <Text style={[
                                    Estilo.tabText,
                                    abaAtiva === aba && Estilo.tabTextActive
                                ]}>
                                    {aba}
                                </Text>
                            </TouchableOpacity>
                        ))}
                    </ScrollView>

                    {/* CHECKBOXES DA ABA ATIVA */}
                    {abaAtiva && <ExamesCheckboxList categoria={abaAtiva} />}

                    {/* LISTA DOS EXAMES SELECIONADOS */}
                    {Object.keys(examesSelecionados).length > 0 && (
                        <View style={{ marginTop: 20 }}>
                            <Text style={Estilo.selecaoTitulo}>Exames Selecionados:</Text>

                            {Object.values(examesSelecionados).map(ex => (
                                <Text key={ex.id} style={Estilo.selecaoItem}>• {ex.nome}</Text>
                            ))}
                        </View>
                    )}
                    {/* Botão fixo */}
                    <View style={Estilo.botaoFixoContainer}>
                        <TouchableOpacity style={Estilo.botao} activeOpacity={0.8} onPress={handleSolicitarExame}>
                            <Text style={Estilo.textoBotao}>Marcar Exame</Text>
                        </TouchableOpacity>
                    </View>

                </View>

            </ScrollView>
        </View>
    );
}

const Estilo = StyleSheet.create({
    container: { flex: 1, backgroundColor: '#f0f0f0' },
    formContent: { paddingHorizontal: 20, paddingBottom: 100 },

    campoContainer: {
        backgroundColor: '#fff',
        padding: 20,
        borderRadius: 10,
        marginTop: 10,
        paddingBottom: 30,
        shadowColor: "#000",
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 3.84,
        elevation: 5,
    },

    label: { fontSize: 16, color: '#333', marginBottom: 5, fontWeight: 'bold' },
    input: { height: 50, borderBottomWidth: 2, borderBottomColor: '#ccc', marginBottom: 20, fontSize: 17 },

    tabsContainer: {
        flexDirection: 'row',
        marginBottom: 15,
        borderBottomWidth: 2,
        borderBottomColor: '#eee',
    },
    tabButton: {
        paddingVertical: 10,
        paddingHorizontal: 18,
        marginRight: 10,
        borderRadius: 8,
        backgroundColor: '#f1f1f1',
    },
    tabButtonActive: {
        backgroundColor: COR_DESTAQUE,
    },
    tabText: {
        fontSize: 14,
        color: '#999',
    },
    tabTextActive: {
        color: '#fff',
        fontWeight: 'bold',
    },

    exameListContainer: { marginBottom: 20 },
    secaoTitulo: { fontSize: 16, fontWeight: 'bold', color: COR_FUNDO_ESCURO, marginTop: 10, marginBottom: 10 },
    checkboxItem: { flexDirection: 'row', alignItems: 'center', paddingVertical: 8 },
    checkboxLabel: { fontSize: 16, color: '#333', marginLeft: 10 },
    checkboxOuter: { height: 20, width: 20, borderWidth: 2, borderColor: COR_FUNDO_ESCURO, alignItems: 'center', justifyContent: 'center' },
    checkboxInner: { height: 12, width: 12, backgroundColor: COR_DESTAQUE },

    selecaoTitulo: { fontSize: 14, fontWeight: 'bold', color: COR_FUNDO_ESCURO, marginTop: 15, marginBottom: 5 },
    selecaoItem: { fontSize: 13, color: '#555' },

    botaoFixoContainer: {
        marginTop: 20,
        alignItems: 'center',
        width: '100%',
    },
    botao: {
        backgroundColor: COR_DESTAQUE,
        borderRadius: 12,
        width: '100%',
        paddingVertical: 18,
        alignItems: 'center',
    },
    textoBotao: { color: '#fff', fontSize: 18, fontWeight: 'bold' },
});
