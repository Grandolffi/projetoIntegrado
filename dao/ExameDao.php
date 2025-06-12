<?php

class ExameDao {

    /**
     * MODIFICADO: Agora aceita um segundo parâmetro, o $laudo_id.
     */
    public function inserir(ResultadoExames $exame, $laudo_id) { // <-- MUDANÇA 1: Novo parâmetro
        try {
            // MODIFICADO: Adiciona a coluna 'laudo_id' no INSERT
            $sql = "INSERT INTO resultados_exames (laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame) 
                    VALUES (:laudo_id, :nome_exame, :tipo_exame, :valor_absoluto, :valor_referencia, :paciente_registro, :data_hora_exame)"; // <-- MUDANÇA 2: Coluna no SQL

            $con_sql = ConnectionFactory::getConnection()->prepare($sql);

            // MODIFICADO: Adiciona o bindValue para o novo parâmetro
            $con_sql->bindValue(":laudo_id", $laudo_id); // <-- MUDANÇA 3: Novo bindValue
            
            // O resto dos bindValue continua igual
            $con_sql->bindValue(":nome_exame", $exame->getNomeExame());
            $con_sql->bindValue(":tipo_exame", $exame->getTipoExame());
            $con_sql->bindValue(":valor_absoluto", $exame->getValorAbsoluto());
            $con_sql->bindValue(":valor_referencia", $exame->getValorReferencia());
            $con_sql->bindValue(":paciente_registro", $exame->getPaciente());
            $con_sql->bindValue(":data_hora_exame", $exame->getDataHora());

            $con_sql->execute();
            return true;
        } catch (PDOException $ex) {
            // É melhor lançar a exceção para que o LaudoController possa capturá-la na transação.
            throw $ex;
        }
    }

    /**
     * Este método não muda. Continua como está.
     */
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