const express = require("express");
const router = express.Router();
const fetch = require("node-fetch"); // ou axios

// Rota para exibir a view com lista de laudos
router.get("/lista_laudos", async (req, res) => {
  try {
    const response = await fetch("http://localhost:3000/laudos"); // Ajuste o endpoint se necessário
    const laudos = await response.json();
    res.render("lista_laudos", { laudos });
  } catch (error) {
    console.error("Erro ao buscar laudos:", error);
    res.render("lista_laudos", { laudos: [] });
  }
});

module.exports = router;
