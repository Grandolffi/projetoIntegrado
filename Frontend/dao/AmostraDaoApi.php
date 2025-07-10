<?php
// Inclui o modelo Amostra, que representa a estrutura dos dados.
require_once __DIR__ . '/../model/Amostra.php'; 

class AmostraDaoApi {

    public function inserir(Amostra $amostra) {
        // Endpoint da API para inserir novas amostras (POST).
        $url = "http://localhost:3000/amostras"; 
        
        // Dados da amostra a serem enviados no corpo da requisição.
        $dados = [
            "pacienteId" => $amostra->getPacienteId(),
            "dataColeta" => $amostra->getDataColeta(),
            "tipoColetaRealizada" => $amostra->getTipoColetaRealizada(),
            "tubosUtilizados" => $amostra->getTubosUtilizados(),
            "loteTubo" => $amostra->getLoteTubo(),
            "validadeTubo" => $amostra->getValidadeTubo(),
            "tamanhoSeringa" => $amostra->getTamanhoSeringa(),
            "loteSeringa" => $amostra->getLoteSeringa(),
            "validadeSeringa" => $amostra->getValidadeSeringa(),
            "lotePoteUrina" => $amostra->getLotePoteUrina(),
            "validadePoteUrina" => $amostra->getValidadePoteUrina(),
            "lotePoteFezes" => $amostra->getLotePoteFezes(),
            "validadePoteFezes" => $amostra->getValidadePoteFezes()
        ];

        // Configuração da requisição HTTP.
        $options = [
            "http" => [
                "header"  => "Content-Type: application/json\r\n",
                "method"  => "POST",
                "content" => json_encode($dados)
            ]
        ];

        $context = stream_context_create($options);
        // Faz a requisição e obtém o resultado. Usa @ para suprimir warnings.
        $result = @file_get_contents($url, false, $context);
        return $result ? json_decode($result, true) : false;
    }

    public function editar(Amostra $amostra) {
        // Endpoint da API para editar uma amostra específica (PUT).
        $url = "http://localhost:3000/amostras/" . urlencode($amostra->getId());
        
        $dados = [
            "pacienteId" => $amostra->getPacienteId(),
            "dataColeta" => $amostra->getDataColeta(),
            "tipoColetaRealizada" => $amostra->getTipoColetaRealizada(),
            "tubosUtilizados" => $amostra->getTubosUtilizados(),
            "loteTubo" => $amostra->getLoteTubo(),
            "validadeTubo" => $amostra->getValidadeTubo(),
            "tamanhoSeringa" => $amostra->getTamanhoSeringa(),
            "loteSeringa" => $amostra->getLoteSeringa(),
            "validadeSeringa" => $amostra->getValidadeSeringa(),
            "lotePoteUrina" => $amostra->getLotePoteUrina(),
            "validadePoteUrina" => $amostra->getValidadePoteUrina(),
            "lotePoteFezes" => $amostra->getLotePoteFezes(),
            "validadePoteFezes" => $amostra->getValidadePoteFezes()
        ];
    
        $options = [
            "http" => [
                "header"  => "Content-Type: application/json\r\n",
                "method"  => "PUT",
                "content" => json_encode($dados)
            ]
        ];

        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);

        if ($result === FALSE) {
            error_log("Falha na requisição PUT para editar amostra. URL: $url");
            return ["erro" => "Falha na requisição PUT para amostra"];
        }
        return json_decode($result, true);
    }

    public function read() {
        // Endpoint da API para listar todas as amostras (GET).
        $url = "http://localhost:3000/amostras";
        $result = @file_get_contents($url);

        if ($result === FALSE) {
            error_log("Falha ao buscar dados de amostras em " . $url);
            return []; // Retorna um array vazio em caso de falha.
        }

        $amostrasList = [];
        $lista = json_decode($result, true); // Decodifica a resposta JSON.

        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Erro ao decodificar JSON de amostras: " . json_last_error_msg());
            return [];
        }

        if (!is_array($lista)) {
            error_log("A resposta da API para listar amostras não é um array. URL: " . $url);
            return [];
        }

        foreach ($lista as $amostraArray) {
            $amostrasList[] = $this->mapearAmostra($amostraArray);
        }
        return $amostrasList;
    }
    
    /**
     * Busca uma amostra específica por ID na API.
     * @param int $id O ID da amostra a ser buscada.
     * @return Amostra|null O objeto Amostra ou null se não for encontrado.
     */
    public function buscarPorId($id) {
        // Endpoint da API para buscar uma amostra por ID (GET).
        $url = "http://localhost:3000/amostras/" . urlencode($id); 
        
        $response = @file_get_contents($url);
        
        if ($response === FALSE){
            error_log("Erro ao buscar amostra por ID via API: " . $id);
            return null; // ID não encontrado ou erro na requisição.
        }
        
        $data = json_decode($response, true);
        
        return $data ? $this->mapearAmostra($data) : null;
    }

    public function excluir($id) {
        // Endpoint da API para exclusão de amostras (DELETE).
        $url = "http://localhost:3000/amostras/" . urlencode($id);

        $options = [
            "http" => [
                "header"  => "Content-Type: application/json\r\n",
                "method"  => "DELETE"
            ]
        ];

        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);
        
        if ($result === FALSE) {
            error_log("Erro ao excluir amostra via API: " . $url);
            return ["erro" => "Erro ao excluir amostra."];
        }
        return json_decode($result, true);
    }
    
    private function mapearAmostra($row) {
        $amostra = new Amostra();
        $amostra->setId($row['id'] ?? null);
        $amostra->setPacienteId($row['pacienteId'] ?? null);
        $amostra->setDataColeta($row['dataColeta'] ?? null);
        $amostra->setTipoColetaRealizada($row['tipoColetaRealizada'] ?? null);
        $amostra->setTubosUtilizados($row['tubosUtilizados'] ?? null);
        $amostra->setLoteTubo($row['loteTubo'] ?? null);
        $amostra->setValidadeTubo($row['validadeTubo'] ?? null);
        $amostra->setTamanhoSeringa($row['tamanhoSeringa'] ?? null);
        $amostra->setLoteSeringa($row['loteSeringa'] ?? null);
        $amostra->setValidadeSeringa($row['validadeSeringa'] ?? null);
        $amostra->setLotePoteUrina($row['lotePoteUrina'] ?? null);
        $amostra->setValidadePoteUrina($row['validadePoteUrina'] ?? null);
        $amostra->setLotePoteFezes($row['lotePoteFezes'] ?? null);
        $amostra->setValidadePoteFezes($row['validadePoteFezes'] ?? null);
        return $amostra;
    } 
}