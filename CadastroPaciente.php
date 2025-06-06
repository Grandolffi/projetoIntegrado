<?php
// Para ver erros PHP, se houver
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Paciente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="Style.css"> </head>
<body class="corpo-dashboard">
    <?php

    if(isset($_GET['editar'])){
        echo "Chamou via editar"; //apenas pra testes
        require_once 'controller/PessoaController.php';

    }

    ?>
    <div class="container-dashboard"> 
        <?php include 'menuLateral.php'; // Inclui o menu lateral com o submenu ?>
        
        <main class="conteudo-principal">
            <header class="cabecalho-principal"> 
                <h1>Cadastro de Paciente</h1> 
                <?php include 'info_cabecalho.php'; // Inclui a saudação, data e hora ?>
            </header>

            <div class="form-container">
                <h2 class="text-center mb-4">Dados do Paciente</h2> 
                <form action="controller/PessoaController.php" method="POST">
                    <input type="hidden" name="id" value="<?php isset($pessoa) && $pessoa->getId() ? $pessoa->getId(): '' ?>">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome completo</label>
                        <input type="text" name="nome" id="nome" value="<?php isset($pessoa) && $pessoa->getNome() ? $pessoa->getNome(): '' ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" name="cpf" id="cpf" value="<?php isset($pessoa) && $pessoa->getCpf() ? $pessoa->getCpf(): '' ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="dtnasc" class="form-label">Data de nascimento</label>
                        <input type="date" name="dtnasc" id="dtnasc" value="<?php isset($pessoa) && $pessoa->getDataNasc() ? $pessoa->getDataNasc(): '' ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" name="email" id="email" value="<?php isset($pessoa) && $pessoa->getEmail() ? $pessoa->getEmail(): '' ?>"class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="nomemae" class="form-label">Nome da mãe</label>
                        <input type="text" name="nomemae" id="nomemae" value="<?php isset($pessoa) && $pessoa->getNomeMae() ? $pessoa->getNomeMae(): '' ?>" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="fone" class="form-label">Celular com DDD</label>
                        <input type="tel" name="fone" id="fone" value="<?php isset($pessoa) && $pessoa->getnumCelular() ? $pessoa->getnumCelular(): '' ?>" class="form-control" required pattern="[0-9]{2} [0-9]{5}-[0-9]{4}"
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
        </main> 
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/validacoes.js"></script> </body>
</html>