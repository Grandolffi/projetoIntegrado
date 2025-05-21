<?php
include '../dao/ConnectionFactory.php';
include '../dao/PessoaDao.php';
include '../model/ClassePessoas.php';


    $Pessoa = new Pessoa();
    $PessoaDao = new PessoaDao();

    if(isset($_POST['cadastrar'])){
        var_dump($_POST);
        $Pessoa->setnome($_POST['nome']);
        $Pessoa->setCpf($_POST['cpf']);
        $Pessoa->setDataNasc($_POST['datanasc']);
        $Pessoa->setEmail($_POST['email']);
        $Pessoa->setNomeMae($_POST['nomemae']);
        $Pessoa->setnumCelular($_POST['fone']);
        $Pessoa->setGenero($_POST['genero']);
        $PessoaDao->inserir($Pessoa);
        
        header("location: ../CadastroPaciente.php");
    }
?>