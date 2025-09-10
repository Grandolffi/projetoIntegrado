import { Text, View, StyleSheet, StatusBar, Touchable, TouchableOpacity } from "react-native"

const statusBarHeight = StatusBar.currentHeight ? StatusBar.currentHeight + 22 : 64;

export default function Header(){
    return(
        <View style={Estilo.container}>
            <View style={Estilo.content}>
                <Text style={Estilo.title}>  
                    <Text style={Estilo.coloredText}>BIO</Text> Diagnóstico
                </Text>
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
        paddingStart: 34,
        paddingEnd: 16,
        paddingBottom: 44
    },
    title: {
        fontSize: 26,
        color: '#fff',
        fontWeight: 'bold',
        marginTop: 30
    },
    buttonUser: {
        width: 44,
        height: 44,
        backgroundColor: 'rgba(255, 255, 255, 0.5)',
        justifyContent: 'center',
        alignItems: 'center',
        borderRadius: 44 / 2
    },
    coloredText: {
        color: '#00C092'
    },
    divider: {
        borderBottomColor: '#586A76', 
        borderBottomWidth: 2,
       
    }
})