const express = require("express");
const bodyParser = require("body-parser");
const app = express();
const Paciente = require('../model/DAO/pacienteDAO');

app.use(bodyParser.json()); //convertendo requisição em json
app.use(bodyParser.urlencoded({extended: true}));

const{insertPaciente, getPacientes} = require("../model/DAO/pacienteDAO");


app.get("/", async (req, res) => {
   //res.status(200).json(Paciente);
   const alunos = await getPacientes();
   console.log("Alunos: ", alunos);
    //res.status(200).render("listaalunos", {alunosDoController: alunos});
})

app.get("/alunos", async (req, res) => {
    const alunos = await getAlunos();
    console.log("Alunos: ", alunos);
    res.status(200).render("listaalunos", {alunosDoController: alunos});
})

//inserindo via api
app.post("/", async (req, res) =>{
    const {nome, cpf, dtnasc, email, nomeMae, numCelular, genero} = req.body;
    console.log(`nome: ${nome} cpf: ${cpf}, dtnasc: ${dtnasc}, nomeMae: ${nomeMae}, numCelular ${numCelular}, genero ${genero}`);
    const result = await insertPaciente(nome, cpf, dtnasc, email, nomeMae, numCelular, genero);
    if(result){
        return res.status(202).json({sucess: true});
    }
    return res.status(404).json({sucess: false});
})





app.listen(3000, 'localhost', () => {
    console.log("Servidor rodando na porta 3000");
})