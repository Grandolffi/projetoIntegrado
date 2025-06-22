<?php

// Para ver erros PHP, se houver
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir as dependências do DAO PHP e da Model
require_once __DIR__ . '/../model/ResultadoExames.php'; // O Modelo do Exame
require_once __DIR__ . '/../dao/ExameDaoApi.php'; // DAO que interage com a API Node.js

// Array com as definições dos exames (útil para referências e tipagem)
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

// Instancia o DAO do Exame uma vez no escopo global do controller
$exameDao = new ExameDaoApi();

// --- Lógicas de Requisição (POST / GET) ---

// Lógica para CADASTRO de Exame (Se houver um formulário que POSTA para 'cadastrar_exame' aqui)
// Atualmente, seu cadastroExames.php POSTA para LaudoController.php para 'salvar_novo_laudo'.
// Mantenha este bloco APENAS se tiver um formulário que realmente envia 'cadastrar_exame' para cá.
if (isset($_POST['cadastrar_exame'])) {
    $exame = new ResultadoExames();
    $exame->setLaudoId($_POST['laudo_id'] ?? null);
    $exame->setNomeExame($_POST['nome_exame'] ?? null);
    $tipoExameKey = $_POST['tipo_exame'] ?? null;
    $exame->setTipoExame($tipoExameKey); 
    $definicaoReferencia = $definicoesExames[$tipoExameKey][1] ?? null;
    $exame->setValorReferencia($definicaoReferencia);
    $exame->setValorAbsoluto($_POST['valor_absoluto'] ?? null);
    $exame->setPacienteRegistro($_POST['paciente_registro'] ?? null);
    $dataHoraExame = $_POST['data_hora_exame'] ?? date('Y-m-d H:i:s');
    if ($dataHoraExame && strpos($dataHoraExame, 'T') !== false) {
         $dataHoraExame = str_replace('T', ' ', $dataHoraExame) . ':00';
    }
    $exame->setDataHora($dataHoraExame); // Usar setDataHora (nome do método no modelo)

    // Assumir que o paciente_id_fk virá do formulário ou será buscado aqui.
    // É um campo NOT NULL no banco. Se não for enviado, dará erro no Node.js DAO.
    // Você precisa garantir que este valor é setado no objeto $exame antes de chamar inserir.
    // Por exemplo: $exame->setPacienteIdFk($_POST['paciente_id_fk'] ?? null);

    try {
        $exameDao->inserir($exame);
        header("Location: ../views/lista_de_exames.php?status=success&message=" . urlencode("Exame cadastrado com sucesso!"));
        exit();
    } catch (Exception $e) {
        header("Location: ../views/cadastroExames.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
}

// Lógica para SALVAR EDIÇÃO de Exame (via POST de cadastroExames.php no modo edição)
$exame = null; // Inicialize $exame para o escopo global do controller. Ele será usado abaixo para 'editar'.
if(isset($_POST['salvar_edicao'])) { // Esta é a flag que virá do hidden input em cadastroExames.php
    $exame = new ResultadoExames();
    $exame->setIdExame($_POST['id']); // Usar setIdExame (nome do método no modelo)

    $exame->setLaudoId($_POST['laudo_id'] ?? null);
    $exame->setNomeExame($_POST['nome_exame'] ?? null);
    $exame->setTipoExame($_POST['tipo_exame'] ?? null); 
    
    // Tentar obter valor_referencia da definição, se tipo_exame_key existe
    // É mais seguro pegar o valor_referencia diretamente do POST se o formulário enviar.
    // Ou garantir que $definicoesExames está disponível globalmente.
    $exame->setValorReferencia($_POST['valor_referencia'] ?? null); 

    $exame->setValorAbsoluto($_POST['valor_absoluto'] ?? null);
    $exame->setPacienteRegistro($_POST['paciente_registro'] ?? null); 
    
    $dataHoraExame = $_POST['data_hora_exame'] ?? null;
    if ($dataHoraExame && strpos($dataHoraExame, 'T') !== false) {
         $dataHoraExame = str_replace('T', ' ', $dataHoraExame) . ':00';
    }
    $exame->setDataHora($dataHoraExame); // Usar setDataHora (nome do método no modelo)

    // IMPORTANTE: Seu exameDAO.js (no Node.js) editResultadoExame espera 'paciente_id_fk'.
    // Você precisa garantir que este valor é setado no objeto $exame antes de chamar editar.
    // Se não vem do formulário, terá que buscá-lo do objeto original ($exame antes da edição)
    // ou fazer com que o editResultadoExame no Node.js não precise dele se não for mudar.
    // Por simplicidade aqui, vou assumir que o DAO no Node.js consegue lidar com a ausência.
    // Alternativa: $exame->setPacienteIdFk(ID_DO_PACIENTE_ORIGINAL);

    try {
        $exameDao->editar($exame); // Chama o método editar no DAO
        header("Location: ../views/lista_de_exames.php?status=success&message=" . urlencode("Resultado de exame atualizado com sucesso!"));
        exit();
    } catch (Exception $e) {
        // Usar getIdExame (nome do método no modelo)
        header("Location: ../views/cadastroExames.php?editar=" . $exame->getIdExame() . "&status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
}


// EXCLUIR Exame
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $exameDao->excluir($id); // Chama o método excluir no DAO
    header("Location: ../views/lista_de_exames.php"); // Redireciona para a lista de exames
    exit();
}

// Lógica para carregar DADOS DE EDIÇÃO DE UM EXAME (Se 'editar' está na URL)
// Esta lógica é executada SEMPRE que o controller é incluído e o parâmetro 'editar' existe.
// Isso setará a variável $exame (global para o controller) que a view poderá acessar para preencher o formulário.
if(isset($_GET['editar'])) {
    $idExame = $_GET['editar'];
    $exame = $exameDao->buscarPorId($idExame); // $exame agora conterá o objeto ResultadoExames
    if (!isset($exame) || $exame === false) { // Verifica se o exame foi encontrado ou se houve erro no DAO
        // Redireciona com mensagem de erro se não encontrar ou se o DAO retornar false/null
        header("Location: ../views/lista_de_exames.php?status=error&message=" . urlencode("Exame de ID {$idExame} não encontrado para edição ou erro ao buscar."));
        exit();
    }
}


// Função para listar exames na interface (GERA O HTML, SEGUINDO O PADRÃO PessoaController.php)
function listarExames(){
    global $exameDao; // Garante que você está usando a instância global
    $lista = $exameDao->read(); // Lê todos os exames da API Node.js

    if (!empty($lista)) {
        foreach($lista as $exame){
            // Use os getters CORRETOS do seu objeto ResultadoExames para a exibição na tabela
            echo "<tr> 
                    <td>" . htmlspecialchars($exame->getLaudoId() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($exame->getPacienteRegistro() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($exame->getNomeExame() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($exame->getValorAbsoluto() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($exame->getValorReferencia() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($exame->getDataHora() ?? 'N/A') . "</td>
                    <td> 
                        <a href='cadastroExames.php?editar=" . htmlspecialchars($exame->getIdExame() ?? '') . "'>Editar</a>
                        &nbsp; | &nbsp; 
                        <a href='../controller/ExameController.php?excluir=" . htmlspecialchars($exame->getIdExame() ?? '') . "' onclick=\"return confirm('Tem certeza que deseja excluir este exame?')\">Excluir</a>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Nenhum exame encontrado.</td></tr>";
    }
}
?>