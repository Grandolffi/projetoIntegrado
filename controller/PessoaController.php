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
        $pessoa = $PessoaDao->buscaPorId($idPessoa);
        if(!isset($pessoa)){
            echo "<p>Pessoa de Id {$idPessoa} não encontrado. </p>";
            header("Location: ../index.php?erro=nao_encontrado");
        }
        
    }

    if(isset($_POST['salvar_edicao'])){
        $pessoa = new Pessoa();
        $pessoa->setId($_POST['id']);
        $pessoa->setnome($_POST['nome']);
        $pessoa->setCpf($_POST['cpf']);
        $pessoa->setDtnasc($_POST['dtnasc']);
        $pessoa->setEmail($_POST['email']);
        $pessoa->setNomeMae($_POST['nomemae']);
        $pessoa->setnumCelular($_POST['fone']);
        $pessoa->setGenero($_POST['genero']);
        $PessoaDao->editar($pessoa); //falta fazer isso aqui pra editar funcionar
        header("Location: ../ListarPaciente.php");
    }

     if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $PessoaDao->excluir($id);
    header("Location: ../ListarPaciente.php");
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
                        <a href='CadastroPaciente.php?editar={$pessoa->getId()}'>Editar</a>
                        <a href='controller/PessoaController.php?excluir={$pessoa->getId()}' onclick=\"return confirm('Tem certeza que deseja excluir esta pessoa?')\">Excluir</a>
                        
                    </td>
            </tr>";
        }   
    }


   



?>  