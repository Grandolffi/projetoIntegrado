const express = require("express");
const router = express.Router();
const { auth } = require('./login');

const{insertPaciente, getPacientes, editPaciente, deletePaciente} = require("../model/DAO/pacienteDAO");


//read
router.get("/pacientes", async (req, res) => { //PessoaDao acessa via json, e lista os pacientes do banco
   if(!auth){
    console.error("não temos auth");
   }
   const paciente = await getPacientes(); // função SELECT * FROM pacientes
   console.log("paciente: ", paciente);
   res.json(paciente);
   
})


//inserindo via api (create)
router.post("/pacientes", async (req, res) =>{
    const {nome, cpf, dtnasc, email, nomeMae, numCelular, genero} = req.body;   
    console.log(`nome: ${nome} cpf: ${cpf}, dtnasc: ${dtnasc}, nomeMae: ${nomeMae}, numCelular ${numCelular}, genero ${genero}`);
    const result = await insertPaciente(nome, cpf, dtnasc, email, nomeMae, numCelular, genero);
    if(result){
        return res.status(201).json({success: true});
    }
    return res.status(404).json({success: false});
})

//update
router.put("/editarpacientes/:idpaciente", async (req, res) => { //PessoaDao acessa via json, e editamos paciente
    try{
        const {nome, cpf, dtnasc, email, nomeMae, numCelular, genero} = req.body
        const id = parseInt(req.params.idpaciente);
        const pacientes = await getPacientes(); //chamando a função que exibe paciente
        const paciente = pacientes.find(a => a.id === id); // busca real por id
            if (!paciente) {
                return res.status(404).send("paciente não encontrado");
            }

        const result = await editPaciente(id, nome, cpf, dtnasc, email, nomeMae, numCelular, genero);
        console.log("abc: ", id, nome, cpf, dtnasc, email, nomeMae, numCelular, genero)
            if(result){
                return res.status(200).json({
                    success: true,
                    message: "Paciente editado com sucesso!",
                    id_paciente: id
                });
            }

            return res.status(500).json({
                success: false,
                message: "Erro ao atualizar paciente"
            });
        }catch (error) {
            return res.status(500).json({
                success: false,
                message: "Erro interno ao editar paciente",
                error: error.message
        });
  }
});


//API PARA REMOVER paciente
router.delete("/pacientes/:id", async (req, res) => {
    try{
        const id = parseInt(req.params.id);
        const result = await deletePaciente(id);
        if(result){
            return res.status(200).json({success: true, message: "Paciente excluído com sucesso!"});
        }

        return res.status(404).json({
            success: false,
            message: "Paciente não encontrado."
        });
    }catch (error) {
        console.error("Erro ao excluir paciente:", error);

        // Aqui é onde você coloca a mensagem amigável!
        return res.status(400).json({
            success: false,
            message: "Este paciente possui solicitações vinculadas e não pode ser excluído.",
            error: error.message
        });
    }
});

// Buscar fabricante por ID
router.get('/pacientes/:id', async (req, res) => {
  const id = parseInt(req.params.id);
  const pacientes = await getPacientes();
  const paciente = pacientes.find(p => p.id === id);
  if (!paciente) return res.status(404).json({ message: 'paciente não encontrado' });
  res.json(paciente);
});

module.exports = router; 