const BASE_URL = "http://localhost:3000/"; 
const AUTH_HEADERS = {
  "Content-Type": "application/json",
  // "Authorization": `Bearer ${TOKEN}` // Descomente se tiver autenticação
};

// --- CRIA UMA NOVA SOLICITAÇÃO (Usado na tela Novo Exame) ---
export const CreateSolicitacaoFromAPI = async (solicitacao) => {
  try {
    console.log("Enviando solicitação para API:", JSON.stringify(solicitacao));

    const res = await fetch(`${BASE_URL}solicitacoes`, {
      method: "POST",
      headers: AUTH_HEADERS,
      body: JSON.stringify(solicitacao)
    });

    if (!res.ok) {
        const text = await res.text();
        throw new Error(text || "Erro ao salvar solicitação");
    }

    const json = await res.json();
    console.log("Resposta da API (Create):", json);

    return json; // Espera-se { success: true, idSolicitacao: 123, ... }

  } catch(error) {
    console.error("Erro na API CreateSolicitacao:", error);
    // Retorna objeto de erro para não quebrar o app
    return { success: false, message: error.message };
  }
};

// --- BUSCA SOLICITAÇÃO POR ID (Usado na tela de Preencher Resultados) ---
export const GetSolicitacaoById = async (id) => {
    try {
        const response = await fetch(`${BASE_URL}solicitacoes/${id}`, {
            method: "GET",
            headers: AUTH_HEADERS
        });

        if (!response.ok) {
            return null; 
        }

        const json = await response.json();
        return json;
        
    } catch (error) {
        console.error("Erro GET Solicitação:", error);
        return null;
    }
};