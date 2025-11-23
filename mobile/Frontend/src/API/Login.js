const BASE_URL = "http://localhost:3000/";
const AUTH_HEADER = {
  "Content-Type": "application/json",
  //"Authorization": `Bearer ${TOKEN}`
}

import AsyncStorage from '@react-native-async-storage/async-storage';

export const loginFromAPI = async (user) => {
    try{
        const res = await fetch(`${BASE_URL}login`, {
            method: 'POST', //metodo que vou mandar via post para login
            headers: AUTH_HEADER, /*avisando que vou mandar um json pro servidor*/
            body: JSON.stringify(user) //corpo da requisição, parametro pro servidor
        })
        console.log(user)

        const json = await res.json();

        if(!res.ok) return { success: false, message: json.message };

        console.log("Conteudo do JSON: ", json);
        return { success: true, json};

    }catch(error){
        console.error("Erro ao realizar requisição Login: ", error);
        return { success: false, message: "Erro interno no login." };
    }
}

export const logoutUser = async () => {
    try {
        // Remove o token de autenticação armazenado
        await AsyncStorage.removeItem('@user_token');
        console.log("Token de usuário removido com sucesso.");
        return { success: true };
    } catch (error) {
        console.error("Erro ao remover token do AsyncStorage:", error);
        return { success: false, message: "Falha ao limpar o token local." };
    }
}