<?php
session_start();
$usuario = $_SESSION['usuario'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Acesso Negado</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/Style.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: #e0f2f7; /* Fundo claro para a página */
        }

        .conteudo-principal {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .cabecalho-principal {
            width: 100%;
            display: flex;
            justify-content: flex-end; /* Alinha o cabeçalho à direita */
            align-items: center;
            padding: 10px 0;
            margin-bottom: 30px; /* Espaço entre o cabeçalho e o card */
        }

        .cartao-negado {
            background-color: #ffffff;
            padding: 40px 30px;
            border-radius: 12px;
            /* Aumentando a sombra aqui */
            box-shadow: 0 12px 30px rgba(0,0,0,0.2); /* Valores ajustados */
            max-width: 480px;
            width: 100%;
            text-align: center;
            margin: auto;
        }

        .cartao-negado h2 {
            font-size: 2em;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 20px;
        }

        .cartao-negado p {
            font-size: 1.1em;
            color: #333;
            margin-bottom: 30px;
        }

        .btn-voltar-dashboard {
            background-color: #007bff; /* Cor azul para o botão */
            border-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 8px; /* Borda arredondada */
            font-size: 1.1em;
            font-weight: bold;
            transition: background-color 0.3s ease;
            width: 100%; /* Ocupa a largura total */
            box-shadow: 0 4px 10px rgba(0,0,0,0.1); /* Sombra para o botão */
        }

        .btn-voltar-dashboard:hover {
            background-color: #0056b3; /* Tom mais escuro no hover */
            border-color: #0056b3;
        }
    </style>
</head>
<body class="corpo-dashboard">

<?php include_once "menuLateral.php"; ?>

<div class="conteudo-principal">
    <header class="cabecalho-principal">
        <?php include_once __DIR__ . '/info_cabecalho.php';?>
    </header>

    <div class="cartao-negado">
        <h2>🚫 Acesso Negado</h2>
        <p>Desculpe, você não tem permissão para visualizar esta página.</p>
        <a href="dashboard.php" class="btn btn-voltar-dashboard">🔙 Voltar para o Dashboard</a>
    </div>
</div>

</body>
</html>