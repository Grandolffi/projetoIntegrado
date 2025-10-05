import { Text, View, StyleSheet, StatusBar } from "react-native"

// Pega a altura da barra de status + um padding superior
const statusBarHeight = StatusBar.currentHeight ? StatusBar.currentHeight + 22 : 64;

export default function Header(){
    return(
        <View style={Estilo.container}>
            <View style={Estilo.content}>
                {/* Adicionado um espaço vazio aqui (View) que corresponderá 
                  ao tamanho do botão de menu na tela Home, garantindo que o título 
                  não fique muito à esquerda.
                */}
                <View style={Estilo.placeholder} /> 

                <Text style={Estilo.title}> 	
                    <Text style={Estilo.coloredText}>BIO</Text> Diagnóstico
                </Text>

                {/* Este View fica vazio ou pode receber um ícone de Notificações, por exemplo */}
                <View style={Estilo.placeholder} /> 
            </View>
            <View style={Estilo.divider} />
        </View>
    )
}

const Estilo = StyleSheet.create({
    container: {
        backgroundColor: '#0A212F',
        paddingTop: statusBarHeight,
    },
    content: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        paddingHorizontal: 16, // Usando padding geral
        paddingBottom: 44
    },
    title: {
        fontSize: 26,
        color: '#fff',
        fontWeight: 'bold',
        marginTop: 30,
        // Alinhamento centralizado se houver placeholders iguais
        flex: 1, 
        textAlign: 'center' 
    },
    coloredText: {
        color: '#00C092'
    },
    divider: {
        borderBottomColor: '#586A76', 
        borderBottomWidth: 2,
    },
    placeholder: {
        // Define um espaço fixo para o botão de menu (hambúrguer)
        width: 40, 
        height: 40,
        marginTop: 30,
    }
})
