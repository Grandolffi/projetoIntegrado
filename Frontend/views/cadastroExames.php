<?php
// Para ver erros PHP, se houver
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui o controlador de exames
require_once __DIR__ . '/../controller/ExameController.php';
// Inclui o modelo ResultadoExames
require_once __DIR__ . '/../model/ResultadoExames.php';

// Variáveis de controle para o formulário
$exames_solicitados_para_preencher = [];
// Alterei aqui: Variável para ID do paciente no formulário, consistente com o que será exibido
$paciente_id_formulario = null; 
$data_laudo_prevista = null; // Usado para a data/hora do input no modo de preenchimento de solicitação
$solicitacao_id = null;
$errorMessage = null;
$exameParaEdicao = null; // Objeto ResultadoExames para edição
$solicitacaoId = $_GET['solicitacao_id'] ?? null;
$solicitacaoData = null;

if ($solicitacaoId) {
    $api_url = "http://localhost:3000/solicitacoes/$solicitacaoId"; // GET /solicitacoes/:id
    $response = @file_get_contents($api_url);
    if ($response !== false) {
        $solicitacaoData = json_decode($response, true);
    }
}

// --- INÍCIO DA LÓGICA REESTRUTURADA ---

// 1. Prioriza a EDIÇÃO DE UM EXAME INDIVIDUAL (se 'editar' está na URL)
if (isset($_GET['editar'])) {
    $idExameParaEditar = $_GET['editar'];

    // A variável $exame (definida globalmente no ExameController.php) já deve conter o objeto
    // após a inclusão de ExameController.php e o processamento de seu GET 'editar'.
    global $exame; // Acesse a variável global $exame do ExameController.php
    $exameParaEdicao = $exame;

    if (!isset($exameParaEdicao) || $exameParaEdicao === false) { // Verifica se o exame foi encontrado
        $errorMessage = "Exame não encontrado para edição (ID: " . htmlspecialchars($idExameParaEditar) . ").";
    } else {
        // Alterei aqui: Preenche campos com o pacienteIdFk do exame para edição
        $paciente_id_formulario = $exameParaEdicao->getPacienteIdFk(); // Usar getPacienteIdFk()
        // Para a data/hora, use a do exame, formatando para datetime-local
        if ($exameParaEdicao->getDataHora()) {
            try {
                $dt = new DateTime($exameParaEdicao->getDataHora());
                $data_laudo_prevista = $dt->format('Y-m-d\TH:i'); // Formato datetime-local
            } catch (Exception $e) { /* fallback */ }
        }
    }
}
// 2. Senão, verifica se é um PREENCHIMENTO DE LAUDO PARA UMA SOLICITAÇÃO (se 'solicitacao_id' está na URL)
elseif (isset($_GET['solicitacao_id'])) {
    $solicitacao_id = $_GET['solicitacao_id'];
    // Ajuste o endpoint conforme sua API Node.js (sem /api/)
    $api_url_solicitacao = "http://localhost:3000/solicitacoes/" . urlencode($solicitacao_id);

    $response = @file_get_contents($api_url_solicitacao);
    $solicitacao_data = json_decode($response, true);

    if ($response === false) {
        $errorMessage = "Erro ao conectar com a API para carregar detalhes da solicitação.";
    } elseif ($solicitacao_data && isset($solicitacao_data['exames'])) {
        // Alterei aqui: Pega o paciente_id da solicitação para o campo do formulário
        $paciente_id_formulario = $solicitacao_data['paciente_id'] ?? 'N/A'; 
        // Adapte data_laudo_prevista para o formato datetime-local
        if (isset($solicitacao_data['data_prevista_realizacao'])) {
            try {
                $dt = new DateTime($solicitacao_data['data_prevista_realizacao']);
                $data_laudo_prevista = $dt->format('Y-m-d\TH:i');
            } catch (Exception $e) {
                // Tenta pegar apenas a parte da data e hora se não for um formato completo
                $data_laudo_prevista = substr($solicitacao_data['data_prevista_realizacao'], 0, 16);
            }
        }
        $exames_solicitados_para_preencher = $solicitacao_data['exames'];
    } else {
        $errorMessage = "Solicitação não encontrada ou erro ao carregar dados.";
    }
}
// 3. Caso não haja nem 'editar' nem 'solicitacao_id' (página acessada sem parâmetros)
else {
    $errorMessage = "Nenhum ID de solicitação ou exame fornecido para preenchimento/edição. Por favor, selecione uma solicitação ou exame na lista.";
}

// --- FIM DA LÓGICA REESTRUTURADA ---
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($exameParaEdicao) ? 'Editar Exame' : 'Preencher Resultado de Exame'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/Style.css">
</head>
<body class="corpo-dashboard">
    <div class="container-dashboard">
        <?php include_once __DIR__ . '/menuLateral.php';?>

        <main class="conteudo-principal">
            <header class="cabecalho-principal">
                <h2><?php echo isset($exameParaEdicao) ? 'Editar Exame' : 'Resultado de Exame'; ?></h2>
                <?php include_once __DIR__ . '/info_cabecalho.php'; ?>
            </header>
            <div class="form-container">
                <?php if (isset($errorMessage)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($errorMessage); ?>
                    </div>
                    <?php if (isset($_GET['editar'])): ?>
                        <a href="lista_de_exames.php" class="btn btn-secondary">Voltar para Lista de Exames</a>
                    <?php else: ?>
                        <a href="lista_solicitacoes_pendentes.php" class="btn btn-secondary">Voltar para Solicitações</a>
                    <?php endif; ?>
                <?php else: ?>
                    <form action="<?php 
                        if (isset($exameParaEdicao)) { // Se estiver editando um exame individual
                            echo '../controller/ExameController.php'; 
                        } elseif (isset($_GET['solicitacao_id'])) { // Se estiver preenchendo resultados para uma solicitação
                            echo '../controller/LaudoController.php'; 
                        } else {
                            // Caso de fallback, não deveria ocorrer se o acesso for sempre via lista
                            echo '#'; 
                        }
                    ?>" method="POST"> 
                        <?php if (isset($exameParaEdicao)): ?>
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($exameParaEdicao->getIdExame() ?? ''); ?>">
                            <input type="hidden" name="salvar_edicao" value="true">
                            <input type="hidden" name="nome_exame" value="<?php echo htmlspecialchars($exameParaEdicao->getNomeExame() ?? ''); ?>">
                            <input type="hidden" name="tipo_exame" value="<?php echo htmlspecialchars($exameParaEdicao->getTipoExame() ?? ''); ?>">
                            <input type="hidden" name="valor_referencia" value="<?php echo htmlspecialchars($exameParaEdicao->getValorReferencia() ?? ''); ?>">
                            <input type="hidden" name="laudo_id" value="<?php echo htmlspecialchars($exameParaEdicao->getLaudoId() ?? ''); ?>">

                        <?php elseif (isset($_GET['solicitacao_id'])): ?>
                            <input type="hidden" name="solicitacao_id" value="<?php echo htmlspecialchars($solicitacao_id ?? ''); ?>">
                            <input type="hidden" name="salvar_novo_laudo" value="true">
                            <input type="hidden" name="paciente_id" value="<?php echo htmlspecialchars($paciente_id_formulario ?? ''); ?>">
                            <input type="hidden" name="data_laudo" value="<?php echo htmlspecialchars(substr($data_laudo_prevista ?? '', 0, 10)); ?>">
                        <?php endif; ?>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="paciente_id_input" class="form-label">ID do Paciente</label>
                                <input type="text" class="form-control" name="paciente_id" id="paciente_id_input"
                                       value="<?php echo htmlspecialchars($paciente_id_formulario ?? ''); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="data_realizacao_input" class="form-label">Data e Hora da Realização do Exame</label>
                                <input type="datetime-local" class="form-control" name="data_hora_exame" id="data_realizacao_input"
                                       value="<?php echo htmlspecialchars($data_laudo_prevista ?? ''); ?>" required>
                            </div>
                            <?php if (isset($exameParaEdicao)): ?>
                            <div class="col-md-4">
                                <label for="laudo_id_input" class="form-label">ID do Laudo (se aplicável)</label>
                                <input type="number" class="form-control" name="laudo_id" id="laudo_id_input"
                                       value="<?php echo htmlspecialchars($exameParaEdicao->getLaudoId() ?? ''); ?>">
                            </div>
                            <?php endif; ?>
                        </div>

                        <h4 class="mt-4 mb-3">Resultados dos Exames</h4>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered align-middle text-center">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-start">Exame</th>
                                        <th scope="col">Valor de Referência</th>
                                        <th scope="col">Resultado do Paciente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Se estiver no modo de EDIÇÃO de UM EXAME INDIVIDUAL
                                    if (isset($exameParaEdicao)): ?>
                                    <tr>
                                        <td class="text-start">
                                            <input type="hidden" name="nome_exame" value="<?php echo htmlspecialchars($exameParaEdicao->getNomeExame() ?? ''); ?>">
                                            <input type="hidden" name="tipo_exame" value="<?php echo htmlspecialchars($exameParaEdicao->getTipoExame() ?? ''); ?>">
                                            <input type="hidden" name="valor_referencia" value="<?php echo htmlspecialchars($exameParaEdicao->getValorReferencia() ?? ''); ?>"> <?php echo htmlspecialchars($exameParaEdicao->getNomeExame() ?? 'N/A'); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($exameParaEdicao->getValorReferencia() ?? 'N/A'); ?></td>
                                        <td>
                                            <input type="text" class="form-control text-center"
                                                   name="valor_absoluto"
                                                   id="valor_absoluto_exame"
                                                   placeholder="Digite o resultado"
                                                   value="<?php echo htmlspecialchars($exameParaEdicao->getValorAbsoluto() ?? ''); ?>"
                                                   aria-label="Resultado para <?php echo htmlspecialchars($exameParaEdicao->getNomeExame() ?? ''); ?>">
                                        </td>
                                    </tr>
                                    <?php
                                    // Senão, se for um PREENCHIMENTO DE LAUDO PARA UMA SOLICITAÇÃO (múltiplos exames)
                                    elseif (!empty($exames_solicitados_para_preencher)): ?>
                                        <?php foreach ($exames_solicitados_para_preencher as $exame_solicitado): ?>
                                        <tr>
                                            <td class="text-start">
                                                <input type="hidden" name="resultados[<?php echo htmlspecialchars($exame_solicitado['nome_exame'] ?? ''); ?>][nome_exame]" value="<?php echo htmlspecialchars($exame_solicitado['nome_exame'] ?? ''); ?>">
                                                <input type="hidden" name="resultados[<?php echo htmlspecialchars($exame_solicitado['nome_exame'] ?? ''); ?>][tipo_exame]" value="<?php echo htmlspecialchars($exame_solicitado['tipo_exame_categoria'] ?? ''); ?>">
                                                <input type="hidden" name="resultados[<?php echo htmlspecialchars($exame_solicitado['nome_exame'] ?? ''); ?>][valor_referencia]" value="<?php echo htmlspecialchars($exame_solicitado['valor_referencia_solicitacao'] ?? ''); ?>">
                                                <?php echo htmlspecialchars($exame_solicitado['nome_exame'] ?? ''); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($exame_solicitado['valor_referencia_solicitacao'] ?? 'N/A'); ?></td>
                                            <td>
                                                <input type="text" class="form-control text-center"
                                                       name="resultados[<?php echo htmlspecialchars($exame_solicitado['nome_exame'] ?? ''); ?>][valor_absoluto]"
                                                       id="exame_<?php echo htmlspecialchars(str_replace([' ', '(', ')', '-', '/', '.'], '_', strtolower($exame_solicitado['nome_exame'] ?? ''))); ?>"
                                                       placeholder="Digite o resultado"
                                                       aria-label="Resultado para <?php echo htmlspecialchars($exame_solicitado['nome_exame'] ?? ''); ?>">
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="3">Nenhum exame para preencher ou ID inválido.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Salvar</button>
                    </form>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../public/js/validacoes.js"></script>
</body>
</html>