<?php

class Pessoa{
    private $id;
    private $nome;
    private $cpf;
    private $dataNasc;
    private $email;
    private $nomeMae;
    private $numCelular;
    private $genero;

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getnome(){
        return $this->nome;
    }

    public function setnome($nome){
        $this->nome = $nome;
    }

    public function getCpf(){
        return $this->cpf;
    }

    public function setCpf($cpf){
        $this->cpf = $cpf;
    }


    public function getDataNasc(){
        return $this->dataNasc;
    }

    public function setDataNasc($dataNasc){
        $this->dataNasc = $dataNasc;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function getNomeMae(){
        return $this->nomeMae;
    }

    public function setNomeMae($nomeMae){
        $this->nomeMae = $nomeMae;
    }

    public function getnumCelular(){
        return $this->numCelular;
    }

    public function setnumCelular($numCelular){
        $this->numCelular = $numCelular;
    }


    public function getGenero(){
        return $this->genero;
    }

    public function setGenero($genero){
        $this->genero = $genero;
    }

    public function __toString(){
        return "Pessoa - nome: {$this->nome}, cpf: {$this->cpf}, data Nascimento: {$this->dataNasc}, 
        numero Telefone: {$this->numCelular}, genero: {$this->genero}";
    }
    
}

?>