    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <title>Document</title>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-3"></div>
                <div class="col-6" style="background-color:rgb(108, 167, 108); border-radius: 20px;">
                        <h1 class="display-29 text-center">Cadastro paciente</h1>
                        <form action="input.php" method= "POST">
                            <div class="mb-3">
                                <label for="Nome Completo" class="form-label" style="color: white"><br>Nome completo</label>
                                <input type="text" name="nome" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="Cpf" class="form-label" style="color: white"><br>Cpf</label>
                                <input type="cpf" name="cpf" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="dtnasc" class="form-label" style="color: white"><br>Data nascimento</label>
                                <input type="date" name="dtnasc" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="Email" class="form-label" style="color: white"><br>E-mail</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="NameMae" class="form-label" style="color: white"><br>Nome mãe:</label>
                                <input type="nomemae" name="Nome mae:" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="fone" class="form-label" style="color: white"><br>Celular com DDD</label>
                                <input type="tel" name="fone" class="form-control"  required pattern="[0-9]{2} [0-9]{5}-[0-9]{4}" placeholder="Ex: 11 99999-9999">
                                <style>
                                input::placeholder {
                                color: #555;       
                                opacity: 1;        
                                font-size: 14px
                                 }
                                </style>
                            </div>
                            <div class="mb-3">
                                <label for="Genero" class="form-label" style="color: white"><br>Genero:</label>
                                <select name="genero" id="genero" class="form-select">
                                    <option value="masc">Maculino</option>
                                    <option value="femi">Feminino</option>
                                    <option value="nf">Não informar</option>
                                </select>
                            </div>
                            <p>Exames socilicitados</p>
                            <div class="form-chek">
                                <input type="checkbox" name="ExameSolicitado[]" id="Microbiologia" value="Microbiologia">
                                <label for="Microbiologia" class="form=check-label">Microbiologia</label>
                            </div>
                            <div class="form-chek">
                                <input type="checkbox" name="ExameSolicitado[]" id="Parasitologia" value="Parasitologia">
                                <label for="Parasitologia" class="form=check-label">Parasitologia</label>
                            </div>
                            <div class="form-chek">
                                <input type="checkbox" name="ExameSolicitado[]" id="Hematologia" value="Hematologia">
                                <label for="Hematologia" class="form=check-label">Hematologia</label>
                            </div>
                            <div class="form-chek">
                                <input type="checkbox" name="ExameSolicitado[]" id="Bioquímica" value="Bioquímica">
                                <label for="Bioquímica" class="form=check-label">Bioquímica</label>
                            </div>
                            <div class="form-chek">
                                <input type="checkbox" name="ExameSolicitado[]" id="Urinálise" value="Urinálise">
                                <label for="Urinálise" class="form=check-label">Urinálise</label>
                            </div>
                            <button type="submit" class="btn btn-dark">Enviar</button>
                        </form>
                </div>
                <div class="col-3">
                </div>
            </div>
        </div>
        
    </body>
    </html>