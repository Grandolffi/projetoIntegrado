// laudosRouter.js
const express = require("express");
const router = express.Router();

// ROTAS PARA LAUDOS
const {insertLaudo, getTodosLaudos, getLaudoById, updateLaudo, deleteLaudo} = require("../model/DAO/laudoDAO");

// POST /laudos
//create 
router.post("/laudos", async (req, res) => {
     const {solicitacao_id, paciente_id, responsavel_tecnico, data_finalizacao, observacoes, resultadosExames} = req.body;

    try {
         const result = await insertLaudo(solicitacao_id, paciente_id, responsavel_tecnico, data_finalizacao, observacoes, resultadosExames);
    } catch (error) {
        console.error("Erro ao inserir laudo de exame:", error.message);
        res.status(500).json({ success: false, message: `Erro interno ao inserir laudo de exame: ${error.message}` });
    }
});

// READ (Listar todos os laudos) - Mude a rota para /laudos
router.get("/laudos", async (req, res) => { // Alterado de "/" para "/laudos"
   const laudos = await getTodosLaudos(); // Função renomeada no DAO
   console.log("Laudos: ", laudos);
   res.json(laudos);
});

// POST /laudos
router.post("/laudos", async (req, res) => {
    // Nomes das variáveis aqui devem ser os mesmos que o PHP envia.
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

// ...
// UPDATE - ajuste os parâmetros e a chamada da função
router.put("/laudos/:id", async (req, res) => { // Rota mais RESTful: /laudos/:id
  const {solicitacao_id, paciente_id, responsavel_tecnico, data_finalizacao, observacoes} = req.body; // Adapte nomes
  const id = parseInt(req.params.id); // id do laudo

  try {
      const result = await updateLaudo(id, solicitacao_id, paciente_id, responsavel_tecnico, data_finalizacao, observacoes); // Chame updateLaudo
      if(result){
          res.status(200).json({ success: true, message: "Laudo editado com sucesso.", laudo: result });
      } else {
          res.status(404).json({ success: false, message: "Laudo não encontrado ou falha na edição." });
      }
  } catch (error) {
      console.error(`Erro ao editar laudo com ID ${id}:`, error);
      res.status(500).json({ success: false, message: `Erro interno ao editar laudo: ${error.message}` });
  }
});

// ...
// DELETE - rota mais RESTful
router.delete("/laudos/:id", async (req, res) => { // Rota mais RESTful: /laudos/:id
    const id = parseInt(req.params.id);
    if (isNaN(id)) { /* ... */ }
    try {
        const result = await deleteLaudo(id);
        if(result){
            return res.status(200).json({success: true, message: "Laudo removido com sucesso."});
        } else {
            return res.status(404).json({success: false, message: "Laudo não encontrado para exclusão."});
        }
    } catch (error) {
        console.error(`Erro ao deletar laudo com ID ${id}:`, error);
        res.status(500).json({success: false, message: `Erro interno ao deletar laudo: ${error.message}`});
    }
});

module.exports = router;