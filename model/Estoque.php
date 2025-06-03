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
        return $this->NomeMaterial;
    }

    public function setNomeMaterial($nomeMaterial){
        $this->NomeMaterial = $nomeMaterial;
    }

    public function getDescricao(){
        return $this->descricao;
    }

    public function setDescricao($descricao){
        $this->descricao = $descricao;
    }
}
?>