<?php
// Para exibir erros (somente em ambiente de desenvolvimento)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../controller/SolicitacaoController.php';
$solicitacoes = listarSolicitacoesPendentes();

// Variáveis para popular o formulário (edição ou novo)
$titulo_pagina = "Solicitar Novo Exame";
$botao_submit_nome = "marcar_exame";
$botao_submit_texto = "Marcar Exame";
$solicitacao_id_form = '';
$paciente_id_form = '';
$nome_paciente_form = '';
$data_marcada_exame_form = '';
$solicitante_nome_form = '';
$status_form = 'Pendente';
$observacoes_form = '';
$exames_solicitados_itens_para_form = [];

if (isset($solicitacaoParaEdicao) && $solicitacaoParaEdicao instanceof Solicitacao) {
    $titulo_pagina = "Editar Solicitação de Exame";
    $botao_submit_nome = "salvar_edicao_solicitacao";
    $botao_submit_texto = "Salvar Edição";

    $solicitacao_id_form = $solicitacaoParaEdicao->getIdSolicitacao();
    $paciente_id_form = $solicitacaoParaEdicao->getPacienteId();

    if (method_exists($solicitacaoParaEdicao, 'getNomePaciente')) {
        $nome_paciente_form = $solicitacaoParaEdicao->getNomePaciente();
    }

    if ($solicitacaoParaEdicao->getDataPrevistaRealizacao()) {
        try {
            $dt = new DateTime($solicitacaoParaEdicao->getDataPrevistaRealizacao());
            $data_marcada_exame_form = $dt->format('Y-m-d\TH:i');
        } catch (Exception $e) {}
    }
    $solicitante_nome_form = $solicitacaoParaEdicao->getSolicitanteNome();
    $status_form = $solicitacaoParaEdicao->getStatus();
    $observacoes_form = $solicitacaoParaEdicao->getObservacoes();

    if (!empty($solicitacaoParaEdicao->getExamesItens())) {
        foreach ($solicitacaoParaEdicao->getExamesItens() as $item) {
            $exames_solicitados_itens_para_form[$item->getTipoExameCategoria()][] = $item->getNomeExame();
        }
    }
}

$labs = ['Microbiologia', 'Parasitologia', 'Hematologia', 'Bioquimica', 'Urinálise'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo htmlspecialchars($titulo_pagina); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../public/css/Style.css" />
    <style>
        .sub-opcoes { margin-left: 20px; margin-bottom: 20px; }
    </style>
</head>
<body class="corpo-dashboard">
<div class="container-dashboard">
    <?php include __DIR__ . '/menuLateral.php'; ?>

    <main class="conteudo-principal">
        <header class="cabecalho-principal">
            <h2><?php echo htmlspecialchars($titulo_pagina); ?></h2>
            <?php include __DIR__ . '/info_cabecalho.php'; ?>
        </header>

        <div class="form-container">
            <form action="../controller/SolicitacaoController.php" method="POST" novalidate>
                <?php if ($solicitacao_id_form): ?>
                    <input type="hidden" name="id_solicitacao" value="<?php echo htmlspecialchars($solicitacao_id_form); ?>" />
                <?php endif; ?>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome do Paciente</label>
                        <input type="text" name="nome" id="nome" class="form-control" value="<?php echo htmlspecialchars($nome_paciente_form); ?>" required />
                    </div>
                    <div class="col-md-6">
                        <label for="idPac" class="form-label">ID do Paciente</label>
                        <input type="text" name="idPac" id="idPac" class="form-control" value="<?php echo htmlspecialchars($paciente_id_form); ?>" required />
                    </div>
                </div>

                <div class="mb-3">
                    <label for="data_marcada_exame" class="form-label">Data e Hora para Marcação do Exame</label>
                    <input type="datetime-local" class="form-control" name="data_marcada_exame" id="data_marcada_exame" value="<?php echo htmlspecialchars($data_marcada_exame_form); ?>" required />
                </div>

                <?php if ($solicitacao_id_form): ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="solicitante_nome" class="form-label">Nome do Solicitante</label>
                            <input type="text" name="solicitante_nome" id="solicitante_nome" class="form-control" value="<?php echo htmlspecialchars($solicitante_nome_form); ?>" />
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status da Solicitação</label>
                            <select name="status" id="status" class="form-select">
                                <option value="Pendente" <?php if ($status_form === 'Pendente') echo 'selected'; ?>>Pendente</option>
                                <option value="Coletado" <?php if ($status_form === 'Coletado') echo 'selected'; ?>>Coletado</option>
                                <option value="Em Análise" <?php if ($status_form === 'Em Análise') echo 'selected'; ?>>Em Análise</option>
                                <option value="Concluído" <?php if ($status_form === 'Concluído') echo 'selected'; ?>>Concluído</option>
                                <option value="Cancelado" <?php if ($status_form === 'Cancelado') echo 'selected'; ?>>Cancelado</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="observacoes" class="form-label">Observações</label>
                        <textarea name="observacoes" id="observacoes" class="form-control" rows="3"><?php echo htmlspecialchars($observacoes_form); ?></textarea>
                    </div>
                <?php endif; ?>

                <p class="fw-bold mt-4">Laboratórios Solicitados:</p>
                <div class="d-flex flex-wrap gap-3 mb-3">
                    <?php foreach ($labs as $lab): ?>
                        <div class="form-check">
                            <input
                                type="checkbox"
                                name="laboratorioSolicitado[]"
                                id="<?php echo $lab; ?>"
                                value="<?php echo $lab; ?>"
                                class="form-check-input"
                                onchange="toggleSubOptions('opcoes<?php echo $lab; ?>', this.checked)"
                                <?php echo isset($exames_solicitados_itens_para_form[$lab]) ? 'checked' : ''; ?>
                            />
                            <label for="<?php echo $lab; ?>" class="form-check-label"><?php echo $lab; ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Sub-opções para cada laboratório -->
                <?php
                // Lista de exames por laboratório (exemplos)
                $examesPorLab = [
                    'Bioquimica' => [
                        'Ácido úrico', 'Alfa amilase', 'Bilirrubina Total', 'Bilirrubina Direta',
                        'Cálcio', 'Colesterol', 'HDL', 'Creatinina', 'Ferro Ferene', 'Fosfatase Alcalina',
                        'Fosfato', 'Gama GT', 'Glicose', 'GOT (AST)', 'GTP (ALT)', 'Magnésio',
                        'Proteína total', 'Triglicerídeos', 'Uréia'
                    ],
                    'Microbiologia' => [
                        'Urocultura com antibiograma', 'Swab ocular', 'Escarro para Micobacterium tuberculosis'
                    ],
                    'Parasitologia' => [
                        'Exame de fezes', 'Pesquisa de hematozoários', 'Pesquisa de protozoários intestinais'
                    ],
                    'Hematologia' => [
                        'Hemograma completo', 'Reticulócitos', 'Tempo de sangramento', 'Tempo de coagulação'
                    ],
                    'Urinálise' => [
                        'Exame físico', 'Exame químico', 'Exame microscópico'
                    ]
                ];

                foreach ($examesPorLab as $lab => $exames):
                    $examesSelecionados = $exames_solicitados_itens_para_form[$lab] ?? [];
                    ?>
                    <div id="opcoes<?php echo $lab; ?>" class="sub-opcoes" style="display: none;">
                        <h6>Exames de <?php echo $lab; ?>:</h6>
                        <?php foreach ($exames as $exame): 
                            $exameId = strtolower(preg_replace('/[^a-z0-9]/i', '', $exame));
                        ?>
                            <div class="form-check">
                                <input
                                    type="checkbox"
                                    name="examesSolicitados[<?php echo $lab; ?>][]"
                                    value="<?php echo $exame; ?>"
                                    id="<?php echo $exameId; ?>"
                                    class="form-check-input"
                                    <?php echo in_array($exame, $examesSelecionados) ? 'checked' : ''; ?>
                                />
                                <label for="<?php echo $exameId; ?>" class="form-check-label"><?php echo $exame; ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>

                <div class="text-center mt-4">
                    <button type="submit" name="<?php echo htmlspecialchars($botao_submit_nome); ?>" class="btn btn-dark">
                        <?php echo htmlspecialchars($botao_submit_texto); ?>
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSubOptions(elementId, isChecked) {
    const element = document.getElementById(elementId);
    if (element) {
        element.style.display = isChecked ? 'block' : 'none';
    }
}
window.addEventListener('DOMContentLoaded', () => {
    <?php foreach ($labs as $lab): ?>
        toggleSubOptions('opcoes<?php echo $lab; ?>', <?php echo isset($exames_solicitados_itens_para_form[$lab]) ? 'true' : 'false'; ?>);
    <?php endforeach; ?>
});
</script>
</body>
</html>
