const express = require("express");
const router = express.Router();

let fetch;
async function loadFetch() {
    if (!fetch) {
        const { default: dynamicFetch } = await import("node-fetch");
        fetch = dynamicFetch;
    }
}

router.get("/lista_exames", async (req, res) => {
    try {
        await loadFetch();
        const response = await fetch("http://localhost:3000/exames");
        const exames = await response.json();
        res.render("lista_exames", { exames });
    } catch (error) {
        console.error("Erro ao buscar exames:", error);
        res.render("lista_exames", { exames: [] });
    }
});

module.exports = router;