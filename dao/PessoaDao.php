<?php
class PessoaDao{

    public function inserir(Pessoa $pessoa){
        try{
            //criando uma string, desenhando o comando
            $sql = "INSERT INTO pessoa(id, nome, cpf, dtnasc, email, nomeMae, numCelular, genero) VALUES(default, :nome, :cpf, :dtnasc, :email, :nomeMae, :numCelular, :genero);";
            $con_sql = ConnectionFactory::getConnection()->prepare($sql);
            $con_sql->bindValue(":nome", $pessoa->getnome()); 
            $con_sql->bindValue(":cpf", $pessoa->getCpf());
            $con_sql->bindValue(":dtnasc", $pessoa->getDtnasc());
            $con_sql->bindValue(":email", $pessoa->getEmail());
            $con_sql->bindValue(":nomeMae", $pessoa->getNomeMae());
            $con_sql->bindValue(":numCelular", $pessoa->getnumCelular());
            $con_sql->bindValue(":genero", $pessoa->getGenero());
            
         echo "<p> Pessoa cadastrado com sucesso";
            return $con_sql->execute();   
        }catch(PDOException $ex){
            echo "<p> Erro ao inserir Pessoa no banco de dados $ex" ;
            return null;
        }
    }

    //Executa SELECT * FROM fabricante 
    public function read(){
        try{
            $sql = "SELECT * FROM pessoa";
            $con_sql = ConnectionFactory::getConnection()->query($sql);
            $lista = $con_sql->fetchall(PDO::FETCH_ASSOC);
            $pesList = array();
            foreach($lista as $linha){
                $pesList[] = $this->listaPessoas($linha);   
            }
            //echo "Temos ". count($pesList). " fabricantes cadastrados";
            return $pesList;
        }catch(PDOException $ex){
            echo "<p> Ocorreu um erro ao selecionar pessoas </p> $ex";
        }
    }

    public function listaPessoas($linha){
        $pessoa = new Pessoa();
        $pessoa->setId($linha['id']);
        $pessoa->setnome($linha['nome']);
        $pessoa->setCpf($linha['cpf']);
        $pessoa->setDtnasc($linha['dtnasc']);
        $pessoa->setEmail($linha['email']);
        $pessoa->setNomeMae($linha['nomeMae']);
        $pessoa->setnumCelular($linha['numCelular']);
        $pessoa->setGenero($linha['genero']);
        return $pessoa;
    }

    public function editar(Pessoa $pes){
        try{
            $sql = "UPDATE pessoa SET 
                nome = :nome, cpf = :cpf, dtnasc = :dtnasc, email = :email, nomeMae = :nomeMae, numCelular = :numCelular, genero = :genero WHERE id = :id";
            $conn = ConnectionFactory::getConnection()->prepare($sql);
            $conn->bindValue(":nome", $pes->getNome());
            $conn->bindValue(":cpf", $pes->getCpf());
            $conn->bindValue(":dtnasc", $pes->getdtnasc());
            $conn->bindValue(":email", $pes->getEmail());
            $conn->bindValue(":nomeMae", $pes->getNomeMae());
            $conn->bindValue(":numCelular", $pes->getnumCelular());
            $conn->bindValue(":genero", $pes->getGenero());
            $conn->bindValue(":id", $pes->getId()); 
            return $conn->execute(); // Executa o update
        }catch(PDOException $ex){
            echo "<p> Erro ao editar </p> <p> $ex </p>";
        }
    }



    public function buscaPorId($id){
        try{
            $sql = "SELECT * FROM pessoa WHERE id = :id";
            $conn = ConnectionFactory::getConnection()->prepare($sql);
            $conn->bindValue(":id", $id);
            $conn->execute();
            $row = $conn->fetch(PDO::FETCH_ASSOC);
            if($row){
                return $this->listaPessoas($row);
            }
            return null;
        }catch(PDOException $e){
            echo "<p>Erro ao buscar ID: {$id}</p> <p>{$e->getMessage()}</p>";
        }
    }

    public function excluir($id){
    try{
        $sql = "DELETE FROM pessoa WHERE id = :id";
        $conn = ConnectionFactory::getConnection()->prepare($sql);
        $conn->bindValue(":id", $id);
        return $conn->execute();
    }catch(PDOException $ex){
        echo "<p>Erro ao excluir: $ex</p>";
        return false;
    }
}


    
}
?>