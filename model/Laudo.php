<?php

class Laudo {
    private $id;
    private $solicitacao_id;
    private $responsavel_tecnico;
    private $data_finalizacao;
    private $observacoes;

    // --- Getters ---
    public function getId() {
        return $this->id;
    }

    public function getSolicitacaoId() {
        return $this->solicitacao_id;
    }

    public function getResponsavelTecnico() {
        return $this->responsavel_tecnico;
    }

    public function getDataFinalizacao() {
        return $this->data_finalizacao;
    }

    public function getObservacoes() {
        return $this->observacoes;
    }

    // --- Setters ---
    public function setId($id) {
        $this->id = $id;
    }

    public function setSolicitacaoId($solicitacao_id) {
        $this->solicitacao_id = $solicitacao_id;
    }

    public function setResponsavelTecnico($responsavel_tecnico) {
        $this->responsavel_tecnico = $responsavel_tecnico;
    }

    public function setDataFinalizacao($data_finalizacao) {
        $this->data_finalizacao = $data_finalizacao;
    }
    
    public function setObservacoes($observacoes) {
        $this->observacoes = $observacoes;
    }
}