const express = require("express");
const router = express.Router();
const fetch = require("node-fetch"); // ou axios

// Rota para exibir a view com lista de solicitações
router.get("/lista_solicitacoes", async (req, res) => {
  try {
    const response = await fetch("http://localhost:3000/solicitacoes"); // Ajuste o endpoint se necessário
    const solicitacoes = await response.json();
    res.render("lista_solicitacoes", { solicitacoes });
  } catch (error) {
    console.error("Erro ao buscar solicitações:", error);
    res.render("lista_solicitacoes", { solicitacoes: [] });
  }
});

module.exports = router;
