<?php
// para ver erros PHP, se houver
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui o controlador de solicitações
require_once __DIR__ . '/../controller/SolicitacaoController.php'; 

// A variável $solicitacaoParaEdicao será definida pelo SolicitacaoController.php
// se $_GET['editar'] estiver presente.
// Acesso a ela para preencher o formulário.

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
$exames_solicitados_itens_para_form = []; // Para pré-selecionar checkboxes em edição

// Verifica se está no modo de edição
if (isset($solicitacaoParaEdicao) && $solicitacaoParaEdicao instanceof Solicitacao) {
    $titulo_pagina = "Editar Solicitação de Exame";
    $botao_submit_nome = "salvar_edicao_solicitacao";
    $botao_submit_texto = "Salvar Edição";

    $solicitacao_id_form = $solicitacaoParaEdicao->getIdSolicitacao();
    $paciente_id_form = $solicitacaoParaEdicao->getPacienteId();
    // O nome do paciente não está diretamente no modelo Solicitacao, precisaria buscar de Pessoa.
    // Para simplificar, vou deixar vazio ou você pode buscar aqui.
    // $nome_paciente_form = ... buscar de pessoa ...
    
    // Formatar data/hora para datetime-local
    if ($solicitacaoParaEdicao->getDataPrevistaRealizacao()) {
        try {
            $dt = new DateTime($solicitacaoParaEdicao->getDataPrevistaRealizacao());
            $data_marcada_exame_form = $dt->format('Y-m-d\TH:i');
        } catch (Exception $e) { /* fallback */ }
    }
    $solicitante_nome_form = $solicitacaoParaEdicao->getSolicitanteNome();
    $status_form = $solicitacaoParaEdicao->getStatus();
    $observacoes_form = $solicitacaoParaEdicao->getObservacoes();
    
    // Popula os itens de exames para marcar os checkboxes
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo_pagina; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <link rel="stylesheet" href="../public/css/Style.css">
</head>
<body class="corpo-dashboard">
    <div class="container-dashboard">
        <?php include __DIR__ . '/menuLateral.php'; // Inclui o menu lateral padrão ?>

        <main class="conteudo-principal">
            <header class="cabecalho-principal">
                <h2><?php echo $titulo_pagina; ?></h2>
                <?php include __DIR__ . '/info_cabecalho.php'; // Inclui saudação, data e hora ?>
            </header>

            <div class="form-container">
                <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo htmlspecialchars($_GET['message']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($_GET['message']); ?>
                    </div>
                <?php endif; ?>

                <form action="../controller/SolicitacaoController.php" method="POST">
                    <?php if (isset($solicitacaoParaEdicao)): ?>
                    <input type="hidden" name="id_solicitacao" value="<?php echo htmlspecialchars($solicitacao_id_form); ?>">
                    <?php endif; ?>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nome" class="form-label">Nome do Paciente</label>
                            <input type="text" name="nome" id="nome" class="form-control" value="<?php echo htmlspecialchars($nome_paciente_form); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="idPac" class="form-label">ID do Paciente</label>
                            <input type="text" name="idPac" id="idPac" class="form-control" value="<?php echo htmlspecialchars($paciente_id_form); ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="data_marcada_exame" class="form-label">Data e Hora para Marcação do Exame</label>
                        <input type="datetime-local" class="form-control" name="data_marcada_exame" id="data_marcada_exame" value="<?php echo htmlspecialchars($data_marcada_exame_form); ?>" required>
                    </div>

                    <?php if (isset($solicitacaoParaEdicao)): // Campos adicionais para edição, se aplicável ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="solicitante_nome" class="form-label">Nome do Solicitante</label>
                            <input type="text" name="solicitante_nome" id="solicitante_nome" class="form-control" value="<?php echo htmlspecialchars($solicitante_nome_form); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status da Solicitação</label>
                            <select name="status" id="status" class="form-select">
                                <option value="Pendente" <?php echo ($status_form == 'Pendente' ? 'selected' : ''); ?>>Pendente</option>
                                <option value="Coletado" <?php echo ($status_form == 'Coletado' ? 'selected' : ''); ?>>Coletado</option>
                                <option value="Em Análise" <?php echo ($status_form == 'Em Análise' ? 'selected' : ''); ?>>Em Análise</option>
                                <option value="Concluído" <?php echo ($status_form == 'Concluído' ? 'selected' : ''); ?>>Concluído</option>
                                <option value="Cancelado" <?php echo ($status_form == 'Cancelado' ? 'selected' : ''); ?>>Cancelado</option>
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
                        <div class="form-check">
                            <input type="checkbox" name="laboratorioSolicitado[]" id="Microbiologia" value="Microbiologia"
                                class="form-check-input" onchange="toggleSubOptions('opcoesMicrobiologia', this.checked)"
                                <?php echo (isset($exames_solicitados_itens_para_form['Microbiologia']) ? 'checked' : ''); ?>>
                            <label for="Microbiologia" class="form-check-label">Microbiologia</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="laboratorioSolicitado[]" id="Parasitologia" value="Parasitologia"
                                class="form-check-input" onchange="toggleSubOptions('opcoesParasitologia', this.checked)"
                                <?php echo (isset($exames_solicitados_itens_para_form['Parasitologia']) ? 'checked' : ''); ?>>
                            <label for="Parasitologia" class="form-check-label">Parasitologia</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="laboratorioSolicitado[]" id="Hematologia" value="Hematologia"
                                class="form-check-input" onchange="toggleSubOptions('opcoesHematologia', this.checked)"
                                <?php echo (isset($exames_solicitados_itens_para_form['Hematologia']) ? 'checked' : ''); ?>>
                            <label for="Hematologia" class="form-check-label">Hematologia</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="laboratorioSolicitado[]" id="Bioquimica" value="Bioquimica"
                                class="form-check-input" onchange="toggleSubOptions('opcoesBioquimica', this.checked)"
                                <?php echo (isset($exames_solicitados_itens_para_form['Bioquimica']) ? 'checked' : ''); ?>>
                            <label for="Bioquimica" class="form-check-label">Bioquímica</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="laboratorioSolicitado[]" id="Urinalise" value="Urinálise"
                                class="form-check-input" onchange="toggleSubOptions('opcoesUrinalise', this.checked)"
                                <?php echo (isset($exames_solicitados_itens_para_form['Urinálise']) ? 'checked' : ''); ?>>
                            <label for="Urinalise" class="form-check-label">Urinálise</label>
                        </div>
                    </div>

                    <div id="opcoesMicrobiologia" class="sub-opcoes">
                        <h6>Exames de Microbiologia:</h6>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Microbiologia][]" value="Urocultura com antibiograma" class="form-check-input" id="urocultura" <?php echo (isset($exames_solicitados_itens_para_form['Microbiologia']) && in_array('Urocultura com antibiograma', $exames_solicitados_itens_para_form['Microbiologia']) ? 'checked' : ''); ?>><label class="form-check-label" for="urocultura">Urocultura com antibiograma</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Microbiologia][]" value="Swab ocular" class="form-check-input" id="swab" <?php echo (isset($exames_solicitados_itens_para_form['Microbiologia']) && in_array('Swab ocular', $exames_solicitados_itens_para_form['Microbiologia']) ? 'checked' : ''); ?>><label class="form-check-label" for="swab">Swab ocular</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Microbiologia][]" value="Escarro exame Micro" class="form-check-input" id="escarro" <?php echo (isset($exames_solicitados_itens_para_form['Microbiologia']) && in_array('Escarro exame Micro', $exames_solicitados_itens_para_form['Microbiologia']) ? 'checked' : ''); ?>><label class="form-check-label" for="escarro">Escarro para exame de Micobacterium tuberculosis</label></div>
                    </div>

                    <div id="opcoesParasitologia" class="sub-opcoes">
                        <h6>Exames de Parasitologia:</h6>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Parasitologia][]" value="Exame parasitológico de fezes" class="form-check-input" id="fezes" <?php echo (isset($exames_solicitados_itens_para_form['Parasitologia']) && in_array('Exame parasitológico de fezes', $exames_solicitados_itens_para_form['Parasitologia']) ? 'checked' : ''); ?>><label class="form-check-label" for="fezes">Exame parasitológico de fezes</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Parasitologia][]" value="Sangue oculto" class="form-check-input" id="sangueOculto" <?php echo (isset($exames_solicitados_itens_para_form['Parasitologia']) && in_array('Sangue oculto', $exames_solicitados_itens_para_form['Parasitologia']) ? 'checked' : ''); ?>><label class="form-check-label" for="sangueOculto">Sangue oculto</label></div>
                    </div>

                    <div id="opcoesHematologia" class="sub-opcoes">
                        <h6>Exames de Hematologia:</h6>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Hematologia][]" value="Hemograma completo" class="form-check-input" id="hemogramaCompleto" <?php echo (isset($exames_solicitados_itens_para_form['Hematologia']) && in_array('Hemograma completo', $exames_solicitados_itens_para_form['Hematologia']) ? 'checked' : ''); ?>><label class="form-check-label" for="hemogramaCompleto">Hemograma completo</label></div>
                    </div>

                    <div id="opcoesBioquimica" class="sub-opcoes">
                        <h6>Exames de Bioquímica:</h6>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Acido urico" class="form-check-input" id="acidoUrico" <?php echo (isset($exames_solicitados_itens_para_form['Bioquimica']) && in_array('Acido urico', $exames_solicitados_itens_para_form['Bioquimica']) ? 'checked' : ''); ?>><label class="form-check-label" for="acidoUrico">Ácido úrico</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Alfa amilase" class="form-check-input" id="alfaAmilase" <?php echo (isset($exames_solicitados_itens_para_form['Bioquimica']) && in_array('Alfa amilase', $exames_solicitados_itens_para_form['Bioquimica']) ? 'checked' : ''); ?>><label class="form-check-label" for="alfaAmilase">Alfa amilase</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Bilirrubina Total" class="form-check-input" id="bilirrubinaTotal" <?php echo (isset($exames_solicitados_itens_para_form['Bioquimica']) && in_array('Bilirrubina Total', $exames_solicitados_itens_para_form['Bioquimica']) ? 'checked' : ''); ?>><label class="form-check-label" for="bilirrubinaTotal">Bilirrubina Total</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Bilirrubina Direta" class="form-check-input" id="bilirrubinaDireta" <?php echo (isset($exames_solicitados_itens_para_form['Bioquimica']) && in_array('Bilirrubina Direta', $exames_solicitados_itens_para_form['Bioquimica']) ? 'checked' : ''); ?>><label class="form-check-label" for="bilirrubinaDireta">Bilirrubina Direta</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Calcio" class="form-check-input" id="calcio" <?php echo (isset($exames_solicitados_itens_para_form['Bioquimica']) && in_array('Calcio', $exames_solicitados_itens_para_form['Bioquimica']) ? 'checked' : ''); ?>><label class="form-check-label" for="calcio">Cálcio</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Colesterol" class="form-check-input" id="colesterol" <?php echo (isset($exames_solicitados_itens_para_form['Bioquimica']) && in_array('Colesterol', $exames_solicitados_itens_para_form['Bioquimica']) ? 'checked' : ''); ?>><label class="form-check-label" for="colesterol">Colesterol</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="HDL" class="form-check-input" id="hdl" <?php echo (isset($exames_solicitados_itens_para_form['Bioquimica']) && in_array('HDL', $exames_solicitados_itens_para_form['Bioquimica']) ? 'checked' : ''); ?>><label class="form-check-label" for="hdl">HDL</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Creatinina" class="form-check-input" id="creatinina" <?php echo (isset($exames_solicitados_itens_para_form['Bioquimica']) && in_array('Creatinina', $exames_solicitados_itens_para_form['Bioquimica']) ? 'checked' : ''); ?>><label class="form-check-label" for="creatinina">Creatinina</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Ferro Ferene" class="form-check-input" id="ferroFerene" <?php echo (isset($exames_solicitados_itens_para_form['Bioquimica']) && in_array('Ferro Ferene', $exames_solicitados_itens_para_form['Bioquimica']) ? 'checked' : ''); ?>><label class="form-check-label" for="ferroFerene">Ferro Ferene</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Fosfatase Alcalina" class="form-check-input" id="fosfataseAlcalina" <?php echo (isset($exames_solicitados_itens_para_form['Bioquimica']) && in_array('Fosfatase Alcalina', $exames_solicitados_itens_para_form['Bioquimica']) ? 'checked' : ''); ?>><label class="form-check-label" for="fosfataseAlcalina">Fosfatase Alcalina</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Fosfato" class="form-check-input" id="fosfato" <?php echo (isset($exames_solicitados_itens_para_form['Bioquimica']) && in_array('Fosfato', $exames_solicitados_itens_para_form['Bioquimica']) ? 'checked' : ''); ?>><label class="form-check-label" for="fosfato">Fosfato</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Gama GT" class="form-check-input" id="gamaGT" <?php echo (isset($exames_solicitados_itens_para_form['Bioquimica']) && in_array('Gama GT', $exames_solicitados_itens_para_form['Bioquimica']) ? 'checked' : ''); ?>><label class="form-check-label" for="gamaGT">Gama GT</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Glicose" class="form-check-input" id="glicose" <?php echo (isset($exames_solicitados_itens_para_form['Bioquimica']) && in_array('Glicose', $exames_solicitados_itens_para_form['Bioquimica']) ? 'checked' : ''); ?>><label class="form-check-label" for="glicose">Glicose</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="GOT_AST" class="form-check-input" id="gotAst" <?php echo (isset($exames_solicitados_itens_para_form['Bioquimica']) && in_array('GOT_AST', $exames_solicitados_itens_para_form['Bioquimica']) ? 'checked' : ''); ?>><label class="form-check-label" for="gotAst">GOT (AST)</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="GTP_ALT" class="form-check-input" id="gtpAlt" <?php echo (isset($exames_solicitados_itens_para_form['Bioquimica']) && in_array('GTP_ALT', $exames_solicitados_itens_para_form['Bioquimica']) ? 'checked' : ''); ?>><label class="form-check-label" for="gtpAlt">GTP (ALT)</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Magnesio" class="form-check-input" id="magnesio" <?php echo (isset($exames_solicitados_itens_para_form['Bioquimica']) && in_array('Magnesio', $exames_solicitados_itens_para_form['Bioquimica']) ? 'checked' : ''); ?>><label class="form-check-label" for="magnesio">Magnésio</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Proteina total" class="form-check-input" id="proteinaTotal" <?php echo (isset($exames_solicitados_itens_para_form['Bioquimica']) && in_array('Proteina total', $exames_solicitados_itens_para_form['Bioquimica']) ? 'checked' : ''); ?>><label class="form-check-label" for="proteinaTotal">Proteina total</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Triglicerideos" class="form-check-input" id="triglicerideos" <?php echo (isset($exames_solicitados_itens_para_form['Bioquimica']) && in_array('Triglicerideos', $exames_solicitados_itens_para_form['Bioquimica']) ? 'checked' : ''); ?>><label class="form-check-label" for="triglicerideos">Triglicerídeos</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Ureia" class="form-check-input" id="ureia" <?php echo (isset($exames_solicitados_itens_para_form['Bioquimica']) && in_array('Ureia', $exames_solicitados_itens_para_form['Bioquimica']) ? 'checked' : ''); ?>><label class="form-check-label" for="ureia">Uréia</label></div>
                    </div>

                    <div id="opcoesUrinalise" class="sub-opcoes">
                        <h6>Exames de Urinálise:</h6>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Urinalise][]" value="Urina 1" class="form-check-input" id="urina1" <?php echo (isset($exames_solicitados_itens_para_form['Urinálise']) && in_array('Urina 1', $exames_solicitados_itens_para_form['Urinálise']) ? 'checked' : ''); ?>><label class="form-check-label" for="urina1">Urina 1</label></div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-dark">Marcar Exame</button>
                    </div>
                </form>
            </div> </main> </div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/validacoes.js"></script> <script>
        // Função para mostrar/esconder as sub-opções de exames
        function toggleSubOptions(elementId, isChecked) {
            const element = document.getElementById(elementId);
            if (element) {
                if (isChecked) {
                    element.style.display = 'block'; // Mostra as sub-opções
                } else {
                    element.style.display = 'none';  // Esconde as sub-opções
                }
            }
        }
    </script>
</body>
</html>