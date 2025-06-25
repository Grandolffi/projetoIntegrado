<?php
include __DIR__ . '/../dao/ConnectionFactory.php';
include __DIR__ . '/../dao/UsuarioDao.php';
include __DIR__ . '/../model/ClasseUsuarios.php';

session_start();

if (isset($_POST['email']) && isset($_POST['senha'])) {
    $dao = new UsuarioDao();
    $usuario = $dao->autenticar($_POST['email'], $_POST['senha']);

    if ($usuario) {
        $_SESSION['usuario_id'] = $usuario->getId();
        $_SESSION['usuario_nome'] = $usuario->getNome();
        $_SESSION['usuario_tipo'] = $usuario->getTipo();
        header("Location: ../views/perfilUsuario.php");
        exit;
    } else {
        header("Location: ../views/login.php?erro=1");
        exit;
    }
}
?>
