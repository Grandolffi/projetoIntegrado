<?php

class ResultadoExames{
    private $idExame; // Propriedade para o ID do exame
    private $nomeExame;
    private $tipoExame;
    private $valorAbsoluto;
    private $valorReferencia;
    // Remova 'private $paciente;' se 'paciente_registro' for apenas uma string
    private $pacienteRegistro; // Adicionado para mapear 'paciente_registro' do DB
    private $laudoId;        // Adicionado para mapear 'laudo_id' do DB
    private $dataHora;
    // Se tiver 'data_cadastro' no DB e quiser no modelo, adicione aqui
    // private $dataCadastro;

    // --- Getters ---
    public function getIdExame(){ // CORRETO: getIdExame
        return $this->idExame;
    }

    public function getNomeExame(){
        return $this->nomeExame;
    }

    public function getTipoExame(){
        return $this->tipoExame;
    }

    public function getValorAbsoluto(){
        return $this->valorAbsoluto;
    }

    public function getPacienteRegistro(){ // NOVO GETTER para pacienteRegistro
        return $this->pacienteRegistro;
    }

    public function getDataHora(){
        return $this->dataHora;
    }

    public function getValorReferencia() {
        return $this->valorReferencia;
    }

    public function getLaudoId() { // NOVO GETTER para laudoId
        return $this->laudoId;
    }
    
    // public function getDataCadastro() { // Opcional, se adicionar dataCadastro
    //    return $this->dataCadastro;
    // }

    // --- Setters ---
    public function setIdExame($idExame){ // CORRETO: setIdExame
        $this->idExame = $idExame;
    }

    public function setNomeExame($nomeExame){
        $this->nomeExame = $nomeExame;
    }

    public function setTipoExame($tipoExame){
        $this->tipoExame = $tipoExame;
    }

    public function setValorAbsoluto($valorAbsoluto){
        $this->valorAbsoluto = $valorAbsoluto;
    }

    public function setPacienteRegistro($pacienteRegistro){ // NOVO SETTER para pacienteRegistro
        $this->pacienteRegistro = $pacienteRegistro;
    }

    public function setDataHora($dataHora){
        $this->dataHora = $dataHora;
    }

    public function setValorReferencia($valorReferencia) {
        $this->valorReferencia = $valorReferencia;
    }

    public function setLaudoId($laudoId) { // NOVO SETTER para laudoId
        $this->laudoId = $laudoId;
    }

    // public function setDataCadastro($dataCadastro) { // Opcional, se adicionar dataCadastro
    //    $this->dataCadastro = $dataCadastro;
    // }

    public function __toString(){
        return "Exame - Nome: {$this->nomeExame}, tipo de exame: {$this->tipoExame}, Valor Absoluto: {$this->valorAbsoluto},
        Valor de referência: {$this->valorReferencia}, Paciente Registro: {$this->pacienteRegistro}, Data e hora{$this->dataHora}";
    }
}
?>