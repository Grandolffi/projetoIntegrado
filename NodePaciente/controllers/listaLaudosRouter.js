const express = require("express");
const router = express.Router();

let fetch;
async function loadFetch() {
    if (!fetch) {
        const { default: dynamicFetch } = await import("node-fetch");
        fetch = dynamicFetch;
    }
}

router.get("/lista_laudos", async (req, res) => {
    try {
        await loadFetch(); // Adição necessária para carregar 'fetch' corretamente
        const response = await fetch("http://localhost:3000/laudos");
        const laudos = await response.json();
        res.render("lista_laudos", { laudos });
    } catch (error) {
        console.error("Erro ao buscar laudos:", error);
        res.render("lista_laudos", { laudos: [] });
    }
});

module.exports = router;