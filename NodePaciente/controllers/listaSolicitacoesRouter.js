const express = require("express");
const cors = require('cors');
const router = express.Router();

const app = express();

//Configurando o CORS como middleware nas requisições
app.use(cors());
app.use(express.json());

// Rota para exibir a view HTML com a lista de pacientes
router.get("/lista_solicitacoes", async (req, res) => {
  try {
    const res = await fetch("http://localhost:3000/solicitacoes");
    const solicitacoes = await res.json();
    res.render("lista_solicitacoes", { solicitacoes });
    //res.json(solicitacoes)
  } catch (error) {
    console.error("Erro ao buscar solicitações:", error);
    res.render("lista_solicitacoes", { solicitacoes: [] });
  }
});

module.exports = router;
