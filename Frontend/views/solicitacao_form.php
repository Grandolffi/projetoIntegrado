<?php
// Para exibir erros (somente em ambiente de desenvolvimento)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui o controller para editar (se aplicável)
require_once __DIR__ . '/../controller/SolicitacaoController.php'; 

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

// Se estiver editando (exemplo: vindo do controller)
if (isset($solicitacaoParaEdicao) && $solicitacaoParaEdicao instanceof Solicitacao) {
    $titulo_pagina = "Editar Solicitação de Exame";
    $botao_submit_nome = "salvar_edicao_solicitacao";
    $botao_submit_texto = "Salvar Edição";

    $solicitacao_id_form = $solicitacaoParaEdicao->getIdSolicitacao();
    $paciente_id_form = $solicitacaoParaEdicao->getPacienteId();
    
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
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo htmlspecialchars($titulo_pagina); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="../public/css/Style.css" />
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
                <?php if (isset($_GET['status'])): ?>
                    <?php if ($_GET['status'] === 'success'): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo htmlspecialchars($_GET['message'] ?? 'Solicitação realizada com sucesso!'); ?>
                        </div>
                    <?php elseif ($_GET['status'] === 'error'): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($_GET['message'] ?? 'Erro ao processar solicitação.'); ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <form action="../controller/SolicitacaoController.php" method="POST" novalidate>
                    <?php if ($solicitacao_id_form): ?>
                        <input type="hidden" name="id_solicitacao" value="<?php echo htmlspecialchars($solicitacao_id_form); ?>" />
                    <?php endif; ?>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nome" class="form-label">Nome do Paciente</label>
                            <input
                                type="text"
                                name="nome"
                                id="nome"
                                class="form-control"
                                value="<?php echo htmlspecialchars($nome_paciente_form); ?>"
                                required
                            />
                        </div>
                        <div class="col-md-6">
                            <label for="idPac" class="form-label">ID do Paciente</label>
                            <input
                                type="text"
                                name="idPac"
                                id="idPac"
                                class="form-control"
                                value="<?php echo htmlspecialchars($paciente_id_form); ?>"
                                required
                            />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="data_marcada_exame" class="form-label">Data e Hora para Marcação do Exame</label>
                        <input
                            type="datetime-local"
                            class="form-control"
                            name="data_marcada_exame"
                            id="data_marcada_exame"
                            value="<?php echo htmlspecialchars($data_marcada_exame_form); ?>"
                            required
                        />
                    </div>

                    <?php if ($solicitacao_id_form): ?>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="solicitante_nome" class="form-label">Nome do Solicitante</label>
                                <input
                                    type="text"
                                    name="solicitante_nome"
                                    id="solicitante_nome"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($solicitante_nome_form); ?>"
                                />
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
                            <textarea
                                name="observacoes"
                                id="observacoes"
                                class="form-control"
                                rows="3"
                            ><?php echo htmlspecialchars($observacoes_form); ?></textarea>
                        </div>
                    <?php endif; ?>

                    <p class="fw-bold mt-4">Laboratórios Solicitados:</p>
                    <div class="d-flex flex-wrap gap-3 mb-3">
                        <?php
                        // Laboratórios fixos (você pode adaptar para gerar dinamicamente se quiser)
                        $labs = ['Microbiologia', 'Parasitologia', 'Hematologia', 'Bioquimica', 'Urinálise'];
                        foreach ($labs as $lab):
                            $checked = isset($exames_solicitados_itens_para_form[$lab]) ? 'checked' : '';
                        ?>
                            <div class="form-check">
                                <input
                                    type="checkbox"
                                    name="laboratorioSolicitado[]"
                                    id="<?php echo $lab; ?>"
                                    value="<?php echo $lab; ?>"
                                    class="form-check-input"
                                    onchange="toggleSubOptions('opcoes<?php echo $lab; ?>', this.checked)"
                                    <?php echo $checked; ?>
                                />
                                <label for="<?php echo $lab; ?>" class="form-check-label"><?php echo $lab; ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Exames detalhados - aqui você pode inserir o HTML completo para os sub-itens -->
                    <!-- Exemplo para Microbiologia -->
                    <div id="opcoesMicrobiologia" class="sub-opcoes" style="display: none;">
                        <h6>Exames de Microbiologia:</h6>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[Microbiologia][]" value="Urocultura com antibiograma" id="urocultura" class="form-check-input" />
                            <label for="urocultura" class="form-check-label">Urocultura com antibiograma</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[Microbiologia][]" value="Swab ocular" id="swab" class="form-check-input" />
                            <label for="swab" class="form-check-label">Swab ocular</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[Microbiologia][]" value="Escarro exame Micro" id="escarro" class="form-check-input" />
                            <label for="escarro" class="form-check-label">Escarro para exame de Micobacterium tuberculosis</label>
                        </div>
                    </div>

                    <!-- Repita o padrão acima para os outros laboratórios -->

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
        // Função para mostrar/esconder as sub-opções de exames
        function toggleSubOptions(elementId, isChecked) {
            const element = document.getElementById(elementId);
            if (element) {
                element.style.display = isChecked ? 'block' : 'none';
            }
        }

        // Inicializa a visibilidade das sub-opções baseado nos checkboxes marcados
        window.addEventListener('DOMContentLoaded', () => {
            <?php foreach ($labs as $lab): ?>
                toggleSubOptions('opcoes<?php echo $lab; ?>', <?php echo isset($exames_solicitados_itens_para_form[$lab]) ? 'true' : 'false'; ?>);
            <?php endforeach; ?>
        });
    </script>
</body>
</html>
