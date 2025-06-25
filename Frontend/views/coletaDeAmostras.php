<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//require_once __DIR__ . '../controller/ColetaDeAmostrasController.php';

$statusClass = '';
$statusMessage = '';

if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'success':
            $statusClass = 'alert-success';
            $statusMessage = $_GET['message'] ?? 'Operação realizada com sucesso!';
            break;
        case 'error':
            $statusClass = 'alert-danger';
            $statusMessage = $_GET['message'] ?? 'Ocorreu um erro durante a operação.';
            break;
        case 'warning':
            $statusClass = 'alert-warning';
            $statusMessage = $_GET['message'] ?? 'Atenção: ação requer sua atenção.';
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Coleta de Amostras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="Style.css">
</head>
<body class="corpo-dashboard">
    <div class="container-dashboard">
        <?php include 'menuLateral.php'; ?>

        <main class="conteudo-principal">
            <header class="cabecalho-principal">
                <h2>Mapa de Bioquímica - Coleta de Amostras</h2>
                <?php include 'info_cabecalho.php'; ?>
            </header>

            <div class="form-container">
            <form action="ColetaDeAmostrasController.php?action=processar" method="POST" id="formColeta">
                <form action="processa_coleta.php" method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nome_paciente" class="form-label">Nome do Paciente</label>
                            <input type="text" name="nome_paciente" id="nome_paciente" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="data_coleta" class="form-label">Data e Hora da Coleta</label>
                            <input type="datetime-local" name="data_coleta" id="data_coleta" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h5>Exames do Paciente</h5>

                        <label class="form-label">Setor Hematologia</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="hematologia[]" value="Hemograma" id="hemograma">
                            <label class="form-check-label" for="hemograma">Hemograma</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="hematologia[]" value="Leucograma" id="leucograma">
                            <label class="form-check-label" for="leucograma">Leucograma</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="hematologia[]" value="Plaquetas" id="plaquetas">
                            <label class="form-check-label" for="plaquetas">Plaquetas</label>
                        </div>

                        <label class="form-label mt-3">Setor Parasitologia</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="parasitologia[]" value="EPF" id="epf">
                            <label class="form-check-label" for="epf">EPF</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="parasitologia[]" value="Sangue Oculto" id="sangue_oculto">
                            <label class="form-check-label" for="sangue_oculto">Sangue Oculto</label>
                        </div>

                        <label class="form-label mt-3">Setor Microbiologia</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="microbiologia[]" value="Urina" id="urina">
                            <label class="form-check-label" for="urina">Urina</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="microbiologia[]" value="Escarro" id="escarro">
                            <label class="form-check-label" for="escarro">Escarro</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="microbiologia[]" value="Antibiograma" id="antibiograma">
                            <label class="form-check-label" for="antibiograma">Antibiograma</label>
                        </div>

                        <label class="form-label mt-3">Setor Urinálise</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="urinalise[]" value="Exame Macroscópico" id="macro">
                            <label class="form-check-label" for="macro">Exame Macroscópico</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="urinalise[]" value="Exame Químico" id="quimico">
                            <label class="form-check-label" for="quimico">Exame Químico</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="urinalise[]" value="Exame Microscópico" id="micro">
                            <label class="form-check-label" for="micro">Exame Microscópico</label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5>Coleta e Materiais</h5>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="tipo_coleta" value="Sim" id="tipo_coleta">
                            <label class="form-check-label" for="tipo_coleta">Tipo de Coleta Realizada</label>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label for="lote_tubo" class="form-label">Lote do Tubo</label>
                                <input type="text" name="lote_tubo" class="form-control" id="lote_tubo">
                            </div>
                            <div class="col-md-4">
                                <label for="validade_tubo" class="form-label">Validade do Tubo</label>
                                <input type="text" name="validade_tubo" class="form-control" id="validade_tubo">
                            </div>
                        </div>

                        <label class="form-label">Tubos utilizados na coleta:</label>
                        <?php
                        $cores = ["Vermelha", "Cinza", "Amarela", "Preta", "Azul Claro", "Roxa", "Verde"];
                        foreach ($cores as $cor) {
                            $id = strtolower(str_replace(" ", "_", $cor));
                            echo "<div class='form-check'>
                                    <input class='form-check-input' type='checkbox' name='cores_tubos[]' value='$cor' id='$id'>
                                    <label class='form-check-label' for='$id'>Tampa $cor</label>
                                  </div>";
                        }
                        ?>

                        <div class="row g-3 mt-3">
                            <div class="col-md-4">
                                <label for="tamanho_seringa" class="form-label">Tamanho da Seringa</label>
                                <input type="text" name="tamanho_seringa" id="tamanho_seringa" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="lote_seringa" class="form-label">Lote da Seringa</label>
                                <input type="text" name="lote_seringa" id="lote_seringa" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="validade_seringa" class="form-label">Validade da Seringa</label>
                                <input type="text" name="validade_seringa" id="validade_seringa" class="form-control">
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label for="lote_urina" class="form-label">Lote Potes de Urina</label>
                                <input type="text" name="lote_urina" id="lote_urina" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="validade_urina" class="form-label">Validade</label>
                                <input type="text" name="validade_urina" id="validade_urina" class="form-control">
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label for="lote_fezes" class="form-label">Lote Potes de Fezes</label>
                                <input type="text" name="lote_fezes" id="lote_fezes" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="validade_fezes" class="form-label">Validade</label>
                                <input type="text" name="validade_fezes" id="validade_fezes" class="form-control">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Registrar</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
