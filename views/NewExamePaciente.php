<?php
// para ver erros PHP, se houver
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Novo Exame</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <link rel="stylesheet" href="../public/css/Style.css">
</head>
<body class="corpo-dashboard">
    <div class="container-dashboard">
        <?php include __DIR__ . '/menuLateral.php'; // Inclui o menu lateral padrão ?>

        <main class="conteudo-principal">
            <header class="cabecalho-principal">
                <h2>Solicitar Novo Exame</h2>
                <?php include __DIR__ . '/info_cabecalho.php'; // Inclui saudação, data e hora ?>
            </header>

            <div class="form-container">
                <form action="../controller/MarcarExamePacienteController.php" method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nome" class="form-label">Nome do Paciente</label>
                            <input type="text" name="nome" id="nome" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="idPac" class="form-label">ID do Paciente</label>
                            <input type="text" name="idPac" id="idPac" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="data_marcada_exame" class="form-label">Data e Hora para Marcação do Exame</label>
                        <input type="datetime-local" class="form-control" name="data_marcada_exame" id="data_marcada_exame" required>
                    </div>

                    <p class="fw-bold mt-4">Laboratórios Solicitados:</p>
                    <div class="d-flex flex-wrap gap-3 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="laboratorioSolicitado[]" id="Microbiologia" value="Microbiologia"
                                class="form-check-input" onchange="toggleSubOptions('opcoesMicrobiologia', this.checked)">
                            <label for="Microbiologia" class="form-check-label">Microbiologia</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="laboratorioSolicitado[]" id="Parasitologia" value="Parasitologia"
                                class="form-check-input" onchange="toggleSubOptions('opcoesParasitologia', this.checked)">
                            <label for="Parasitologia" class="form-check-label">Parasitologia</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="laboratorioSolicitado[]" id="Hematologia" value="Hematologia"
                                class="form-check-input" onchange="toggleSubOptions('opcoesHematologia', this.checked)">
                            <label for="Hematologia" class="form-check-label">Hematologia</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="laboratorioSolicitado[]" id="Bioquimica" value="Bioquimica"
                                class="form-check-input" onchange="toggleSubOptions('opcoesBioquimica', this.checked)">
                            <label for="Bioquimica" class="form-check-label">Bioquímica</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="laboratorioSolicitado[]" id="Urinalise" value="Urinálise"
                                class="form-check-input" onchange="toggleSubOptions('opcoesUrinalise', this.checked)">
                            <label for="Urinalise" class="form-check-label">Urinálise</label>
                        </div>
                    </div>

                    <div id="opcoesMicrobiologia" class="sub-opcoes">
                        <h6>Exames de Microbiologia:</h6>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Microbiologia][]" value="Urocultura com antibiograma" class="form-check-input" id="urocultura"><label class="form-check-label" for="urocultura">Urocultura com antibiograma</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Microbiologia][]" value="Swab ocular" class="form-check-input" id="swab"><label class="form-check-label" for="swab">Swab ocular</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Microbiologia][]" value="Escarro exame Micro" class="form-check-input" id="escarro"><label class="form-check-label" for="escarro">Escarro para exame de Micobacterium tuberculosis</label></div>
                    </div>

                    <div id="opcoesParasitologia" class="sub-opcoes">
                        <h6>Exames de Parasitologia:</h6>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Parasitologia][]" value="Exame parasitológico de fezes" class="form-check-input" id="fezes"><label class="form-check-label" for="fezes">Exame parasitológico de fezes</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Parasitologia][]" value="Sangue oculto" class="form-check-input" id="sangueOculto"><label class="form-check-label" for="sangueOculto">Sangue oculto</label></div>
                    </div>

                    <div id="opcoesHematologia" class="sub-opcoes">
                        <h6>Exames de Hematologia:</h6>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Hematologia][]" value="Hemograma completo" class="form-check-input" id="hemogramaCompleto"><label class="form-check-label" for="hemogramaCompleto">Hemograma completo</label></div>
                    </div>

                    <div id="opcoesBioquimica" class="sub-opcoes">
                        <h6>Exames de Bioquímica:</h6>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Acido urico" class="form-check-input" id="acidoUrico"><label class="form-check-label" for="acidoUrico">Ácido úrico</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Alfa amilase" class="form-check-input" id="alfaAmilase"><label class="form-check-label" for="alfaAmilase">Alfa amilase</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Bilirrubina Total" class="form-check-input" id="bilirrubinaTotal"><label class="form-check-label" for="bilirrubinaTotal">Bilirrubina Total</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Bilirrubina Direta" class="form-check-input" id="bilirrubinaDireta"><label class="form-check-label" for="bilirrubinaDireta">Bilirrubina Direta</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Calcio" class="form-check-input" id="calcio"><label class="form-check-label" for="calcio">Cálcio</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Colesterol" class="form-check-input" id="colesterol"><label class="form-check-label" for="colesterol">Colesterol</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="HDL" class="form-check-input" id="hdl"><label class="form-check-label" for="hdl">HDL</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Creatinina" class="form-check-input" id="creatinina"><label class="form-check-label" for="creatinina">Creatinina</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Ferro Ferene" class="form-check-input" id="ferroFerene"><label class="form-check-label" for="ferroFerene">Ferro Ferene</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Fosfatase Alcalina" class="form-check-input" id="fosfataseAlcalina"><label class="form-check-label" for="fosfataseAlcalina">Fosfatase Alcalina</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Fosfato" class="form-check-input" id="fosfato"><label class="form-check-label" for="fosfato">Fosfato</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Gama GT" class="form-check-input" id="gamaGT"><label class="form-check-label" for="gamaGT">Gama GT</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Glicose" class="form-check-input" id="glicose"><label class="form-check-label" for="glicose">Glicose</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="GOT_AST" class="form-check-input" id="gotAst"><label class="form-check-label" for="gotAst">GOT (AST)</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="GTP_ALT" class="form-check-input" id="gtpAlt"><label class="form-check-label" for="gtpAlt">GTP (ALT)</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Magnesio" class="form-check-input" id="magnesio"><label class="form-check-label" for="magnesio">Magnésio</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Proteina total" class="form-check-input" id="proteinaTotal"><label class="form-check-label" for="proteinaTotal">Proteina total</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Triglicerideos" class="form-check-input" id="triglicerideos"><label class="form-check-label" for="triglicerideos">Triglicerídeos</label></div>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Bioquimica][]" value="Ureia" class="form-check-input" id="ureia"><label class="form-check-label" for="ureia">Uréia</label></div>
                    </div>

                    <div id="opcoesUrinalise" class="sub-opcoes">
                        <h6>Exames de Urinálise:</h6>
                        <div class="form-check"><input type="checkbox" name="examesSolicitados[Urinalise][]" value="Urina 1" class="form-check-input" id="urina1"><label class="form-check-label" for="urina1">Urina 1</label></div>
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