// solicitacoesRouter.js
const express = require("express");
const router = express.Router();

const {insertSolicitacao, getSolicitacoes, getSolicitacaoById, updateSolicitacao, deleteSolicitacao, updateSolicitacaoStatus} = require("../model/DAO/solicitacaoDAO"); // Garante que updateSolicitacaoStatus está importado

// --- ROTAS PARA SOLICITAÇÕES (Montadas em /solicitacoes no app.js/server.js) ---

// CREATE (inserir nova solicitação) - Caminho será /solicitacoes/
router.post("/", async (req, res) => { // Alterei aqui: Removido '/solicitacoes' do caminho da rota
    const { pacienteId, dataSolicitacao, dataPrevistaRealizacao, solicitanteNome, status, observacoes, exames } = req.body;
    console.log(
      `ID do paciente: ${pacienteId}, Data da solicitação: ${dataSolicitacao}, Data prevista realização: ${dataPrevistaRealizacao}, Nome do solicitante: ${solicitanteNome}, Status: ${status}, Observações: ${observacoes}, Exames: ${JSON.stringify(
        exames
      )}`
    );

    try {
        const result = await insertSolicitacao(pacienteId, dataSolicitacao, dataPrevistaRealizacao, solicitanteNome, status, observacoes, exames);
        if (result && result.success) {
            return res.status(201).json(result);
        }
        return res.status(400).json({ success: false, message: "Falha ao inserir solicitação." });
    } catch (error) {
        console.error("Erro ao inserir solicitação:", error);
        return res.status(500).json({ success: false, message: "Erro interno do servidor ao inserir solicitação." });
    }
});

// READ ALL (listar todas as solicitações) - Caminho será /solicitacoes/
router.get("/", async (req, res) => {
    try {
        const solicitacoes = await getSolicitacoes(req.query.status);
        console.log("Solicitação: ", solicitacoes);
        res.status(200).json(solicitacoes);
    } catch (error) {
        console.error("Erro ao buscar solicitações:", error);
        return res.status(500).json({ success: false, message: "Erro interno do servidor ao buscar solicitações." });
    }
});

// READ ONE (buscar uma solicitação específica por ID) - Caminho será /solicitacoes/:id
router.get("/:id", async (req, res) => { // Alterei aqui: Removido '/solicitacoes' do caminho da rota
    const id = parseInt(req.params.id);
    if (isNaN(id)) {
        return res.status(400).json({ success: false, message: 'ID de solicitação inválido.' });
    }
    try {
        const solicitacao = await getSolicitacaoById(id);
        if (!solicitacao) {
            return res.status(404).json({ success: false, message: 'Solicitação não encontrada.' });
        }
        res.status(200).json(solicitacao);
    } catch (error) {
        console.error(`Erro ao buscar solicitação com ID ${id}:`, error);
        return res.status(500).json({ success: false, message: "Erro interno do servidor ao buscar solicitação." });
    }
});

// ********** CORREÇÃO CRÍTICA AQUI: ORDEM E ESPECIFICIDADE DE ROTAS PUT **********

// Rota PUT para atualizar APENAS o status de uma solicitação - Caminho será /solicitacoes/:id/status
// Esta rota DEVE vir ANTES da rota PUT genérica /:idsolicitacao
router.put('/:id/status', async (req, res) => { // Alterei aqui: Removido '/solicitacoes' do caminho da rota
    const { id } = req.params;
    const { status } = req.body;

    if (!status) {
        return res.status(400).json({ error: "Status é obrigatório para atualização." });
    }

    try {
        const updatedSolicitacao = await updateSolicitacaoStatus(id, status);
        if (updatedSolicitacao) {
            res.status(200).json({ success: true, message: "Status da solicitação atualizado com sucesso.", solicitacao: updatedSolicitacao });
        } else {
            res.status(404).json({ error: "Solicitação não encontrada para atualização de status." });
        }
    } catch (error) {
        console.error("Erro ao atualizar status da solicitação:", error);
        res.status(500).json({ error: "Erro interno do servidor ao atualizar status da solicitação." });
    }
});

// Rota PUT para atualização COMPLETA de solicitação (genérica) - Caminho será /solicitacoes/:idsolicitacao
// Esta é a ÚNICA versão desta rota PUT que deve existir neste arquivo.
router.put("/:idsolicitacao", async (req, res) => { // Alterei aqui: Removido '/solicitacoes' do caminho da rota
    const { pacienteId, dataSolicitacao, dataPrevistaRealizacao, solicitanteNome, status, observacoes, exames } = req.body;
    const id = parseInt(req.params.idsolicitacao);
    console.log("ID recebido no PUT (Atualização Completa):", id);

    try {
        const solicitacao = await getSolicitacaoById(id);
        if (!solicitacao) {
            return res.status(404).json({ success: false, message: "Solicitação não encontrada para atualização." });
        }

        const result = await updateSolicitacao(id, pacienteId, dataSolicitacao, dataPrevistaRealizacao, solicitanteNome, status, observacoes, exames);
        if (result) {
            return res.status(200).json({ success: true, message: "Solicitação atualizada com sucesso.", solicitacao: result });
        } else {
            return res.status(500).json({ success: false, message: "Erro ao atualizar solicitação." });
        }
    } catch (error) {
        console.error("Erro na rota PUT (atualização completa):", error);
        return res.status(500).json({ success: false, message: "Erro interno do servidor ao atualizar solicitação." });
    }
});

// Rota DELETE genérica (para excluir por ID) - Caminho será /solicitacoes/:id
// Esta é a ÚNICA versão desta rota DELETE que deve existir neste arquivo.
router.delete("/:id", async (req, res) => { // Alterei aqui: Removido '/solicitacoes' do caminho da rota
    const id = parseInt(req.params.id);
    if (isNaN(id)) {
        return res.status(400).json({ success: false, message: 'ID de solicitação inválido.' });
    }
    try {
        const result = await deleteSolicitacao(id);
        if (result) {
            return res.status(200).json({ success: true, message: "Solicitação excluída com sucesso." });
        }
        return res.status(404).json({ success: false, message: 'Solicitação não encontrada para exclusão.' });
    } catch (error) {
        console.error("Erro ao excluir solicitação:", error);
        return res.status(500).json({ success: false, message: "Erro interno do servidor ao excluir solicitação." });
    }
});

module.exports = router;