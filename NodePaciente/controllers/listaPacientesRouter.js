const express = require("express");
const router = express.Router();
const fetch = require("node-fetch"); // ou axios, se preferir

// Rota para exibir a view HTML com a lista de pacientes
router.get("/lista_pacientes", async (req, res) => {
  try {
    const response = await fetch("http://localhost:3000/pacientes");
    const pacientes = await response.json();
    res.render("lista_pacientes", { pacientes });
  } catch (error) {
    console.error("Erro ao buscar pacientes:", error);
    res.render("lista_pacientes", { pacientes: [] });
  }
});

module.exports = router;
