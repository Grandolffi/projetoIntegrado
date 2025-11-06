const express = require("express");
const cors = require('cors');
const router = express.Router();
//const fetch = require("node-fetch"); // ou axios, se preferir

const app = express();

//Configurando o CORS como middleware nas requisições
app.use(cors());
app.use(express.json());

// Rota para exibir a view HTML com a lista de pacientes
router.get("/lista_pacientes", async (req, res) => {
  try {
    const response = await fetch("http://localhost:3000/pacientes");
    const pacientes = await response.json();
    res.render("lista_pacientes", { pacientes });
    //res.json(pacientes)
  } catch (error) {
    console.error("Erro ao buscar pacientes:", error);
    res.render("lista_pacientes", { pacientes: [] });
  }
});

module.exports = router;
