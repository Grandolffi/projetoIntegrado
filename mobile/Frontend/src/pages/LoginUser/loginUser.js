import { StyleSheet, View, Text, Image, TextInput, TouchableOpacity, ScrollView  } from "react-native";
import { useState } from "react";
import {AntDesign, Feather} from "@expo/vector-icons";

export default function Login({navigation}) {
    const [login, setLogin] = useState("");
    const [senha, setSenha] = useState("");

    const [token, setToken] = useState(null);

    async function handleLogin(){
        try{
            if(!login || !senha){
                console.error("Login e/ou senha não informados");
            }

            const res = await fetch('http://localhost:3000/login', {
                method: 'POST', //metodo que vou mandar via post para login
                headers: {'Content-Type': 'application/json'}, /*avisando que vou mandar um json pro servidor*/
                body: JSON.stringify({plogin: login, psenha: senha}) //corpo da requisição, parametro pro servidor
            })

            if(!res.ok) throw new Error(await res.text());

            const data = await res.json();

            if(data.token){
                setToken(data.token);
                navigation.navigate("Home", {token: data.token}); //passa token para outra pasta
            }else{
                console.error("Login e/ou senha incorretos!");
            }

        }catch(error){
            console.error("Erro ao realizar POST na API", error);
        }
    }
    return (
        
            <View style={Estilo.container}>
                
                {/* TÍTULO IGUAL AO DA WEB */}
                <Text style={Estilo.tituloPrincipal}>Bem-vindo ao Programa de Bioquímica</Text>
                <Text style={Estilo.subTitulo}>Bio Diagnóstico</Text>

                <View style={Estilo.cardLogin}>
                    <Text style={Estilo.txtLogin}>Login</Text>

                    <Text style={Estilo.label}>Username:</Text>
                    <TextInput
                        style={Estilo.input}
                        placeholder="Enter your Username"
                        keyboardType="email-address"
                        autoCapitalize="none"
                        value={login}
                        onChangeText={setLogin}
                    />

                    <Text style={Estilo.label}>Password:</Text>
                    <TextInput
                        style={Estilo.input}
                        placeholder="**********"
                        secureTextEntry={true}
                        autoCapitalize="none"
                        value={senha}
                        onChangeText={setSenha}
                    />

                    <TouchableOpacity style={Estilo.labelForgotContainer}>
                        <Text style={Estilo.labelForgot}>Esqueceu a senha?</Text>
                    </TouchableOpacity>
                    

                    <View style={Estilo.socialButtonsContainer}>
                        <TouchableOpacity style={Estilo.socialButton}>
                            <AntDesign name="google" style={Estilo.socialIcon} size={20} />
                        </TouchableOpacity>
                        <TouchableOpacity style={Estilo.socialButton}>
                            <Feather name="facebook" style={Estilo.socialIcon} size={20} />
                        </TouchableOpacity>
                        <TouchableOpacity style={Estilo.socialButton}>
                            <AntDesign name="instagram" style={Estilo.socialIcon} size={20} />
                        </TouchableOpacity>
                    </View>

                    <TouchableOpacity style={Estilo.sendButton} onPress={handleLogin}>
                        <Text style={Estilo.sendButtonText}>Login</Text>
                    </TouchableOpacity>
                    <TouchableOpacity style={Estilo.labelForgotContainer}>
                        <Text style={Estilo.labelForgot}>Sou paciente</Text>
                    </TouchableOpacity>

                </View>
            </View>
        
    );
}

const Estilo = StyleSheet.create({
    container: {
        flex: 1,
        justifyContent: "flex-start",
        alignItems: "center",
        paddingTop: 40,
        paddingHorizontal: 20,

        /* FUNDO IGUAL AO DA WEB */
        backgroundColor: "#e0f2f7",
    },

    /* TÍTULO PRINCIPAL */
    tituloPrincipal: {
        fontSize: 26,
        fontWeight: "bold",
        color: "#2c3e50",
        textAlign: "center",
        marginBottom: 0
    },

    subTitulo: {
        fontSize: 18,
        color: "#1abc9c",
        textAlign: "center",
        marginBottom: 15,
        fontWeight: "600"
    },

    cardLogin: {
        width: "88%",
        backgroundColor: "rgba(255,255,255,0.95)",
        padding: 20,
        borderRadius: 12,
        shadowColor: "#000",
        shadowOpacity: 0.15,
        shadowRadius: 8,
        elevation: 4,
        marginTop: 5,                  
        marginBottom: 20 
    },

    txtLogin: {
        fontSize: 22,
        fontWeight: "bold",
        color: "#2c3e50",
        textAlign: "center",
        marginBottom: 25
    },

    label: {
        fontSize: 14,
        color: "#34495e",
        fontWeight: "500",
        marginBottom: 6
    },

    input: {
        backgroundColor: "#f8f9fa",
        borderColor: "#ced4da",
        color: "#495057",
        borderWidth: 1,
        borderRadius: 8,
        padding: 12,
        marginBottom: 18
    },

    labelForgotContainer: {
        alignItems: "center",
        width: "100%"
    },

    labelForgot: {
        color: "#8EBFDD",
        fontWeight: "500"
    },

    socialButtonsContainer: {
        flexDirection: "row",
        justifyContent: "center",
        marginVertical: 20,
        gap: 25
    },

    socialButton: {
        width: 65,
        height: 65,
        backgroundColor: "#B7E7FE",
        borderColor: "#A8D1E3",
        borderWidth: 2,
        borderRadius: 10,
        justifyContent: "center",
        alignItems: "center"
    },

    socialIcon: {
        color: "#51BCF4"
    },

    sendButton: {
        backgroundColor: "#1abc9c",
        paddingVertical: 14,
        borderRadius: 10,
        alignItems: "center"
    },

    sendButtonText: {
        color: "#fff",
        fontWeight: "bold",
        fontSize: 17
    }
}); 