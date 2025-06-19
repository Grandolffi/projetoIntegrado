<?php
// Para ver erros PHP, se houver
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../controller/ExameController.php';
require_once __DIR__ . '/../dao/ExameDao.php';
require_once __DIR__ . '/../model/ResultadoExames.php';   

$exames_solicitados_para_preencher = [];
$paciente_registro = null;
$data_laudo_prevista = null;
$solicitacao_id = $_GET['solicitacao_id'] ?? null;

if ($solicitacao_id) {
    // URL do endpoint da API Node.js para buscar detalhes da solicitação e seus exames
    // Exemplo: GET /api/solicitacoes/:id
    $api_url_solicitacao = "http://localhost:3000/api/solicitacoes/" . urlencode($solicitacao_id);

    $response = @file_get_contents($api_url_solicitacao); // @ para suprimir warnings em caso de falha
    $solicitacao_data = json_decode($response, true);

    if ($solicitacao_data && isset($solicitacao_data['exames'])) {
        $paciente_registro = $solicitacao_data['pacienteId'] ?? 'N/A';
        $data_laudo_prevista = $solicitacao_data['dataPrevistaRealizacao'] ?? null;
        $exames_solicitados_para_preencher = $solicitacao_data['exames']; //API deve retornar os exames solicitados aqui
    } else {
        // Lidar com erro ou solicitação não encontrada
        $errorMessage = "Solicitação não encontrada ou erro ao carregar dados.";
        // Opcional: redirecionar ou exibir uma mensagem na página
    }
} else {
    $errorMessage = "ID de solicitação não fornecido.";
    // Opcional: redirecionar ou exibir uma mensagem na página
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado de Exames Bioquímicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/Style.css">
</head>
<body class="corpo-dashboard">
    <div class="container-dashboard">
        <?php include_once __DIR__ . '/menuLateral.php';?>

        <main class="conteudo-principal">
            <header class="cabecalho-principal">
                <h2>Resultado de Exame</h2>
                <?php include_once __DIR__ . '/info_cabecalho.php'; ?>
            </header>
            <div class="form-container">
                <?php if (isset($errorMessage)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($errorMessage); ?>
                    </div>
                    <a href="lista_solicitacoes_pendentes.php" class="btn btn-secondary">Voltar para Solicitações</a>
                <?php else: ?>
                    <form action="../controller/LaudoController.php" method="POST">
                        <input type="hidden" name="solicitacao_id" value="<?php echo htmlspecialchars($solicitacao_id); ?>">

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="numero_registro" class="form-label">Nº do Registro do Paciente</label>
                                <input type="text" class="form-control" name="numero_registro" id="numero_registro"
                                       value="<?php echo htmlspecialchars($paciente_registro); ?>" required readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="data_laudo" class="form-label">Data prevista para entrega do laudo</label>
                                <input type="date" class="form-control" name="data_laudo" id="data_laudo"
                                       value="<?php echo htmlspecialchars(substr($data_laudo_prevista, 0, 10) ?? ''); ?>">
                            </div>
                        </div>

                        <h4 class="mt-4 mb-3">Resultados dos Exames Solicitados</h4>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered align-middle text-center">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-start">Exame</th>
                                        <th scope="col">Valor de Referência (Solicitado)</th>
                                        <th scope="col">Resultado do Paciente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($exames_solicitados_para_preencher as $exame_solicitado): ?>
                                    <tr>
                                        <td class="text-start"><?php echo htmlspecialchars($exame_solicitado['nomeExame'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($exame_solicitado['valorReferenciaSolicitacao'] ?? 'N/A'); ?></td>
                                        <td>
                                            <input type="text" class="form-control text-center"
                                                   name="resultados[<?php echo htmlspecialchars($exame_solicitado['nomeExame'] ?? ''); ?>]"
                                                   id="exame_<?php echo htmlspecialchars(str_replace([' ', '(', ')', '-', '/', '.'], '_', strtolower($exame_solicitado['nomeExame'] ?? ''))); ?>"
                                                   placeholder="Digite o resultado"
                                                   aria-label="Resultado para <?php echo htmlspecialchars($exame_solicitado['nomeExame'] ?? ''); ?>">
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($exames_solicitados_para_preencher)): ?>
                                    <tr>
                                        <td colspan="3">Nenhum exame solicitado para esta requisição ou ID de solicitação inválido.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Salvar Laudo e Exames</button>
                    </form>
                <?php endif; ?>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../public/js/validacoes.js"></script>
</body>
</html>