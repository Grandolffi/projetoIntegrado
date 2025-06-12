<?php

class LaudoDao {

    /**
     * Insere o registro principal do laudo e retorna seu ID.
     */
    public function inserirLaudo($dbConnection, $solicitacao_id, $responsavel_tecnico, $observacoes = null) {
        $sql = "INSERT INTO laudos (solicitacao_id, responsavel_tecnico, observacoes) 
                VALUES (:sol_id, :resp_tec, :obs)";
        
        $stmt = $dbConnection->prepare($sql);
        $stmt->bindValue(':sol_id', $solicitacao_id);
        $stmt->bindValue(':resp_tec', $responsavel_tecnico);
        $stmt->bindValue(':obs', $observacoes);
        $stmt->execute();
        
        return $dbConnection->lastInsertId();
    }

    /**
     * Atualiza o status da solicitação original para 'Concluído'.
     */
    public function finalizarSolicitacao($dbConnection, $solicitacao_id) {
        $sql = "UPDATE solicitacoes SET status = 'Concluído' WHERE id = :id";
        
        $stmt = $dbConnection->prepare($sql);
        $stmt->bindValue(':id', $solicitacao_id);
        
        return $stmt->execute();
    }
}