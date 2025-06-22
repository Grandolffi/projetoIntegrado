<?php

class SolicitacaoExameItem {
    private $id;
    private $solicitacao_id;
    private $nome_exame;
    private $tipo_exame_categoria;
    private $valor_referencia_solicitacao;
    private $status_item;

    // --- Getters ---
    public function getId() {
        return $this->id;
    }

    public function getSolicitacaoId() {
        return $this->solicitacao_id;
    }

    public function getNomeExame() {
        return $this->nome_exame;
    }

    public function getTipoExameCategoria() {
        return $this->tipo_exame_categoria;
    }

    public function getValorReferenciaSolicitacao() {
        return $this->valor_referencia_solicitacao;
    }

    public function getStatusItem() {
        return $this->status_item;
    }

    // --- Setters ---
    public function setId($id) {
        $this->id = $id;
    }

    public function setSolicitacaoId($solicitacao_id) {
        $this->solicitacao_id = $solicitacao_id;
    }

    public function setNomeExame($nome_exame) {
        $this->nome_exame = $nome_exame;
    }

    public function setTipoExameCategoria($tipo_exame_categoria) {
        $this->tipo_exame_categoria = $tipo_exame_categoria;
    }

    public function setValorReferenciaSolicitacao($valor_referencia_solicitacao) {
        $this->valor_referencia_solicitacao = $valor_referencia_solicitacao;
    }

    public function setStatusItem($status_item) {
        $this->status_item = $status_item;
    }

    public function __toString() {
        return "Item ID: {$this->id}, Exame: {$this->nome_exame}, Categoria: {$this->tipo_exame_categoria}";
    }
}
?>