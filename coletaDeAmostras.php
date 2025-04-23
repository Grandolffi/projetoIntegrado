<?php
class ColetaDeAmostras{

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
   
   
   
}
?>