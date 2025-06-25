<?php
// Para ver erros PHP, se houver
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*if ($temPermissaoEditar): ?>
    <a href="editarResultados.php" class="btn btn-success">Editar Resultados</a>
<?php endif; ?>*/
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Exames</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/Style.css">
</head>
<body class="corpo-dashboard">
    <div class="container-dashboard">
        <?php include_once __DIR__ . '/menuLateral.php';?>

        <main class="conteudo-principal">
            <header class="cabecalho-principal">
                <h2>Exame > Lista de exames</h2>
                <?php include_once __DIR__ . '/info_cabecalho.php'; ?>
            </header>

            <div class="form-container">
                <div class="d-flex justify-content-between mb-3">
                    <div class="input-group" style="max-width: 300px;">
                        <input type="text" class="form-control" placeholder="Pesquisar..." aria-label="Pesquisar">
                        <button class="btn btn-outline-secondary" type="button">🔎</button>
                    </div>
                    <a href="solicitacao_form.php" class="btn btn-primary">+ Solicitar novo exame</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">ID Laudo</th> 
                                <th scope="col">Nº Registro Paciente</th>
                                <th scope="col">Nome Exame</th> 
                                <th scope="col">Valor Absoluto</th>
                                <th scope="col">Valor Referência</th>
                                <th scope="col">Data e Hora (Realização)</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Adaptação da lógica do ListarPaciente.php
                            if($_SERVER["REQUEST_METHOD"] == "GET"){
                                require_once __DIR__ . '/../controller/ExameController.php'; // Inclui o controlador de exames
                                listarExames(); // Chama a função para listar os exames
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
