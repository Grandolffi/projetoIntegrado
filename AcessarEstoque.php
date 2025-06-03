<?php
// Adicione estas linhas NO TOPO ABSOLUTO do arquivo para ver erros PHP, se houver:
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso ao Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Style.css">
</head>
<body class="corpo-dashboard">
    <div class="container-dashboard">
        <?php include 'menuLateral.php'; // Inclui o menu lateral ?>

        <main class="conteudo-principal">
            <header class="cabecalho-principal">
                <h2>Estoque</h2>
                <?php include 'info_cabecalho.php'; // Inclui a saudação, data e hora ?>
            </header>

            <div class="p-3">
                <p>Aqui você poderá gerenciar o estoque de insumos e materiais do laboratório.</p>
            </div>

        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>