<?php
// Ajuste os caminhos conforme a estrutura do seu projeto
include_once '../dao/ConnectionFactory.php'; // O ConnectionFactory já tem um echo "Conectado...", pode ser bom remover em produção.
include_once '../dao/ExameDao.php';
include_once '../model/ResultadoExames.php'; // Se o nome do arquivo for Exames.php

// Este array mapeia as chaves do formulário para os nomes descritivos e valores de referência.
// Ele deve ser IGUAL ao array $exames usado em cadastroExames.php para gerar o formulário.
$definicoesExames = [
    'bilirrubina_total' => ['Bilirrubina Total (mg/dL)', '0,2 – 1,2 mg/dL'],
    'bilirrubina_direta' => ['Bilirrubina Direta (mg/dL)', '0,0 – 0,2 mg/dL'],
    'proteina_total' => ['Proteína Total', ''], 
    'tgo' => ['TGO - Transaminase Glutamico Oxalacética (U/L)', '5 – 34 U/L'],
    'tgp' => ['TGP - Transaminase Glutamico Piruvica (U/L)', 'Masculino: 21 – 72 U/L<br>Feminino: 9 – 52 U/L'],
    'gama_gt' => ['Gama GT - Glutamiltransferase (U/L)', 'Masculino: 15 – 73 U/L<br>Feminino: 12 – 43 U/L'],
    'fosfatase_alcalina' => ['Fosfatase Alcalina (U/L)', '38 – 126 U/L'],
    'creatinina' => ['Creatinina (mg/dL)', 'Masculino: 0,70 – 1,25 mg/dL<br>Feminino: 0,57 – 1,11 mg/dL'],
    'glicose' => ['Glicose (mg/dL)', '75 – 99 mg/dL'],
    'colesterol_total' => ['Colesterol Total (mg/dL)', 'Adultos (acima de 20 anos): menor que 190 mg/dL<br>Crianças e adolescentes (menores de 20 anos): menor que 170 mg/dL'],
    'triglicerideos' => ['Triglicerídeos (mg/dL)', 'Adultos (acima de 20 anos): menor que 150 mg/dL<br>Crianças de 0 a 9 anos: menor que 75 mg/dL<br>Crianças e adolescentes de 10 a 19 anos: menor que 90 mg/dL'],
    'ureia' => ['Ureia (mg/dL)', ''], 
    'acido_urico' => ['Ácido Úrico (mg/dL)', ''], 
    'pcr' => ['PCR - Proteína C Reativa (mg/dL)', 'Inferior a 1,0 mg/dL'],
    'calcio' => ['Cálcio', ''], 
    'ldh' => ['LDH', ''] 
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ExameDao = new ExameDao();

    // Dados comuns a todos os exames desta submissão
    $numero_registro = isset($_POST['numero_registro']) ? $_POST['numero_registro'] : null;
    $data_exame_realizado = isset($_POST['data_laudo']) ? $_POST['data_laudo'] : null;
    
    // em uma tabela principal de "laudos" ou "solicitações de exame".
    // $data_laudo_prevista = isset($_POST['data_laudo']) ? $_POST['data_laudo'] : null;
    // $responsavel_exame_tec = isset($_POST['responsavel_exame']) ? $_POST['responsavel_exame'] : null;
    // $preceptor_responsavel_med = isset($_POST['preceptor_responsavel']) ? $_POST['preceptor_responsavel'] : null;

    $todosSalvosComSucesso = true;

    // Iterar sobre os exames definidos para encontrar os valores no POST
    foreach ($definicoesExames as $tipoExameChave => $detalhes) {
        // o isset verifica se o campo do exame foi enviado e não está vazio
        if (isset($_POST[$tipoExameChave]) && $_POST[$tipoExameChave] !== '') {
            $Exame = new ResultadoExames();

            $nomeExameDescritivo = $detalhes[0];
            $valorReferenciaTexto = $detalhes[1];

            $Exame->setNomeExame($nomeExameDescritivo);
            $Exame->setTipoExame($tipoExameChave); // Chave do formulário, ex: 'bilirrubina_total'
            $Exame->setValorAbsoluto($_POST[$tipoExameChave]);
            $Exame->setValorReferencia($valorReferenciaTexto); 
            $Exame->setPaciente($numero_registro);
            $Exame->setDataHora($data_exame_realizado);

            if (!$ExameDao->inserir($Exame)) {
                $todosSalvosComSucesso = false;
            }
        }
    }

    // if ($todosSalvosComSucesso) {
    //     // Opcional: Adicionar uma mensagem de sucesso à sessão para exibir após o redirect
    //     // session_start(); // Se não iniciada
    //     // $_SESSION['mensagem_sucesso'] = "Resultados dos exames salvos com sucesso!";
    // } else {
    //     // Opcional: Adicionar uma mensagem de erro à sessão
    //     // $_SESSION['mensagem_erro'] = "Ocorreu um erro ao salvar um ou mais resultados de exames.";
    // }

    // Redirecionar de volta para a página de cadastro de exames
    header("Location: ../cadastroExames.php");
    exit(); //  função p/ garantir que o script pare após o redirecionamento

} else {
    header("Location: ../cadastroExames.php");
    exit();
}
?>