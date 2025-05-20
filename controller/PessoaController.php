<?php
include '../dao/ConnectionFactory.php';
include '../dao/PessoaDao.php';


echo "Controller foi chamado";


include '../model/ClassePessoas.php';

    $Pessoa = new Pessoa();
    $PessoaDao = new PessoaDao();

    if(isset($_POST['cadastrar'])){
        var_dump($_POST);
        $Pessoa->setNomeCompleto($_POST['nome']);
        $Pessoa->setCpf($_POST['cpf']);
        $Pessoa->setDatNasc($_POST['dtnasc']);
        $Pessoa->setEmail($_POST['email']);
        $Pessoa->setNomeMae($_POST['nomemae']);
        $Pessoa->setNumTel($_POST['fone']);
        $Pessoa->setGenero($_POST['genero']);
        $PessoaDao->inserir($Pessoa);
        
        header("location: ../CadastroPaciente.php");
    }
?>