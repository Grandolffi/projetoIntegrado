<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

include __DIR__ . '/../dao/ConnectionFactory.php';
include __DIR__ . '/../dao/UserDao.php';



if (isset($_POST['email']) && isset($_POST['senha'])) {
    $dao = new UserDao();
    $usuario = $dao->autenticar($_POST['email'], $_POST['senha']);

    if ($usuario) {
        $_SESSION['usuario_id'] = $usuario->getId();
        $_SESSION['usuario_nome'] = $usuario->getNome();
        $_SESSION['usuario_tipo'] = $usuario->getTipo();
        header("Location: ../views/perfilUser.php");
        exit;
    } else {
        header("Location: ../views/login.php?erro=1");
        exit;
    }
}
?>
