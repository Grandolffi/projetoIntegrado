<?php
require_once 'ConnectionFactory.php';
require_once 'Amostras.php';

class AmostrasDao {

    public function inserir(Amostras $amostras) {
        try {
            $sql = "INSERT INTO amostras (
                        hematologia, parasitologia, microbiologia, urinalise,
                        tipoDeColetaRealizada, tubosUtilizados, coresTubos,
                        seringa, potesUrina, potesFezes
                    ) VALUES (
                        :hematologia, :parasitologia, :microbiologia, :urinalise,
                        :tipoDeColetaRealizada, :tubosUtilizados, :coresTubos,
                        :seringa, :potesUrina, :potesFezes
                    )";

            $conn = ConnectionFactory::getConnection()->prepare($sql);
            $dados = $amostras->toArray();

            foreach ($dados as $campo => $valor) {
                $conn->bindValue(":$campo", $valor);
            }

            $conn->execute();
            echo "<p>Amostras inseridas com sucesso!</p>";
            return true;

        } catch (PDOException $ex) {
            echo "<p>Erro ao inserir amostras: " . $ex->getMessage() . "</p>";
            return false;
        }
    }

    public function buscarTodos() {
        try {
            $sql = "SELECT * FROM amostras";
            $stmt = ConnectionFactory::getConnection()->query($sql);
            $lista = [];

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $linha) {
                $lista[] = $this->mapearAmostras($linha);
            }

            echo "<p>Foram encontradas " . count($lista) . " amostras.</p>";
            return $lista;

        } catch (PDOException $ex) {
            echo "<p>Erro ao buscar amostras: " . $ex->getMessage() . "</p>";
            return [];
        }
    }

    public function buscarPorId($id) {
        try {
            $sql = "SELECT * FROM amostras WHERE id = :id";
            $stmt = ConnectionFactory::getConnection()->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->execute();

            $linha = $stmt->fetch(PDO::FETCH_ASSOC);
            return $linha ? $this->mapearAmostras($linha) : null;

        } catch (PDOException $ex) {
            echo "<p>Erro ao buscar amostras por ID: " . $ex->getMessage() . "</p>";
            return null;
        }
    }

    public function atualizar(Amostras $amostras, $id) {
        try {
            $sql = "UPDATE amostras SET 
                        hematologia = :hematologia,
                        parasitologia = :parasitologia,
                        microbiologia = :microbiologia,
                        urinalise = :urinalise,
                        tipoDeColetaRealizada = :tipoDeColetaRealizada,
                        tubosUtilizados = :tubosUtilizados,
                        coresTubos = :coresTubos,
                        seringa = :seringa,
                        potesUrina = :potesUrina,
                        potesFezes = :potesFezes
                    WHERE id = :id";

            $conn = ConnectionFactory::getConnection()->prepare($sql);
            $dados = $amostras->toArray();
            $dados['id'] = $id;

            foreach ($dados as $campo => $valor) {
                $conn->bindValue(":$campo", $valor);
            }

            $conn->execute();
            echo "<p>Amostras atualizadas com sucesso!</p>";
            return true;

        } catch (PDOException $ex) {
            echo "<p>Erro ao atualizar amostras: " . $ex->getMessage() . "</p>";
            return false;
        }
    }

    public function deletar($id) {
        try {
            $sql = "DELETE FROM amostras WHERE id = :id";
            $stmt = ConnectionFactory::getConnection()->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->execute();

            echo "<p>Amostra excluída com sucesso!</p>";
            return true;

        } catch (PDOException $ex) {
            echo "<p>Erro ao deletar amostra: " . $ex->getMessage() . "</p>";
            return false;
        }
    }

    private function mapearAmostras($linha) {
        $amostra = new Amostras();
        $amostra->setHematologia($linha['hematologia']);
        $amostra->setParasitologia($linha['parasitologia']);
        $amostra->setMicrobiologia($linha['microbiologia']);
        $amostra->setUrinalise($linha['urinalise']);
        $amostra->setTipoDeColetaRealizada($linha['tipoDeColetaRealizada']);
        $amostra->setTubosUtilizados($linha['tubosUtilizados']);
        $amostra->setCoresTubos(explode(',', $linha['coresTubos']));
        $amostra->setSeringa($linha['seringa']);
        $amostra->setPotesUrina($linha['potesUrina']);
        $amostra->setPotesFezes($linha['potesFezes']);
        return $amostra;
    }
}
?>
