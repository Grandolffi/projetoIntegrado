<?php
session_start();
require_once __DIR__ . '/../dao/ConnectionFactory.php';
require_once __DIR__ . '/../dao/UserDao.php'; // Crie esse DAO com método de autenticação

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $dao = new UserDao();
    $usuario = $dao->autenticar($email, $senha);

    if ($usuario) {
        $_SESSION['usuario_id'] = $usuario->getId();
        $_SESSION['usuario_nome'] = $usuario->getNome();
        $_SESSION['usuario_tipo'] = $usuario->getTipo(); // Ex: 'medico' ou 'balconista'

        header('Location: perfilUser.php');
        exit;
    } else {
        $erro = 'E-mail ou senha inválidos.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Bio Diagnóstico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos personalizados para a página de login */
        body {
            /* Fundo com gradiente suave ou imagem abstrata relacionada à saúde */
            background: linear-gradient(to right, #e0f2f7, #c1e4f3); /* Um gradiente de azul claro para o fundo */
            /* Se quiser uma imagem abstrata, pode usar: */
            /* background: url('https://via.placeholder.com/1500x1000/e0f2f7/c1e4f3?text=Abstrato+Saude') no-repeat center center fixed; */
            /* background-size: cover; */
            
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Fonte mais profissional e limpa */
            color: #34495e; /* Cor do texto padrão mais escura para contraste com fundo claro */
        }

        .login-wrapper {
            text-align: center;
            animation: fadeIn 1s ease-out; /* Adiciona uma animação de fade-in */
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .welcome-title {
            color: #2c3e50; /* Cor do título principal */
            font-size: 2.8rem; /* Título maior */
            margin-bottom: 25px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1); /* Sombra para o título */
        }

        .brand-name {
            color: #1abc9c; /* Cor para o nome da marca (verde do Bio Diagnóstico) */
            font-size: 1.5rem;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .login-card {
            background-color: rgba(255, 255, 255, 0.95); /* Fundo branco levemente translúcido */
            border-radius: 12px; /* Cantos arredondados */
            padding: 40px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15); /* Sombra mais sutil e clara */
            border: 1px solid #dcdcdc; /* Borda leve para definir o card */
        }

        .login-card h2 {
            color: #2c3e50; /* Cor do título "Login" */
            margin-bottom: 30px;
            font-weight: 600;
        }

        .form-label {
            color: #34495e; /* Cor para os labels */
            font-weight: 500;
        }

        .form-control {
            background-color: #f8f9fa; /* Fundo dos inputs bem claro */
            border: 1px solid #ced4da; /* Borda padrão */
            color: #495057; /* Cor do texto digitado nos inputs */
            padding: 12px 18px;
            border-radius: 8px; /* Cantos arredondados para inputs */
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control::placeholder {
            color: #6c757d; /* Cor do placeholder */
        }

        .form-control:focus {
            border-color: #1abc9c; /* Borda ao focar (cor do Bio Diagnóstico) */
            box-shadow: 0 0 0 0.25rem rgba(26, 188, 156, 0.25); /* Sombra ao focar com a cor da marca */
            background-color: #fff;
            color: #495057;
        }

        .btn-primary {
            background-color: #1abc9c; /* Cor vibrante para o botão (verde-azulado do Bio Diagnóstico) */
            border-color: #1abc9c;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #16a085; /* Cor mais escura ao passar o mouse */
            border-color: #16a085;
            transform: translateY(-2px); /* Efeito de "levantar" */
        }

        .alert-danger {
            background-color: #e74c3c; /* Vermelho vibrante para erros */
            border-color: #c0392b;
            color: #fff;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            padding: 15px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container login-wrapper">
        <h1 class="welcome-title">Bem-vindo ao Programa de Bioquímica</h1>
        <p class="brand-name">Bio Diagnóstico</p>

        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4"> <div class="card login-card">
                    <h2 class="text-center">Login</h2>

                    <?php if ($erro): ?>
                        <div class="alert alert-danger"><?= $erro ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" name="senha" class="form-control" required>
                        </div>
                        <div class="d-grid mt-4"> <button type="submit" class="btn btn-primary">Entrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>