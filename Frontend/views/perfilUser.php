<?php
// Exibir erros PHP (dev)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../controller/UsuarioController.php';
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../model/ClasseUsuario.php';

$UserDao = new UserDao();

$tipo = $_SESSION['usuario_tipo']; // ex: 'medico', 'balconista'
$temPermissaoEditar = $tipo === 'medico';

$idUsuario = $_SESSION['usuario_id'];
$usuario = $UserDao->buscaPorId($idUsuario);

// Processa submissão do formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $temPermissaoEditar) {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $tipoForm = $_POST['tipo'] ?? '';

    // Validar dados básicos aqui (pode melhorar depois)

    // Atualiza usuário
    $usuario->setNome($nome);
    $usuario->setEmail($email);
    if (!empty($senha)) {
        // Atualiza a senha só se for preenchida
        $usuario->setSenha(password_hash($senha, PASSWORD_DEFAULT));
    }
    $usuario->setTipo($tipoForm);

    $UserDao->atualizar($usuario);

    // Atualiza sessão com possíveis novas infos
    $_SESSION['usuario_nome'] = $nome;
    $_SESSION['usuario_tipo'] = $tipoForm;

    // Recarrega os dados atualizados
    $usuario = $UserDao->buscaPorId($idUsuario);

    $msgSucesso = "Dados atualizados com sucesso!";
}

// Se médico, busca todos usuários para listagem
$usuariosListagem = [];
if ($tipo === 'medico') {
    $usuariosListagem = $UserDao->listarTodos();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Perfil do Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../public/css/Style.css" />
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

                <?php if (!empty($msgSucesso)) : ?>
                    <div class="alert alert-success"><?= $msgSucesso ?></div>
                <?php endif; ?>

                <div class="card shadow p-4 mb-5">
                    <h2 class="text-center mb-4">Meus Dados</h2>

                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome completo</label>
                            <input type="text" name="nome" id="nome" class="form-control" value="<?= htmlspecialchars($usuario->getNome()); ?>" <?= $temPermissaoEditar ? '' : 'readonly' ?> required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($usuario->getEmail()); ?>" <?= $temPermissaoEditar ? '' : 'readonly' ?> required>
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha (preencha para alterar)</label>
                            <input type="password" name="senha" id="senha" class="form-control" placeholder="Nova senha">
                        </div>
                        <?php if ($temPermissaoEditar): ?>
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo de usuário</label>
                            <select name="tipo" id="tipo" class="form-select" required>
                                <option value="medico" <?= $usuario->getTipo() === 'medico' ? 'selected' : '' ?>>Médico</option>
                                <option value="balconista" <?= $usuario->getTipo() === 'balconista' ? 'selected' : '' ?>>Balconista</option>
                                <!-- Outros tipos se houver -->
                            </select>
                        </div>
                        <?php else: ?>
                            <input type="hidden" name="tipo" value="<?= htmlspecialchars($usuario->getTipo()) ?>">
                        <?php endif; ?>

                        <?php if ($temPermissaoEditar): ?>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>

                <?php if ($tipo === 'medico'): ?>
                <div class="card shadow p-4">
                    <h2 class="text-center mb-4">Todos os Usuários</h2>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Tipo</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuariosListagem as $u): ?>
                                <tr>
                                    <td><?= $u->getId() ?></td>
                                    <td><?= htmlspecialchars($u->getNome()) ?></td>
                                    <td><?= htmlspecialchars($u->getEmail()) ?></td>
                                    <td><?= htmlspecialchars($u->getTipo()) ?></td>
                                    <td>
                                        <a href="editarUsuario.php?id=<?= $u->getId() ?>" class="btn btn-warning btn-sm">Editar</a>
                                        <!-- Pode adicionar botão excluir aqui -->
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($usuariosListagem)) : ?>
                                <tr><td colspan="5" class="text-center">Nenhum usuário encontrado.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>

            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
