<?php
// Para ver erros PHP, se houver
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Incluir as dependências do Model e do DAO
require_once __DIR__ . '/../model/Amostra.php';       // Modelo da Amostra
require_once __DIR__ . '/../dao/AmostraDaoApi.php'; // DAO que se comunica com a API

// 2. Instanciar o DAO
$amostraDao = new AmostraDaoApi();

// Variável para armazenar a amostra em caso de edição
$amostra = null;

// === CREATE - Processar o formulário de nova amostra ===
// Verifica a ação na URL e se o método é POST, conforme o action do seu formulário
if (isset($_GET['action']) && $_GET['action'] === 'processar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $novaAmostra = new Amostra();

    // Popula o objeto Amostra com os dados do formulário
    // Assumindo que o nome do paciente é usado para buscar um ID. O ideal seria ter um campo 'paciente_id'.
    $novaAmostra->setPacienteId($_POST['nome_paciente']); // ATENÇÃO: Trocar por um ID real do paciente
    $novaAmostra->setDataColeta($_POST['data_coleta']);
    $novaAmostra->setTipoColetaRealizada(isset($_POST['tipo_coleta']) ? 'Sim' : 'Não');
    
    // Converte o array de tubos para uma string JSON para armazenamento
    $novaAmostra->setTubosUtilizados(json_encode($_POST['cores_tubos'] ?? []));
    
    $novaAmostra->setLoteTubo($_POST['lote_tubo']);
    $novaAmostra->setValidadeTubo($_POST['validade_tubo']);
    $novaAmostra->setTamanhoSeringa($_POST['tamanho_seringa']);
    $novaAmostra->setLoteSeringa($_POST['lote_seringa']);
    $novaAmostra->setValidadeSeringa($_POST['validade_seringa']);
    $novaAmostra->setLotePoteUrina($_POST['lote_urina']);
    $novaAmostra->setValidadePoteUrina($_POST['validade_urina']);
    $novaAmostra->setLotePoteFezes($_POST['lote_fezes']);
    $novaAmostra->setValidadePoteFezes($_POST['validade_fezes']);

    try {
        $amostraDao->inserir($novaAmostra);
        // Redireciona para a página do formulário com mensagem de sucesso
        header("Location: ../views/coletaDeAmostras.php?status=success&message=" . urlencode("Amostra registrada com sucesso!"));
        exit();
    } catch (Exception $e) {
        // Redireciona com mensagem de erro
        header("Location: ../views/coletaDeAmostras.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
}

// === READ - Buscar amostra para edição ===
// (Você precisará criar uma view de edição, como 'editarAmostra.php')
if (isset($_GET['editar'])) {
    $idAmostra = $_GET['editar'];
    $amostra = $amostraDao->buscarPorId($idAmostra);
    if (!$amostra) {
        header("Location: ../views/listaDeAmostras.php?status=error&message=" . urlencode("Amostra não encontrada."));
        exit();
    }
}

// === UPDATE - Salvar edição de uma amostra ===
// (O formulário de edição enviaria um POST com o campo 'salvar_edicao')
if (isset($_POST['salvar_edicao'])) {
    $amostraEditada = new Amostra();
    $amostraEditada->setId($_POST['id']); // O ID vem de um campo oculto no form de edição
    
    // Repete o processo de popular o objeto
    $amostraEditada->setPacienteId($_POST['paciente_id']);
    $amostraEditada->setDataColeta($_POST['data_coleta']);
    // ... preencher todos os outros campos do formulário de edição ...
    
    try {
        $amostraDao->editar($amostraEditada);
        header("Location: ../views/listaDeAmostras.php?status=success&message=" . urlencode("Amostra atualizada com sucesso!"));
        exit();
    } catch (Exception $e) {
        header("Location: ../views/editarAmostra.php?id=" . $amostraEditada->getId() . "&status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
}

// === DELETE - Excluir amostra ===
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    try {
        $amostraDao->excluir($id);
        header("Location: ../views/listaDeAmostras.php?status=success&message=" . urlencode("Amostra excluída com sucesso!"));
        exit();
    } catch (Exception $e) {
        header("Location: ../views/listaDeAmostras.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
}

/**
 * Função para listar todas as amostras em uma tabela HTML.
 * Pode ser chamada de uma view como 'listaDeAmostras.php'.
 */
function listarAmostras() {
    global $amostraDao; // Usa a instância do DAO criada no escopo global do script
    $lista = $amostraDao->read();

    if (!empty($lista)) {
        foreach($lista as $amostra) {
            // Decodifica a string JSON de tubos para exibição
            $tubos = json_decode($amostra->getTubosUtilizados() ?? '[]', true);
            $tubosStr = !empty($tubos) ? implode(', ', $tubos) : 'N/A';

            echo "<tr> 
                    <td>" . htmlspecialchars($amostra->getId() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($amostra->getPacienteId() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($amostra->getDataColeta() ?? 'N/A') . "</td>
                    <td>" . htmlspecialchars($tubosStr) . "</td>
                    <td>" . htmlspecialchars($amostra->getLoteSeringa() ?? 'N/A') . "</td>
                    <td>
                        <a href='editarAmostra.php?editar=" . htmlspecialchars($amostra->getId() ?? '') . "'>Editar</a> |
                        <a href='../controller/ColetaDeAmostrasController.php?excluir=" . htmlspecialchars($amostra->getId() ?? '') . "' onclick=\"return confirm('Tem certeza que deseja excluir esta amostra?')\">Excluir</a>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>Nenhuma amostra encontrada.</td></tr>";
    }
}