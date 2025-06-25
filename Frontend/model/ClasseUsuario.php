<?php
debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
if (class_exists('Usuario')) {
    return;
}
if (!class_exists('Usuario')) {
class Usuario {
    private $id;
    private $nome;
    private $email;
    private $senha; // senha armazenada como hash
    private $tipo;

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    public function getNome() {
        return $this->nome;
    }
    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getEmail() {
        return $this->email;
    }
    public function setEmail($email) {
        $this->email = $email;
    }

    public function getSenha() {
        return $this->senha;
    }
    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function getTipo() {
        return $this->tipo;
    }
    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }
}
}
?>
