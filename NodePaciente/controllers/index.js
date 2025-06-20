const express = require("express");
const bodyParser = require("body-parser");
const app = express();
const Paciente = require('../model/DAO/pacienteDAO');

app.use(bodyParser.json()); //convertendo requisição em json
app.use(bodyParser.urlencoded({extended: true}));

const{insertPaciente, getPacientes, editPaciente, deletePaciente} = require("../model/DAO/pacienteDAO");


//read
app.get("/", async (req, res) => { //PessoaDao acessa via json, e lista os pacientes do banco
   //res.status(200).json(Paciente);
   const paciente = await getPacientes(); // função SELECT * FROM pacientes
   console.log("paciente: ", paciente);
   res.json(paciente);
   
})


//inserindo via api (create)
app.post("/", async (req, res) =>{
    const {nome, cpf, dtnasc, email, nomeMae, numCelular, genero} = req.body;
    console.log(`nome: ${nome} cpf: ${cpf}, dtnasc: ${dtnasc}, nomeMae: ${nomeMae}, numCelular ${numCelular}, genero ${genero}`);
    const result = await insertPaciente(nome, cpf, dtnasc, email, nomeMae, numCelular, genero);
    if(result){
        return res.status(202).json({success: true});
    }
    return res.status(404).json({success: false});
})

//update
app.put("/editarpaciente/:idpaciente", async (req, res) => { //PessoaDao acessa via json, e editamos paciente
  const {nome, cpf, dtnasc, email, nomeMae, numCelular, genero} = req.body
  const id = parseInt(req.params.idpaciente);
  const pacientes = await getPacientes(); //chamando a função que exibe paciente
  const paciente = pacientes.find(a => a.id === id); // busca real por id
    if (!paciente) {
        return res.status(404).send("paciente não encontrado");
    }

  const result = await editPaciente(id, nome, cpf, dtnasc, email, nomeMae, numCelular, genero);
  console.log("abc: ", id, nome, cpf, dtnasc, email, nomeMae, numCelular, genero)
    if(result){
        res.status(200).send("paciente Editado");
    }
});


//API PARA REMOVER paciente
app.delete("/paciente/:id", async (req, res) => {
    const id = parseInt(req.params.id);
    const result = await deletePaciente(id);
    if(result){
        return res.status(200).json({success: true});
    }

    return res.status(404).json({success: false});
})

// Buscar fabricante por ID
app.get('/pacientes/:id', async (req, res) => {
  const id = parseInt(req.params.id);
  const pacientes = await getPacientes();
  const paciente = pacientes.find(p => p.id === id);
  if (!paciente) return res.status(404).json({ message: 'paciente não encontrado' });
  res.json(paciente);
});



//  ROTAS DE EXAMES
const examesRouter = require('./examesRouter.js'); 
const solicitacoesRouter = require('./solicitacoesRouter.js'); 
const laudosRouter = require('./laudosRouter.js'); 

// Registra as rotas de exames. Se examesRouter.js já usa '/exames' nos seus paths, use apenas `app.use()`.
app.use(examesRouter);
app.use(solicitacoesRouter);
app.use(laudosRouter);

app.listen(3000, 'localhost', () => {
    console.log("Servidor rodando na porta 3000");
})