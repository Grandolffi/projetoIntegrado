// examesRouter.js
const express = require("express");
const router = express.Router(); // AGORA É UM ROUTER, NÃO O APP PRINCIPAL
// const bodyParser = require("body-parser"); // Pode ser removido se body-parser for configurado apenas no arquivo principal

// Importa as funções do DAO (caminho relativo a este arquivo, se examesRouter.js e exameDAO.js estiverem na mesma pasta ou similar)
const {getResultadoExame, getResultadoExameById, insertResultadoExame, editResultadoExame, deleteResultadoExame} = require("../model/DAO/exameDAO");

// --- ROTAS DA API (Exames) ---
// Note que as chamadas são 'router.get', 'router.post', etc.

//read 
router.get("/exames", async (req, res) => {
    try {
        const exames = await getResultadoExame();
        res.status(200).json(exames);
    } catch (error) {
        console.error("Erro ao buscar todos os exames:", error);
        res.status(500).json({ success: false, message: "Erro interno do servidor ao buscar exames." });
    }
});

// read por id
router.get("/exames/:id", async (req, res) => {
    const id_exame = parseInt(req.params.id);
    if (isNaN(id_exame)) {
        return res.status(400).json({ success: false, message: 'ID de exame inválido.' });
    }
    try {
        const exame = await getResultadoExameById(id_exame);
        if (!exame) {
            return res.status(404).json({ success: false, message: 'Resultado de exame não encontrado.' });
        }
        res.status(200).json(exame);
    } catch (error) {
        console.error(`Erro ao buscar exame com ID ${id_exame}:`, error);
        res.status(500).json({ success: false, message: "Erro interno do servidor ao buscar exame." });
    }
});

// crate
router.post("/exames", async (req, res) => {
    const { laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame} = req.body;
    try {
        const novoExame = await insertResultadoExame(laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame);
        if (novoExame) {
            res.status(201).json({ success: true, message: "Exame inserido com sucesso.", exame: novoExame });
        } else {
            res.status(400).json({ success: false, message: "Dados inválidos ou erro ao inserir exame." });
        }
    } catch (error) {
        console.error("Erro ao inserir exame:", error.message);
        res.status(500).json({ success: false, message: `Erro interno ao inserir exame: ${error.message}` });
    }
});

// update
router.put("/exames/:id", async (req, res) => {
    const id_exame = parseInt(req.params.id);
    if (isNaN(id_exame)) {
        return res.status(400).json({ success: false, message: 'ID de exame inválido.' });
    }
    const { laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro} = req.body;
    try {
        const result = await editResultadoExame(id_exame, laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro);
        if (result) {
            res.status(200).json({ success: true, message: "Exame atualizado com sucesso.", exame: result });
        } else {
            res.status(404).json({ success: false, message: "Exame não encontrado ou falha na atualização." });
        }
    } catch (error) {
        console.error(`Erro ao atualizar exame com ID ${id_exame}:`, error.message);
        res.status(500).json({ success: false, message: `Erro interno ao atualizar exame: ${error.message}` });
    }
});

// delete
router.delete("/exames/:id", async (req, res) => {
    const id_exame = parseInt(req.params.id);
    if (isNaN(id_exame)) {
        return res.status(400).json({ success: false, message: 'ID de exame inválido.' });
    }
    try {
        const result = await deleteResultadoExame(id_exame);
        if (result) {
            return res.status(200).json({ success: true, message: "Exame removido com sucesso." });
        } else {
            return res.status(404).json({ success: false, message: "Exame não encontrado." });
        }
    } catch (error) {
        console.error(`Erro ao deletar exame com ID ${id_exame}:`, error.message);
        res.status(500).json({ success: false, message: `Erro interno ao deletar exame: ${error.message}` });
    }
});

module.exports = router; // EXPORTA O OBJETO ROUTER

