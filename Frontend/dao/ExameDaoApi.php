<?php
// Inclua o modelo ResultadoExames, que representa a estrutura dos dados de exame.
require_once __DIR__ . '/../model/ResultadoExames.php';

class ExameDaoApi {
    public function inserir(ResultadoExames $exame) {
        // Endpoint da API Node.js para inserção de exames (POST).
        // Ajuste esta URL se seu endpoint for diferente (ex: http://localhost:3000/api/exames)
        $url = "http://localhost:3000/exames"; 
        
        // Dados do exame a serem enviados no corpo da requisição JSON.
        $dados = [
            "laudo_id" => $exame->getLaudoId(), 
            "nome_exame" => $exame->getNomeExame(),
            "tipo_exame" => $exame->getTipoExame(),
            "valor_absoluto" => $exame->getValorAbsoluto(),
            "valor_referencia" => $exame->getValorReferencia(),
             "paciente_id_fk" => $exame->getPacienteIdFk(), 
            //"paciente_id_fk" => $exame->getPacienteIdFk() ?? null, 
            "data_hora_exame" => $exame->getDataHora() 
        ];

        // Opções para a requisição HTTP (método POST, cabeçalho JSON, conteúdo JSON).
        $options = [
            "http" => [
                "header"  => "Content-Type: application/json\r\n",
                "method"  => "POST",
                "content" => json_encode($dados)
            ]
        ];

        // Cria o contexto de stream para a requisição.
        $context = stream_context_create($options);
        // Faz a requisição e obtém o resultado. Usa @ para suprimir warnings em caso de erro.
        $result = @file_get_contents($url, false, $context);
        return $result ? json_decode($result, true) : false;
    }

    // update
    public function editar(ResultadoExames $exame) { // <-- ESTE É O PROBLEMA! NOME DUPLICADO
        // Endpoint da API Node.js para edição de exames (PUT).
        // Ajuste esta URL se seu endpoint for diferente (ex: http://localhost:3000/api/exames/)
        $url = "http://localhost:3000/editarexame/" . urlencode($exame->getIdExame());
        
        // Dados do exame a serem enviados para atualização.
        $dados = [
            "laudo_id" => $exame->getLaudoId(),
            "nome_exame" => $exame->getNomeExame(),
            "tipo_exame" => $exame->getTipoExame(),
            "valor_absoluto" => $exame->getValorAbsoluto(),
            "valor_referencia" => $exame->getValorReferencia(),
            "paciente_registro" => $exame->getPacienteRegistro(), // Atenção: aqui ainda usa paciente_registro
            "data_hora_exame" => $exame->getDataHora()
        ];

        // Opções para a requisição HTTP (método PUT, cabeçalho JSON, conteúdo JSON).
        $options = [
            "http" => [
                "header"  => "Content-Type: application/json\r\n",
                "method"  => "PUT",
                "content" => json_encode($dados)
            ]
        ];

        // Cria o contexto de stream.
        $context = stream_context_create($options);
        // Faz a requisição.
        $result = file_get_contents($url, false, $context);
        
       if ($result === FALSE) {
            return ["erro" => "Falha na requisição PATCH"];
        }

        return json_decode($result, true);
    }

    // read
    public function read(){
        // Endpoint da API Node.js para listar exames (GET).
        // Ajuste esta URL se seu endpoint for diferente (ex: http://localhost:3000/api/exames)
        $url = "http://localhost:3000/exames";
        $result = @file_get_contents($url); // Use @ para suprimir o aviso temporariamente

        // Lida com a falha na requisição.
        if ($result === FALSE) {
            error_log("Falha ao buscar dados de exames em " . $url);
            return array(); // Retorna um array vazio.
        }

        $examesList = array();
        $lista = json_decode($result, true); // Decodifica a resposta JSON.

        // Lida com erros de decodificação JSON.
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Erro ao decodificar JSON de exames: " . json_last_error_msg());
            return array();
        }

        // Garante que a resposta seja um array.
        if (!is_array($lista)) {
            error_log("Esperava um array de exames de " . $url . ", mas recebeu outra coisa.");
            return array();
        }

        // Itera sobre a lista de exames e popula objetos ResultadoExames.
         foreach ($lista as $exameArray):
            $examesList[] = $this->listaExames($exameArray);
        endforeach;
        return $examesList;
    }
    
        public function listaExames($row){
        $exame = new ResultadoExames();
        // CUIDADO AQUI: O seu modelo usa setIdExame, não setId.
        $exame->setIdExame($row['id_exame'] ?? null); // Mude para setIdExame
        $exame->setLaudoId($row['laudo_id'] ?? null); // Use setLaudoId (novo no modelo)
        $exame->setNomeExame($row['nome_exame'] ?? null);
        $exame->setTipoExame($row['tipo_exame'] ?? null);
        $exame->setValorAbsoluto($row['valor_absoluto'] ?? null);
        $exame->setValorReferencia($row['valor_referencia'] ?? null);
        $exame->setPacienteIdFk($row['paciente_id_fk'] ?? null); 
        $exame->setDataHora($row['data_hora_exame'] ?? null); // Mude para setDataHora
        // Se `paciente_id_fk` vier da API Node e for um campo no seu modelo ResultadoExames, adicione:
        // $exame->setPacienteIdFk($row['paciente_id_fk'] ?? null); 
        return $exame;
    } 

    //   Busca um resultado de exame específico por ID através da API Node.js.
    public function buscarPorId($id){
        // Endpoint da API Node.js para buscar exame por ID (GET).
        // Ajuste esta URL se seu endpoint for diferente (ex: http://localhost:3000/api/exames/)
        $url = "http://localhost:3000/exames/" . urlencode($id); 
        
        try {
            // Faz a requisição GET, suprimindo avisos.
            $response = @file_get_contents($url);
            
            // Se a resposta for falsa (erro na requisição ou 404/500 da API).
            if ($response === FALSE){
                error_log("Erro ao buscar exame por ID: " . $id);
                return null; // ID não encontrado ou erro na requisição.
            }
            
            // Decodifica a resposta JSON.
            $data = json_decode($response, true);
            
            // Se os dados foram decodificados com sucesso.
            if ($data){
                return $this->listaExames($data); // Popula e retorna o objeto.
            }
            return null;
        } catch (Exception $e) {
            echo "<p>Erro ao buscar pessoa por ID: </p> <p>{$e->getMessage()}</p>";
            return null;
        }
    }

    // Exclui um resultado de exame através da API Node.js.
    public function excluir($id){
        // Endpoint da API Node.js para exclusão de exames (DELETE).
        // Ajuste esta URL se seu endpoint for diferente (ex: http://localhost:3000/api/exames/)
        $url = "http://localhost:3000/exames/" . urlencode($id);

        // Opções para a requisição HTTP (método DELETE).
        $options = [
            "http" => [
                "header"  => "Content-Type: application/json\r\n",
                "method"  => "DELETE"
            ]
        ];

        // Cria o contexto de stream.
        $context = stream_context_create($options);
        // Faz a requisição.
        $result = @file_get_contents($url, false, $context);
        
        // Verifica se a requisição falhou.
        if ($result === FALSE) {
            error_log("Erro ao excluir: " . $url);
            return ["erro" => "Erro ao excluir exame."];
        }

        // Decodifica a resposta JSON.
        return json_decode($result, true);
    }
}
?>
