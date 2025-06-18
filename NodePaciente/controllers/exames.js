const express = require("express");
const bodyParser = require("body-parser");
const app = express(); // app criado aqui

const { getTodosResultadosExames, getResultadoExameById, updateResultadoExame, deleteResultadoExame } = require("../model/DAO/exameDAO");

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// ROTAS PARA EXAMES/RESULTADOS (resultados_exames)
// GET /exames - Listar todos os resultados de exames
app.get("/exames", async (req, res) => {
    const exames = await getTodosResultadosExames();
    res.status(200).json(exames);
});

// GET /exames/:id - Buscar um resultado de exame específico por ID
app.get("/exames/:id", async (req, res) => {
    const id = parseInt(req.params.id);
    if (isNaN(id)) {
        return res.status(400).json({ success: false, message: 'ID de exame inválido.' });
    }
    const exame = await getResultadoExameById(id);
    if (!exame) {
        return res.status(404).json({ success: false, message: 'Resultado de exame não encontrado.' });
    }
    res.status(200).json(exame);
});

// PUT /exames/:id - Atualizar um resultado de exame
app.put("/exames/:id", async (req, res) => {
    const id = parseInt(req.params.id);
    if (isNaN(id)) {
        return res.status(400).json({ success: false, message: 'ID de exame inválido.' });
    }
    const { laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame } = req.body;

    if (!nome_exame || !paciente_registro || !data_hora_exame) {
        return res.status(400).json({ success: false, message: 'Dados mínimos (nome_exame, paciente_registro, data_hora_exame) são obrigatórios para atualização.' });
    }
    const updatedExame = await updateResultadoExame(id, laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame);
    if (!updatedExame) {
        return res.status(404).json({ success: false, message: 'Resultado de exame não encontrado para atualização ou dados não alterados.' });
    }
    res.status(200).json({ success: true, message: 'Resultado de exame atualizado com sucesso.', exame: updatedExame });
});

// DELETE /exames/:id - Deletar um resultado de exame
app.delete("/exames/:id", async (req, res) => {
    const id = parseInt(req.params.id);
    if (isNaN(id)) {
        return res.status(400).json({ success: false, message: 'ID de exame inválido.' });
    }
    const success = await deleteResultadoExame(id);
    if (!success) {
        return res.status(404).json({ success: false, message: 'Resultado de exame não encontrado para exclusão.' });
    }
    res.status(200).json({ success: true, message: 'Resultado de exame excluído com sucesso.' });
});


app.listen(3000, 'localhost', () => {
    console.log("Servidor rodando na porta 3000 (Exames)"); // Adaptado para diferenciar o log
});