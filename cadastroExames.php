<?php
// para ver erros PHP, se houver
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$exames = [
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
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado de Exames Bioquímicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Style.css">
</head>
<body class="corpo-dashboard">
    <div class="container-dashboard">
        <?php include 'menuLateral.php'; // Inclui o novo menu lateral ?>

        <main class="conteudo-principal">
            <header class="cabecalho-principal">
                <h2>Resultado de Exame</h2>
                <?php include 'info_cabecalho.php'; // INCLUINDO A SAUDAÇÃO, DATA E HORA ?>
            </header>

            <div class="form-container">
                <form action="controller/ExameController.php" method="POST">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="numero_registro" class="form-label">Nº do Registro</label>
                            <input type="text" class="form-control" name="numero_registro" id="numero_registro" required>
                        </div>
                        <div class="col-md-4">
                            <label for="data_laudo" class="form-label">Data prevista para entrega do laudo</label>
                            <input type="date" class="form-control" name="data_laudo" id="data_laudo">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="data_exame" class="form-label">Data e hora da realização do exame</label>
                        <input type="datetime-local" class="form-control" name="data_exame" id="data_exame">
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle text-center">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Exame</th>
                                    <th>Valor Absoluto</th>
                                    <th>Valor de Referência</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($exames as $name => [$label, $referencia]) {
                                    echo "<tr>";
                                    echo "<td class='text-start'><label for='{$name}'>{$label}</label></td>";
                                    echo "<td><input type='number' step='0.01' class='form-control form-control-sm' name='{$name}' id='{$name}'></td>";
                                    echo "<td class='text-muted text-start small'>{$referencia}</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="responsavel_exame" class="form-label">Responsável pelo exame</label>
                            <input type="text" class="form-control" name="responsavel_exame" id="responsavel_exame">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="preceptor_responsavel" class="form-label">Preceptor responsável</label>
                            <input type="text" class="form-control" name="preceptor_responsavel" id="preceptor_responsavel">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Salvar Exames</button>
                </form>
            </div> </main> </div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// Este bloco PHP de processamento POST pode ser movido para o controller/ExameController.php
// ou mantido aqui para testes rápidos, mas para uma aplicação organizada, o controller é melhor.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function salvarExame($dados) {
        // Lógica real de salvamento iria aqui
        // Para feedback visual, você pode redirecionar ou mostrar uma mensagem mais integrada ao layout.
        // Este echo aqui aparecerá no final da página, após o HTML.
        // echo "<div class='alert alert-success mt-3'>Exame salvo com sucesso!</div>";
        // var_dump($dados); // Para debug
    }
    // salvarExame($_POST); // Comente ou remova se o controller for tratar
}
?>