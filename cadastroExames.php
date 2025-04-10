<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- getbootstrap.com > include via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>aula 09/04</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Aqui vai o menu -->
                <?php
                //include "menu.php";
                require "menu.php";
            ?>
            </div>
            <div class="col-5 border bg-light p-5">
                <h1 class="display-5">Cadastro de exames</h1>
                <form action="dados.php" method="get">
                    <div class="mb-3">
                        <label for="registro" class="form-label">Número do registro</label>
                        <input type="text" class="form-control" name="registro" id="registro">
                    </div>
                    <div class="mb-3">
                        <label for="nome_paciente" class="form-label">Nome do paciente</label>
                        <input type="text" class="form-control" name="nome_paciente" id="nome_paciente">
                    </div>
                    <div class="mb-3">
                        <label for="entrada" class="form-label">Entrada</label>
                        <input type="text" class="form-control" name="entrada" id="entrada">
                    </div>
                    <div class="mb-3">
                        <label for="data_laudo" class="form-label">Data prevista para entrega do laudo</label>
                        <input type="text" class="form-control" name="data_laudo" id="data_laudo">
                    </div>
                    <div class="mb-3">
                        <label for="data_exame" class="form-label">Data e hora de realização do exame</label>
                        <input type="text" class="form-control" name="data_exame" id="data_exame">
                    </div>
                    <div class="mb-3">
                        <p>Exames para qual laboratório?</p>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="bioq" value="bioquimica"
                                id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Bioquímica
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="hemato" value="hematologia"
                                id="flexCheckChecked">
                            <label class="form-check-label" for="flexCheckChecked">
                                Hematologia
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="imuno" value="imunologia"
                                id="flexCheckChecked">
                            <label class="form-check-label" for="flexCheckChecked">
                                Imunologia
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="micro" value="microbiologia"
                                id="flexCheckChecked">
                            <label class="form-check-label" for="flexCheckChecked">
                                Microbiologia
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="parasito" value="parasitologia"
                                id="flexCheckChecked">
                            <label class="form-check-label" for="flexCheckChecked">
                                Parasitologia
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Salvar via GET</button>
                </form>
            </div>
            <div class="col p-3 mt-2">
                <!-- Solicitação via POST para formulario.php-->
                <form action="dados.php" method="post">
                    <input type="text" name="oi">
                    <button type="submit" class="btn btn-danger">
                        Solictar dados.php via POST
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>