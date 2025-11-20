const BASE_URL = "http://localhost:3000/";
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

        if(!res.ok) throw new Error(await res.text()); // se não for ok, lance um erro

        const json = await res.json();
        console.log("Conteudo do JSON: ", json);
        return json;
    }catch(error){
        console.error("Erro ao realizar requisição POST: ", error);
        return false;
    }
}
export const DeletePacientesFromAPI = async (id) => {
    try{
        const res = await fetch(`${BASE_URL}pacientes/${id}`, {
          method: "DELETE",
          headers: AUTH_HEADER
        });

        const json = await res.json();

        if(!res.ok) return { success: false, message: json.message }; // se não for ok, lance um erro

        
        console.log("Conteudo do JSON: ", json);
        return json;
    }catch(error){
        console.error("Erro ao realizar requisição DELETE: ", error);
        return { success: false, message: "Erro interno ao excluir." };
    }
}
  