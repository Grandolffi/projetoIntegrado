<?php

class Solicitacao {
    private $id_solicitacao;
    private $paciente_id;
    private $data_solicitacao;
    private $data_prevista_realizacao;
    private $solicitante_nome;
    private $status;
    private $observacoes;
    private $exames_itens; // Para armazenar um array de objetos SolicitacaoExameItem

    // --- Getters ---
    public function getIdSolicitacao() {
        return $this->id_solicitacao;
    }

    public function getPacienteId() {
        return $this->paciente_id;
    }

    public function getDataSolicitacao() {
        return $this->data_solicitacao;
    }

    public function getDataPrevistaRealizacao() {
        return $this->data_prevista_realizacao;
    }

    public function getSolicitanteNome() {
        return $this->solicitante_nome;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getObservacoes() {
        return $this->observacoes;
    }

    public function getExamesItens() {
        return $this->exames_itens;
    }

    // --- Setters ---
    public function setIdSolicitacao($id_solicitacao) {
        $this->id_solicitacao = $id_solicitacao;
    }

    public function setPacienteId($paciente_id) {
        $this->paciente_id = $paciente_id;
    }

    public function setDataSolicitacao($data_solicitacao) {
        $this->data_solicitacao = $data_solicitacao;
    }

    public function setDataPrevistaRealizacao($data_prevista_realizacao) {
        $this->data_prevista_realizacao = $data_prevista_realizacao;
    }

    public function setSolicitanteNome($solicitante_nome) {
        $this->solicitante_nome = $solicitante_nome;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setObservacoes($observacoes) {
        $this->observacoes = $observacoes;
    }

    public function setExamesItens(array $exames_itens) {
        $this->exames_itens = $exames_itens;
    }

    public function __toString() {
        return "Solicitação ID: {$this->id_solicitacao}, Paciente: {$this->paciente_id}, Status: {$this->status}";
    }
}
?>