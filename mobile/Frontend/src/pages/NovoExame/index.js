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
        { id: 20, nome_exame: 'Urocultura com antibiograma', tipo_exame_categoria: 'Microbiologia' },
        { id: 21, nome_exame: 'Swab ocular', tipo_exame_categoria: 'Microbiologia' },
        { id: 22, nome_exame: 'Escarro para Micobacterium tuberculosis', tipo_exame_categoria: 'Microbiologia' },
    ],
    Parasitologia: [
        { id: 27, nome_exame: 'Exame de fezes', tipo_exame_categoria: 'Parasitologia' },
        { id: 28, nome_exame: 'Pesquisa de hematozoários', tipo_exame_categoria: 'Parasitologia' },
        { id: 29, nome_exame: 'Pesquisa de protozoários intestinais', tipo_exame_categoria: 'Parasitologia' },
    ],
    Hematologia: [
        { id: 23, nome_exame: 'Hemograma Completo', tipo_exame_categoria: 'Hematologia' },
        { id: 24, nome_exame: 'Reticulócitos', tipo_exame_categoria: 'Hematologia' },
        { id: 25, nome_exame: 'Tempo de sangramento', tipo_exame_categoria: 'Hematologia' },
        { id: 26, nome_exame: 'Tempo de coagulação', tipo_exame_categoria: 'Hematologia' },
    ],
    Bioquimica: [
        { id: 1, nome_exame: 'Ácido úrico', tipo_exame_categoria: 'Bioquímica' },
        { id: 2, nome_exame: 'Alfa Amilase', tipo_exame_categoria: 'Bioquímica' },
        { id: 3, nome_exame: 'Bilirrubina Total', tipo_exame_categoria: 'Bioquímica' },
        { id: 4, nome_exame: 'Bilirrubina Direta', tipo_exame_categoria: 'Bioquímica' },
        { id: 5, nome_exame: 'Cálcio', tipo_exame_categoria: 'Bioquímica' },
        { id: 6, nome_exame: 'Colesterol Total', tipo_exame_categoria: 'Bioquímica' },
        { id: 7, nome_exame: 'HDL', tipo_exame_categoria: 'Bioquímica' },
        { id: 8, nome_exame: 'Creatinina', tipo_exame_categoria: 'Bioquímica' },
        { id: 9, nome_exame: 'Ferro Ferene', tipo_exame_categoria: 'Bioquímica' },
        { id: 10, nome_exame: 'Fosfatase Alcalina', tipo_exame_categoria: 'Bioquímica' },
        { id: 11, nome_exame: 'Fosfato', tipo_exame_categoria: 'Bioquímica' },
        { id: 12, nome_exame: 'Gama GT', tipo_exame_categoria: 'Bioquímica' },
        { id: 13, nome_exame: 'Glicose', tipo_exame_categoria: 'Bioquímica' },
        { id: 14, nome_exame: 'GOT (AST)', tipo_exame_categoria: 'Bioquímica' },
        { id: 15, nome_exame: 'GTP (ALT)', tipo_exame_categoria: 'Bioquímica' },
        { id: 16, nome_exame: 'Magnésio', tipo_exame_categoria: 'Bioquímica' },
        { id: 17, nome_exame: 'Proteína total', tipo_exame_categoria: 'Bioquímica' },
        { id: 18, nome_exame: 'Triglicerídeos', tipo_exame_categoria: 'Bioquímica' },
        { id: 19, nome_exame: 'Uréia', tipo_exame_categoria: 'Bioquímica' },
    ],
    Urinálise: [
        { id: 30, nome_exame: 'Exame físico', tipo_exame_categoria: 'Urinálise' },
        { id: 31, nome_exame: 'Exame químico', tipo_exame_categoria: 'Urinálise' },
        { id: 32, nome_exame: 'Exame microscópico', tipo_exame_categoria: 'Urinálise' },
    ],
};

export default function NovoExame({ navigation }) {

    const [nomePaciente, setNomePaciente] = useState('');
    const [pacienteId, setPacienteId] = useState('');
    const [dataHora, setDataHora] = useState('');
    const [examesSelecionados, setExamesSelecionados] = useState({});
    const [abaAtiva, setAbaAtiva] = useState(null);

    const [token, setToken] = useState(null);


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
        console.log("Entrei aqui");
        const idsExames = Object.keys(examesSelecionados);

        try {
            if (!pacienteId || idsExames.length === 0 || !dataHora) {
                Alert.alert("Atenção", "Preencha o ID do paciente, a data/hora e selecione pelo menos um exame.");
            }

            const res = await fetch('http://localhost:3000/solicitacoes', {
                method: 'POST', //metodo que vou mandar via post para solicitacoes
                headers: { 'Content-Type': 'application/json' }, /*avisando que vou mandar um json pro servidor*/
                body: JSON.stringify({ ppacienteId: paciente, pidExames: Exames, pdataHora: dataHora }) //corpo da requisição, parametro pro servidor
            });

            if (!res.ok) throw new Error(await res.text());

            const data = await res.json();

            if (data.token) {
                setToken(data.token);
                navigation.navigate("Home", { token: data.token }); //passa token para outra pasta
            } else {
                console.error("Paciente não encontrado");
            }
        } catch (error) {
            console.error("Erro ao realizar POST na API", error);
        }
    }

    const solicitacaoData = {
        paciente_id: pacienteId,                 // ✔ NOME IGUAL AO BANCO
        data_prevista_realizacao: dataHora,      // ✔ NOME IGUAL AO BANCO
        solicitante_nome: 'Fernanda',            // ✔
        observacoes: "",                         // ✔ opcional, mas existe na tabela
        exames: Object.values(examesSelecionados).map(ex => ({
            nome_exame: ex.nome,                 // ✔ igual ao banco
            tipo_exame_categoria: ex.tipo,       // ✔ igual ao banco
            valor_referencia_solicitacao: "",    // ✔ existe no banco
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
                <Text style={Estilo.checkboxLabel}>{exame.nome_exame}</Text>
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

                {/* CHECKBOXES */}
                {abaAtiva && <ExamesCheckboxList categoria={abaAtiva} />}

                {/* LISTA DOS EXAMES SELECIONADOS */}
                {Object.keys(examesSelecionados).length > 0 && (
                    <View style={{ marginTop: 20 }}>
                        <Text style={Estilo.selecaoTitulo}>Exames Selecionados:</Text>

                        {Object.values(examesSelecionados).map(ex => (
                            <Text key={ex.id} style={Estilo.selecaoItem}>• {ex.nome_exame}</Text>
                        ))}
                    </View>
                )}

                {/* Botão */}
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
