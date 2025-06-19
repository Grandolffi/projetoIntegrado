<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Inclua os modelos necessários (não mais DAOs diretos ao banco)
include_once __DIR__ . '/../model/ResultadoExames.php'; // Pode ser necessário para tipagem ou objetos
include_once __DIR__ . '/../model/Laudo.php'; // Pode ser necessário para tipagem ou objetos

// NOTA: Os DAOs PHP não se conectarão mais ao BD diretamente.
// Eles serão substituídos por chamadas HTTP para a API Node.js.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $solicitacao_id = $_POST['solicitacao_id'] ?? null;
    $resultados_preenchidos = $_POST['resultados'] ?? []; // Array. Ex: ['Glicose' => '99', 'Ureia' => '40']
    $paciente_registro = $_POST['numero_registro'] ?? null;
    $data_laudo_prevista = $_POST['data_laudo'] ?? null;

    // Supondo que você pegue o nome do técnico da sessão de login
    $responsavel_tecnico = "Fernanda (Técnica)"; // Substitua pela lógica real

    if (empty($solicitacao_id) || empty($resultados_preenchidos) || empty($paciente_registro)) {
        header("Location: ../views/cadastroExames.php?solicitacao_id={$solicitacao_id}&error=" . urlencode("Dados incompletos para salvar o laudo."));
        exit();
    }

    // Prepare os dados para enviar à API Node.js
    $laudoData = [
        'solicitacaoId' => (int)$solicitacao_id,
        'pacienteId' => (int)$paciente_registro, // Garante que seja INT
        'responsavelTecnico' => $responsavel_tecnico,
        'dataFinalizacao' => date('Y-m-d H:i:s'), // Data/hora da finalização do laudo
        'observacoes' => "Laudo finalizado para solicitação {$solicitacao_id}.", // Adicione observações do formulário se houver
        'resultadosExames' => []
    ];

    foreach ($resultados_preenchidos as $nomeDoExame => $valorDoResultado) {
        if (!empty($valorDoResultado)) {
            $laudoData['resultadosExames'][] = [
                'nomeExame' => $nomeDoExame,
                'valorAbsoluto' => $valorDoResultado,
                // Outros campos como tipo_exame, valor_referencia devem ser preenchidos pela API Node.js
                // ao buscar o tipo de exame ou vir da solicitação original se for salvo lá
                'dataHoraExame' => $data_laudo_prevista . " 00:00:00" // Use a data prevista do laudo, ou a data atual de preenchimento
            ];
        }
    }

    $jsonData = json_encode($laudoData);

    // URL do endpoint da sua API Node.js para criar/finalizar o laudo
    // Exemplo: POST /api/laudos
    $api_url = "http://localhost:3000/api/laudos"; // Ajuste para a URL real da sua API

    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); // Ou PUT se for um update de laudo existente
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData)
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        header("Location: ../views/cadastroExames.php?solicitacao_id={$solicitacao_id}&error=" . urlencode("Erro ao conectar com a API: " . $curlError));
        exit();
    }

    $apiResponse = json_decode($response, true);

    if ($httpCode >= 200 && $httpCode < 300) {
        // Sucesso: Redireciona para a lista de laudos (ou alguma página de confirmação)
        header("Location: ../views/lista_de_exames.php?status=success&message=" . urlencode("Laudo e exames salvos com sucesso!"));
        exit();
    } else {
        // Erro retornado pela API
        $errorMessage = $apiResponse['message'] ?? "Erro desconhecido da API. Código HTTP: {$httpCode}";
        header("Location: ../views/cadastroExames.php?solicitacao_id={$solicitacao_id}&error=" . urlencode("Erro ao salvar laudo: " . $errorMessage));
        exit();
    }
} else {
    // Se não for POST
    header("Location: ../views/dashboard.php"); // Ou para uma lista de solicitações
    exit();
}
?>