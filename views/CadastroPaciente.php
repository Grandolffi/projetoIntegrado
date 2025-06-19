<?php
// Para ver erros PHP, se houver
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../controller/PessoaController.php';
require_once __DIR__ . '/../dao/PessoaDao.php';
require_once __DIR__ . '/../model/ClassePessoas.php';    
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Paciente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../public/css/Style.css">
<body class="corpo-dashboard">
    <?php

    if(isset($_GET['editar'])){
         $id = $_GET['editar'];
         $dao = new PessoaDao();
         $pessoa = $dao->buscaPorId($id);

    }

    ?>
    <div class="container-dashboard"> 
        <?php include_once __DIR__ . '/menuLateral.php';?>
        
        <main class="conteudo-principal">
            <header class="cabecalho-principal"> 
                <h1>Cadastro de Paciente</h1> 
                <?php include 'info_cabecalho.php'; // Inclui a saudação, data e hora ?>
            </header>

            <div class="form-container">
                <h2 class="text-center mb-4">Dados do Paciente</h2> 
                <form action="/projetoIntegrado/controller/PessoaController.php" method="POST">
                    <input type="hidden" name="id" value="<?= isset($pessoa) && $pessoa->getId() ? $pessoa->getId(): '' ?>">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome completo</label>
                        <input type="text" name="nome" id="nome" value="<?= isset($pessoa) && $pessoa->getNome() ? $pessoa->getNome(): '' ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" name="cpf" id="cpf" value="<?= isset($pessoa) && $pessoa->getCpf() ? $pessoa->getCpf(): '' ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="dtnasc" class="form-label">Data de nascimento</label>
                        <input type="date" name="dtnasc" id="dtnasc" value="<?= isset($pessoa) && $pessoa->getdtnasc() ? $pessoa->getdtnasc(): '' ?>" class="form-control" required>
                    </div>  
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" name="email" id="email" value="<?= isset($pessoa) && $pessoa->getEmail() ? $pessoa->getEmail(): '' ?>"class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="nomemae" class="form-label">Nome da mãe</label>
                        <input type="text" name="nomemae" id="nomemae" value="<?= isset($pessoa) && $pessoa->getNomeMae() ? $pessoa->getNomeMae(): '' ?>" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="fone" class="form-label">Celular com DDD</label>
                        <input type="tel" name="fone" id="fone" value="<?= isset($pessoa) && $pessoa->getnumCelular() ? $pessoa->getnumCelular(): '' ?>" class="form-control" required pattern="[0-9]{2} [0-9]{5}-[0-9]{4}"
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
                    <?php if(isset($pessoa) && $pessoa->getId()): ?>
                        <div class="text-center mt-4">
                        <button type="submit" name="salvar_edicao" class="btn btn-primary">Salvar Edição</button>
                        </div>
                    <?php else: ?>
                        <div class="text-center mt-4">
                        <button type="submit" name="cadastrar" class="btn btn-success">Cadastrar</button>
                        </div>
                    <?php endif; ?>
                </form>
            </div> 
        </main> 
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../public/js/validacoes.js"></script>
</html>