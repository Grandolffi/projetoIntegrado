import { Text, View, StyleSheet, } from "react-native"

export default function PageAtual({Pageatual = "Não identificado"}){
    return(
        <View style={Estilo.container}>
            <View style={Estilo.content}>
                <Text style={Estilo.title}> {Pageatual} </Text>
            </View>
        </View>
    )
}

const Estilo = StyleSheet.create({
    container: {
        backgroundColor: '#00C092',
        paddingTop: 15,
        paddingStart: 16,
        paddingEnd: 16,
        paddingBottom: 15
    },
    content: {
        flexDirection: 'row',
        justifyContent: 'center',
        alignItems: 'center'
    },
    title: {
        fontSize: 22,
        color: '#fff',
        fontWeight: 'bold',
        
    },
    
})