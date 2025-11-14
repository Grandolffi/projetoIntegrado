// NO ARQUIVO API/Resultadosexames.js

const BASE_URL = "http://localhost:3000/"; 
const VALIDO_TOKEN = "Bearer ABC-123-BIO"; 

const AUTH_HEADERS = {
  "Content-Type": "application/json",
  "Authorization": VALIDO_TOKEN 
}

export const LoadExamesFromAPI = async () => {
    try {
        const response = await fetch(`${BASE_URL}exames`, {
            method: "GET",
            headers: AUTH_HEADERS // ENVIA O TOKEN DE SEGURANÇA
        });
        
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`Erro ${response.status}: ${errorText}`);
        }
        
        const json = await response.json();
        // O JSON deve ser um array de objetos
        return json; 

    } catch(error) {
        console.error("Erro ao realizar requisição GET de Exames: ", error);
        return [];
    }
}