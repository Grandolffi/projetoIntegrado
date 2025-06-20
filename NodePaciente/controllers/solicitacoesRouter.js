// solicitacoesRouter.js
const express = require("express");
const router = express.Router();
// const app = express();
const { insertSolicitacao, getSolicitacoes, getSolicitacaoById, updateSolicitacaoStatus, deleteSolicitacao } = require("../model/DAO/solicitacaoDAO");

// ROTAS PARA SOLICITAÇÕES

//inserindo via api (create)
router.post("/", async (req, res) =>{
    const {pacienteId, dataSolicitacao, dataPrevistaRealizacao, solicitanteNome, status, observacoes, exames} = req.body;
    console.log(`ID do paciente: ${pacienteId} Data da solicitação: ${dataSolicitacao}, Data prevista realização: ${dataPrevistaRealizacao}, Nome do solicitante: ${solicitanteNome}, Status: ${status}, Observações: ${observacoes}, Exames: ${exames}`);
    const result = await insertPaciente(pacienteId, dataSolicitacao, dataPrevistaRealizacao, solicitanteNome, status, observacoes, exames);
    if(result){
        return res.status(202).json({success: true});
    }
    return res.status(404).json({success: false});
});

//read
router.get("/solicitacoes", async (req, res) => { 
   const solicitacao = await getSolicitacoes(); // função SELECT * FROM solicitacoes
   console.log("Solicitação: ", solicitacao);
   res.json(solicitacao);
   
});

// GET Buscar uma solicitação específica
router.get("/solicitacoes/:id", async (req, res) => {
    const id = parseInt(req.params.id);
    if (isNaN(id)) {
        return res.status(400).json({ success: false, message: 'ID de solicitação inválido.' });
    }
    const solicitacao = await getSolicitacaoById(id);
    if (!solicitacao) {
        return res.status(404).json({ success: false, message: 'Solicitação não encontrada.' });
    }
    res.status(200).json(solicitacao);
});

//update
router.put("/editarsolicitacao/:idsolicitacao", async (req, res) => {
  const {pacienteId, dataSolicitacao, dataPrevistaRealizacao, solicitanteNome, status, observacoes, exames} = req.body
  const id = parseInt(req.params.idsolicitacao);
  const solicitacoes = await getSolicitacoes(); //chamando a função que exibe paciente
  const  solicitacao = solicitacoes.find(a => a.id === id); // busca real por id
    if (!solicitacao){
        return res.status(404).send("Laudo não encontrado");
    }

  const result = await editSolicitacoes(pacienteId, dataSolicitacao, dataPrevistaRealizacao, solicitanteNome, status, observacoes, exames);
  console.log("abc: ", pacienteId, dataSolicitacao, dataPrevistaRealizacao, solicitanteNome, status, observacoes, exames)
    if(result){
        res.status(200).send("Laudo editado");
    }
});

//API PARA REMOVER solicitações
router.delete("/solicitacao/:id", async (req, res) => {
    const id = parseInt(req.params.id);
    const result = await deleteSolicitacao(id);
    if(result){
        return res.status(200).json({success: true});
    }

    return res.status(404).json({success: false});
});

// DELETE /solicitacoes/:id - Excluir uma solicitação
router.delete("/solicitacoes/:id", async (req, res) => {
    const id = parseInt(req.params.id);
    if (isNaN(id)) {
        return res.status(400).json({ success: false, message: 'ID de solicitação inválido.' });
    }
    const success = await deleteSolicitacao(id);
    if (!success) {
        return res.status(404).json({ success: false, message: 'Solicitação não encontrada para exclusão.' });
    }
    res.status(200).json({ success: true, message: 'Solicitação excluída com sucesso.' });
});


// app.listen(3000, 'localhost', () => {
//     console.log("Servidor rodando na porta 3000 (Solicitações)"); // Adaptado para diferenciar o log
// });

module.exports = router; // EXPORTA O OBJETO ROUTER