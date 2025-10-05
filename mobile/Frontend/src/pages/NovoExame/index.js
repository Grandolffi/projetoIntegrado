import { View, StyleSheet, Text, TextInput, TouchableOpacity } from "react-native";
import Header from "../../components/Header";
import User from "../../components/User";
import PageAtual from "../../components/PageAtual";


export default function NovoExame(){
    return(
        <View style={Estilo.container}>
            <Header />
            <User nomeUsuario="Fernanda"/>
            <PageAtual Pageatual="Solicitar Novo Exame"/>

             <View style={Estilo.campoContainer}>
                <Text style={Estilo.label}>Nome do Paciente</Text>
                <TextInput
                    style={Estilo.input}
                    placeholder="Digite o nome..."
                    placeholderTextColor="#999"
                />
                <Text style={Estilo.label}>Id do Paciente</Text>
                <TextInput
                    style={Estilo.input}
                    placeholder="Digite o ID do paciente..."
                    placeholderTextColor="#999"
                />
                <Text style={Estilo.label}>Data e hora do Exame</Text>
                <TextInput
                    style={Estilo.input}
                    placeholder="dd/mm/aaaa --:--"
                    placeholderTextColor="#999"
                />
                
                {/*Professor aqui iria precisar de uma parte de checkbox porem deixamos ser fazer parar tirar duvida com voce
                na quinta feira, de como podemos fazer*/}
            </View>
            <View style={Estilo.botaoContainer}>
                    <TouchableOpacity style={Estilo.botao}>
                        <Text style={Estilo.textoBotao}>Marcar Exame</Text>
                    </TouchableOpacity>
            </View>
            
        </View>
    )
}

const Estilo = StyleSheet.create({
    container: {
        flex: 1, // Isso garante que a view ocupe toda a tela
        backgroundColor: '#f0f0f0' // Um fundo mais claro
    },
    campoContainer: {
        backgroundColor: '#fff',
        paddingHorizontal: 20,
        paddingTop: 20,
        marginBottom: 20
    },
    label: {
        fontSize: 16,
        fontWeight: 'bold',
        marginBottom: 10
    },
    input: {
        height: 50,
        borderWidth: 1,
        borderColor: '#ccc',
        borderRadius: 8,
        paddingHorizontal: 15,
        backgroundColor: '#f8f8f8',
        marginBottom: 20
    }, 
    botaoContainer: {
        position: 'absolute',
        bottom: 10,
        left: 0,
        right: 0,
        alignItems: 'center',
        paddingVertical: 20,
        backgroundColor: '#fff', 
    },
    botao: {
        backgroundColor: '#0A212F',
        borderRadius: 8,
        paddingVertical: 15,
        paddingHorizontal: 50,
        alignItems: 'center',
    },
    textoBotao: {
        color: '#fff',
        fontSize: 16,
        fontWeight: 'bold',
    }
        
})