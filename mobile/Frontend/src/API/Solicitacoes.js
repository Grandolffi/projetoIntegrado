// NO ARQUIVO API/Solicitacoes.js

const BASE_URL = "http://localhost:3000/"; 
const VALIDO_TOKEN = "Bearer ABC-123-BIO"; 

const AUTH_HEADERS = {
  "Content-Type": "application/json",
  "Authorization": VALIDO_TOKEN 
}

/**
 * Rota POST /solicitacoes
 * Utilizada pela tela NovoExame.js para criar uma nova solicitação.
 * * @param {object} solicitacao Objeto contendo pacienteId, dataHora, e array de exames.
 */
export const CreateSolicitacaoFromAPI = async (solicitacao) => {
    try {
        console.log("Enviando nova solicitação de exame...");
        const response = await fetch(`${BASE_URL}solicitacoes`, {
            method: "POST",
            headers: AUTH_HEADERS, // Envia o Token de Segurança
            body: JSON.stringify(solicitacao)
        });

        if (!response.ok) {
             const errorText = await response.text();
             throw new Error(`Erro ao solicitar exame: ${errorText}`);
        }
        
        const json = await response.json();
        return json; 

    } catch(error) {
        console.error("Erro ao realizar requisição POST de Solicitação: ", error);
        return { success: false, message: error.message };
    }
}