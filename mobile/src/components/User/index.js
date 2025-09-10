import { Text, View, StyleSheet, Touchable, TouchableOpacity } from "react-native"

import {Feather} from '@expo/vector-icons';


export default function User({nomeUsuario = "Não identificado"}){
    return(
        <View style={Estilo.container}>
            <View style={Estilo.content}>
                <TouchableOpacity style={Estilo.buttonUser}> 
                 <Feather name='user' size={40} color={'#fff'}/>
                </TouchableOpacity>
                <View style={Estilo.DadosUser}> 
                    <Text style={Estilo.title}> {nomeUsuario} </Text>
                    <Text style={Estilo.Subtitle} > Super Admin </Text>
                </View>
            </View>
            <TouchableOpacity style={Estilo.buttonMore}> 
                    <Feather name="more-vertical" size={24} color={'#fff'} />
                </TouchableOpacity>
        </View>
    )
}

const Estilo = StyleSheet.create({
    container: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        backgroundColor: '#0A212F',
        paddingTop: 30,
        paddingStart: 16,
        paddingEnd: 16,
        paddingBottom: 40
    },
    content: {
        flexDirection: 'row',
        justifyContent: 'flex-start',
        alignItems: 'center'
    },
    title: {
        fontSize: 18,
        color: '#fff',
        fontWeight: 'bold',
        paddingStart: 20
    },
    buttonUser: {
        width: 44,
        height: 44,
        backgroundColor: 'rgba(255, 255, 255, 0.5)',
        justifyContent: 'center',
        alignItems: 'center',
        borderRadius: 44 / 2
    },
    buttonMore: {
        width: 54,
        height: 54,
        justifyContent: 'center',
    },
    DadosUser: {
        flexDirection: 'column',
    },
    Subtitle: {
        marginTop: 10,
        fontSize: 14,
        color: '#fff',
        paddingStart: 20
    }
})