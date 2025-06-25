<?php
// Inclua o modelo Laudo, que representa a estrutura dos dados de laudo.
require_once __DIR__ . '/../model/Laudo.php';
require_once __DIR__ . '/../model/ResultadoExames.php'; // Pode ser útil se precisar passar objetos exame para API

class LaudoDaoApi {

    // Helper para fazer requisições HTTP (copiado do seu PessoaDao.php e ExameDaoApi.php)
    private function callApi($method, $endpoint, $data = null) {
        // Ajuste esta URL base se a sua API Node.js para Laudos estiver em um endpoint diferente
        $url = "http://localhost:3000" . $endpoint; 

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
        
        if ($result === FALSE) {
            error_log("Erro na requisição {$method} para: " . $url);
            // Poderia lançar uma exceção ou retornar um erro mais detalhado
            return ["erro" => "Falha na requisição para a API Node.js: Verifique se ela está rodando e o endpoint está correto."];
        }

        $apiResponse = json_decode($result, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Erro ao decodificar JSON da API: " . json_last_error_msg() . " Resposta: " . $result);
            return ["erro" => "Resposta inválida da API (JSON malformado)."];
        }

        return $apiResponse; // Retorna a resposta decodificada
    }

    // Insere um novo Laudo através da API Node.js.
    public function inserir(Laudo $laudo, array $resultadosExames = []) {
        // Certifique-se de que sua API Node.js de Laudo espera esta estrutura,
        // incluindo um array de exames.
        $dados = [
            "solicitacao_id" => $laudo->getSolicitacaoId(),
            "paciente_id" => $laudo->getPacienteId(),
            "responsavel_tecnico" => $laudo->getResponsavelTecnico(),
            "observacoes" => $laudo->getObservacoes(),
            "data_finalizacao" => $laudo->getDataFinalizacao(),
            "resultadosExames" => $resultadosExames // Envia os exames junto com o laudo
        ];
        // O endpoint para inserir laudos, ex: POST http://localhost:3000/laudos
        return $this->callApi('POST', '/laudos', $dados);
    }

public function editar(Laudo $laudo) {
    $id = $laudo->getId();
    if (!$id) {
        throw new Exception("ID do laudo é obrigatório para editar.");
    }

    $url = $this->apiBaseUrl . "/laudos/{$id}";
    $data = [
        'solicitacaoId' => $laudo->getSolicitacaoId(),
        'pacienteId' => $laudo->getPacienteId(),
        'responsavelTecnico' => $laudo->getResponsavelTecnico(),
        'dataFinalizacao' => $laudo->getDataFinalizacao(),
        'observacoes' => $laudo->getObservacoes()
    ];

    $options = [
        "http" => [
            "method" => "PUT",
            "header" => "Content-Type: application/json\r\n",
            "content" => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === false) {
        throw new Exception("Erro ao atualizar o laudo via API.");
    }

    // Você pode fazer decode do resultado para verificar sucesso
    $response = json_decode($result, true);
    if (!$response || isset($response['error'])) {
        throw new Exception("Resposta de erro da API ao editar o laudo.");
    }

    return $response;
}

    // Exclui um Laudo através da API Node.js.
    public function excluir($id) {
        // O endpoint para excluir laudos, ex: DELETE http://localhost:3000/laudos/:id
        return $this->callApi('DELETE', "/laudos/" . urlencode($id));
    }

    // Busca um Laudo específico por ID através da API Node.js.
    public function buscarPorId($id) {
        $data = $this->callApi('GET', "/laudos/" . urlencode($id));
        if ($data && !isset($data['erro'])) {
            return $this->listaLaudos($data);
        }
        return null;
    }

    // read (lista) todos os Laudos através da API Node.js.
    public function read() {
        $lista = $this->callApi('GET', '/laudos');
        $laudosList = array();

        if (is_array($lista) && !isset($lista['erro'])) { // Verifica se é um array válido e não um erro
            foreach ($lista as $linha) {
                $laudosList[] = $this->listaLaudos($linha);
            }
        }
        return $laudosList;
    }

    public function listaLaudos($linha) {
        $laudo = new Laudo();
        // Os nomes das chaves aqui devem corresponder EXATAMENTE ao que sua API Node.js retorna.
        $laudo->setId($linha['id_laudo'] ?? $linha['id'] ?? null); // API pode retornar 'id' ou 'id_laudo'
        $laudo->setSolicitacaoId($linha['solicitacao_id'] ?? null);
        $laudo->setPacienteId($linha['paciente_id'] ?? null);
        $laudo->setResponsavelTecnico($linha['responsavel_tecnico'] ?? null);
        $laudo->setObservacoes($linha['observacoes'] ?? null);
        $laudo->setDataFinalizacao($linha['data_finalizacao'] ?? null);
        return $laudo;
    }
}
