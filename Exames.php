<?php

class Exames{
    private $nomeExame;
    private $tipoExame;
    private $valorAbsoluto;
    private $valorReferencia;
    private $paciente;
    private $dataHora;

    public function getNomeExame(){
        return $this->getNomeExame;
    }

    public function setNomeExame($nomeExame){
        $this->omeExame = $nomeExame;
    }

    public function getTipoExame(){
        return $this->getTipoExame;
    }

    public function setTipoExame($tipoExame){
        $this->tipoExame= $tipoExame;
    }

    public function getValorAbsoluto(){
        return $this->getValorAbsoluto;
    }

    public function setValorAbsoluto($valorAbsoluto){
        $this->valorAbsoluto= $valorAbsoluto;
    }

    public function getPaciente(){
        return $this->getPaciente;
    }

    public function setPaciente($paciente){
        $this->paciente= $paciente;
    }

    public function getDataHora(){
        return $this->getDataHora;
    }

    public function setDataHora($dataHora){
        $this->dataHora= $dataHora;
    }

    public function __toString(){
        return "Exame - Nome: {$this->nomeExaame}, tipo de exame: {$this->tipoExame}, Valor Absoluto: {$this->valorAbsoluto}, 
        Valor de referência: {$this->valorReferencia}, Paciente: {$this->paciente}, Data e hora{$this->DataHora}";
    }
}
?>