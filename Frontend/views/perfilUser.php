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
// A permissão para editar os próprios dados, se necessário, pode ser controlada aqui
// No seu original, o tipo de usuário médico podia editar, vamos manter a flag.
$temPermissaoEditar = $tipo === 'medico'; // Usuários com tipo 'medico' podem ver os campos habilitados

$idUsuario = $_SESSION['usuario_id'];
$usuario = $UserDao->buscaPorId($idUsuario); // Dados do usuário logado

$msgSucesso = '';

// --- INÍCIO DO BLOCO PHP PARA PROCESSAR O FORMULÁRIO DE EDIÇÃO (MANTIDO DO SEU ORIGINAL) ---
// Este bloco processa a submissão do formulário "Meus Dados"
// NOTA: Com o botão de submit removido, este bloco não será mais acionado por este formulário.
// Se você ainda precisa de uma forma de salvar as alterações de Nome/Email, precisará de outro botão de submit.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $temPermissaoEditar) {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    // O campo de senha foi removido do HTML, então $senha não virá do POST aqui.
    // Se precisar de alteração de senha, será outra funcionalidade.
    $tipoForm = $_POST['tipo'] ?? '';

    // Atualiza usuário
    $usuario->setNome($nome);
    $usuario->setEmail($email);
    $usuario->setTipo($tipoForm); // Atualiza o tipo, se permitido

    $UserDao->atualizar($usuario);

    // Atualiza sessão com possíveis novas infos
    $_SESSION['usuario_nome'] = $nome;
    $_SESSION['usuario_tipo'] = $tipoForm;

    // Recarrega os dados atualizados
    $usuario = $UserDao->buscaPorId($idUsuario);

    $msgSucesso = "Dados atualizados com sucesso!";
}
// --- FIM DO BLOCO PHP PARA PROCESSAR O FORMULÁRIO DE EDIÇÃO ---

// Se médico, busca todos usuários para listagem (mantido do seu original)
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
                        <?php if ($temPermissaoEditar): ?>
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo de usuário</label>
                            <select name="tipo" id="tipo" class="form-select" required>
                                <option value="medico" <?= $usuario->getTipo() === 'medico' ? 'selected' : '' ?>>Médico</option>
                                <option value="balconista" <?= $usuario->getTipo() === 'balconista' ? 'selected' : '' ?>>Balconista</option>
                                </select>
                        </div>
                        <?php else: ?>
                            <input type="hidden" name="tipo" value="<?= htmlspecialchars($usuario->getTipo()) ?>">
                        <?php endif; ?>

                        <div class="text-center">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmLogoutModal">
                                Sair
                            </button>
                        </div>
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

    <div class="modal fade" id="confirmLogoutModal" tabindex="-1" aria-labelledby="confirmLogoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmLogoutModalLabel">Confirmação de Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Você tem certeza que deseja sair?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="logout.php" class="btn btn-primary">Sair</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>