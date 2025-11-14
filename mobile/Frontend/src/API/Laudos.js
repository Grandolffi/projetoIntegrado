const BASE_URL = "http://localhost:3000/";
const AUTH_HEADER = {
  "Content-Type": "application/json",
  //"Authorization": `Bearer ${TOKEN}`
}

export const LoadLaudosFromAPI = async () => {
    try{
      console.log("Iniciando a conexão com a API...");
      const response = await fetch(`${BASE_URL}laudos`, {
        method: "GET",
        headers: AUTH_HEADER 
      });
      console.log("Conteudo de Response: ", response);
      
      const json = await response.json();
      console.log("Conteudo do JSON: ", json);
      return json;
    }catch(error){
      console.error("Erro ao realizar requisição GET: ", error);
      return null;
    }
  }