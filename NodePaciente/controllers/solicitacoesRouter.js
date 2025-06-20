// solicitacoesRouter.js
const express = require("express");
const router = express.Router();
const app = express();
const { insertSolicitacao, getSolicitacoes, getSolicitacaoById, updateSolicitacaoStatus, deleteSolicitacao } = require("../model/DAO/solicitacaoDAO");

// ROTAS PARA SOLICITAÇÕES
// POST /solicitacoes - Criar nova solicitação e seus itens
app.post("/solicitacoes", async (req, res) => {
    const { pacienteId, dataSolicitacao, dataPrevistaRealizacao, solicitanteNome, status, observacoes, exames } = req.body;

    if (!pacienteId || !dataPrevistaRealizacao || !exames || exames.length === 0) {
        return res.status(400).json({ success: false, message: 'Dados mínimos (ID do Paciente, Data Prevista, e ao menos um Exame) são obrigatórios para a solicitação.' });
    }
    const result = await insertSolicitacao(pacienteId, dataSolicitacao, dataPrevistaRealizacao, solicitanteNome, status, observacoes, exames);
    if (result && result.success) {
        return res.status(201).json({ success: true, message: "Solicitação criada com sucesso.", idSolicitacao: result.idSolicitacao });
    }
    return res.status(500).json({ success: false, message: "Falha ao criar solicitação no banco de dados." });
});

// GET /solicitacoes - Listar solicitações (com filtro opcional por status)
app.get("/solicitacoes", async (req, res) => {
    const statusFilter = req.query.status;
    const solicitacoes = await getSolicitacoes(statusFilter);
    res.status(200).json(solicitacoes);
});

// GET /solicitacoes/:id - Buscar uma solicitação específica com seus itens
app.get("/solicitacoes/:id", async (req, res) => {
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

// PUT /solicitacoes/:id/status - Atualizar apenas o status de uma solicitação
app.put("/solicitacoes/:id/status", async (req, res) => {
    const id = parseInt(req.params.id);
    const { newStatus } = req.body;
    if (isNaN(id) || !newStatus) {
        return res.status(400).json({ success: false, message: 'ID de solicitação e novo status são obrigatórios.' });
    }
    const result = await updateSolicitacaoStatus(id, newStatus);
    if (result) {
        return res.status(200).json({ success: true, message: "Status da solicitação atualizado com sucesso.", solicitacao: result });
    }
    return res.status(404).json({ success: false, message: "Solicitação não encontrada ou status não alterado." });
});

// DELETE /solicitacoes/:id - Excluir uma solicitação
app.delete("/solicitacoes/:id", async (req, res) => {
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


app.listen(3000, 'localhost', () => {
    console.log("Servidor rodando na porta 3000 (Solicitações)"); // Adaptado para diferenciar o log
});

module.exports = router; // EXPORTA O OBJETO ROUTER