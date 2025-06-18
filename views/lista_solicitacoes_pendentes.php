<?php
// Para ver erros PHP, se houver
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$solicitacoes = [];
$errorMessage = null;

// URL do endpoint da sua API Node.js para listar solicitações pendentes
$api_url_pendentes = "http://localhost:3000/api/solicitacoes?status=Pendente"; // Ajuste o endpoint conforme sua API

$response = @file_get_contents($api_url_pendentes);
$solicitacoes_data = json_decode($response, true);

if ($response === false) {
    $errorMessage = "Erro ao conectar com a API para listar solicitações.";
} elseif (empty($solicitacoes_data)) {
    $errorMessage = "Nenhuma solicitação pendente encontrada.";
} elseif (isset($solicitacoes_data['message']) && !isset($solicitacoes_data[0])) { // API retornou erro como objeto com 'message'
    $errorMessage = "Erro da API: " . $solicitacoes_data['message'];
} else {
    $solicitacoes = $solicitacoes_data;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitações de Exames Pendentes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/Style.css">
</head>
<body class="corpo-dashboard">
    <div class="container-dashboard">
        <?php include_once __DIR__ . '/menuLateral.php';?>

        <main class="conteudo-principal">
            <header class="cabecalho-principal">
                <h2>Solicitações de Exames Pendentes</h2>
                <?php include_once __DIR__ . '/info_cabecalho.php'; ?>
            </header>
            <div class="form-container">
                <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo htmlspecialchars($_GET['message']); ?>
                    </div>
                <?php endif; ?>
                <?php if ($errorMessage): ?>
                    <div class="alert alert-warning" role="alert">
                        <?php echo htmlspecialchars($errorMessage); ?>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">ID Solicitação</th>
                                    <th scope="col">ID Paciente</th>
                                    <th scope="col">Data Solicitação</th>
                                    <th scope="col">Previsto para</th>
                                    <th scope="col">Solicitante</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($solicitacoes as $solicitacao): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($solicitacao['idSolicitacao'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($solicitacao['pacienteId'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($solicitacao['dataSolicitacao'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($solicitacao['dataPrevistaRealizacao'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($solicitacao['solicitanteNome'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($solicitacao['status'] ?? ''); ?></td>
                                    <td>
                                        <a href="cadastroExames.php?solicitacao_id=<?php echo htmlspecialchars($solicitacao['idSolicitacao'] ?? ''); ?>" class="btn btn-primary btn-sm">Preencher Resultados</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../public/js/validacoes.js"></script>
</body>
</html>