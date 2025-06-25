<?php
// Para ver erros PHP, se houver
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/Style.css">
</head> <body class="corpo-dashboard">
    <div class="container-dashboard">
        <?php include_once __DIR__ . '/menuLateral.php';?>

        <main class="conteudo-principal">
            <header class="cabecalho-principal">
                <div class="page-title">
                    <h1>Listagem de Pacientes</h1>
                </div>
                <?php include_once __DIR__ . '/info_cabecalho.php';?>
            </header>
            <div class="form-container">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nome</th>
                                <th scope="col">CPF</th>
                                <th scope="col">Nascimento</th>
                                <th scope="col">Email</th>
                                <th scope="col">Nome da Mãe</th>
                                <th scope="col">Telefone</th>
                                <th scope="col">Gênero</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if($_SERVER["REQUEST_METHOD"] == "GET"){
                            require_once __DIR__ . '/../controller/PessoaController.php'; 
                            listar();
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../public/js/validacoes.js"></script>
</body>
</html>