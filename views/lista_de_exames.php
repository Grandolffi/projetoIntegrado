<?php
// Para ver erros PHP, se houver
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- Lógica para buscar os exames/laudos da API Node.js ---
$exames_listados = [];
$api_url_exames = "http://localhost:3000/api/exames"; // Endpoint da API Node.js para listar TODOS os resultados de exames

$response = @file_get_contents($api_url_exames); //para suprimir warnings em caso de falha de conexão
$api_data = json_decode($response, true);

if ($response === false) {
    // Erro de conexão com a API
    echo "<div class='alert alert-danger'>Erro ao conectar com a API Node.js para listar exames. Verifique se a API está rodando e acessível em {$api_url_exames}.</div>";
} elseif (json_last_error() !== JSON_ERROR_NONE) {
    // Erro ao decodificar JSON (resposta inválida da API)
    echo "<div class='alert alert-danger'>Erro ao decodificar a resposta da API (JSON inválido). Resposta: " . htmlspecialchars($response) . "</div>";
} elseif (isset($api_data['message']) && !isset($api_data[0])) {
    // API retornou erro como um objeto com 'message' (não é um array de exames)
    echo "<div class='alert alert-warning'>Erro da API ao listar exames: " . htmlspecialchars($api_data['message']) . "</div>";
} else {
    $exames_listados = $api_data; // A API deve retornar um array de objetos de exame
}
// --- Fim da lógica para buscar os exames ---

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
                    <a href="NewExamePaciente.php" class="btn btn-primary">+ Solicitar novo exame</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">ID Laudo</th> <th scope="col">Nº Registro Paciente</th>
                                <th scope="col">Nome Exame</th> <th scope="col">Valor Absoluto</th>
                                <th scope="col">col">Valor Referência</th>
                                <th scope="col">Data e Hora (Realização)</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($exames_listados)): ?>
                                <?php foreach ($exames_listados as $exame):
                                    // Certifique-se de que 'data_hora_exame' existe e é válido
                                    $dataHoraExame = isset($exame['data_hora_exame']) ? new DateTime($exame['data_hora_exame']) : null;
                                    $dataExameFormatada = $dataHoraExame ? $dataHoraExame->format('d/m/Y') : 'N/A';
                                    $horaExameFormatada = $dataHoraExame ? $dataHoraExame->format('H:i') : 'N/A';
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($exame['laudo_id'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($exame['paciente_registro'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($exame['nome_exame'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($exame['valor_absoluto'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($exame['valor_referencia'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($dataExameFormatada . ' ' . $horaExameFormatada); ?></td>
                                    <td>
                                        <a href="editar_exame.php?id=<?php echo htmlspecialchars($exame['id_exame'] ?? ''); ?>" class="btn btn-warning btn-sm me-1">Editar</a>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmarExclusao(<?php echo htmlspecialchars($exame['id_exame'] ?? ''); ?>)">Excluir</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7">Nenhum exame encontrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../public/js/validacoes.js"></script>
    <script>
        // Função JavaScript para confirmar exclusão
        function confirmarExclusao(idExame) {
            if (confirm('Tem certeza que deseja excluir este resultado de exame? Esta ação é irreversível.')) {
                // Se confirmar, redireciona para o controlador PHP que irá chamar a API Node.js para exclusão
                window.location.href = '../controller/ExameController.php?action=delete&id=' + idExame;
            }
        }
    </script>
</body>
</html>