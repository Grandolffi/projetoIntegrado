<?php
// Ajuste os caminhos conforme a estrutura do seu projeto
include_once '../dao/ConnectionFactory.php';
include_once '../dao/ExameDao.php';
include_once '../model/ResultadoExames.php';

// Array com as definições dos exames
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

// 1. VERIFICA O MÉTODO DA REQUISIÇÃO (APENAS UMA VEZ)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ExameDao = new ExameDao();

    // Dados comuns a todos os exames
    $numero_registro = isset($_POST['numero_registro']) ? $_POST['numero_registro'] : null;
    $data_exame_realizado = isset($_POST['data_laudo']) ? $_POST['data_laudo'] : null;
    
    // Pega o array de resultados do POST
    $resultados_submetidos = isset($_POST['exames']) ? $_POST['exames'] : [];

    $todosSalvosComSucesso = true;

    // 2. PERCORRE A LISTA DE EXAMES (APENAS UMA VEZ)
    foreach ($definicoesExames as $tipoExameChave => $detalhes) {
        
        // Verifica se o resultado para este exame foi enviado e não está vazio
        if (isset($resultados_submetidos[$tipoExameChave]) && $resultados_submetidos[$tipoExameChave] !== '') {
            $Exame = new ResultadoExames();

            $nomeExameDescritivo = $detalhes[0];
            $valorReferenciaTexto = $detalhes[1];

            $Exame->setNomeExame($nomeExameDescritivo);
            $Exame->setTipoExame($tipoExameChave);
            
            // Atribui o valor correto do array de resultados
            $Exame->setValorAbsoluto($resultados_submetidos[$tipoExameChave]);
            
            $Exame->setValorReferencia($valorReferenciaTexto); 
            $Exame->setPaciente($numero_registro);
            $Exame->setDataHora($data_exame_realizado);

            if (!$ExameDao->inserir($Exame)) {
                $todosSalvosComSucesso = false;
            }
        }
    }

    // 3. REDIRECIONA APÓS O FIM DO LAÇO
    // Redirecionar de volta para a página de listagem para ver o resultado
    header("Location: ../lista_de_exames.php");
    exit();

} else {
    // Se não for POST, redireciona para a página de cadastro
    header("Location: ../cadastroExames.php");
    exit();
}
?>