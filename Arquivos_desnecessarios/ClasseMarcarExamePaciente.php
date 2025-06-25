<?php

class examePaciente{

    private $nomePaciente;
    private $idExame;
    private $tipoExame;
    private $dataHora;

    public function getNomePaciente(){
        return $this -> nomePaciente;
    }

    public function setNomePaciente($nomePaciente){
        $this->nomePaciente = $nomePaciente;
    }

    public function getIdExame(){
        return $this -> idExame;
    }

    public function setIdExame($idExame){
        $this->idExame = $idExame;
    }

    public function getTipoExame(){
        return $this -> tipoExame;
    }

    public function setTipoExame($tipoExame){
        $this->tipoExame = $tipoExame;
    }

    public function getDataHora(){
        return $this -> dataHora;
    }

    public function setDataHora($dataHora){
        $this->dataHora = $dataHora;
    }

    public function validaId($idExame){

    }


}

?>