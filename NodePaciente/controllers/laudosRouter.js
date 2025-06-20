// laudosRouter.js
const express = require("express");
const router = express.Router();
// const app = express();

const {insertLaudo, getTodosLaudos, getLaudoById, updateLaudo, deleteLaudo} = require("../model/DAO/laudoDAO");

// ROTAS PARA LAUDOS

// inserindo via api (create)
router.post("/laudos", async (req, res) =>{
    const {solicitacaoId, pacienteId, responsavelTecnico, dataFinalizacao, observacoes, resultadosExames} = req.body;
    console.log(`ID da solicitação: ${solicitacaoId} ID do paciente: ${pacienteId}, Responsável técnico: ${responsavelTecnico}, Data fim: ${dataFinalizacao}, Observações: ${observacoes}, Resultado exame: ${resultadosExames}`);
    const result = await insertLaudo(solicitacaoId, pacienteId, responsavelTecnico, dataFinalizacao, observacoes, resultadosExames);
    if(result){
        return res.status(202).json({success: true});
    }
    return res.status(404).json({success: false});
});

// read
router.get("/", async (req, res) => { //PessoaDao acessa via json, e lista os pacientes do banco
   //res.status(200).json(Paciente);
   const laudo = await getLaudos(); // função SELECT * FROM pacientes
   console.log("Laudo: ", laudo);
   res.json(laudo);
   
});

// GET /laudos/:id - Buscar um laudo específico por ID
router.get("/laudos/:id", async (req, res) => {
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

// update
router.put("/editarlaudo/:idlaudo", async (req, res) => { 
  const {solicitacaoId, pacienteId, responsavelTecnico, dataFinalizacao, observacoes, resultadosExames} = req.body
  const id = parseInt(req.params.idlaudo);
  const laudos = await getLaudos(); //chamando a função que exibe o laudo
  const laudo = laudos.find(a => a.id === id); // busca real por id
    if (!laudo) {
        return res.status(404).send("Laudo não encontrado");
    }

  const result = await editPaciente(solicitacaoId, pacienteId, responsavelTecnico, dataFinalizacao, observacoes, resultadosExames);
  console.log("abc: ", solicitacaoId, pacienteId, responsavelTecnico, dataFinalizacao, observacoes, resultadosExames)
    if(result){
        res.status(200).send("Laudo editado");
    }
});


//API PARA REMOVER laudo
router.delete("/laudo/:id", async (req, res) => {
    const id = parseInt(req.params.id);
    const result = await deleteLaudo(id);
    if(result){
        return res.status(200).json({success: true});
    }

    return res.status(404).json({success: false});
});


// app.listen(3000, 'localhost', () => {
//     console.log("Servidor rodando na porta 3000 (Laudos)"); // Adaptado para diferenciar o log
// });

module.exports = router; // EXPORTA O OBJETO ROUTER