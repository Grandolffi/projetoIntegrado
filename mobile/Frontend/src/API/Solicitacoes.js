const BASE_URL = "http://localhost:3000/"; 
const VALIDO_TOKEN = "Bearer ABC-123-BIO"; 

const AUTH_HEADERS = {
  "Content-Type": "application/json",
  "Authorization": VALIDO_TOKEN 
}

export const LoadPacientesFromAPI = async () => {
    try{
      console.log("Iniciando a conexão com a API...");
      const response = await fetch(`${BASE_URL}`, {
        method: "GET",
        headers: AUTH_HEADER,
      });
      console.log("Conteudo de Response: ", response);
      
      const json = await response.json();
      console.log("Conteudo do JSON: ", json);
      return json;
    }catch(error){
      console.error("Erro ao realizar requisiçaão GET: ", error);
      return null;
    }
  }

export const CreateSolicitacoesFromAPI = async (solicitacao) => {
    try {
       console.log(`nome: ${solicitacao.nome}`);
        const res = await fetch(`${BASE_URL}solicitacoes`, {
        method: "POST",
        headers: AUTH_HEADER,
        body: JSON.stringify(solicitacao)
        });

        if(!res.ok) throw new Error(await res.text());
        
        const json = await response.json();
        console.log("Conteúdo do JSON: ", json);
        return json; 

    } catch(error) {
        console.error("Erro ao realizar requisição POST de Solicitação: ", error);
        return { success: false, message: error.message };
    }
}