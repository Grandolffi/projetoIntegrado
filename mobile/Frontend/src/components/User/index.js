import { Text, View, StyleSheet, Touchable, TouchableOpacity } from "react-native"

import {Feather} from '@expo/vector-icons';

const COR_DESTAQUE = '#1ABC9C';
const COR_FUNDO_ESCURO = '#0A212F';

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
        backgroundColor: COR_FUNDO_ESCURO,
        paddingTop: 10,
        paddingStart: 20,
        paddingEnd: 20,
        paddingBottom: 20
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
        paddingStart: 15
    },
    buttonUser: {
        width: 50,
        height: 50,
        justifyContent: 'center',
        alignItems: 'center',
        borderRadius: 50 / 2
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
        marginTop: 2,
        fontSize: 14,
        color: '#ccc',
        paddingStart: 15
    }
})