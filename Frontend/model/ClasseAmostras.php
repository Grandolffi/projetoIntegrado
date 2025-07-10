<?php

class Amostra {
    private $id;
    private $pacienteId;
    private $dataColeta;
    private $tipoColetaRealizada; // Corresponde ao checkbox 'tipo_coleta'
    
    // Propriedades para os materiais de coleta
    private $tubosUtilizados;     // Para as cores dos tubos (array ou string)
    private $loteTubo;
    private $validadeTubo;
    
    private $tamanhoSeringa;
    private $loteSeringa;
    private $validadeSeringa;

    private $lotePoteUrina;
    private $validadePoteUrina;

    private $lotePoteFezes;
    private $validadePoteFezes;
    
    // --- Getters ---

    public function getId() {
        return $this->id;
    }

    public function getPacienteId() {
        return $this->pacienteId;
    }

    public function getDataColeta() {
        return $this->dataColeta;
    }

    public function getTipoColetaRealizada() {
        return $this->tipoColetaRealizada;
    }

    public function getTubosUtilizados() {
        return $this->tubosUtilizados;
    }

    public function getLoteTubo() {
        return $this->loteTubo;
    }

    public function getValidadeTubo() {
        return $this->validadeTubo;
    }

    public function getTamanhoSeringa() {
        return $this->tamanhoSeringa;
    }

    public function getLoteSeringa() {
        return $this->loteSeringa;
    }

    public function getValidadeSeringa() {
        return $this->validadeSeringa;
    }

    public function getLotePoteUrina() {
        return $this->lotePoteUrina;
    }

    public function getValidadePoteUrina() {
        return $this->validadePoteUrina;
    }

    public function getLotePoteFezes() {
        return $this->lotePoteFezes;
    }

    public function getValidadePoteFezes() {
        return $this->validadePoteFezes;
    }

    // --- Setters ---

    public function setId($id) {
        $this->id = $id;
    }

    public function setPacienteId($pacienteId) {
        $this->pacienteId = $pacienteId;
    }

    public function setDataColeta($dataColeta) {
        $this->dataColeta = $dataColeta;
    }

    public function setTipoColetaRealizada($tipoColetaRealizada) {
        $this->tipoColetaRealizada = $tipoColetaRealizada;
    }

    public function setTubosUtilizados($tubosUtilizados) {
        $this->tubosUtilizados = $tubosUtilizados;
    }

    public function setLoteTubo($loteTubo) {
        $this->loteTubo = $loteTubo;
    }

    public function setValidadeTubo($validadeTubo) {
        $this->validadeTubo = $validadeTubo;
    }

    public function setTamanhoSeringa($tamanhoSeringa) {
        $this->tamanhoSeringa = $tamanhoSeringa;
    }

    public function setLoteSeringa($loteSeringa) {
        $this->loteSeringa = $loteSeringa;
    }

    public function setValidadeSeringa($validadeSeringa) {
        $this->validadeSeringa = $validadeSeringa;
    }

    public function setLotePoteUrina($lotePoteUrina) {
        $this->lotePoteUrina = $lotePoteUrina;
    }

    public function setValidadePoteUrina($validadePoteUrina) {
        $this->validadePoteUrina = $validadePoteUrina;
    }

    public function setLotePoteFezes($lotePoteFezes) {
        $this->lotePoteFezes = $lotePoteFezes;
    }

    public function setValidadePoteFezes($validadePoteFezes) {
        $this->validadePoteFezes = $validadePoteFezes;
    }

    public function __toString() {
        $tubosStr = is_array($this->tubosUtilizados) ? implode(', ', $this->tubosUtilizados) : $this->tubosUtilizados;

        return "Amostra ID: {$this->id}, " .
               "Paciente ID: {$this->pacienteId}, " .
               "Data da Coleta: {$this->dataColeta}, " .
               "Tubos: [{$tubosStr}], " .
               "Lote Seringa: {$this->loteSeringa}";
    }
}