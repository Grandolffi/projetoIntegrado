<?php
// Para ver erros PHP, se houver
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir as dependências do DAO PHP e da Model
require_once __DIR__ . '/../model/ResultadoExames.php'; // Modelo ResultadoExames
require_once __DIR__ . '/../dao/ExameDaoApi.php';       // DAO que conversa com API Node.js

// Array com definições dos exames para referência e valores padrões
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

// Instancia DAO do Exame
$exameDao = new ExameDaoApi();

// Inicializa variável global $exame para edição e leitura
$exame = null;

// === CREATE - Inserir novo exame ===
// Este bloco é para criar um ResultadoExames individual
if (isset($_POST['salvar_novo_laudo'])) { // Apesar do nome, este bloco em ExameController lida com um único ResultadoExames
    $exame = new ResultadoExames();
    $exame->setLaudoId($_POST['laudo_id'] ?? null);
    $exame->setNomeExame($_POST['nome_exame'] ?? null);
    $tipoExameKey = $_POST['tipo_exame'] ?? null;
    $exame->setTipoExame($tipoExameKey);
    $exame->setValorReferencia($definicoesExames[$tipoExameKey][1] ?? null);
    $exame->setValorAbsoluto($_POST['valor_absoluto'] ?? null);
    // Usando paciente_id do POST para setar pacienteIdFk
    $exame->setPacienteIdFk((int)$_POST['paciente_id'] ?? null); 
    
    $dataHoraExame = $_POST['data_hora_exame'] ?? date('Y-m-d H:i:s');
    if ($dataHoraExame && strpos($dataHoraExame, 'T') !== false) {
        $dataHoraExame = str_replace('T', ' ', $dataHoraExame) . ':00';
    }
    $exame->setDataHora($dataHoraExame);
    
    try {
        $exameDao->inserir($exame);
        header("Location: ../views/lista_de_exames.php?status=success&message=" . urlencode("Exame cadastrado com sucesso!"));
        exit();
    } catch (Exception $e) {
        header("Location: ../views/cadastroExames.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
}

// === READ - Buscar exame para edição ===
if (isset($_GET['editar'])) {
    $idExame = $_GET['editar'];
    $exame = $exameDao->buscarPorId($idExame);
    if (!$exame) {
        header("Location: ../views/lista_de_exames.php?status=error&message=" . urlencode("Exame não encontrado para edição."));
        exit();
    }
}

// === UPDATE - Salvar edição ===
if (isset($_POST['salvar_edicao'])) {
    $exame = new ResultadoExames();
    $exame->setIdExame($_POST['id']);
    $exame->setLaudoId($_POST['laudo_id'] ?? null);
    $exame->setNomeExame($_POST['nome_exame'] ?? null);
    $exame->setTipoExame($_POST['tipo_exame'] ?? null);
    $exame->setValorReferencia($_POST['valor_referencia'] ?? null);
    $exame->setValorAbsoluto($_POST['valor_absoluto'] ?? null);
    // Usando paciente_id do POST para setar pacienteIdFk na edição
    $exame->setPacienteIdFk((int)$_POST['paciente_id'] ?? null); 
    
    $dataHoraExame = $_POST['data_hora_exame'] ?? null;
    if ($dataHoraExame && strpos($dataHoraExame, 'T') !== false) {
        $dataHoraExame = str_replace('T', ' ', $dataHoraExame) . ':00';
    }
    $exame->setDataHora($dataHoraExame);

    try {
        $exameDao->editar($exame);
        header("Location: ../views/lista_de_exames.php?status=success&message=" . urlencode("Exame atualizado com sucesso!"));
        exit();
    } catch (Exception $e) {
        header("Location: ../views/cadastroExames.php?editar=" . $exame->getIdExame() . "&status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
}

// === DELETE - Excluir exame ===
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    try {
        $exameDao->excluir($id);
        header("Location: ../views/lista_de_exames.php?status=success&message=" . urlencode("Exame excluído com sucesso!"));
        exit();
    } catch (Exception $e) {
        header("Location: ../views/lista_de_exames.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
}

// === Função para listar todos exames (para a view) ===
function listarExames(){
    global $exameDao;
    $lista = $exameDao->read();

    if (!empty($lista)) {
        foreach($lista as $exame) {
            echo "<tr> 
                    <td>" . htmlspecialchars($exame->getLaudoId() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($exame->getPacienteIdFk() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($exame->getNomeExame() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($exame->getValorAbsoluto() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($exame->getValorReferencia() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($exame->getDataHora() ?? 'N/A') . "</td>
                    <td>
                        <a href='cadastroExames.php?editar=" . htmlspecialchars($exame->getIdExame() ?? '') . "'>Editar</a> |
                        <a href='../controller/ExameController.php?excluir=" . htmlspecialchars($exame->getIdExame() ?? '') . "' onclick=\"return confirm('Tem certeza que deseja excluir este exame?')\">Excluir</a>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Nenhum exame encontrado.</td></tr>";
    }
}

?>