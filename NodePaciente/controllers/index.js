const express = require("express");
const bodyParser = require("body-parser");
const fs = require("fs");
const app = express();
const path = require("path");
const cors = require("cors");

const graficosPath = path.join(
  "C:\\xampp\\htdocs\\projetoIntegrado\\DataSciencie\\outputs\\graficos_pacientes"
);


// Middleware
app.use(cors());
app.use(express.json());

app.set("view engine", "ejs");
app.set("views", path.join(__dirname, "..", "views"));


app.use(bodyParser.json()); //convertendo requisição em json
app.use(bodyParser.urlencoded({extended: true}));

//  ROTAS DE EXAMES
const pacientesRouter = require('./pacientesRouter.js'); 
const examesRouter = require('./examesRouter.js'); 
const solicitacoesRouter = require('./solicitacoesRouter.js'); 
const laudosRouter = require('./laudosRouter.js'); 
const listaPacientesRouter = require("./listaPacientesRouter");
const listaExamesRouter = require("./listaExamesRouter");
const listaLaudosRouter = require("./listaLaudosRouter");
const listaSolicitacoesRouter = require("./listaSolicitacoesRouter");


// IMPORTA O LOGIN
const { router: loginRouter, auth } = require('./login');
app.use(loginRouter); // /login funciona
//app.use(auth); fALAR COM PROFESSOR VERIFICAR COMO USAR NA OUTRA PAGINA PARA ELA RECEBER TOKEN/ OLHAR CODIGO QUE ELE DEIXOU NO BLACKBOARD

// Registra as rotas com prefixos para evitar conflitos
app.use(pacientesRouter);
app.use(examesRouter); // Agora as rotas em examesRouter começarão com /exames
app.use('/solicitacoes', solicitacoesRouter);// E as rotas em solicitacoesRouter começarão com /solicitacoes
app.use(laudosRouter); // E as rotas em laudosRouter começarão com /laudos
app.use(listaPacientesRouter);
app.use(listaExamesRouter);
app.use(listaLaudosRouter);
app.use(listaSolicitacoesRouter);


// Deixa a pasta pública ==================
app.use(
  "/graficos_pacientes",
  express.static("C:/xampp/htdocs/projetoIntegrado/DataSciencie/outputs/graficos_pacientes")
);

app.get("/lista-graficos", (req, res) => {
  fs.readdir(graficosPath, (err, files) => {
    if (err) return res.status(500).json({ error: "Erro ao listar arquivos" });

    const imagens = files.filter(f => f.endsWith(".png"));

    res.json(imagens);
  });
});
// parte com ajuda da IA tentando visualizar a img py no front ==========

app.listen(3000, "0.0.0.0", () => {
    console.log("Servidor rodando na porta 3000");
})

//Rota de saúde da aplicação (health check)
app.get("/", async (req, res) => {
    res.status(200).json({ok: true});
});