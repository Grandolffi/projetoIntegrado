// laudosRouter.js
const express = require("express");
const router = express.Router();
const app = express();

 

const { insertLaudo, getTodosLaudos, getLaudoById, updateLaudo, deleteLaudo } = require("../model/DAO/laudoDAO");

// ROTAS PARA LAUDOS
// POST /laudos - Criar um novo laudo e inserir seus resultados de exames
app.post("/laudos", async (req, res) => {
    const { solicitacaoId, pacienteId, responsavelTecnico, dataFinalizacao, observacoes, resultadosExames } = req.body;

    if (!solicitacaoId || !pacienteId || !responsavelTecnico || !dataFinalizacao || !resultadosExames || resultadosExames.length === 0) {
        return res.status(400).json({ success: false, message: 'Dados mínimos (solicitacaoId, pacienteId, responsavelTecnico, dataFinalizacao, resultadosExames) são obrigatórios para criar o laudo.' });
    }

    const novoLaudo = await insertLaudo(pacienteId, responsavelTecnico, observacoes, dataFinalizacao);
    if (!novoLaudo) {
        return res.status(500).json({ success: false, message: "Falha ao criar o registro principal do laudo." });
    }
    const novoLaudoId = novoLaudo.id_laudo;

    for (const resultado of resultadosExames) {
        const tipo_exame = resultado.tipoExame || "Generico";
        const valor_referencia = resultado.valorReferencia || "N/A";

        const insertedExame = await insertResultadoExame(
            novoLaudoId,
            resultado.nomeExame,
            tipo_exame,
            resultado.valorAbsoluto,
            valor_referencia,
            pacienteId,
            resultado.dataHoraExame
        );
        if (!insertedExame) {
            console.warn(`Aviso: Falha ao inserir resultado para o exame '${resultado.nomeExame}' no laudo ${novoLaudoId}.`);
        }
    }

    const solicitacaoAtualizada = await updateSolicitacaoStatus(solicitacaoId, 'Concluído');
    if (!solicitacaoAtualizada) {
        console.warn(`Aviso: Não foi possível atualizar o status da solicitação ${solicitacaoId} para 'Concluído'.`);
    }

    res.status(201).json({ success: true, message: "Laudo e resultados salvos com sucesso.", laudoId: novoLaudoId });
});

// GET /laudos - Listar todos os laudos
app.get("/laudos", async (req, res) => {
    const laudos = await getTodosLaudos();
    res.status(200).json(laudos);
});

// GET /laudos/:id - Buscar um laudo específico por ID
app.get("/laudos/:id", async (req, res) => {
    const id = parseInt(req.params.id);
    if (isNaN(id)) {
        return res.status(400).json({ success: false, message: 'ID de laudo inválido.' });
    }
    const laudo = await getLaudoById(id);
    if (!laudo) {
        return res.status(404).json({ success: false, message: 'Laudo não encontrado.' });
    }
    res.status(200).json(laudo);
});

// PUT /laudos/:id - Atualizar um laudo
app.put("/laudos/:id", async (req, res) => {
    const id = parseInt(req.params.id);
    if (isNaN(id)) {
        return res.status(400).json({ success: false, message: 'ID de laudo inválido.' });
    }
    const { paciente_id, responsavel_tecnico, observacoes, data_finalizacao } = req.body;

    if (!paciente_id || !responsavel_tecnico || !data_finalizacao) {
        return res.status(400).json({ success: false, message: 'Dados mínimos (paciente_id, responsavel_tecnico, data_finalizacao) são obrigatórios para atualização do laudo.' });
    }
    const updatedLaudo = await updateLaudo(id, paciente_id, responsavel_tecnico, observacoes, data_finalizacao);
    if (!updatedLaudo) {
        return res.status(404).json({ success: false, message: 'Laudo não encontrado para atualização ou dados não alterados.' });
    }
    res.status(200).json({ success: true, message: 'Laudo atualizado com sucesso.', laudo: updatedLaudo });
});

// DELETE /laudos/:id - Deletar um laudo
app.delete("/laudos/:id", async (req, res) => {
    const id = parseInt(req.params.id);
    if (isNaN(id)) {
        return res.status(400).json({ success: false, message: 'ID de laudo inválido.' });
    }
    const success = await deleteLaudo(id);
    if (!success) {
        return res.status(404).json({ success: false, message: 'Laudo não encontrado para exclusão.' });
    }
    res.status(200).json({ success: true, message: 'Laudo excluído com sucesso.' });
});


app.listen(3000, 'localhost', () => {
    console.log("Servidor rodando na porta 3000 (Laudos)"); // Adaptado para diferenciar o log
});

module.exports = router; // EXPORTA O OBJETO ROUTER