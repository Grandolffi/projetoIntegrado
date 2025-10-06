import { View, StyleSheet, Text, TextInput, TouchableOpacity, ScrollView } from "react-native";
import Header from "../../components/Header";
import User from "../../components/User";
import PageAtual from "../../components/PageAtual";


export default function NovoExame(){
    return(
        // Use 'keyboardShouldPersistTaps="handled"' se tiver problemas com o teclado
        <View style={Estilo.container}>
            <Header />
            <User nomeUsuario="Fernanda" />
            {/* O componente PageAtual deve ter um estilo que o destaque mais */}
            <PageAtual Pageatual="Solicitar Novo Exame" />
            {/* Este View agora irá preencher o espaço restante, empurrando o botão para baixo */}
            <ScrollView contentContainerStyle={Estilo.formEBotaoWrapper}>
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
                        keyboardType="numeric" // Sugestão para ID
                    />
                    <Text style={Estilo.label}>Data e hora do Exame</Text>
                    <TextInput
                        style={Estilo.input}
                        placeholder="dd/mm/aaaa --:--"
                        placeholderTextColor="#999"
                    />
                    
                    {/* Aqui entraria a seção de Checkbox */}
                </View>

                {/* Este container tem 'marginTop: "auto"' para empurrá-lo para baixo */}
            </ScrollView>

            <View style={Estilo.botaoContainer}>
                    <TouchableOpacity style={Estilo.botao} activeOpacity={0.8}>
                        <Text style={Estilo.textoBotao}>Marcar Exame</Text>
                    </TouchableOpacity>
                </View>
        </View>
    )
}

const Estilo = StyleSheet.create({
    container: {
        flex: 1, 
        backgroundColor: '#f0f0f0' // Fundo mais claro para contraste
    }, 
    //wrapper para o formulário e botão
    contentContainerStyle: {
           paddingHorizontal: 20,
           paddingBottom: 40
    },
    campoContainer: {
        backgroundColor: '#fff',
        padding: 20, // Padding mais uniforme
        borderRadius: 10, // Levemente arredondado
        marginTop: 10, // Mais espaço após o título da página
        paddingBottom: 30,
        shadowColor: "#000",
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 3.84,
        elevation: 5,
    },
    label: {
        fontSize: 16, // Um pouco menor para dar destaque ao input
        color: '#333', // Cor mais escura para legibilidade, sem negrito
        marginBottom: 5,
        fontWeight: 'bold', 
    },
    input: {
        height: 50,
        borderBottomWidth: 2, // Apenas borda inferior para um visual limpo
        borderBottomColor: '#ccc', // Cor da borda em cinza claro
        borderRadius: 0, // Sem borda arredondada na parte inferior
        paddingTop: 5,
        paddingBottom: 5, 
        paddingHorizontal: 0, // Padding horizontal zero para alinhar com a label
        backgroundColor: '#fff', 
        marginBottom: 20,
        fontSize: 17, // Fonte maior para o que será digitado
    }, 
    // Mudança de posicionamento: agora usa o flexbox para empurrar o botão
    botaoContainer: {
        marginTop: 'auto', // Empurra para o final do formEBotaoWrapper
        paddingVertical: 20,
        alignItems: 'center',
        // Opcional: Adicionar uma linha superior para separar do conteúdo
        borderTopWidth: 1,
        borderTopColor: '#eee',
        backgroundColor: '#f0f0f0', // Mantém o fundo da tela na área do botão
    },
    botao: {
        backgroundColor: '#0A212F', // Cor escura (principal)
        borderRadius: 12, // Mais arredondado
        width: '100%', // Ocupa a largura total do 'botaoContainer'
        paddingVertical: 18, // Um pouco mais alto
        alignItems: 'center',
        // Sombra mais intensa para o botão
        shadowColor: "#000",
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.3,
        shadowRadius: 5.46,
        elevation: 9,
    }, 
    textoBotao: {
        color: '#fff',
        fontSize: 18, // Fonte maior para destaque
        fontWeight: 'bold',
    }
});