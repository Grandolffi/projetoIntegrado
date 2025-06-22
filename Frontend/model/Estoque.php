<?php
class Estoque{
    private $idEstoque;
    private $nomeMaterial;
    private $descricao;

    public function getIdEstoque(){
        return $this->idEstoque;
    }

    public function setIdEstoque($idEstoque){
        $this->idEstoque = $idEstoque;
    }
    public function getNomeMaterial(){
        return $this->nomeMaterial;
    }

    public function setNomeMaterial($nomeMaterial){
        $this->nomeMaterial = $nomeMaterial;
    }

    public function getDescricao(){
        return $this->descricao;
    }

    public function setDescricao($descricao){
        $this->descricao = $descricao;
    }
}
?>