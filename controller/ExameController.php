<?php

// Incluir as dependências do DAO PHP e da Model
require_once __DIR__ . '/../dao/ConnectionFactory.php'; // Para a conexão com o banco de dados
require_once __DIR__ . '/../dao/ExameDao.php';        // O DAO PHP que irá interagir com o banco
require_once __DIR__ . '/../model/ResultadoExames.php'; // O Modelo do Exame
require_once __DIR__ . '/../model/Laudo.php'; // Pode ser necessário se a lógica de laudo for aqui

// Instanciar o DAO do Exame
$exameDao = new ExameDao();

// Array com as definições dos exames (ainda útil para referências e tipagem)
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

// Lógica para CADASTRO de Exame (formulário para cadastrar exames individuais)
if (isset($_POST['cadastrar_exame'])) {
    $exame = new ResultadoExames();
    // precisa garantir que todos esses campos estão sendo enviados pelo formulário
    $exame->setLaudoId($_POST['laudo_id'] ?? null); // Se um exame pode ser cadastrado sem um laudo ainda
    $exame->setNomeExame($_POST['nome_exame'] ?? null);
    $tipoExameKey = $_POST['tipo_exame'] ?? null;
    $exame->setTipoExame($tipoExameKey); // Salvar a chave do tipo, ou o nome completo
    $exame->setValorAbsoluto($_POST['valor_absoluto'] ?? null);
    
    $definicaoReferencia = $definicoesExames[$tipoExameKey][1] ?? null;
    $exame->setValorReferencia($definicaoReferencia);
    
    $exame->setPacienteRegistro($_POST['paciente_registro'] ?? null);
    $dataHoraExame = $_POST['data_hora_exame'] ?? date('Y-m-d H:i:s'); // Usar data atual se não fornecida
    if ($dataHoraExame && strpos($dataHoraExame, 'T') !== false) {
         $dataHoraExame = str_replace('T', ' ', $dataHoraExame) . ':00'; // Formatar de datetime-local
    }
    $exame->setDataHoraExame($dataHoraExame);

    try {
        $exameDao->inserir($exame);
        header("Location: ../views/lista_de_exames.php?status=success&message=" . urlencode("Exame cadastrado com sucesso!"));
        exit();
    } catch (Exception $e) {
        header("Location: ../views/cadastro_exame.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
}


// Lógica para EDITAR e SALVAR EDIÇÃO de Exame (via GET para editar, POST para salvar)
if(isset($_GET['editar'])) {
    $idExame = $_GET['editar'];
    $exame = $exameDao->buscarPorId($idExame);
    if (!isset($exame)) {
        echo "<p>Exame de ID {$idExame} não encontrado.</p>";
    }
    // A variável $exame agora pode ser usada na view 'editar_exame.php' para preencher o formulário
}

if(isset($_POST['salvar_edicao'])) {
    $exame = new ResultadoExames();
    $exame->setId($_POST['id']); // ID é crucial para o método UPDATE do DAO
    $exame->setLaudoId($_POST['laudo_id'] ?? null);
    $exame->setNomeExame($_POST['nome_exame'] ?? null);
    $tipoExameKey = $_POST['tipo_exame'] ?? null;
    $exame->setTipoExame($tipoExameKey); 

    // Tentar obter valor_referencia da definição, se tipo_exame_key existe
    $definicaoReferencia = $definicoesExames[$tipoExameKey][1] ?? $_POST['valor_referencia'];
    $exame->setValorReferencia($definicaoReferencia);

    $exame->setValorAbsoluto($_POST['valor_absoluto'] ?? null);
    $exame->setPacienteRegistro($_POST['paciente_registro'] ?? null);
    $dataHoraExame = $_POST['data_hora_exame'] ?? null;
    if ($dataHoraExame && strpos($dataHoraExame, 'T') !== false) {
         $dataHoraExame = str_replace('T', ' ', $dataHoraExame) . ':00';
    }
    $exame->setDataHoraExame($dataHoraExame);

    try {
        $exameDao->editar($exame); // Chama o método editar no DAO
        header("Location: ../views/lista_de_exames.php?status=success&message=" . urlencode("Resultado de exame atualizado com sucesso!"));
        exit();
    } catch (Exception $e) {
        header("Location: ../views/editar_exame.php?id=" . $exame->getId() . "&status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
}


// EXCLUIR Exame
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $exameDao->excluir($id); // Chama o método excluir no DAO
    header("Location: ../views/lista_de_exames.php"); // Redireciona para a lista de exames
    exit(); // Garante que o script pare após o redirecionamento
}


// Função para listar exames na interface (geralmente chamada a partir de uma view)
function listarExames(){
    $exameDao = new ExameDao(); // Instancia o DAO dentro da função
    $lista = $exameDao->read(); // Lê todos os exames do banco de dados

    if (!empty($lista)) {
        foreach($lista as $exame){
            // Atenção: Certifique-se de que os métodos get correspondem às propriedades do seu objeto ResultadoExames
            // E que os nomes das colunas HTML correspondem à sua tabela.
            echo "<tr> 
                    <td>{$exame->getId()}</td>
                    <td>{$exame->getLaudoId()}</td>
                    <td>{$exame->getNomeExame()}</td>
                    <td>{$exame->getTipoExame()}</td>
                    <td>{$exame->getValorAbsoluto()}</td>
                    <td>{$exame->getValorReferencia()}</td>
                    <td>{$exame->getPacienteRegistro()}</td>
                    <td>{$exame->getDataHoraExame()}</td>
                    <td> 
                        <a href='editar_exame.php?editar={$exame->getId()}'>Editar</a>
                        <a href='../controller/ExameController.php?excluir={$exame->getId()}' onclick=\"return confirm('Tem certeza que deseja excluir este exame?')\">Excluir</a>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='9'>Nenhum exame encontrado.</td></tr>";
    }
}
