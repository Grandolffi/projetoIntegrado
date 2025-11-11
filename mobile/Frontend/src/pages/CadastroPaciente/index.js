import React, { useState } from 'react';
import { View, StyleSheet, Text, TextInput, TouchableOpacity, ScrollView, Alert } from "react-native";
import { Picker } from '@react-native-picker/picker'; // 👈 Importar o Picker
import { Feather } from '@expo/vector-icons'; // Para o ícone de calendário
import Header from "../../components/Header";
import User from "../../components/User";
import PageAtual from "../../components/PageAtual"; // Vamos usar este componente para o título
import { CreatePacientesFromAPI } from '../../API/Pacientes';

export default function CadastroPaciente(){
    // 1. Definição dos Estados para os campos de texto e seleção
    const [nomeCompleto, setNomeCompleto] = useState('');
    const [cpf, setCpf] = useState('');
    const [dtnasc, setdtnasc] = useState('');
    const [email, setEmail] = useState('');
    const [nomeMae, setNomeMae] = useState('');
    const [numCelular, setnumCelular] = useState('');
    const [genero, setGenero] = useState('Masculino'); // Valor inicial

    async function handleCadastro(){
    try{
      Alert.alert('Cadastrando paciente!');
      if(nomeCompleto && cpf && dtnasc && email && nomeMae && numCelular && genero){
        await CreatePacientesFromAPI({nome: nomeCompleto, cpf: cpf, dtnasc: dtnasc, email: email, nomeMae: nomeMae, numCelular: numCelular, genero:genero });
        setNomeCompleto("");
        setCpf("");
        setdtnasc("");
        setEmail("");
        setNomeMae("");
        setnumCelular("");
        Alert.alert("Sucesso", "Paciente cadastrado!");
      }
    }catch(error){
      Alert.alert("Erro", "Paciente não inserido!");
    }
  }
    
    return(
        <View style={Estilo.container}>
            <Header />
            <User nomeUsuario="Fernanda" />
            
            {/* Título da Página inspirado no NovoExame */}
            <PageAtual Pageatual="Cadastro de Paciente" />

            {/* ScrollView para garantir que todos os campos sejam acessíveis */}
            <ScrollView contentContainerStyle={Estilo.formContent}>
                
                {/* O formulário agora ocupa toda a largura para um visual mais clean, como na imagem */}
                <View style={Estilo.campoContainer}>
                    
                    {/* Nome Completo */}
                    <Text style={Estilo.label}>Nome completo</Text>
                    <TextInput
                        style={Estilo.input}
                        placeholder="Digite o nome completo..."
                        placeholderTextColor="#999"
                        value={nomeCompleto}
                        onChangeText={setNomeCompleto}
                    />
                    
                    {/* CPF */}
                    <Text style={Estilo.label}>CPF</Text>
                    <TextInput
                        style={Estilo.input}
                        placeholder="000.000.000-00"
                        placeholderTextColor="#999"
                        keyboardType="numeric" 
                        maxLength={14} // Incluindo pontos e traço para máscara simples
                        value={cpf}
                        onChangeText={setCpf}
                    />
                    
                    {/* Data de Nascimento - Com ícone de Calendário */}
                    <Text style={Estilo.label}>Data de nascimento</Text>
                    <View style={Estilo.inputComIcone}>
                        <TextInput
                            style={[Estilo.input, Estilo.inputData]} // Estilo inputData garante o flex
                            placeholder="dd/mm/aaaa"
                            placeholderTextColor="#999"
                            keyboardType="numeric"
                            maxLength={10} 
                            value={dtnasc}
                            onChangeText={setdtnasc}
                        />
                         {/* Ícone de calendário */}
                        <Feather name="calendar" size={24} color="#999" style={Estilo.iconeInput} /> 
                    </View>
                    
                    {/* E-mail */}
                    <Text style={Estilo.label}>E-mail</Text>
                    <TextInput
                        style={Estilo.input}
                        placeholder="exemplo@dominio.com"
                        placeholderTextColor="#999"
                        keyboardType="email-address"
                        value={email}
                        onChangeText={setEmail}
                    />

                    {/* Nome da mãe */}
                    <Text style={Estilo.label}>Nome da mãe</Text>
                    <TextInput
                        style={Estilo.input}
                        placeholder="Digite o nome completo da mãe..."
                        placeholderTextColor="#999"
                        value={nomeMae}
                        onChangeText={setNomeMae}
                    />
                    
                    {/* Celular com DDD */}
                    <Text style={Estilo.label}>Celular com DDD</Text>
                    <TextInput
                        style={Estilo.input}
                        placeholder="Ex: 11 99999-9999"
                        placeholderTextColor="#999"
                        keyboardType="phone-pad"
                        maxLength={15}
                        value={numCelular}
                        onChangeText={setnumCelular}
                    />

                    {/* Gênero (Usando Picker) */}
                    <Text style={Estilo.label}>Gênero</Text>
                    {/* O container e a borda simulam o estilo input */}
                    <View style={Estilo.pickerContainer}> 
                        <Picker
                            selectedValue={genero}
                            onValueChange={(itemValue) => setGenero(itemValue)}
                            style={Estilo.picker}
                            itemStyle={Estilo.pickerItem}
                        >
                            <Picker.Item label="Masculino" value="Masculino" />
                            <Picker.Item label="Feminino" value="Feminino" />
                            <Picker.Item label="Não-binário" value="Nao-binario" />
                            <Picker.Item label="Prefiro não informar" value="Nao-Informar" />
                        </Picker>
                    </View>
                    
                </View>
            </ScrollView>

            {/* Botão Fixo no Rodapé, alinhado ao Estilo NovoExame */}
            <View style={Estilo.botaoFixoContainer}>
                <TouchableOpacity style={Estilo.botao} activeOpacity={0.8} onPress={handleCadastro}>
                    <Text style={Estilo.textoBotao}>Enviar</Text>
                </TouchableOpacity>
            </View>
        </View>
    )
}

const Estilo = StyleSheet.create({
    container: {
        flex: 1, 
        backgroundColor: '#f0f0f0' 
    }, 
    // Estilo do contentContainerStyle do ScrollView
    formContent: {
        paddingHorizontal: 20,
        // Espaço para que o Picker não fique escondido atrás do botão fixo
        paddingBottom: 100, 
    },
    campoContainer: {
        backgroundColor: '#fff',
        padding: 20, 
        borderRadius: 10, 
        // 💡 Ajuste: Usando margem zero para encostar no PageAtual
        marginTop: 10, 
        paddingBottom: 30,
        shadowColor: "#000",
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 3.84,
        elevation: 5,
    },
    
    label: {
        fontSize: 16, 
        color: '#333',
        marginBottom: 5,
        fontWeight: 'bold', 
    },
    
    // Estilo para o campo de data com ícone
    inputComIcone: {
        flexDirection: 'row',
        alignItems: 'center',
        // BorderBottom é aplicado no TextInput interno para ser o mesmo
    },
    iconeInput: {
        position: 'absolute',
        right: 0,
        bottom: 25, // Alinhado verticalmente com o input
    },
    inputData: {
        flex: 1, // Permite que o TextInput ocupe o máximo de espaço possível
        paddingRight: 30, // Deixa espaço para o ícone
    },
    
    // Estilo Básico do Input
    input: {
        height: 50,
        borderBottomWidth: 2, 
        borderBottomColor: '#ccc',
        borderRadius: 0, 
        paddingTop: 5,
        paddingBottom: 5, 
        paddingHorizontal: 0, 
        backgroundColor: '#fff', 
        marginBottom: 20,
        fontSize: 17, 
    }, 
    
    // Estilos do Picker (Gênero)
    pickerContainer: {
        // Simula a borda inferior do input
        borderBottomWidth: 2, 
        borderBottomColor: '#ccc',
        marginBottom: 20,
        height: 50,
        justifyContent: 'center',
    },
    picker: {
        height: 50,
        // Remova a cor de fundo aqui para que o borderBottom apareça
        backgroundColor: 'transparent',
    },
    pickerItem: {
        fontSize: 17,
    },
    
    // Botão Fixo (BOTTOM BAR)
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
        zIndex: 10, // Garante que o botão fique acima do ScrollView
    },
    botao: {
        backgroundColor: '#0A212F',
        borderRadius: 12,
        width: '100%',
        paddingVertical: 18,
        alignItems: 'center',
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