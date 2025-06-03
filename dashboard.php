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
    <title>BioDiagnóstico - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Style.css"> </head>
<body class="corpo-dashboard">
    <div class="container-dashboard">
        <?php include 'menuLateral.php'; // Inclui o menu lateral ?>

        <main class="conteudo-principal">
            <header class="cabecalho-principal">
                <h2>Dashboard</h2>
                <?php include 'info_cabecalho.php'; // Inclui a saudação, data e hora ?>
            </header>

            <section class="botoes-dashboard">
                <a href="CadastroPaciente.php" class="link-botao-dashboard">
                    <div class="botao-dashboard botao-cliente">
                        Cadastrar Cliente
                    </div>
                </a>

                <a href="#" class="link-botao-dashboard"> <div class="botao-dashboard botao-coleta">
                        Questionário de Coleta
                    </div>
                </a>

                <a href="cadastroExames.php" class="link-botao-dashboard"> <div class="botao-dashboard botao-resultado">
                        Questionário de resultado de exames
                    </div>
                </a>
            </section>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>