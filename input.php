<?php

// Incluindo o arquivo cadastroExames.php
require_once 'cadastroExames.php';

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
// validando cadastroExames
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar se os campos necessários estão presentes
    if (!empty($_POST['numero_registro']) && !empty($_POST['data_exame']) && !empty($_POST['responsavel_exame']) && !empty($_POST['preceptor_responsavel'])) {
        
        // Aqui você pode processar os dados e salvar no banco ou em algum outro lugar
        $numero_registro = $_POST['numero_registro'];
        $data_laudo = $_POST['data_laudo']; // Este campo pode ser opcional
        $data_exame = $_POST['data_exame'];
        $responsavel_exame = $_POST['responsavel_exame'];
        $preceptor_responsavel = $_POST['preceptor_responsavel'];

        // Aqui, você também pode verificar os valores dos exames
        $exames = [];
        foreach ($_POST as $campo => $valor) {
            if (strpos($campo, 'bilirrubina') !== false || strpos($campo, 'proteina') !== false || strpos($campo, 'tgo') !== false) {
                $exames[$campo] = $valor; // Guardar o valor de cada exame
            }
        }

        // Salvar os dados (aqui você faria o que for necessário para armazená-los, como inserir no banco)
        // Por enquanto, só vamos exibir os dados para fins de teste
        echo "Dados do Exame Salvo com Sucesso!<br>";
        echo "Número de Registro: " . htmlspecialchars($numero_registro) . "<br>";
        echo "Data do Exame: " . htmlspecialchars($data_exame) . "<br>";
        echo "Responsável pelo Exame: " . htmlspecialchars($responsavel_exame) . "<br>";
        echo "Preceptor Responsável: " . htmlspecialchars($preceptor_responsavel) . "<br>";

        // Exibir os valores dos exames
        echo "Exames: <br>";
        foreach ($exames as $exame => $valor) {
            echo $exame . ": " . htmlspecialchars($valor) . "<br>";
        }
    } else {
        echo "Por favor, preencha todos os campos obrigatórios!";
    }
} else {
    echo "Erro ao enviar o formulário.";
}
?>
