<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Inclua os DAOs necessários para interagir diretamente com o banco de dados
require_once __DIR__ . '/../dao/ConnectionFactory.php'; // Para a conexão com o banco de dados
require_once __DIR__ . '/../dao/LaudoDao.php';        // O DAO para Laudo
require_once __DIR__ . '/../dao/ExameDao.php';        // O DAO para Exame (LaudoDao o usará para inserir exames)

// Inclua os modelos necessários
require_once __DIR__ . '/../model/ResultadoExames.php';
require_once __DIR__ . '/../model/Laudo.php';

// Instanciar o DAO do Laudo
$laudoDao = new LaudoDao();

// Array de definições de exames (útil para preencher detalhes como tipo ou valor de referência)
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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lógica para Salvar NOVO Laudo e Exames (o que já estava aqui)
    if (isset($_POST['solicitacao_id']) && !isset($_POST['salvar_edicao_laudo'])) { // Identifica a ação de salvar um novo laudo
        $solicitacao_id = $_POST['solicitacao_id'] ?? null;
        $resultados_preenchidos = $_POST['resultados'] ?? []; // Array. Ex: ['Glicose' => '99', 'Ureia' => '40']
        $paciente_registro = $_POST['numero_registro'] ?? null;
        $data_laudo_prevista = $_POST['data_laudo'] ?? date('Y-m-d'); // Data do laudo, usar data atual se não fornecida

        // Supondo que você pegue o nome do técnico da sessão de login
        $responsavel_tecnico = "Fernanda (Técnica)"; // Substitua pela lógica real

        if (empty($solicitacao_id) || empty($resultados_preenchidos) || empty($paciente_registro)) {
            header("Location: ../views/cadastroExames.php?solicitacao_id={$solicitacao_id}&error=" . urlencode("Dados incompletos para salvar o laudo."));
            exit();
        }

        $laudo = new Laudo();
        $laudo->setSolicitacaoId((int)$solicitacao_id);
        $laudo->setPacienteId((int)$paciente_registro);
        $laudo->setResponsavelTecnico($responsavel_tecnico);
        $laudo->setDataFinalizacao(date('Y-m-d H:i:s')); // Data/hora da finalização do laudo
        $laudo->setObservacoes("Laudo finalizado para solicitação {$solicitacao_id}."); // Adicione observações do formulário se houver

        $examesParaLaudo = [];
        foreach ($resultados_preenchidos as $nomeDoExame => $valorDoResultado) {
            if (!empty($valorDoResultado)) {
                // Tente obter o tipo_exame e valor_referencia das definições, se aplicável
                $tipo_exame = array_key_exists($nomeDoExame, $definicoesExames) ? $definicoesExames[$nomeDoExame][0] : $nomeDoExame; // Ou alguma lógica para obter o tipo
                $valor_referencia = array_key_exists($nomeDoExame, $definicoesExames) ? $definicoesExames[$nomeDoExame][1] : '';

                $examesParaLaudo[] = [
                    'nomeExame' => $nomeDoExame,
                    'valorAbsoluto' => $valorDoResultado,
                    'tipoExame' => $tipo_exame,
                    'valorReferencia' => $valor_referencia,
                    'dataHoraExame' => $data_laudo_prevista . " 00:00:00" // Use a data prevista do laudo
                ];
            }
        }

        try {
            // O método inserir do LaudoDao é responsável por salvar o laudo e seus exames associados
            $laudoDao->inserir($laudo, $examesParaLaudo);

            header("Location: ../views/lista_de_exames.php?status=success&message=" . urlencode("Laudo e exames salvos com sucesso!"));
            exit();
        } catch (Exception $e) {
            header("Location: ../views/cadastroExames.php?solicitacao_id={$solicitacao_id}&error=" . urlencode("Erro ao salvar laudo: " . $e->getMessage()));
            exit();
        }
    } 
    // Lógica para SALVAR EDIÇÃO de Laudo
    elseif (isset($_POST['salvar_edicao_laudo'])) {
        $id_laudo = $_POST['id_laudo'] ?? null;
        if (!$id_laudo) {
            header("Location: ../views/lista_de_laudos.php?status=error&message=" . urlencode("ID do laudo não fornecido para atualização."));
            exit();
        }

        $laudo = new Laudo();
        $laudo->setId($id_laudo);
        $laudo->setSolicitacaoId((int)($_POST['solicitacao_id'] ?? 0));
        $laudo->setPacienteId((int)($_POST['paciente_id'] ?? 0));
        $laudo->setResponsavelTecnico($_POST['responsavel_tecnico'] ?? null);
        $laudo->setDataFinalizacao($_POST['data_finalizacao'] ?? date('Y-m-d H:i:s'));
        $laudo->setObservacoes($_POST['observacoes'] ?? null);

        try {
            $laudoDao->editar($laudo);
            header("Location: ../views/lista_de_laudos.php?status=success&message=" . urlencode("Laudo atualizado com sucesso!"));
            exit();
        } catch (Exception $e) {
            header("Location: ../views/editar_laudo.php?id={$id_laudo}&status=error&message=" . urlencode("Erro ao atualizar laudo: " . $e->getMessage()));
            exit();
        }
    }
    // Outras ações POST não reconhecidas
    else {
        header("Location: ../views/dashboard.php?status=error&message=" . urlencode("Ação POST não reconhecida ou dados inválidos."));
        exit();
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Lógica para EDITAR Laudo (via GET)
    if (isset($_GET['editar'])) {
        $idLaudo = $_GET['editar'];
        $laudo = $laudoDao->buscarPorId($idLaudo);
        if (!isset($laudo)) {
            echo "<p>Laudo de ID {$idLaudo} não encontrado.</p>";
            // Opcional: redirecionar para uma página de erro ou lista de laudos
        }
        // A variável $laudo agora pode ser usada na view 'editar_laudo.php' para preencher o formulário
    }
    // Lógica para EXCLUIR Laudo (via GET)
    elseif (isset($_GET['excluir'])) {
        $id = $_GET['excluir'];
        try {
            $laudoDao->excluir($id);
            header("Location: ../views/lista_de_laudos.php?status=success&message=" . urlencode("Laudo excluído com sucesso!"));
            exit();
        } catch (Exception $e) {
            header("Location: ../views/lista_de_laudos.php?status=error&message=" . urlencode($e->getMessage()));
            exit();
        }
    }
    // Outras ações GET não reconhecidas
    else {
        header("Location: ../views/dashboard.php");
        exit();
    }
}
// Se não for POST nem GET com ação específica, redireciona (já coberto acima)
else {
    header("Location: ../views/dashboard.php");
    exit();
}

//Função para listar laudos na interface.
function listarLaudos(){
    $laudoDao = new LaudoDao(); // Instancia o DAO dentro da função
    $lista = $laudoDao->read(); // Lê todos os laudos do banco de dados

    if (!empty($lista)) {
        foreach($lista as $laudo){
            echo "<tr> 
                    <td>" . htmlspecialchars($laudo->getId() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($laudo->getSolicitacaoId() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($laudo->getPacienteId() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($laudo->getResponsavelTecnico() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($laudo->getDataFinalizacao() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($laudo->getObservacoes() ?? 'N/A') . "</td>
                    <td> 
                        <a href='editar_laudo.php?editar=" . htmlspecialchars($laudo->getId() ?? '') . "' class='btn btn-warning btn-sm me-1'>Editar</a>
                        <button type='button' class='btn btn-danger btn-sm' onclick=\"confirmarExclusaoLaudo(" . htmlspecialchars($laudo->getId() ?? '') . ")\">Excluir</button>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Nenhum laudo encontrado.</td></tr>";
    }
}
?>
