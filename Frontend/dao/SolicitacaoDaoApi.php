<?php
// Inclui os modelos necessários
require_once __DIR__ . '/../model/Solicitacoes.php'; 
require_once __DIR__ . '/../model/SolicitacaoExameItem.php';

class SolicitacaoDaoApi {

    private function callApi($method, $endpoint, $data = null) {
        $url = "http://localhost:3000" . $endpoint; // Base URL da sua API Node.js

        $options = [
            "http" => [
                "header"  => "Content-Type: application/json\r\n",
                "method"  => $method,
                "content" => $data ? json_encode($data) : null,
                "ignore_errors" => true // Importante para ler a resposta da API mesmo em caso de erro HTTP (4xx/5xx)
            ]
        ];

        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);
        file_put_contents(__DIR__ . '/../log_api_resposta.txt', $result);
        
        if ($result === FALSE) {
            error_log("Erro na requisição {$method} para: " . $url . ". Verifique se a API Node.js está rodando.");
            return ["erro" => "Falha na requisição para a API Node.js"];
        }

file_put_contents(__DIR__ . '/../log_api_resposta_bruta.txt', $result);

        $apiResponse = json_decode($result, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Erro ao decodificar JSON da API: " . json_last_error_msg() . " Resposta: " . $result);
            return ["erro" => "Resposta inválida da API (JSON malformado)."];
        }

        return $apiResponse; // Retorna a resposta decodificada
    }

    // Método para inserir uma nova Solicitação e seus Itens (POST)
    public function inserir(Solicitacao $solicitacao, array $examesItens = []) {
        $dados = [
            // Adapte as chaves para camelCase se sua API Node.js espera assim (solicitacaoDAO.js)
            "pacienteId" => $solicitacao->getPacienteId(),
            "dataSolicitacao" => $solicitacao->getDataSolicitacao(),
            "dataPrevistaRealizacao" => $solicitacao->getDataPrevistaRealizacao(),
            "solicitanteNome" => $solicitacao->getSolicitanteNome(),
            "status" => $solicitacao->getStatus(),
            "observacoes" => $solicitacao->getObservacoes(),
            "exames" => [] // Array para os itens de exame
        ];

        foreach ($examesItens as $item) {
            // Adapte as chaves dos itens de exame para camelCase se sua API Node.js espera assim
            $dados['exames'][] = [
                "nomeExame" => $item->getNomeExame(),
                "tipoExameCategoria" => $item->getTipoExameCategoria(),
                "valorReferenciaSolicitacao" => $item->getValorReferenciaSolicitacao(),
                "statusItem" => $item->getStatusItem()
            ];
        }

        file_put_contents(__DIR__ . '/../log_dados_envio.txt', json_encode($dados, JSON_PRETTY_PRINT));
        return $this->callApi('POST', '/solicitacoes', $dados);
    }

    // Método para ler todas as Solicitações (GET)
    public function read() {
        $lista = $this->callApi('GET', '/solicitacoes');
        $solicitacoesList = [];

        if (is_array($lista) && !isset($lista['erro'])) {
            foreach ($lista as $linha) {
                $solicitacoesList[] = $this->popularSolicitacao($linha);
            }
        }
        return $solicitacoesList;
    }

    // Método para buscar uma Solicitação por ID (GET)
    public function buscarPorId($id) {
        $data = $this->callApi('GET', "/solicitacoes/" . urlencode($id));
        if ($data && !isset($data['erro'])) {
            return $this->popularSolicitacao($data);
        }
        return null;
    }

    // Dentro da classe SolicitacaoDaoApi
public function updateSolicitacaoStatus($id, $newStatus) {
    $data = [
        "status" => $newStatus
    ];
    // O endpoint PUT em SolicitacaoRouter.js para atualização de status seria /solicitacoes/:id/status
    // OU, se seu updateSolicitacao no Node.js puder receber apenas o status, você pode usar ele.
    // Pelo seu solicitacaoDAO.js, há um updateSolicitacaoStatus que parece ser um endpoint específico.
    // Assumo um endpoint PUT /solicitacoes/:id/status
    return $this->callApi('PUT', "/solicitacoes/" . urlencode($id) . "/status", $data); 
}

    // Método para editar uma Solicitação (PUT)
    public function editar(Solicitacao $solicitacao) {
        $dados = [
            // Adapte as chaves para camelCase se sua API Node.js espera assim
            "pacienteId" => $solicitacao->getPacienteId(),
            "dataSolicitacao" => $solicitacao->getDataSolicitacao(),
            "dataPrevistaRealizacao" => $solicitacao->getDataPrevistaRealizacao(),
            "solicitanteNome" => $solicitacao->getSolicitanteNome(),
            "status" => $solicitacao->getStatus(),
            "observacoes" => $solicitacao->getObservacoes()
        ];
        return $this->callApi('PUT', "/solicitacoes/" . urlencode($solicitacao->getIdSolicitacao()), $dados);
    }

    // Método para excluir uma Solicitação (DELETE)
    public function excluir($id) {
        return $this->callApi('DELETE', "/solicitacoes/" . urlencode($id));
    }

    // Método interno para popular um objeto Solicitacao a partir do array da API Node.js
    private function popularSolicitacao($linha) {
        $solicitacao = new Solicitacao();
        // A API Node.js em solicitacaoDAO.js e solicitacoesRouter.js retorna snake_case.
        // Mantenha snake_case aqui para os setters do modelo Solicitacao.php.
        $solicitacao->setIdSolicitacao($linha['id_solicitacao'] ?? null);
        $solicitacao->setPacienteId($linha['paciente_id'] ?? null);
        $solicitacao->setDataSolicitacao($linha['data_solicitacao'] ?? null);
        $solicitacao->setDataPrevistaRealizacao($linha['data_prevista_realizacao'] ?? null);
        $solicitacao->setSolicitanteNome($linha['solicitante_nome'] ?? null);
        $solicitacao->setStatus($linha['status'] ?? null);
        $solicitacao->setObservacoes($linha['observacoes'] ?? null);

        // Se a API retornar os itens de exame junto com a solicitação, popular aqui
        $itens = [];
        if (isset($linha['exames']) && is_array($linha['exames'])) {
            foreach ($linha['exames'] as $itemArray) {
                $item = new SolicitacaoExameItem();
                $item->setId($itemArray['id'] ?? null);
                $item->setSolicitacaoId($itemArray['solicitacao_id'] ?? null);
                $item->setNomeExame($itemArray['nome_exame'] ?? null);
                $item->setTipoExameCategoria($itemArray['tipo_exame_categoria'] ?? null);
                $item->setValorReferenciaSolicitacao($itemArray['valor_referencia_solicitacao'] ?? null);
                $item->setStatusItem($itemArray['status_item'] ?? null);
                $itens[] = $item;
            }
        }
        $solicitacao->setExamesItens($itens);

        return $solicitacao;
    }
}
?>