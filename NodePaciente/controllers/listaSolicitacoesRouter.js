const express = require("express");
const router = express.Router();

let fetch;
async function loadFetch() {
    if (!fetch) {
        const { default: dynamicFetch } = await import("node-fetch");
        fetch = dynamicFetch;
    }
}

router.get("/lista_solicitacoes", async (req, res) => {
    try {
        await loadFetch();
        const response = await fetch("http://localhost:3000/solicitacoes");
        const solicitacoes = await response.json();
        res.render("lista_solicitacoes", { solicitacoes });
    } catch (error) {
        console.error("Erro ao buscar solicitações:", error);
        res.render("lista_solicitacoes", { solicitacoes: [] });
    }
});

module.exports = router;