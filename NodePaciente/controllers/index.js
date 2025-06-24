const express = require("express");
const bodyParser = require("body-parser");
const app = express();
//const Paciente = require('../model/DAO/pacienteDAO');

app.use(bodyParser.json()); //convertendo requisição em json
app.use(bodyParser.urlencoded({extended: true}));

//  ROTAS DE EXAMES
const pacientesRouter = require('./pacientesRouter.js'); 
const examesRouter = require('./examesRouter.js'); 
const solicitacoesRouter = require('./solicitacoesRouter.js'); 
const laudosRouter = require('./laudosRouter.js'); 


// Registra as rotas com prefixos para evitar conflitos
app.use('/pacientes', pacientesRouter);
app.use('/exames', examesRouter);// Agora as rotas em examesRouter começarão com /exames
app.use('/solicitacoes', solicitacoesRouter);// E as rotas em solicitacoesRouter começarão com /solicitacoes
app.use(laudosRouter); // E as rotas em laudosRouter começarão com /laudos

app.listen(3000, 'localhost', () => {
    console.log("Servidor rodando na porta 3000");
})