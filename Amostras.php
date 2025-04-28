<?php
class Amostras{

    private $hematologia; //hemograma, leucograma, plaquetas
    
    private $parasitologia; //epf, sangue oculto
    
    private $microbilogoia; //urina, escarro, antibiograma
    
    private $urinalise; //exame macroscopico, exame quimico, exame microscopico

    private $tipoDeColetaRealizada; //aberto, fechado

    private $tubosUtilizados; //lote, validade

    private $coresTubos; //cores do tubos utilizados - deve ser uma checkbox em que sera possivel escolher diversas cores de tubos

    private $seringa; //tamanho, lote, validade

    private $potesUrina; //lote, validade
    
    private $potesFezes; //lote, validade

    //Getters and Setters

    public function getHematologia(){
        return $this->hematologia;
    }

    public function setHematolgoia($hematologia){
        $this->hematologia = $hematologia;
    }


    public function getParasitologia(){
        return $this->parasitologia;
    }
    
    public function setParasitologia($parasitologia){
        $this->parasitologia = $parasitologia;
    }
   
    public function getMicrobiologia(){
        return $this->microbiologia;
    }
    
    public function setMicrobiologia($microbiologia){
        $this->microbiologia = $microbiologia;
    }
   
    public function getUrinalise(){
        return $this->urinalise;
    }
    
    public function setUrinalise($urinalise){
        $this->urinalise = $urinalise;
    }

    public function getTipoDeColetaRealizada(){
        return $this->tipoDeColetaRealizada;
    }
    
    public function setTipoDeColetaRealizada($tipoDeColetaRealizada){
        $this->tipoDeColetaRealizada = $tipoDeColetaRealizada;
    }

    public function getTubosUtilizados(){
        return $this->tubosUtilizados;
    }
    
    public function setTubosUtilizados($tubosUtilizados){
        $this->tubosUtilizados = $tubosUtilizados;
    }

    public function getCoresTubos(){
        return $this->coresTubos;
    }
    
    public function setCoresTubos($coresTubos){
        $this->coresTubos = $coresTubos;
    }

    public function getSeringa(){
        return $this->seringa;
    }
    
    public function setSeringa($seringa){
        $this->seringa = $seringa;
    }

    public function getPotesUrina(){
        return $this->potesUrina;
    }
    
    public function setPotesUrina($potesUrina){
        $this->potesUrina = $potesUrina;
    }

    public function getPotesFezes(){
        return $this->potesFezes;
    }
    
    public function setPotesFezes($potesFezes){
        $this->potesFezes = $potesFezes;
    }
   
}
?>