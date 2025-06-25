const express = require("express");
const router = express.Router();
const fetch = require("node-fetch");

router.get("/lista_exames", async (req, res) => {
  try {
    const response = await fetch("http://localhost:3000/exames"); // Altere o endpoint se for outro
    const exames = await response.json();
    res.render("lista_exames", { exames });
  } catch (error) {
    console.error("Erro ao buscar exames:", error);
    res.render("lista_exames", { exames: [] });
  }
});

module.exports = router;
