<?php

class ExameDao {

    public function inserir(ResultadoExames $exame) {
        try {
            $sql = "INSERT INTO resultados_exames (nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame) 
                    VALUES (:nome_exame, :tipo_exame, :valor_absoluto, :valor_referencia, :paciente_registro, :data_hora_exame)";

            $con_sql = ConnectionFactory::getConnection()->prepare($sql);

            $con_sql->bindValue(":nome_exame", $exame->getNomeExame());
            $con_sql->bindValue(":tipo_exame", $exame->getTipoExame());
            $con_sql->bindValue(":valor_absoluto", $exame->getValorAbsoluto());
            $con_sql->bindValue(":valor_referencia", $exame->getValorReferencia()); // O valor de referência será definido no controller
            $con_sql->bindValue(":paciente_registro", $exame->getPaciente());
            $con_sql->bindValue(":data_hora_exame", $exame->getDataHora());

            $con_sql->execute();
            echo "<p>Resultado de exame ({$exame->getNomeExame()}) cadastrado com sucesso!</p>";
            return true;
        } catch (PDOException $ex) {
            echo "<p>Erro ao inserir resultado de exame ({$exame->getNomeExame()}) no banco de dados: " . $ex->getMessage() . "</p>";
            return false;
        }
    }

    // NOVO MÉTODO: Para buscar todos os exames
    public function getAll() {
        try {
            $sql = "SELECT * FROM resultados_exames ORDER BY data_hora_exame DESC, paciente_registro ASC";
            $con_sql = ConnectionFactory::getConnection()->prepare($sql);
            $con_sql->execute();
            return $con_sql->fetchAll(PDO::FETCH_ASSOC); // Retorna todos os resultados como um array associativo
        } catch (PDOException $ex) {
            echo "<p>Erro ao buscar resultados de exames no banco de dados: " . $ex->getMessage() . "</p>";
            return []; // Retorna um array vazio em caso de erro
        }
    }
}
?>