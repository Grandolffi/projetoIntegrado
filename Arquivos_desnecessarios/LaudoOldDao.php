<?php
require_once __DIR__ . '/ConnectionFactory.php';
require_once __DIR__ . '/../model/Laudo.php';
require_once __DIR__ . '/../model/ResultadoExames.php';
require_once __DIR__ . '/ExameDao.php'; // Incluir ExameDao para inserir exames associados

class LaudoDao {
    private $connection;

    public function __construct() {
        $this->connection = ConnectionFactory::getConnection();
    }

    // Insere um novo Laudo e seus Resultados de Exames associados
    public function inserir(Laudo $laudo, array $resultadosExames = []) {
        $this->connection->beginTransaction(); // Inicia uma transação para garantir atomicidade

        try {
            // Insere o Laudo principal
            $sqlLaudo = "INSERT INTO laudos (solicitacao_id, paciente_id, responsavel_tecnico, observacoes, data_finalizacao) 
                         VALUES (:solicitacao_id, :paciente_id, :responsavel_tecnico, :observacoes, :data_finalizacao)";
            $stmtLaudo = $this->connection->prepare($sqlLaudo);
            $stmtLaudo->bindValue(":solicitacao_id", $laudo->getSolicitacaoId());
            $stmtLaudo->bindValue(":paciente_id", $laudo->getPacienteId());
            $stmtLaudo->bindValue(":responsavel_tecnico", $laudo->getResponsavelTecnico());
            $stmtLaudo->bindValue(":observacoes", $laudo->getObservacoes());
            $stmtLaudo->bindValue(":data_finalizacao", $laudo->getDataFinalizacao());
            $stmtLaudo->execute();
            
            $laudoId = $this->connection->lastInsertId('laudos_id_laudo_seq'); // Para PostgreSQL, especifique a sequência

            // Insere os Resultados de Exames associados
            if (!empty($resultadosExames)) {
                $exameDao = new ExameDao(); // Instancia ExameDao para inserir os exames
                foreach ($resultadosExames as $exameData) {
                    $exame = new ResultadoExames();
                    $exame->setLaudoId($laudoId); // Associa o exame ao ID do laudo recém-criado
                    $exame->setNomeExame($exameData['nomeExame'] ?? null);
                    $exame->setTipoExame($exameData['tipoExame'] ?? null);
                    $exame->setValorAbsoluto($exameData['valorAbsoluto'] ?? null);
                    $exame->setValorReferencia($exameData['valorReferencia'] ?? null);
                    $exame->setPacienteRegistro($laudo->getPacienteId()); // Assume que o paciente é o mesmo do laudo
                    $exame->setDataHoraExame($exameData['dataHoraExame'] ?? date('Y-m-d H:i:s')); // Data/hora do exame
                    
                    $exameDao->inserir($exame); // Chama o método de inserção do ExameDao
                }
            }

            $this->connection->commit(); // Confirma a transação se tudo deu certo
            return true;
        } catch (PDOException $e) {
            $this->connection->rollback(); // Reverte a transação em caso de erro
            error_log("Erro ao inserir Laudo e Exames: " . $e->getMessage());
            throw $e; // Relança a exceção para ser capturada no Controller
        }
    }

    // read (lista) todos os Laudos diretamente do banco de dados.
    public function read() {
        try {
            $sql = "SELECT * FROM laudos";
            $stmt = $this->connection->query($sql);
            $lista = $stmt->fetchall(PDO::FETCH_ASSOC);
            $laudosList = [];
            foreach ($lista as $linha) {
                $laudo = new Laudo();
                $laudo->setId($linha['id_laudo']);
                $laudo->setSolicitacaoId($linha['solicitacao_id']);
                $laudo->setPacienteId($linha['paciente_id']);
                $laudo->setResponsavelTecnico($linha['responsavel_tecnico']);
                $laudo->setObservacoes($linha['observacoes']);
                $laudo->setDataFinalizacao($linha['data_finalizacao']);
                $laudosList[] = $laudo;
            }
            return $laudosList;
        } catch(PDOException $ex) {
            error_log("Ocorreu um erro ao selecionar Laudos: " . $ex->getMessage());
            return array();
        }
    }

    // read - Busca um Laudo específico por ID diretamente no banco de dados.
    public function buscarPorId($id) {
        try {
            $sql = "SELECT * FROM laudos WHERE id_laudo = :id_laudo";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(":id_laudo", $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row) {
                $laudo = new Laudo();
                $laudo->setId($row['id_laudo']);
                $laudo->setSolicitacaoId($row['solicitacao_id']);
                $laudo->setPacienteId($row['paciente_id']);
                $laudo->setResponsavelTecnico($row['responsavel_tecnico']);
                $laudo->setObservacoes($row['observacoes']);
                $laudo->setDataFinalizacao($row['data_finalizacao']);
                return $laudo;
            }
            return null;
        } catch (PDOException $e) {
            error_log("Erro ao buscar Laudo por ID {$id}: " . $e->getMessage());
            return null;
        }
    }

    // Edita um Laudo existente diretamente no banco de dados.
    public function editar(Laudo $laudo) {
        try {
            $sql = "UPDATE laudos SET 
                        solicitacao_id = :solicitacao_id, 
                        paciente_id = :paciente_id, 
                        responsavel_tecnico = :responsavel_tecnico, 
                        observacoes = :observacoes, 
                        data_finalizacao = :data_finalizacao 
                    WHERE id_laudo = :id_laudo";
            
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(":solicitacao_id", $laudo->getSolicitacaoId());
            $stmt->bindValue(":paciente_id", $laudo->getPacienteId());
            $stmt->bindValue(":responsavel_tecnico", $laudo->getResponsavelTecnico());
            $stmt->bindValue(":observacoes", $laudo->getObservacoes());
            $stmt->bindValue(":data_finalizacao", $laudo->getDataFinalizacao());
            $stmt->bindValue(":id_laudo", $laudo->getId());
            
            return $stmt->execute();
        } catch(PDOException $ex) {
            error_log("Erro ao editar Laudo: " . $ex->getMessage());
            return false;
        }
    }

    // Exclui um Laudo e seus Resultados de Exames associados diretamente do banco de dados.
    public function excluir($id) {
        $this->connection->beginTransaction();
        try {
            // Primeiro, exclui todos os exames associados a este laudo
            $sqlDeleteExames = "DELETE FROM resultados_exames WHERE laudo_id = :laudo_id";
            $stmtDeleteExames = $this->connection->prepare($sqlDeleteExames);
            $stmtDeleteExames->bindValue(":laudo_id", $id);
            $stmtDeleteExames->execute();

            // Depois, exclui o Laudo principal
            $sqlDeleteLaudo = "DELETE FROM laudos WHERE id_laudo = :id_laudo";
            $stmtDeleteLaudo = $this->connection->prepare($sqlDeleteLaudo);
            $stmtDeleteLaudo->bindValue(":id_laudo", $id);
            $stmtDeleteLaudo->execute();

            $this->connection->commit();
            return true;
        } catch(PDOException $ex) {
            $this->connection->rollback();
            error_log("Erro ao excluir Laudo e Exames associados: " . $ex->getMessage());
            return false;
        }
    }
}
?>
