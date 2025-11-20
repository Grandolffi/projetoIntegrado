const BASE_URL = "http://localhost:3000/";
const AUTH_HEADER = {
  "Content-Type": "application/json",
  //"Authorization": `Bearer ${TOKEN}`
}

export const LoadResultadoExamesFromAPI = async () => {
    try{
      console.log("Iniciando a conexão com a API...");
      const response = await fetch(`${BASE_URL}exames`, {
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

export const CreateResultadoExamesFromAPI = async (exame) => {
    try{
        console.log(`nome exame: ${exame.nome_exame}`); 
        const res = await fetch(`${BASE_URL}exames`, {
        method: "POST",
        headers: AUTH_HEADER,
        body: JSON.stringify(exame)
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

export const UpdateResultadoExamesFromAPI = async (id, exame) => {
    try{
        console.log(`Atualizando exame ID: ${id}`);
        const res = await fetch(`${BASE_URL}exames/${id}`, {
            method: "PUT",
            headers: AUTH_HEADER,
            body: JSON.stringify(exame)
        });

        if(!res.ok) throw new Error(await res.text());

        const json = await res.json();
        console.log("Conteudo do JSON (Update): ", json);
        return json;
    }catch(error){
        console.error("Erro ao realizar requisição PUT: ", error);
        return { success: false, message: "Erro interno ao atualizar." };
    }
}

export const DeleteResultadoExamesFromAPI = async (id) => {
    try{
        const res = await fetch(`${BASE_URL}exames/${id}`, {
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