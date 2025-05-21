<?php
class PessoaDao{

    public function inserir(Pessoa $pessoa){
        try{
            $sql = "INSERT INTO pessoa(id, nome, cpf, dataNasc, email, nomeMae, numCelular, genero) VALUES(default, :nome, :cpf, :dataNasc, :email, :nomeMae, :numCelular, :genero);";
            $con_sql = ConnectionFactory::getConnection()->prepare($sql);
            $con_sql->bindValue(":nome", $pessoa->getnome()); 
            $con_sql->bindValue(":cpf", $pessoa->getCpf());
            $con_sql->bindValue(":dataNasc", $pessoa->getdataNasc());
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
            echo "Temos ". count($pesList). " fabricantes cadastrados";
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
        $pessoa->setDataNasc($linha['dataNasc']);
        $pessoa->setEmail($linha['email']);
        $pessoa->setNomeMae($linha['nomeMae']);
        $pessoa->setnumCelular($linha['numCelular']);
        $pessoa->setGenero($linha['genero']);
        return $pessoa;

    }
}
?>