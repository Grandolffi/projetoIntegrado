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
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-12 col-md-8 col-lg-6 form-container">
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

                <div class="form-check">
                    <input type="checkbox" name="ExameSolicitado[]" id="Microbiologia" value="Microbiologia"  
                        class="form-check-input" onchange="toggleSubOptions('opcoesMicrobiologia')" /*aqui chamo a função toggle que serve pra ativar/desativar a visibilidade de um elemento html*/> 
                    <label for="Microbiologia" class="form-check-label">Microbiologia</label> 

                    <!-- Submenu está agora DENTRO do mesmo bloco da checkbox -->
                    <div id="opcoesMicrobiologia" class="sub-opcoes">
                        <div class="form-check">
                            <input type="checkbox" name="categorias[]" value="Urocultura com antibiograma" class="form-check-input" id="urocultura">
                            <label class="form-check-label" for="urocultura">Urocultura com antibiograma</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="categorias[]" value="Swab ocular" class="form-check-input" id="swab">
                            <label class="form-check-label" for="swab">Swab ocular</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="categorias[]" value="Escarro exame Micro" class="form-check-input" id="escarro">
                            <label class="form-check-label" for="escarro">Escarro para exame de Micobacterium tuberculosis</label>
                        </div>
                    </div>
                </div>


                <div class="form-check">
                    <input type="checkbox" name="ExameSolicitado[]" id="Parasitologia" value="Parasitologia"
                        class="form-check-input">
                    <label for="Parasitologia" class="form-check-label">Parasitologia</label>
                </div>

                <div class="form-check">
                    <input type="checkbox" name="ExameSolicitado[]" id="Hematologia" value="Hematologia"
                        class="form-check-input">
                    <label for="Hematologia" class="form-check-label">Hematologia</label>
                </div>

                <div class="form-check">
                    <input type="checkbox" name="ExameSolicitado[]" id="Bioquimica" value="Bioquímica"
                        class="form-check-input">
                    <label for="Bioquimica" class="form-check-label">Bioquímica</label>
                </div>

                <div class="form-check">
                    <input type="checkbox" name="ExameSolicitado[]" id="Urinalise" value="Urinálise"
                        class="form-check-input">
                    <label for="Urinalise" class="form-check-label">Urinálise</label>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-dark">Enviar</button>
                </div>

                <style>
                    .sub-opcoes {
                    margin-left: 20px;
                    display: none; /* Escondido inicialmente */
                     }
                 </style>

                <script>
                    function toggleSubOptions(id) { //usando o toggle chamado pelas checks princiapais 
                    const subMenu = document.getElementById(id);
                            subMenu.style.display = subMenu.style.display === 'block' ? 'none' : 'block';
                        }
                    </script>

                
            </form>
        </div>
    </div>
</body>

</html>
