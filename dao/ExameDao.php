// Exemplo de como ExameDao.php (no PHP) deveria ser refeito:
<?php
// Não precisa mais de ConnectionFactory.php ou ResultadoExames.php aqui
// se eles não forem usados para nada além de tipagem ou objetos.

class ExameDao {

    private $apiBaseUrl = "http://localhost:3000/api/exames"; // Endpoint base para exames na sua API Node.js

    // Helper para fazer requisições à API
    private function callApi($method, $id = null, $data = null) {
        $url = $this->apiBaseUrl;
        if ($id) {
            $url .= "/" . $id; // Ex: /api/exames/123
        }

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
            return $apiResponse; // Retorna os dados da API
        } else {
            $errorMessage = $apiResponse['message'] ?? 'Erro desconhecido da API.';
            throw new Exception("Erro da API (código {$httpCode}): " . $errorMessage);
        }
    }

    // MODIFICADO: Inserir um resultado de exame via API (usado pelo LaudoController)
    public function inserir(ResultadoExames $exame, $laudo_id) {
        $data = [
            'laudo_id' => $laudo_id,
            'nome_exame' => $exame->getNomeExame(),
            'tipo_exame' => $exame->getTipoExame(),
            'valor_absoluto' => $exame->getValorAbsoluto(),
            'valor_referencia' => $exame->getValorReferencia(),
            'paciente_registro' => $exame->getPaciente(),
            'data_hora_exame' => $exame->getDataHora()
        ];
        // Note: A API Node.js espera que o tipo_exame e valor_referencia sejam preenchidos
        // Se a API for buscar esses dados, você pode omiti-los aqui.

        try {
            return $this->callApi('POST', null, $data); // POST para criar novo
        } catch (Exception $e) {
            throw $e; // Re-lança a exceção para o controlador
        }
    }

    // MODIFICADO: Buscar todos os resultados de exames via API (usado por lista_de_exames.php)
    public function getAll() {
        try {
            return $this->callApi('GET'); // GET sem ID para listar todos
        } catch (Exception $e) {
            // Em vez de echo, é melhor retornar um array vazio e logar o erro
            error_log("Erro em ExameDao::getAll(): " . $e->getMessage());
            return [];
        }
    }

    // NOVO: Buscar um exame por ID para edição
    public function getById($id) {
        try {
            return $this->callApi('GET', $id);
        } catch (Exception $e) {
            error_log("Erro em ExameDao::getById(): " . $e->getMessage());
            return null;
        }
    }

    // NOVO: Atualizar um exame via API
    public function update(array $exameData) { // Espera um array com os dados do exame
        $id = $exameData['id_exame']; // O ID do exame a ser atualizado
        try {
            return $this->callApi('PUT', $id, $exameData);
        } catch (Exception $e) {
            throw $e;
        }
    }

    // NOVO: Excluir um exame via API
    public function delete($id) {
        try {
            return $this->callApi('DELETE', $id);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
?>