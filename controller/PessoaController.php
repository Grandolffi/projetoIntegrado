<?php
include __DIR__. '/../dao/ConnectionFactory.php';
include __DIR__. '/../dao/PessoaDao.php';
include __DIR__. '/../model/ClassePessoas.php';


    //$Pessoa = new Pessoa();
    $PessoaDao = new PessoaDao();

    if(isset($_POST['cadastrar'])){
        //var_dump($_POST);
        $Pessoa = new Pessoa();
        $Pessoa->setnome($_POST['nome']);
        $Pessoa->setCpf($_POST['cpf']);
        $Pessoa->setDtnasc($_POST['dtnasc']);
        $Pessoa->setEmail($_POST['email']);
        $Pessoa->setNomeMae($_POST['nomemae']);
        $Pessoa->setnumCelular($_POST['fone']);
        $Pessoa->setGenero($_POST['genero']);
        $PessoaDao->inserir($Pessoa);
        header("location: ../CadastroPaciente.php");
    }

    if(isset($_GET['editar'])){
        $idPessoa = $_GET['editar'];
        $pessoa = $PessoaDao->buscarPorId($idPessoa);
        if(!isset($pessoa)){
            echo "<p>Pessoa de Id {$idPessoa} não encontrado. </p>";
            header("Location: ../index.php?erro=nao_encontrado");
        }
    }

    if(isset($_POST['salvar_edicao'])){
        $pessoa = new Pessoa();
        $pessoa->setId($_POST['id']);
        $Pessoa->setnome($_POST['nome']);
        $Pessoa->setCpf($_POST['cpf']);
        $Pessoa->setDtnasc($_POST['dtnasc']);
        $Pessoa->setEmail($_POST['email']);
        $Pessoa->setNomeMae($_POST['nomemae']);
        $Pessoa->setnumCelular($_POST['fone']);
        $Pessoa->setGenero($_POST['genero']);
        $PessoaDao->editar($pessoa);
        header("Location: ../CadastroPaciente.php");
    }




    function listar(){
        $PessoaDao = new PessoaDao();
        $lista = $PessoaDao->read();
        foreach($lista as $pessoa){
            //atençao no get cpf
            echo "<tr> 
                    <td>{$pessoa->getId()}</td>
                    <td>{$pessoa->getNome()}</td>
                    <td>{$pessoa->getCpf()}</td>
                    <td>{$pessoa->getDtnasc()}</td>
                    <td>{$pessoa->getEmail()}</td>
                    <td>{$pessoa->getNomeMae()}</td>
                    <td>{$pessoa->getnumCelular()}</td>
                    <td>{$pessoa->getGenero()}</td>
                    <td> 
                        <a href=''ListarPaciente.php?editar={$pessoa->getId()}>Editar</a>
                        
                    </td>
            </tr>";
        }
    }
?>  