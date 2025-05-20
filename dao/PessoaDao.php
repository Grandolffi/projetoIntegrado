<?php
class PessoaDao{

    public function inserir(Pessoa $pessoa){
        try{
            $sql = "INSERT INTO pessoa(id, nome, cpf, dataNasc, email, nomeMae, numCelular, genero) VALUES(default, :nome, :cpf, :dataNasc, :email, :nomeMae, :numCelular, :genero);";
            $con_sql = ConnectionFactory::getConnection()->prepare($sql);
            $con_sql->bindValue(":nome", $pessoa->getNomeCompleto()); 
            $con_sql->bindValue(":cpf", $pessoa->getCpf());
            $con_sql->bindValue(":dataNasc", $pessoa->getDatNasc());
            $con_sql->bindValue(":email", $pessoa->getEmail());
            $con_sql->bindValue(":nomeMae", $pessoa->getNomeMae());
            $con_sql->bindValue(":numCelular", $pessoa->getNumTel());
            $con_sql->bindValue(":genero", $pessoa->getGenero());
            
         echo "<p> Pessoa cadastrado com sucesso";
            return $con_sql->execute();   
        }catch(PDOException $ex){
            echo "<p> Erro ao insetir Pessoa no banco de dados $ex" ;
            return null;
        }
    }
}
?>