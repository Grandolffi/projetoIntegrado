// examesRouter.js
const express = require("express");
const router = express.Router(); // AGORA É UM ROUTER, NÃO O APP PRINCIPAL

console.log("--> examesRouter.js foi carregado com sucesso! <-- "); // teste

const {getResultadoExame, getResultadoExameById, insertResultadoExame, editResultadoExame, deleteResultadoExame} = require("../model/DAO/exameDAO");

// --- ROTAS DA API (Exames) ---
// crate
router.post("/exames", async (req, res) => {
    const {laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame} = req.body;
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

// update
router.put("/exames/:idexame", async (req, res) => {
    const { laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame, paciente_id_fk } = req.body;
    const id_exame = parseInt(req.params.idexame);
    const exames = await getResultadoExame();
    const exame = exames.find(e => e.id_exame === id_exame);

    if (!exame) {
        return res.status(404).send("Exame não encontrado");
    }

    const result = await editResultadoExame(id_exame, laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame, paciente_id_fk);

    if (result) {
        return res.status(200).send("Exame editado com sucesso");
    }
    res.status(400).send("Erro ao editar exame");
});


// delete
router.delete("/exames/:id", async (req, res) => {
    const id_exame = parseInt(req.params.id);
    const result = await deleteResultadoExame(id_exame);
    if(result){
        return res.status(200).json({success: true});
    }
    return res.status(404).json({success: false});
});

module.exports = router; // EXPORTA O OBJETO ROUTER

