<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Paciente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
    body {
        background-color: white;
    }

    .form-container {
        background-color: white;
        padding: 30px;
        margin-top: 60px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    input::placeholder {
        color: #555;
        opacity: 1;
        font-size: 14px;
    }

    label {
        margin-top: 10px;
    }


    .form-check-input {
        vertical-align: middle;
        margin-top: 0.9em;
    }
    </style>
</head>

<body>
    <div class="container">
        <!-- d-flex justify-content-center align-items-center min-vh-100 -->
        <div class="row">
            <div class="col-2"></div>
            <div class="col-10 form-container">
                <h1 class="text-center mb-4">Cadastro paciente</h1>

                <form action="input.php" method="POST">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome completo</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" name="cpf" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="dtnasc" class="form-label">Data de nascimento</label>
                        <input type="date" name="dtnasc" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="nomemae" class="form-label">Nome da mãe</label>
                        <input type="text" name="nomemae" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="fone" class="form-label">Celular com DDD</label>
                        <input type="tel" name="fone" class="form-control" required pattern="[0-9]{2} [0-9]{5}-[0-9]{4}"
                            placeholder="Ex: 11 99999-9999">
                    </div>

                    <div class="mb-3">
                        <label for="genero" class="form-label">Gênero</label>
                        <select name="genero" id="genero" class="form-select">
                            <option value="masc">Masculino</option>
                            <option value="femi">Feminino</option>
                            <option value="nf">Não informar</option>
                        </select>
                    </div>

                    <p>Exames solicitados</p>


                    <!-- check microbiologia -->
                    <div class="form-check">
                        <input type="checkbox" name="laboratorioSolicitado[]" id="Microbiologia" value="Microbiologia"
                            class="form-check-input" onchange="toggleSubOptions('opcoesMicrobiologia')">
                        <!-- aqui chamo a função toggle que serve pra ativar/desativar a visibilidade de um elemento html* -->
                        <label for="Microbiologia" class="form-check-label">Microbiologia</label>
                    </div>

                    <!-- Submenu está agora DENTRO do mesmo bloco da checkbox -->
                    <div id="opcoesMicrobiologia" class="sub-opcoes">
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Urocultura com antibiograma"
                                class="form-check-input" id="urocultura">
                            <label class="form-check-label" for="urocultura">Urocultura com antibiograma</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Swab ocular"
                                class="form-check-input" id="swab">
                            <label class="form-check-label" for="swab">Swab ocular</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Escarro exame Micro"
                                class="form-check-input" id="escarro">
                            <label class="form-check-label" for="escarro">Escarro para exame de Micobacterium
                                tuberculosis</label>
                        </div>
                    </div>

                    <!-- check parasitologia -->
                    <div class="form-check">
                        <input type="checkbox" name="laboratorioSolicitado[]" id="Parasitologia" value="Parasitologia"
                            class="form-check-input" onchange="toggleSubOptions('opcoesParasitologia')">
                        <label for="Parasitologia" class="form-check-label">Parasitologia</label>
                    </div>

                    <!-- Submenu parasitologia -->
                    <div id="opcoesParasitologia" class="sub-opcoes">
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Exame parasitológico de fezes"
                                class="form-check-input" id="fezes">
                            <label class="form-check-label" for="fezes">Exame parasitológico de fezes</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Sangue oculto"
                                class="form-check-input" id="sangueOculto">
                            <label class="form-check-label" for="sangueOculto">Sangue oculto</label>
                        </div>
                    </div>

                    <!-- check hematologia -->
                    <div class="form-check">
                        <input type="checkbox" name="laboratorioSolicitado[]" id="Hematologia" value="Hematologia"
                            class="form-check-input" onchange="toggleSubOptions('opcoesHematologia')">
                        <label for="Hematologia" class="form-check-label">Hematologia</label>
                    </div>

                    <!-- Submenu hematologia -->

                    <div id="opcoesHematologia" class="sub-opcoes">
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Hemograma completo"
                                class="form-check-input" id="hemogramaCompleto">
                            <label class="form-check-label" for="hemogramaCompleto">Hemograma completo</label>
                        </div>

                    </div>

                    <!-- check bioquimica -->
                    <div class="form-check">
                        <input type="checkbox" name="laboratorioSolicitado[]" id="Bioquimica" value="Bioquimica"
                            class="form-check-input" onchange="toggleSubOptions('opcoesBioquimica')">
                        <label for="Bioquimica" class="form-check-label">Bioquímica</label>
                    </div>

                    <!-- Submenu bioquimica -->

                    <div id="opcoesBioquimica" class="sub-opcoes">
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Acido urico"
                                class="form-check-input" id="acidoUrico">
                            <label class="form-check-label" for="acidoUrico">Ácido úrico</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Alfa amilase"
                                class="form-check-input" id="alfaAmilase">
                            <label class="form-check-label" for="alfaAmilase">Alfa amilase</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Bilirrubina Total"
                                class="form-check-input" id="bilirrubinaTotal">
                            <label class="form-check-label" for="bilirrubinaTotal">Bilirrubina Total</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Bilirrubina Direta"
                                class="form-check-input" id="bilirrubinaDireta">
                            <label class="form-check-label" for="bilirrubinaDireta">Bilirrubina Direta</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Calcio" class="form-check-input"
                                id="calcio">
                            <label class="form-check-label" for="calcio">Cálcio</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Colesterol"
                                class="form-check-input" id="colesterol">
                            <label class="form-check-label" for="colesterol">Colesterol</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="HDL" class="form-check-input"
                                id="hdl">
                            <label class="form-check-label" for="hdl">HDL</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Creatinina"
                                class="form-check-input" id="creatinina">
                            <label class="form-check-label" for="creatinina">Creatinina</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Ferro Ferene"
                                class="form-check-input" id="ferroFerene">
                            <label class="form-check-label" for="ferroFerene">Ferro Ferene</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Fosfatase Alcalina"
                                class="form-check-input" id="fosfataseAlcalina">
                            <label class="form-check-label" for="fosfataseAlcalina">Fosfatase Alcalina</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Fosfato" class="form-check-input"
                                id="fosfato">
                            <label class="form-check-label" for="fosfato">Fosfato</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Gama GT" class="form-check-input"
                                id="gamaGT">
                            <label class="form-check-label" for="gamaGT">Gama GT</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Glicose" class="form-check-input"
                                id="glicose">
                            <label class="form-check-label" for="glicose">Glicose</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="GOT_AST" class="form-check-input"
                                id="gotAst">
                            <label class="form-check-label" for="gotAst">GOT (AST)</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="GTP_ALT" class="form-check-input"
                                id="gtpAlt">
                            <label class="form-check-label" for="gtpAlt">GTP (ALT)</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Magnesio" class="form-check-input"
                                id="magnesio">
                            <label class="form-check-label" for="magnesio">Magnésio</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Proteina total"
                                class="form-check-input" id="proteinaTotal">
                            <label class="form-check-label" for="proteinaTotal">Proteina total</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Triglicerideos"
                                class="form-check-input" id="triglicerideos">
                            <label class="form-check-label" for="triglicerideos">Triglicerídeos</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Ureia" class="form-check-input"
                                id="ureia">
                            <label class="form-check-label" for="ureia">Uréia</label>
                        </div>
                    </div>

                    <!-- FIMMMMMMMMMMMMMMMMMMMMMMMMMMMM -->
                    <!-- FIMMMMMMMMMMMMMMMMMMMMMMMMMMMM -->
                    <!-- FIMMMMMMMMMMMMMMMMMMMMMMMMMMMM -->
                    <!-- FIMMMMMMMMMMMMMMMMMMMMMMMMMMMM -->

                    <!-- check Urinalise -->

                    <div class="form-check">
                        <input type="checkbox" name="laboratorioSolicitado[]" id="Urinalise" value="Urinálise"
                            class="form-check-input" onchange="toggleSubOptions('opcoesUrinalise')">
                        <label for="Urinalise" class="form-check-label">Urinálise</label>
                    </div>

                    <!-- Submenu bioquimica -->

                    <div id="opcoesUrinalise" class="sub-opcoes">
                        <div class="form-check">
                            <input type="checkbox" name="examesSolicitados[]" value="Urina 1" class="form-check-input"
                                id="urina1">
                            <label class="form-check-label" for="urina1">Urina 1</label>
                        </div>
                    </div>


                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-dark">Enviar</button>
                    </div>

                    <style>
                    .sub-opcoes {
                        margin-left: 20px;
                        display: none;
                        /* Escondido inicialmente */
                    }
                    </style>
                </form>
            </div>
        </div>
    </div>
    <script src="js/validacoes.js"></script>
</body>

</html>