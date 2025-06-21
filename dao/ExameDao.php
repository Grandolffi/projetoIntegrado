<?php

class ExameDao {

    // Insere um novo resultado de exame diretamente no banco de dados (PostgreSQL).
    public function inserir(ResultadoExames $exame) {
        try {
            // string sql recebe um comando sql
            $sql = "INSERT INTO resultados_exames(laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_id_fk, data_hora_exame) 
                    VALUES (:laudo_id, :nome_exame, :tipo_exame, :valor_absoluto, :valor_referencia, :paciente_id_fk, :data_hora_exame)";
            
            // Prepara a consulta usando a conexão PDO (que deve ser PDO_PGSQL).
            $con_sql = ConnectionFactory::getConnection()->prepare($sql);
            
            // Vincula os valores do objeto ResultadoExames aos parâmetros da consulta.
            $con_sql->bindValue(":laudo_id", $exame->getLaudoId());
            $con_sql->bindValue(":nome_exame", $exame->getNomeExame());
            $con_sql->bindValue(":tipo_exame", $exame->getTipoExame());
            $con_sql->bindValue(":valor_absoluto", $exame->getValorAbsoluto());
            $con_sql->bindValue(":valor_referencia", $exame->getValorReferencia());
            $con_sql->bindValue(":paciente_id_fk", $exame->getPacienteRegistro()); // Mapeado de getPacienteRegistro() para paciente_id_fk
            $con_sql->bindValue(":data_hora_exame", $exame->getDataHoraExame());
            
            echo "<p> Resultade de exame cadastrado com sucesso";
            // Executa a consulta.
            return $con_sql->execute();
        } catch(PDOException $ex) {
            // Em caso de erro, exibe uma mensagem e retorna null.
            echo "<p> Erro ao inserir resultado de exame no banco de dados: " . $ex->getMessage() . "</p>";
            return null;
        }
    }

    // read para ver os resultados de exames do banco
      public function read(){
        try {
            $sql = "SELECT * FROM resultados_exames";
            
            // Executa a consulta e obtém os resultados.
            $con_sql = ConnectionFactory::getConnection()->query($sql);
            $lista = $con_sql->fetchall(PDO::FETCH_ASSOC);
            $examesList = array();
            foreach($lista as $linha){
                $examesList[] = $this->listaExames($linha);
            }
            return $examesList;
        } catch(PDOException $ex) {
            echo "<p> Ocorreu um erro ao selecionar exames: " . $ex->getMessage() . "</p>";
            return array();
        }
    }

    // Usado internamente por métodos como read() e buscarPorId().
    public function listaExames($linha){
        $exame = new ResultadoExames();
        $exame->setId($linha['id_exame'] ?? null); // Usar 'id_exame'
        $exame->setLaudoId($linha['laudo_id'] ?? null);
        $exame->setNomeExame($linha['nome_exame'] ?? null);
        $exame->setTipoExame($linha['tipo_exame'] ?? null);
        $exame->setValorAbsoluto($linha['valor_absoluto'] ?? null);
        $exame->setValorReferencia($linha['valor_referencia'] ?? null);
        $exame->setPacienteRegistro($linha['paciente_id_fk'] ?? null); // Usar 'paciente_id_fk'
        $exame->setDataHoraExame($linha['data_hora_exame'] ?? null);
        return $exame;
    } 

   // Edita um resultado de exame existente diretamente no banco de dados (PostgreSQL).
    public function editar(ResultadoExames $exame) {
        try {
            $sql = "UPDATE resultados_exames SET 
                        laudo_id = :laudo_id, nome_exame = :nome_exame, tipo_exame = :tipo_exame, valor_absoluto = :valor_absoluto, 
                        valor_referencia = :valor_referencia, paciente_id_fk = :paciente_id_fk, data_hora_exame = :data_hora_exame 
                    WHERE id_exame = :id_exame";
            
            // Prepara a consulta.
            $conn = ConnectionFactory::getConnection()->prepare($sql);
            
            // Vincula os valores.
            $conn->bindValue(":laudo_id", $exame->getLaudoId());
            $conn->bindValue(":nome_exame", $exame->getNomeExame());
            $conn->bindValue(":tipo_exame", $exame->getTipoExame());
            $conn->bindValue(":valor_absoluto", $exame->getValorAbsoluto());
            $conn->bindValue(":valor_referencia", $exame->getValorReferencia());
            $conn->bindValue(":paciente_id_fk", $exame->getPacienteRegistro()); // Mapeado de getPacienteRegistro() para paciente_id_fk
            $conn->bindValue(":data_hora_exame", $exame->getDataHoraExame());
            $conn->bindValue(":id_exame", $exame->getId()); // ID do exame a ser editado
            
            // Executa o update.
            return $conn->execute(); 
        } catch(PDOException $ex) {
            // Em caso de erro, exibe uma mensagem.
            echo "<p> Erro ao editar exame: " . $ex->getMessage() . "</p>";
            return false;
        }
    }

    // Busca um resultado de exame específico por ID diretamente no banco de dados (PostgreSQL).
    public function buscarPorId($id){
        try {
            $sql = "SELECT * FROM resultados_exames WHERE id_exame = :id_exame";
            
            // Prepara a consulta.
            $conn = ConnectionFactory::getConnection()->prepare($sql);
            $conn->bindValue(":id_exame", $id); // Vincula ao 'id_exame'
            $conn->execute();
            
            // Obtém a linha de resultado.
            $row = $conn->fetch(PDO::FETCH_ASSOC);
            
            // Se encontrou a linha, popula e retorna o objeto.
            if($row){
                return $this->listaExames($row);
            }
            return null; 
        } catch (PDOException $e) {
            // Em caso de erro, exibe mensagem e retorna null.
            echo "<p>Erro ao buscar exame por ID {$id}: " . $e->getMessage() . "</p>";
            return null;
        }
    }

    // Exclui um resultado de exame diretamente do banco de dados (PostgreSQL).
    public function excluir($id){
        try {
            $sql = "DELETE FROM resultados_exames WHERE id_exame = :id_exame";
            
            // Prepara a consulta.
            $conn = ConnectionFactory::getConnection()->prepare($sql);
            $conn->bindValue(":id_exame", $id); // Vincula ao 'id_exame'
            
            // Executa a exclusão.
            return $conn->execute();
        } catch(PDOException $ex) {
            // Em caso de erro, exibe mensagem e retorna false.
            echo "<p>Erro ao excluir exame: " . $ex->getMessage() . "</p>";
            return false;
        }
    }
}
?>