import React, { useState } from 'react';
import { View, StyleSheet, Text, TextInput, TouchableOpacity, ScrollView, Alert } from "react-native";
import Header from "../../components/Header";
import User from "../../components/User"; 
import PageAtual from "../../components/PageAtual";
import { CreateSolicitacaoFromAPI } from '../../API/Solicitacoes'; 

const COR_DESTAQUE = '#1ABC9C'; 
const COR_FUNDO_ESCURO = '#0A212F'; 

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


export default function NovoExame(){
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

    // FUNÇÃO DE ENVIO PARA A API (POST /solicitacoes)
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
                Alert.alert("Sucesso", "Solicitação de Exame enviada com sucesso!");
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
    
    // Componente auxiliar para renderizar a lista de Checkboxes nativas
    const ExamesCheckboxList = ({ categoria }) => (
        <View style={Estilo.exameListContainer}>
            {/* Título da seção (Exames de Bioquímica:) */}
             <Text style={Estilo.secaoTitulo}>Exames de {categoria}:</Text>
            {EXAMES_POR_CATEGORIA[categoria]?.map(exame => (
                <TouchableOpacity
                    key={exame.id}
                    style={Estilo.checkboxItem}
                    onPress={() => toggleExame(exame)}
                >
                    {/* Simulação da Checkbox usando Views (Nativo) */}
                    <View style={Estilo.checkboxOuter}>
                         {examesSelecionados[exame.id] && (
                            <View style={Estilo.checkboxInner} />
                        )}
                    </View>
                    <Text style={Estilo.checkboxLabel}>{exame.nome}</Text>
                </TouchableOpacity>
            ))}
        </View>
    );

    return(
        <View style={Estilo.container}>
            <Header />
            <User nomeUsuario="Fernanda" />
            
            <PageAtual Pageatual="Solicitar Novo Exame" />
            
            <ScrollView contentContainerStyle={Estilo.formContent}>
                <View style={Estilo.campoContainer}>
                    
                    {/* CAMPOS BÁSICOS DO PACIENTE */}
                    <Text style={Estilo.label}>Nome do Paciente</Text>
                    <TextInput style={Estilo.input} placeholder="Digite o nome..." placeholderTextColor="#999" value={nomePaciente} onChangeText={setNomePaciente} />
                    
                    <Text style={Estilo.label}>Id do Paciente</Text>
                    <TextInput style={Estilo.input} placeholder="Digite o ID do paciente..." placeholderTextColor="#999" keyboardType="numeric" value={pacienteId} onChangeText={setPacienteId} />
                    
                    <Text style={Estilo.label}>Data e hora do Exame</Text>
                    <TextInput style={Estilo.input} placeholder="dd/mm/aaaa --:--" placeholderTextColor="#999" value={dataHora} onChangeText={setDataHora} />

                    
                    {/* SEÇÃO DINÂMICA DE SELEÇÃO DE LABORATÓRIOS (ABAS) */}
                    <Text style={[Estilo.label, {marginTop: 10}]}>Laboratórios Solicitados</Text>
                    
                    {/* Guias/Tabs */}
                    <View style={Estilo.tabsContainer}>
                        {Object.keys(EXAMES_POR_CATEGORIA).map(aba => (
                            <TouchableOpacity
                                key={aba}
                                style={[Estilo.tabButton, abaAtiva === aba && Estilo.tabButtonActive]}
                                onPress={() => setAbaAtiva(aba)}
                            >
                                <Text style={[Estilo.tabText, abaAtiva === aba && Estilo.tabTextActive]}>{aba}</Text>
                            </TouchableOpacity>
                        ))}
                    </View>
                    
                    {/* RENDERIZAÇÃO CONDICIONAL: A lista de checkboxes só aparece se uma aba estiver ativa */}
                    {abaAtiva && (
                        <ExamesCheckboxList categoria={abaAtiva} />
                    )}
                    
                </View>
                {/* Aqui está a contagem e a lista de debug que você gosta */}
                <Text style={Estilo.selecaoTitulo}>Exames Selecionados ({Object.keys(examesSelecionados).length})</Text>
                {Object.values(examesSelecionados).map(ex => (
                    <Text key={ex.id} style={Estilo.selecaoItem}>{ex.nome} ({ex.tipo || ex.nome})</Text>
                ))}
            </ScrollView>

            <View style={Estilo.botaoFixoContainer}>
                <TouchableOpacity style={Estilo.botao} activeOpacity={0.8} onPress={handleSolicitarExame}>
                    <Text style={Estilo.textoBotao}>Marcar Exame</Text>
                </TouchableOpacity>
            </View>
        </View>
    )
}

const Estilo = StyleSheet.create({
    container: { flex: 1, backgroundColor: '#f0f0f0' }, 
    formContent: { paddingHorizontal: 20, paddingBottom: 100 }, 
    
    // Estilo de Card para o Formulário 
    campoContainer: { 
        backgroundColor: '#fff', padding: 20, borderRadius: 10, marginTop: 10, paddingBottom: 30,
        shadowColor: "#000", shadowOffset: { width: 0, height: 2 }, shadowOpacity: 0.1, shadowRadius: 3.84, elevation: 5,
    },
    label: { fontSize: 16, color: '#333', marginBottom: 5, fontWeight: 'bold' },
    input: { height: 50, borderBottomWidth: 2, borderBottomColor: '#ccc', borderRadius: 0, paddingTop: 5, paddingBottom: 5, paddingHorizontal: 0, backgroundColor: '#fff', marginBottom: 20, fontSize: 17, }, 

    tabsContainer: {
        flexDirection: 'row',
        marginBottom: 15,
        borderBottomWidth: 2,
        borderBottomColor: '#eee',
    },
    tabButton: {
        paddingHorizontal: 15,
        paddingVertical: 10,
        marginRight: 10,
        borderBottomWidth: 3,
        borderBottomColor: 'transparent',
    },
    tabButtonActive: {
        borderBottomColor: COR_DESTAQUE, 
    },
    tabText: {
        fontSize: 15,
        color: '#999',
        fontWeight: '600',
    },
    tabTextActive: {
        color: COR_FUNDO_ESCURO,
    },
    exameListContainer: {
        marginBottom: 20, 
    },
    secaoTitulo: {
        fontSize: 16,
        fontWeight: 'bold',
        color: COR_FUNDO_ESCURO,
        marginTop: 10,
        marginBottom: 10,
    },
    checkboxItem: {
        flexDirection: 'row',
        alignItems: 'center',
        paddingVertical: 8, 
    },
    checkboxLabel: {
        fontSize: 16,
        color: '#333',
        marginLeft: 10,
    },
    checkboxOuter: {
        height: 20,
        width: 20,
        borderWidth: 2,
        borderColor: COR_FUNDO_ESCURO,
        alignItems: 'center',
        justifyContent: 'center',
        marginRight: 8,
    },
    checkboxInner: {
        height: 12,
        width: 12,
        backgroundColor: COR_DESTAQUE,
    },
    selecaoTitulo: {
        fontSize: 14,
        fontWeight: 'bold',
        color: COR_FUNDO_ESCURO,
        marginTop: 15,
        marginBottom: 5,
        paddingHorizontal: 20,
    },
    selecaoItem: {
        fontSize: 13,
        color: '#555',
        paddingHorizontal: 20,
    },
    botaoFixoContainer: { 
        position: 'absolute', bottom: 0, left: 0, right: 0, paddingHorizontal: 20, paddingVertical: 15, alignItems: 'center', backgroundColor: '#f0f0f0', borderTopWidth: 1, borderTopColor: '#eee', zIndex: 10,
    },
    botao: {
        backgroundColor: COR_DESTAQUE, 
        borderRadius: 12, width: '100%', paddingVertical: 18, alignItems: 'center', shadowColor: "#000", shadowOffset: { width: 0, height: 4 }, shadowOpacity: 0.3, shadowRadius: 5.46, elevation: 9,
    }, 
    textoBotao: { color: '#fff', fontSize: 18, fontWeight: 'bold', },
});