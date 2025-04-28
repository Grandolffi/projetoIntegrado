<?php

class Pessoa{
    private $nomeCompleto;
    private $cpf;
    private $datNasc;
    private $numTel;
    private $genero;


    public function getNomeCompleto(){
        return $this->nomeCompleto;
    }

    public function setNomeCompleto($nomeCompleto){
        $this->nomeCompleto = $nomeCompleto;
    }

    public function getCpf(){
        return $this->cpf;
    }

    public function setCpf($cpf){
        $this->cpf = $cpf;
    }


    public function getDatNasc(){
        return $this->datNasc;
    }

    public function setDatNasc($datNasc){
        $this->datNasc = $datNasc;
    }


    public function getNumTel(){
        return $this->numTel;
    }

    public function setNumTel($numTel){
        $this->numTel = $numTel;
    }


    public function getGenero(){
        return $this->genero;
    }

    public function setGenero($genero){
        $this->genero = $genero;
    }

    public function __toString(){
        return "Pessoa - nome: {$this->nomeCompleto}, cpf: {$this->cpf}, data Nascimento: {$this->datNasc}, 
        numero Telefone: {$this->numTel}, genero: {$this->genero}";
    }
<<<<<<< Updated upstream
    
=======


>>>>>>> Stashed changes
}

?>