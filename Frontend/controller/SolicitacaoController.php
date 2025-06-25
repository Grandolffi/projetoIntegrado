<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui os modelos e o DAO da API para Solicitações
require_once __DIR__ . '/../model/Solicitacoes.php'; 
require_once __DIR__ . '/../model/SolicitacaoExameItem.php';
require_once __DIR__ . '/../dao/SolicitacaoDaoApi.php';

class SolicitacaoController {

    private static $solicitacaoDao;

    // Garante que o DAO esteja pronto para uso
    public static function init() {
        if (self::$solicitacaoDao === null) {
            self::$solicitacaoDao = new SolicitacaoDaoApi();
        }
    }

    public static function marcarExame() {
        self::init();

        $paciente_id = filter_var($_POST['idPac'] ?? null, FILTER_VALIDATE_INT);
        $data_marcada_exame = $_POST['data_marcada_exame'] ?? null;
        $exames_solicitados_por_categoria = $_POST['examesSolicitados'] ?? [];
        $nome_paciente_form = $_POST['nome'] ?? null; // Apenas UMA VEZ aqui

        // Validação básica - Apenas UMA VEZ
        if (empty($paciente_id) || empty($data_marcada_exame) || empty($exames_solicitados_por_categoria)) {
            header("Location: ../views/solicitacao_form.php?status=error&message=" . urlencode("Parece que faltou preencher algum campo essencial. Por favor, complete as informações para marcar o exame."));
            exit();
        }

        $solicitacao = new Solicitacao();
        $solicitacao->setPacienteId((int)$paciente_id);
        $solicitacao->setDataSolicitacao(date('Y-m-d H:i:s'));
        
        // Ajusta o formato da data/hora para o banco de dados
        if ($data_marcada_exame && strpos($data_marcada_exame, 'T') !== false) {
            $data_marcada_exame = str_replace('T', ' ', $data_marcada_exame) . ':00';
        }

        // Validação da data - Apenas UMA VEZ
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $data_marcada_exame);
        if (!$date) {
            header("Location: ../views/solicitacao_form.php?status=error&message=" . urlencode("A data e hora que você informou para o exame não são válidas. Poderia verificar e tentar de novo?"));
            exit();
        }

        $solicitacao->setDataPrevistaRealizacao($data_marcada_exame);
        $solicitacao->setSolicitanteNome("Atendente BioDiagnóstico"); // Pode ser dinâmico, vindo da sessão de usuário logado
        $solicitacao->setStatus("Pendente");
        $solicitacao->setObservacoes("Solicitação criada para paciente " . htmlspecialchars($nome_paciente_form));

        $examesItens = [];
        foreach ($exames_solicitados_por_categoria as $categoria => $exames) {
            foreach ($exames as $nome_exame) {
                $item = new SolicitacaoExameItem();
                // Impedindo injeção de código malicioso nesses campos - Apenas UMA VEZ
                $item->setNomeExame(htmlspecialchars($nome_exame));
                $item->setTipoExameCategoria(htmlspecialchars($categoria));
                $item->setStatusItem("Pendente");
                $examesItens[] = $item;
            }
        }

        try {
            $result = self::$solicitacaoDao->inserir($solicitacao, $examesItens);
            if (isset($result['success']) && $result['success']) {
                header("Location: ../views/lista_solicitacoes_pendentes.php?status=success&message=" . urlencode("Uhu! A solicitação de exame foi marcada com sucesso! ID: " . $result['idSolicitacao']));
            } else {
                header("Location: ../views/solicitacao_form.php?status=error&message=" . urlencode("Poxa, não conseguimos marcar a solicitação agora. " . ($result['erro'] ?? 'Um erro inesperado aconteceu. Tente novamente mais tarde.')));
            }
            exit();
        } catch (Exception $e) {
            error_log("Erro ao processar POST de solicitação: " . $e->getMessage());
            header("Location: ../views/solicitacao_form.php?status=error&message=" . urlencode("Ah não! Ocorreu um problema técnico ao tentar marcar o exame. Por favor, tente novamente mais tarde."));
            exit();
        }
    }

    public static function salvarEdicaoSolicitacao() {
        self::init();

        $id = $_POST['id_solicitacao'] ?? null;

        if (!$id) {
            header("Location: ../views/lista_de_exames.php?status=error&message=" . urlencode("Não foi possível identificar qual solicitação você tentou editar."));
            exit();
        }

        $dados = [
            "pacienteId" => (int)($_POST['paciente_id'] ?? 0),
            "dataSolicitacao" => $_POST['data_solicitacao'] ?? '',
            "dataPrevistaRealizacao" => $_POST['data_prevista'] ?? '',
            "solicitanteNome" => $_POST['solicitante_nome'] ?? '',
            "status" => $_POST['status'] ?? 'Pendente',
            "observacoes" => $_POST['observacoes'] ?? '',
            "exames" => []
        ];

        // Normaliza a data para o formato esperado pela API (se vier de um input datetime-local)
        if (!empty($dados['dataPrevistaRealizacao']) && strpos($dados['dataPrevistaRealizacao'], 'T') !== false) {
            $dados['dataPrevistaRealizacao'] = str_replace('T', ' ', $dados['dataPrevistaRealizacao']) . ':00';
        }

        // Validação da data no modo de edição (se aplicável)
        if (!empty($dados['dataPrevistaRealizacao'])) {
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $dados['dataPrevistaRealizacao']);
            if (!$date) {
                header("Location: ../views/solicitacao_form.php?status=error&message=" . urlencode("A data e hora para edição que você informou não são válidas. Por favor, verifique."));
                exit();
            }
        }


        if (isset($_POST['exames_nome']) && is_array($_POST['exames_nome'])) {
            foreach ($_POST['exames_nome'] as $i => $nome) {
                $dados["exames"][] = [
                    "nomeExame" => $nome,
                    "tipoExameCategoria" => $_POST['exames_categoria'][$i] ?? '',
                    // Adicione estes campos se eles vierem do formulário de edição
                    "valorReferenciaSolicitacao" => $_POST['exames_referencia'][$i] ?? '',
                    "statusItem" => $_POST['exames_status'][$i] ?? 'Pendente'
                ];
            }
        }

        // Idealmente, esta lógica de PUT estaria encapsulada no SolicitacaoDaoApi
        $api_url = "http://localhost:3000/solicitacoes/$id";

        $options = [
            "http" => [
                "method"  => "PUT",
                "header"  => "Content-Type: application/json\r\n",
                "content" => json_encode($dados)
            ]
        ];

        $context = stream_context_create($options);
        $result = @file_get_contents($api_url, false, $context);

        $responseCode = null;
        if (isset($http_response_header)) { // Verifica se o cabeçalho HTTP existe
            foreach ($http_response_header as $header) {
                if (preg_match('#HTTP/\d+\.\d+\s+(\d+)#', $header, $matches)) {
                    $responseCode = intval($matches[1]);
                    break;
                }
            }
        }
        
        if ($result === false || $responseCode >= 400) {
            header("Location: ../views/lista_de_exames.php?status=error&message=" . urlencode("Parece que algo deu errado ao tentar salvar as alterações na solicitação. Por favor, tente novamente. Código: $responseCode"));
            exit();
        } else {
            header("Location: ../views/lista_de_exames.php?status=success&message=" . urlencode("Sucesso! A solicitação foi atualizada com sucesso!"));
            exit();
        }
    }

    public static function buscarSolicitacaoParaEdicao($idSolicitacao) {
        self::init();
        $solicitacao = self::$solicitacaoDao->buscarPorId($idSolicitacao);
        // O SolicitacaoDaoApi já deve retornar um objeto Solicitacao ou null/false
        if (!$solicitacao) { // Verifica se não é nulo ou false
            // Redirecionamento movido para a view, pois o controller deve apenas retornar o dado.
            // A view decide o que fazer se o dado não for encontrado.
            return null; // Retorna null para a view indicar que não encontrou
        }
        return $solicitacao;
    }

    public static function excluirSolicitacao($id) {
        self::init();
        try {
            $result = self::$solicitacaoDao->excluir($id);
            if (isset($result['success']) && $result['success']) {
                header("Location: ../views/lista_solicitacoes_pendentes.php?status=success&message=" . urlencode("A solicitação foi excluída com sucesso!")); // Ajustado para lista_solicitacoes_pendentes.php
            } else {
                header("Location: ../views/lista_solicitacoes_pendentes.php?status=error&message=" . urlencode("Putz! Não foi possível excluir a solicitação. " . ($result['erro'] ?? 'Talvez ela já tenha sido removida.'))); // Ajustado para lista_solicitacoes_pendentes.php
            }
            exit();
        } catch (Exception $e) {
            error_log("Erro ao processar GET de exclusão de solicitação: " . $e->getMessage());
            header("Location: ../views/lista_solicitacoes_pendentes.php?status=error&message=" . urlencode("Ocorreu um erro interno ao tentar remover a solicitação. Por favor, tente mais tarde.")); // Ajustado para lista_solicitacoes_pendentes.php
            exit();
        }
    }

    // Retorna uma lista de objetos Solicitacao para exibição
    public static function listarSolicitacoesPendentes(){
        self::init();
        $api_url = "http://localhost:3000/solicitacoes?status=Pendente";
        $response = @file_get_contents($api_url);
        $solicitacoes = json_decode($response, true);

        if ($response === false || !is_array($solicitacoes)) {
            error_log("Erro ao buscar solicitações pendentes da API: " . ($response === false ? 'Falha na conexão ou resposta vazia' : 'Resposta não é um array válido.'));
            return [];
        }
        
        $listaObjetosSolicitacao = [];
        if (!empty($solicitacoes)) {
            foreach ($solicitacoes as $solData) {
                $solicitacao = new Solicitacao();
                // Assumindo que os setters em Solicitacoes.php correspondem aos nomes da API
                $solicitacao->setIdSolicitacao($solData['id'] ?? null);
                $solicitacao->setPacienteId($solData['pacienteId'] ?? null);
                $solicitacao->setDataSolicitacao($solData['dataSolicitacao'] ?? null);
                $solicitacao->setDataPrevistaRealizacao($solData['dataPrevistaRealizacao'] ?? null);
                $solicitacao->setSolicitanteNome($solData['solicitanteNome'] ?? null);
                $solicitacao->setStatus($solData['status'] ?? null);
                $solicitacao->setObservacoes($solData['observacoes'] ?? null);

                if (isset($solData['exames']) && is_array($solData['exames'])) {
                    $examesItens = [];
                    foreach ($solData['exames'] as $itemData) {
                        $item = new SolicitacaoExameItem();
                        $item->setId($itemData['id'] ?? null);
                        $item->setNomeExame($itemData['nomeExame'] ?? null);
                        $item->setTipoExameCategoria($itemData['tipoExameCategoria'] ?? null);
                        $item->setValorReferenciaSolicitacao($itemData['valorReferenciaSolicitacao'] ?? null);
                        $item->setStatusItem($itemData['statusItem'] ?? null);
                        $examesItens[] = $item;
                    }
                    $solicitacao->setExamesItens($examesItens);
                }
                $listaObjetosSolicitacao[] = $solicitacao;
            }
        }
        return $listaObjetosSolicitacao;
    }
}

// Este bloco é para processar requisições HTTP DIRETAS para este controller.
// Ele age como um "roteador" para os métodos estáticos da classe.
if (basename($_SERVER['PHP_SELF']) === 'SolicitacaoController.php') {
    SolicitacaoController::init(); // Inicializa o DAO para este contexto de requisição direta

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['marcar_exame'])) {
            SolicitacaoController::marcarExame();
        } elseif (isset($_POST['salvar_edicao_solicitacao'])) {
            SolicitacaoController::salvarEdicaoSolicitacao();
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['excluir'])) {
            SolicitacaoController::excluirSolicitacao($_GET['excluir']);
        }
    }
}

// Variável para a view de edição, caso ela precise popular o formulário
// Essa variável não é tratada pelo bloco de roteamento direto acima,
// mas sim pela própria view que inclui este controller.
$solicitacaoParaEdicao = null; // Reinicializa para garantir que está limpa antes de ser usada
if (isset($_GET['editar']) && !isset($_POST['salvar_edicao_solicitacao'])) {
    $idSolicitacao = $_GET['editar'];
    $solicitacaoParaEdicao = SolicitacaoController::buscarSolicitacaoParaEdicao($idSolicitacao);
    // Se a solicitação não for encontrada, redireciona aqui na view, não no controller
    if (!$solicitacaoParaEdicao) {
        header("Location: ../views/lista_solicitacoes_pendentes.php?status=error&message=" . urlencode("Não conseguimos encontrar a solicitação (ID: {$idSolicitacao}) para edição. Ela pode não existir mais."));
        exit();
    }
}

?>