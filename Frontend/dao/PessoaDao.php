<?php

class PessoaDao{

    //funcao(create)
    public function inserir(Pessoa $pessoa){
        $url = "http://localhost:3000/pacientes"; // "BARRA" VIA POST INSERE
        $dados = [
            //"id" => $fab->getId(),
            "nome" => $pessoa->getNome(),
            "cpf" => $pessoa->getCpf(),
            "dtnasc" => $pessoa->getDtnasc(),
            "email" => $pessoa->getEmail(),
            "nomeMae" => $pessoa->getNomeMae(),
            "numCelular" => $pessoa->getnumCelular(),
            "genero" => $pessoa->getGenero()
        ];

        $options = [
            "http" => [
                "header"  => "Content-Type: application/json\r\n",
                "method"  => "POST",
                "content" => json_encode($dados)
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result ? json_decode($result, true) : false;
    }

    //(update)
    public function editar(Pessoa $pessoa){
        $url = "http://localhost:3000/pacientes/".$pessoa->getId();
        $dados = [
            "nome" => $pessoa->getNome(),
            "cpf" => $pessoa->getCpf(),
            "dtnasc" => $pessoa->getDtnasc(),
            "email" => $pessoa->getEmail(),
            "nomeMae" => $pessoa->getNomeMae(),
            "numCelular" => $pessoa->getnumCelular(),
            "genero" => $pessoa->getGenero()
        ];

        $options = [
            "http" => [
                "header"  => "Content-Type: application/json\r\n",
                "method"  => "PUT",
                "content" => json_encode($dados)
                
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        if ($result === FALSE) {
            return ["erro" => "Falha na requisição PATCH"];
        }

        return json_decode($result, true);
    }

    public function read(){
    $url = "http://localhost:3000/pacientes";
    $result = @file_get_contents($url); // Use @ para suprimir o aviso temporariamente

    if ($result === FALSE) {
        // Lidar com o erro - registre-o, exiba uma mensagem amigável ao usuário, etc.
        error_log("Falha ao buscar dados de " . $url);
        return array(); // Retorna um array vazio ou lida de forma apropriada
    }

    $pesList = array();
    $lista = json_decode($result, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("Erro ao decodificar JSON: " . json_last_error_msg());
        return array();
    }

    if (!is_array($lista)) {
        error_log("Esperava um array de " . $url . ", mas recebeu outra coisa.");
        return array();
    }

    foreach ($lista as $pes):
        $pesList[] = $this->listaPessoas($pes);
    endforeach;
    return $pesList;
}
    
     public function listaPessoas($row){
        $pessoa = new Pessoa();
        $pessoa->setId(htmlspecialchars($row['id']));
        $pessoa->setNome(htmlspecialchars($row['nome']));
        $pessoa->setCpf(htmlspecialchars($row['cpf']));
        $pessoa->setDtnasc(htmlspecialchars($row['dtnasc']));
        $pessoa->setEmail(htmlspecialchars($row['email']));
        $pessoa->setNomeMae(htmlspecialchars($row['nomemae']));
        $pessoa->setnumCelular(htmlspecialchars($row['numcelular']));
        $pessoa->setGenero(htmlspecialchars($row['genero']));
        return $pessoa;
    } 

    
    public function buscaPorId($id){
        $url = "http://localhost:3000/pacientes/" . urlencode($id);
        try {
            // @file_get_contents() para evitar warnings automáticos.
            $response = @file_get_contents($url);
            if ($response === FALSE) {
                return null; // ID não encontrado ou erro na requisição
            }
            $data = json_decode($response, true);
            if ($data) {
                return $this->listaPessoas($data);
            }
            return null;
        } catch (Exception $e) {
            echo "<p>Erro ao buscar pessoa por ID: </p> <p>{$e->getMessage()}</p>";
            return null;
        }
    }


    public function excluir($id){
        $url = "http://localhost:3000/pacientes/" . urlencode($id);

        $options = [
            "http" => [
                "header"  => "Content-Type: application/json\r\n",
                "method"  => "DELETE"
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        if ($result === FALSE) {
            return ["erro" => "Erro ao excluir"];
        }

        return json_decode($result, true);
    }

}


?>