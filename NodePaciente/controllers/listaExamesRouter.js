const express = require("express");
const cors = require('cors');
const router = express.Router();
//const fetch = require("node-fetch"); // ou axios, se preferir

const app = express();

//Configurando o CORS como middleware nas requisições
app.use(cors());
app.use(express.json());

// Rota para exibir a view HTML com a lista de exames
router.get("/lista_exames", async (req, res) => {
  try {
    const response = await fetch("http://localhost:3000/exames");
    const exames = await response.json();
    res.render("lista_exames", { exames });
    //res.json(exames)
  } catch (error) {
    console.error("Erro ao buscar exames:", error);
    res.render("lista_exames", { exames: [] });
  }
});

module.exports = router;
