<?php
// Para ver erros PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui os modelos e o DAO da API para Solicitações
require_once __DIR__ . '/../model/Solicitacoes.php'; 
require_once __DIR__ . '/../model/SolicitacaoExameItem.php';
require_once __DIR__ . '/../dao/SolicitacaoDaoApi.php';

// Instancia o DAO de Solicitação uma vez no escopo global
$solicitacaoDao = new SolicitacaoDaoApi();

// Variável para armazenar o objeto Solicitacao para edição, se aplicável
$solicitacaoParaEdicao = null;

// --- Lógicas de Requisição (POST / GET) ---

// Lógica para Salvar Nova Solicitação ou Salvar Edição de Solicitação
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['marcar_exame'])) { // Nome do botão de submit em NewExamePaciente.php
        $paciente_id = $_POST['idPac'] ?? null;
        $data_marcada_exame = $_POST['data_marcada_exame'] ?? null;
        $exames_solicitados_por_categoria = $_POST['examesSolicitados'] ?? [];
        $nome_paciente_form = $_POST['nome'] ?? null; // Nome do paciente do formulário (para observações/solicitante)

        // Validação básica
        if (empty($paciente_id) || empty($data_marcada_exame) || empty($exames_solicitados_por_categoria)) {
            header("Location: ../views/solicitacao_form.php?status=error&message=" . urlencode("Dados incompletos para solicitar o exame."));
            exit();
        }

        $solicitacao = new Solicitacao();
        $solicitacao->setPacienteId((int)$paciente_id);
        $solicitacao->setDataSolicitacao(date('Y-m-d H:i:s')); // Data/hora atual da solicitação
        // Adaptação da data/hora marcada para o formato esperado pelo banco (sem 'T')
        if ($data_marcada_exame && strpos($data_marcada_exame, 'T') !== false) {
             $data_marcada_exame = str_replace('T', ' ', $data_marcada_exame) . ':00';
        }
        $solicitacao->setDataPrevistaRealizacao($data_marcada_exame);
        $solicitacao->setSolicitanteNome("Atendente BioDiagnóstico"); // Exemplo, pode vir da sessão
        $solicitacao->setStatus("Pendente");
        $solicitacao->setObservacoes("Solicitação criada para paciente " . htmlspecialchars($nome_paciente_form));

        $examesItens = [];
        foreach ($exames_solicitados_por_categoria as $categoria => $exames) {
            foreach ($exames as $nome_exame) {
                $item = new SolicitacaoExameItem();
                $item->setNomeExame($nome_exame);
                $item->setTipoExameCategoria($categoria);
                $item->setStatusItem("Pendente"); // Item de exame também pendente
                // valor_referencia_solicitacao pode ser buscado aqui ou deixado nulo
                $examesItens[] = $item;
            }
        }

        try {
            $result = $solicitacaoDao->inserir($solicitacao, $examesItens);
            if (isset($result['success']) && $result['success']) {
                header("Location: ../views/lista_solicitacoes_pendentes.php?status=success&message=" . urlencode("Solicitação de exame criada com sucesso! ID: " . $result['idSolicitacao']));
            } else {
                header("Location: ../views/solicitacao_form.php?status=error&message=" . urlencode("Erro ao criar solicitação: " . ($result['erro'] ?? '')));
            }
            exit();
        } catch (Exception $e) {
            error_log("Erro ao processar POST de solicitação: " . $e->getMessage());
            header("Location: ../views/solicitacao_form.php?status=error&message=" . urlencode("Erro interno ao criar solicitação: " . $e->getMessage()));
            exit();
        }
    }
    elseif (isset($_POST['salvar_edicao_solicitacao'])) { // Flag para edição
        $id_solicitacao = $_POST['id_solicitacao'] ?? null;
        if (!$id_solicitacao) {
            header("Location: ../views/_pendentes.php?status=error&message=" . urlencode("ID da solicitação não fornecido para atualização."));
            exit();
        }
        $solicitacao = new Solicitacao();
        $solicitacao->setIdSolicitacao((int)$id_solicitacao);
        $solicitacao->setPacienteId((int)$_POST['idPac']);
        if ($_POST['data_marcada_exame'] && strpos($_POST['data_marcada_exame'], 'T') !== false) {
             $data_marcada_exame_edit = str_replace('T', ' ', $_POST['data_marcada_exame']) . ':00';
        }
        $solicitacao->setDataPrevistaRealizacao($data_marcada_exame_edit);
        $solicitacao->setSolicitanteNome($_POST['solicitante_nome']);
        $solicitacao->setStatus($_POST['status']);
        $solicitacao->setObservacoes($_POST['observacoes']);

        try {
            $result = $solicitacaoDao->editar($solicitacao);
            if (isset($result['success']) && $result['success']) {
                header("Location: ../views/_pendentes.php?status=success&message=" . urlencode("Solicitação atualizada com sucesso!"));
            } else {
                header("Location: ../views/solicitacao_form.php?editar=" . $id_solicitacao . "&status=error&message=" . urlencode("Erro ao atualizar solicitação: " . ($result['erro'] ?? '')));
            }
            exit();
        } catch (Exception $e) {
            error_log("Erro ao processar POST de edição de solicitação: " . $e->getMessage());
            header("Location: ../views/solicitacao_form.php?editar=" . $id_solicitacao . "&status=error&message=" . urlencode("Erro interno ao atualizar solicitação: " . $e->getMessage()));
            exit();
        }
    }
}
// Lógica para carregar DADOS DE EDIÇÃO DE UMA SOLICITAÇÃO (GET)
elseif (isset($_GET['editar'])) {
    $idSolicitacao = $_GET['editar'];
    $solicitacaoParaEdicao = $solicitacaoDao->buscarPorId($idSolicitacao);
    if (!isset($solicitacaoParaEdicao) || $solicitacaoParaEdicao === false) {
        header("Location: ../views/_pendentes.php?status=error&message=" . urlencode("Solicitação ID {$idSolicitacao} não encontrada ou erro ao buscar."));
        exit();
    }
}
// Lógica para EXCLUIR Solicitação (GET)
elseif (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    try {
        $result = $solicitacaoDao->excluir($id);
        if (isset($result['success']) && $result['success']) {
             header("Location: ../views/_pendentes.php?status=success&message=" . urlencode("Solicitação excluída com sucesso!"));
        } else {
             header("Location: ../views/_pendentes.php?status=error&message=" . urlencode("Erro ao excluir solicitação: " . ($result['erro'] ?? '')));
        }
        exit();
    } catch (Exception $e) {
        error_log("Erro ao processar GET de exclusão de solicitação: " . $e->getMessage());
        header("Location: ../views/_pendentes.php?status=error&message=" . urlencode("Erro interno ao excluir solicitação: " . $e->getMessage()));
        exit();
    }
}

// Função para listar solicitações na interface (gera o HTML da tabela)
function listarSolicitacoes(){
    global $solicitacaoDao; // Acessa a instância global do DAO
    $lista = $solicitacaoDao->read(); // Lê todas as solicitações da API Node.js

    if (!empty($lista)) {
        foreach($lista as $solicitacao){
            echo "<tr> 
                    <td>" . htmlspecialchars($solicitacao->getIdSolicitacao() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($solicitacao->getPacienteId() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($solicitacao->getDataSolicitacao() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($solicitacao->getDataPrevistaRealizacao() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($solicitacao->getSolicitanteNome() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($solicitacao->getStatus() ?? 'N/A') . "</td>
                    <td> 
                        <a href='solicitacao_form.php?editar=" . htmlspecialchars($solicitacao->getIdSolicitacao() ?? '') . "'>Editar</a>
                        &nbsp; | &nbsp; 
                        <a href='../controller/SolicitacaoController.php?excluir=" . htmlspecialchars($solicitacao->getIdSolicitacao() ?? '') . "' onclick=\"return confirm('Tem certeza que deseja excluir esta solicitação?')\">Excluir</a>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Nenhuma solicitação encontrada.</td></tr>"; // Ajuste o colspan
    }
}
?>