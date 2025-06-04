<?php

class ResultadoExames{
    private $idExame;
    private $nomeExame;
    private $tipoExame;
    private $valorAbsoluto;
    private $valorReferencia;
    private $paciente;
    private $dataHora;

    public function getIdExame(){
        return $this->idExame;
    }

    public function setIdExame($idExame){
        $this->idExame = $idExame;
    }

    public function getNomeExame(){
        return $this->nomeExame;
    }

    public function setNomeExame($nomeExame){
        $this->nomeExame = $nomeExame;
    }

    public function getTipoExame(){
        return $this->tipoExame;
    }

    public function setTipoExame($tipoExame){
        $this->tipoExame= $tipoExame;
    }

    public function getValorAbsoluto(){
        return $this->valorAbsoluto;
    }

    public function setValorAbsoluto($valorAbsoluto){
        $this->valorAbsoluto= $valorAbsoluto;
    }

    public function getPaciente(){
        return $this->paciente;
    }

    public function setPaciente($paciente){
        $this->paciente= $paciente;
    }

    public function getDataHora(){
        return $this->dataHora;
    }

    public function setDataHora($dataHora){
        $this->dataHora= $dataHora;
    }

    public function getValorReferencia() {
        return $this->valorReferencia;
    }

    public function setValorReferencia($valorReferencia) {
        $this->valorReferencia = $valorReferencia;
    }


    // perguntar para a professora se o idExame precisa estar no toString
    public function __toString(){
        return "Exame - Nome: {$this->nomeExame}, tipo de exame: {$this->tipoExame}, Valor Absoluto: {$this->valorAbsoluto}, 
        Valor de referência: {$this->valorReferencia}, Paciente: {$this->paciente}, Data e hora{$this->dataHora}";
    }
}
?>