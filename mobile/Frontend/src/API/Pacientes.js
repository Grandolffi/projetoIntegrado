export const BASE_URL = "http://localhost:3000/";
const AUTH_HEADER = {
  "Content-Type": "application/json",
  //"Authorization": `Bearer ${TOKEN}`
}

export const LoadPacientesFromAPI = async () => {
    try{
      console.log("Iniciando a conexão com a API...");
      const response = await fetch(`${BASE_URL}pacientes`, {
        method: "GET",
        headers: AUTH_HEADER 
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

export const CreatePacientesFromAPI = async (paciente) => {
    try{
        console.log(`nome: ${paciente.nome}`);
        const res = await fetch(`${BASE_URL}pacientes`, {
        method: "POST",
        headers: AUTH_HEADER,
        body: JSON.stringify(paciente)
        });

        if(!res.ok) throw new Error(await res.text());

        const json = await res.json();
        console.log("Conteudo do JSON: ", json);
        return json;
    }catch(error){
        console.error("Erro ao realizar requisição POST: ", error);
        return false;
    }
}
  