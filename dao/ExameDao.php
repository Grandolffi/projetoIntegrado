<?php

class ExameDao {

    public function inserir(Exames $exame) {
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
            echo "<p>Resultado de exame ({$exame->getNomeExame()}) cadastrado com sucesso!</p>"; // decidir se melhor tratar msgs no controller ou após redirect
            return true;
        } catch (PDOException $ex) {
            echo "<p>Erro ao inserir resultado de exame ({$exame->getNomeExame()}) no banco de dados: " . $ex->getMessage() . "</p>";
            return false;
        }
    }

    // Você pode adicionar outros métodos aqui (read, update, delete) conforme necessário
}
?>