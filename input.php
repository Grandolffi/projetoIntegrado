<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
    echo "Solicitação via POST! <br>";
    if(!empty($_POST['nome'])){
        echo "<p> Olá ". $_POST['nome']. "!! <br>";    
    }
    foreach($_POST as $chave => $exec3){
        echo $chave . ": " . $exec3 . "<br>";
    }
}elseif($_SERVER["REQUEST_METHOD"] == "GET"){
    echo "Solicitação via GET! <br>";
}
    
    
    $nome = $_POST["nome"];
    $genero = $_POST["genero"];
    if($idade > 25){
        echo "Olá ". $nome;
    }
?>