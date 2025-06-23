// laudosRouter.js
const express = require("express");
const router = express.Router();

// ROTAS PARA LAUDOS
const {insertLaudo, getTodosLaudos, getLaudoById, updateLaudo, deleteLaudo} = require("../model/DAO/laudoDAO");

// CREATE - POST /laudos 
router.post("/laudos", async (req, res) => {

    const {solicitacao_id, paciente_id, responsavel_tecnico, data_finalizacao, observacoes, resultadosExames} = req.body;
    console.log(`ID solicitação: ${solicitacao_id} ID paciente: ${paciente_id}, Responsável técnico: ${responsavel_tecnico}, Data Fim: ${data_finalizacao}, Observações: ${observacoes}, Resultado exame: ${resultadosExames}`);
    const result = await insertLaudo(solicitacao_id, paciente_id, responsavel_tecnico, data_finalizacao, observacoes, resultadosExames)
    if(result){
        return res.status(202).json({success: true});
    }
    return res.status(404).json({success: false});
});

// READ 
router.get("/laudos", async (req, res) => { 
    const laudos = await getTodosLaudos(); // Função Select * from laudos da DAO
    console.log("Laudos: ", laudos);
    res.json(laudos);
});

// POST /laudos
router.post("/laudos", async (req, res) => {
    // O PHP em LaudoController.php envia: solicitacao_id, paciente_id, responsavel_tecnico, data_finalizacao, observacoes, resultadosExames
    const {solicitacao_id, paciente_id, responsavel_tecnico, data_finalizacao, observacoes, resultadosExames} = req.body; // Mude para snake_case como o PHP envia
    console.log(`Dados Laudo POST: Solicitacao ID: ${solicitacao_id}, Paciente ID: ${paciente_id}, Responsável: ${responsavel_tecnico}`);

    try {
        const result = await insertLaudo(
            solicitacao_id,       // Passando o solicitacao_id
            paciente_id,          // Passando o paciente_id
            responsavel_tecnico,
            data_finalizacao,
            observacoes,
            resultadosExames      // Array de exames
        );
        if(result.success){ // Se o DAO retornar { success: true, idLaudo: ... }
            return res.status(201).json(result); // 201 Created
        }
        return res.status(400).json({success: false, message: "Falha ao inserir laudo."});
    } catch (error) {
        console.error("Erro ao inserir laudo:", error);
        res.status(500).json({success: false, message: "Erro interno do servidor ao inserir laudo."});
    }
});

// UPDATE - ajuste os parâmetros e a chamada da função
router.put("/laudos/:id", async (req, res) => { // Rota mais RESTful: /laudos/:id
const {solicitacao_id, paciente_id, responsavel_tecnico, data_finalizacao, observacoes, resultadosExames} = req.body
const id = parseInt(req.params.idpaciente);
const laudos = await getTodosLaudos(); //chamando a função que exibe paciente
const laudo = laudos.find(a => a.id === id); // busca real por id
    
if (!laudo) {
        return res.status(404).send("paciente não encontrado");
    }

const result = await editPaciente(solicitacao_id, paciente_id, responsavel_tecnico, data_finalizacao, observacoes, resultadosExames);
console.log("abc: ", solicitacao_id, paciente_id, responsavel_tecnico, data_finalizacao, observacoes, resultadosExames)
   
if(result){
        res.status(200).send("paciente Editado");
    }
});

// DELETE - rota mais RESTful
router.delete("/laudos/:id", async (req, res) => {
    const id = parseInt(req.params.id);
    const result = await deleteLaudo(id);
    if(result){
        return res.status(200).json({success: true});
    }

    return res.status(404).json({success: false});
});

module.exports = router;