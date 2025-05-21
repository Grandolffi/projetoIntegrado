<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <table class="table table-hover">
                    <thead> <!-- Cabeçalho -->
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Cpf</th>
                            <th scope="col">Nasc</th>
                            <th scope="col">Email</th>
                            <th scope="col">NomeMae</th>
                            <th scope="col">Tel</th>
                            <th scope="col">Genero</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Linha 1 da table -->
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                            <td>editar </td>
                        </tr>   
                        <?php
                        if($_SERVER["REQUEST_METHOD"] == "GET"){
                            require 'dao/ConnectionFactory.php';
                            require 'dao/PessoaDao.php';
                            require 'model/ClassePessoas.php';

                            $fabricanteDao = new PessoaDao();
                            $lista = $fabricanteDao->read();

                            foreach($lista as $pessoas){
                        ?>
                        <tr>
                            <td><?php echo $pessoas->getId() ?></td>
                            <td><?php echo $pessoas->getnome() ?></td>
                            <td><?php echo $pessoas->getCpf() ?></td>
                            <td><?php echo $pessoas->getdataNasc() ?></td>
                            <td><?php echo $pessoas->getEmail() ?></td>
                            <td><?php echo $pessoas->getNomeMae() ?></td>
                            <td><?php echo $pessoas->getnumCelular() ?></td>
                            <td><?php echo $pessoas->getGenero() ?></td>
                            <td>
                                <a href="#">Editar</a> <a href="#">Excuir</a>
                            </td>
                        </tr>
                        <?php
                        }
                    }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>