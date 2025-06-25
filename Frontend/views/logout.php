<?php
session_start(); // Inicia a sessão para poder manipulá-la
session_unset(); // Remove todas as variáveis registradas na sessão atual
session_destroy(); // Destrói todos os dados registrados em uma sessão
header("Location: login.php"); // Redireciona o navegador para a página de login
exit; // Garante que o script PHP pare de ser executado imediatamente
?>