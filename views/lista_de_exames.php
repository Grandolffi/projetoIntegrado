<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir os arquivos necessários
include_once 'dao/ConnectionFactory.php';
include_once 'dao/ExameDao.php';

$examesAgrupados = [];
try {
    $exameDao = new ExameDao();
    $examesRegistrados = $exameDao->getAll(); // Busca todos os exames do banco de dados

    // Agrupar os exames por paciente e data do exame
    foreach ($examesRegistrados as $exame) {
        $key = $exame['paciente_registro'] . '_' . date('Y-m-d', strtotime($exame['data_hora_exame']));
        if (!isset($examesAgrupados[$key])) {
            $examesAgrupados[$key] = [
                'paciente_registro' => $exame['paciente_registro'],
                'data_exame' => date('d/m/Y', strtotime($exame['data_hora_exame'])),
                'hora_exame' => date('H:i', strtotime($exame['data_hora_exame'])),
                'exames_detalhes' => []
            ];
        }
        $examesAgrupados[$key]['exames_detalhes'][] = [
            'nome_exame' => $exame['nome_exame'],
            'valor_absoluto' => $exame['valor_absoluto'],
            'valor_referencia' => $exame['valor_referencia']
        ];
    }
} catch (Exception $e) {
    echo "<p>Erro ao carregar exames: " . $e->getMessage() . "</p>";
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Exames</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Style.css">
    <style>
        /* Estilos adicionais para melhor visualização */
        .details-row {
            display: none; /* Esconde os detalhes por padrão */
        }
        .details-row.show {
            display: table-row; /* Mostra os detalhes quando a classe 'show' é adicionada */
        }
        .toggle-details {
            cursor: pointer;
            color: #0d6efd; /* Cor de link do Bootstrap */
            text-decoration: none;
        }
        .toggle-details:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="corpo-dashboard">
    <div class="container-dashboard">
        <?php include 'menuLateral.php'; // Inclui o menu lateral ?>
        <main class="conteudo-principal">
            <header class="cabecalho-principal">
                <h2>Exame <span class="text-muted fw-light">&gt;</span> Lista de exames</h2>
                <?php include 'info_cabecalho.php'; // Inclui a saudação, data e hora ?>
            </header>
            <div class="p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="input-group" style="max-width: 400px;">
                        <input type="text" class="form-control" placeholder="Pesquisar...">
                        <button class="btn btn-outline-secondary" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                        </button>
                    </div>
                    <a href="NewExamePaciente.php" class="btn btn-danger"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16" style="margin-bottom: 2px;">
                           <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/>
                        </svg>
                        Solicitar novo exame
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nº Registro Paciente</th>
                                <th>Data do Exame</th>
                                <th>Hora do Exame</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($examesAgrupados)): ?>
                                <tr>
                                    <td colspan="4" class="text-center">Nenhum exame cadastrado ainda.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($examesAgrupados as $key => $grupoExames): ?>
                                    <tr id="main-row-<?php echo htmlspecialchars($key); ?>">
                                        <td><?php echo htmlspecialchars($grupoExames['paciente_registro']); ?></td>
                                        <td><?php echo htmlspecialchars($grupoExames['data_exame']); ?></td>
                                        <td><?php echo htmlspecialchars($grupoExames['hora_exame']); ?></td>
                                        <td>
                                            <a href="#" class="toggle-details" data-target="details-<?php echo htmlspecialchars($key); ?>">
                                                Ver Detalhes Completos
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="details-row" id="details-<?php echo htmlspecialchars($key); ?>">
                                        <td colspan="4">
                                            <table class="table table-bordered table-sm my-2">
                                                <thead>
                                                    <tr>
                                                        <th>Exame</th>
                                                        <th>Valor</th>
                                                        <th>Valor de Referência</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($grupoExames['exames_detalhes'] as $detalhe): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($detalhe['nome_exame']); ?></td>
                                                            <td><?php echo htmlspecialchars($detalhe['valor_absoluto']); ?></td>
                                                            <td><?php echo $detalhe['valor_referencia']; ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-details').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault(); // Evita que o link navegue para outra página
                    var targetId = this.dataset.target;
                    var targetRow = document.getElementById(targetId);
                    if (targetRow) {
                        targetRow.classList.toggle('show');
                        // Opcional: Altera o texto do botão
                        if (targetRow.classList.contains('show')) {
                            this.textContent = 'Esconder Detalhes';
                        } else {
                            this.textContent = 'Ver Detalhes Completos';
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>