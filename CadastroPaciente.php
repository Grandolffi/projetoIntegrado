<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Paciente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css">
    <style>
    body {
        background-color: white;
    }

    .form-container {
        background-color: white;
        padding: 30px;
        margin-top: 60px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    input::placeholder {
        color: #555;
        opacity: 1;
        font-size: 14px;
    }

    label {
        margin-top: 10px;
    }


    .form-check-input {
        vertical-align: middle;
        margin-top: 0.9em;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-2 sidebar">
                <div class="logo">🧪</div>
            <nav class="nav flex-column">
                <a class="nav-link active" aria-current="page" href="#">Dashboard</a>
                <a class="nav-link" href="#">Exames</a>
                <a class="nav-link" href="#">Usuario</a>
                <a class="nav-link disabled" aria-disabled="true">Estoque</a>
            </nav>
            </div>
            <div class="col-9 form-container">
                <h1 class="text-center mb-4">Cadastro paciente</h1>

                <form action="controller/PessoaController.php" method="POST">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome completo</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" name="cpf" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="dtnasc" class="form-label">Data de nascimento</label>
                        <input type="date" name="dtnasc" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="nomemae" class="form-label">Nome da mãe</label>
                        <input type="text" name="nomemae" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="fone" class="form-label">Celular com DDD</label>
                        <input type="tel" name="fone" class="form-control" required pattern="[0-9]{2} [0-9]{5}-[0-9]{4}"
                            placeholder="Ex: 11 99999-9999">
                    </div>

                    <div class="mb-3">
                        <label for="genero" class="form-label">Gênero</label>
                        <select name="genero" id="genero" class="form-select">
                            <option value="masc">Masculino</option> 
                            <option value="femi">Feminino</option>
                            <option value="nf">Não informar</option>
                        </select>
                    </div>

                     <div class="text-center mt-4">
                        <button type="submit" class="btn btn-dark" name="cadastrar">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="js/validacoes.js"></script>
</body>

</html>