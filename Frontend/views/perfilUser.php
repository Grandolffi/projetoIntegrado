<?php
// Para ver erros PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../controller/PessoaController.php';
require_once __DIR__ . '/../dao/PessoaDao.php';
require_once __DIR__ . '/../model/ClassePessoas.php';

// Suponha que o ID do usuário logado esteja em uma sessão, por exemplo
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$tipo = $_SESSION['usuario_tipo']; // Ex: 'medico' ou 'balconista'

// Exemplo de permissão
$temPermissaoEditar = $tipo === 'medico';
    
$idUsuario = $_SESSION['usuario_id'] ?? 1; // Exemplo: usuário logado

$dao = new PessoaDao();
$usuario = $dao->buscaPorId($idUsuario);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perfil do Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/Style.css">
</head>
<body class="corpo-dashboard">
    <div class="container-dashboard">
        <?php include_once __DIR__ . '/menuLateral.php'; ?>

        <main class="conteudo-principal">
            <header class="cabecalho-principal">
                <h1>Perfil do Usuário</h1>
                <?php include_once __DIR__ . '/info_cabecalho.php'; ?>
            </header>

            <div class="form-container">
                <div class="card shadow p-4">
                    <h2 class="text-center mb-4">Informações Pessoais</h2>

                    <div class="mb-3">
                        <label class="form-label">Nome completo</label>
                        <input type="text" class="form-control" value="<?= $usuario->getNome(); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">CPF</label>
                        <input type="text" class="form-control" value="<?= $usuario->getCpf(); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data de Nascimento</label>
                        <input type="text" class="form-control" value="<?= $usuario->getdtnasc(); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">E-mail</label>
                        <input type="email" class="form-control" value="<?= $usuario->getEmail(); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nome da Mãe</label>
                        <input type="text" class="form-control" value="<?= $usuario->getNomeMae(); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Celular</label>
                        <input type="text" class="form-control" value="<?= $usuario->getnumCelular(); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gênero</label>
                        <input type="text" class="form-control" value="<?= $usuario->getGenero(); ?>" readonly>
                    </div>

                    <div class="text-center mt-4">
                        <a href="CadastroPaciente.php?editar=<?= $usuario->getId(); ?>" class="btn btn-warning">Editar Perfil</a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
