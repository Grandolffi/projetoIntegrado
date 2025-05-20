<?php
class ConnectionFactory
{
    static $connection;
    public static function getConnection()
    {
        if (!isset(self::$connection)) {
        
            $port = 3306; // PORTA
            $dbName = "projetointegrado"; //nome do banco de dados
            $userDb  = "root"; //usuario que acessa
            $host = "localhost"; //onde esta o hospedado do SGBD
            $pass = "";

            try {
                self::$connection = new PDO("mysql:host=$host;dbname=$dbName;port=$port", $userDb, $pass); //CLASE PRA ACESSAR BANCO DE DADOS (PADRÃO PHP);
                //(New pdo é uma classe para conectar com o banco) conection é obj da classe pdo que recebe por parametro em ordem a string de conexão, usuario e senha.
            echo "Conectado com sucesso!<br>";
            // Teste: imprimir qual banco está sendo usado
            $result = self::$connection->query("SELECT DATABASE()")->fetchColumn();
            echo "Banco conectado: $result<br>";
            } catch (PDOException $ex) {
                 echo "<strong>Erro de conexão com o banco:</strong> " . $ex->getMessage();
                return null;
            }
        }

        return self::$connection;  //se não entrar no if ela vem para ca
    }
}
