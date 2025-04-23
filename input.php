<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    echo "Solicitação via POST! <br>";
    if(!empty($_POST['nome'])){
        echo "<p> Olá ". $_POST['nome']. "!! <br>";    
    }
    

    foreach($_POST as $nomeCampo => $valor){
        if(is_array($valor)){//estou verificando se estou recebendo um array
            echo $nomeCampo . ":<br>"; //imprimo o nome do arrayList
            
            foreach($valor as $itens){
                echo "" .htmlspecialchars($itens). "<br>"; //o "." concatena   !!HOJE É 13/04/25 SÃO 08 DA MANHA DE UM DOMINGO E ESTOU COM DOR DE CABEÇA POR CODAR FRONT!!
            }
        }else 
        echo $nomeCampo . ": " .htmlspecialchars($valor). "<br>"; 
    }


}elseif($_SERVER["REQUEST_METHOD"] == "GET"){
    echo "Solicitação via GET! <br>";
}

