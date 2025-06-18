<?php
// Ajuste os caminhos conforme a estrutura do seu projeto
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Não precisamos mais dos DAOs PHP que acessam o BD diretamente aqui.
// A comunicação será via API Node.js.
// include_once '../dao/ConnectionFactory.php';
// include_once '../dao/ExameDao.php';
// include_once '../model/ResultadoExames.php';

// Array com as definições dos exames (ainda útil para referências e tipagem)
$definicoesExames = [
    'bilirrubina_total' => ['Bilirrubina Total (mg/dL)', '0,2 – 1,2 mg/dL'],
    'bilirrubina_direta' => ['Bilirrubina Direta (mg/dL)', '0,0 – 0,2 mg/dL'],
    'proteina_total' => ['Proteína Total', ''],
    'tgo' => ['TGO - Transaminase Glutamico Oxalacética (U/L)', '5 – 34 U/L'],
    'tgp' => ['TGP - Transaminase Glutamico Piruvica (U/L)', 'Masculino: 21 – 72 U/L<br>Feminino: 9 – 52 U/L'],
    'gama_gt' => ['Gama GT - Glutamiltransferase (U/L)', 'Masculino: 15 – 73 U/L<br>Feminino: 12 – 43 U/L'],
    'fosfatase_alcalina' => ['Fosfatase Alcalina (U/L)', '38 – 126 U/L'],
    'creatinina' => ['Creatinina (mg/dL)', 'Masculino: 0,70 – 1,25 mg/dL<br>Feminino: 0,57 – 1,11 mg/dL'],
    'glicose' => ['Glicose (mg/dL)', '75 – 99 mg/dL'],
    'colesterol_total' => ['Colesterol Total (mg/dL)', 'Adultos (acima de 20 anos): menor que 190 mg/dL<br>Crianças e adolescentes (menores de 20 anos): menor que 170 mg/dL'],
    'triglicerideos' => ['Triglicerídeos (mg/dL)', 'Adultos (acima de 20 anos): menor que 150 mg/dL<br>Crianças de 0 a 9 anos: menor que 75 mg/dL<br>Crianças e adolescentes de 10 a 19 anos: menor que 90 mg/dL'],
    'ureia' => ['Ureia (mg/dL)', ''],
    'acido_urico' => ['Ácido Úrico (mg/dL)', ''],
    'pcr' => ['PCR - Proteína C Reativa (mg/dL)', 'Inferior a 1,0 mg/dL'],
    'calcio' => ['Cálcio', ''],
    'ldh' => ['LDH', '']
];


// Função genérica para fazer requisições à API Node.js
function callApi($method, $endpoint, $data = null) {
    $url = "http://localhost:3000/api" . $endpoint; // Base da sua API Node.js
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    if ($method === 'POST' || $method === 'PUT') {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        throw new Exception("Erro de conexão com a API: " . $curlError);
    }

    $apiResponse = json_decode($response, true);

    if ($httpCode >= 200 && $httpCode < 300) {
        return ['success' => true, 'data' => $apiResponse];
    } else {
        $errorMessage = $apiResponse['message'] ?? 'Erro desconhecido da API.';
        throw new Exception("Erro da API (código {$httpCode}): " . $errorMessage);
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ação de UPDATE de Exame
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $id_exame = $_POST['id_exame'] ?? null;
        $laudo_id = $_POST['laudo_id'] ?? null; // Passar o laudo_id que veio
        $nome_exame = $_POST['nome_exame'] ?? null;
        $tipo_exame_key = $_POST['tipo_exame'] ?? null;
        $valor_absoluto = $_POST['valor_absoluto'] ?? null;
        $valor_referencia = $_POST['valor_referencia'] ?? null;
        $paciente_registro = $_POST['paciente_registro'] ?? null;
        $data_hora_exame = $_POST['data_hora_exame'] ?? null; // datetime-local format 'YYYY-MM-DDTHH:MM'

        if ($data_hora_exame) {
             // Formatar para 'YYYY-MM-DD HH:MM:SS' para o banco de dados
             $data_hora_exame = str_replace('T', ' ', $data_hora_exame) . ':00';
        }

        // Tentar obter valor_referencia da definição, se tipo_exame_key existe
        $definicao_referencia = $definicoesExames[$tipo_exame_key][1] ?? $valor_referencia;
        
        $exameData = [
            'laudo_id' => $laudo_id,
            'nome_exame' => $nome_exame,
            'tipo_exame' => $tipo_exame_key, // Salvar a chave do tipo, ou o nome completo
            'valor_absoluto' => $valor_absoluto,
            'valor_referencia' => $definicao_referencia, // Usar a ref da definição ou a passada
            'paciente_registro' => $paciente_registro,
            'data_hora_exame' => $data_hora_exame
        ];

        try {
            callApi('PUT', "/exames/{$id_exame}", $exameData);
            header("Location: ../views/lista_de_exames.php?status=success&message=" . urlencode("Resultado de exame atualizado com sucesso!"));
            exit();
        } catch (Exception $e) {
            header("Location: ../views/editar_exame.php?id={$id_exame}&status=error&message=" . urlencode($e->getMessage()));
            exit();
        }
    }
    // Lógica antiga de inserção de exames (do cadastroExames.php original)
    // Se você estiver usando o LaudoController para salvar os resultados,
    // esta seção pode ser removida ou adaptada para outra finalidade.
    // O LaudoController já lida com a inserção de múltiplos resultados de exames.
    else {
        // Redireciona de volta se for um POST sem ação definida (ex: do antigo cadastroExames.php)
        // Isso é mais um fallback, o fluxo ideal é via LaudoController ou a nova solicitação.
        header("Location: ../views/dashboard.php?status=error&message=" . urlencode("Ação não reconhecida ou dados inválidos."));
        exit();
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete') {
    // Ação de DELETE de Exame
    $id_exame = $_GET['id'] ?? null;

    if (!$id_exame) {
        header("Location: ../views/lista_de_exames.php?status=error&message=" . urlencode("ID do exame não fornecido para exclusão."));
        exit();
    }

    try {
        callApi('DELETE', "/exames/{$id_exame}");
        header("Location: ../views/lista_de_exames.php?status=success&message=" . urlencode("Resultado de exame excluído com sucesso!"));
        exit();
    } catch (Exception $e) {
        header("Location: ../views/lista_de_exames.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Redireciona se não for POST ou GET com ação específica
    header("Location: ../views/dashboard.php");
    exit();
}
?>