import { Text, View, StyleSheet, } from "react-native"

const COR_DESTAQUE = '#1ABC9C';

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
        backgroundColor: COR_DESTAQUE,
        paddingVertical: 15,
        paddingHorizontal: 16,
        borderBottomLeftRadius: 15,
        borderBottomRightRadius: 15,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 3,
        elevation: 5,
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
        textTransform: 'uppercase',
    },
    
})