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
    <title>BioDiagnóstico - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Style.css"> </head>
<body class="corpo-dashboard">
    <div class="container-dashboard">
        <?php include 'menuLateral.php'; // Inclui o menu lateral ?>
        <main class="conteudo-principal">
            <header class="cabecalho-principal">
                <h2>Dashboard</h2>
                <div class="info-cabecalho">
                    <?php
                        // Configura o fuso horário e o locale para Português do Brasil
                        date_default_timezone_set('America/Sao_Paulo');
                        setlocale(LC_TIME, 'pt_BR.utf8', 'pt_BR.UTF-8', 'pt_BR', 'portuguese');

                        // Para replicar a data da imagem original:
                        // $dataHoraFormatada = "14 Janeiro 2025 22:45:04";

                        $dia = date('d');
                        $mes = ucfirst(strftime('%B')); // Primeira letra do mês maiúscula
                        $ano = date('Y');
                        $horaMinutoSegundo = date('H:i:s');
                        $dataHoraFormatada = "$dia $mes $ano $horaMinutoSegundo";

                        // Saudação baseada na hora atual do servidor
                        $horaAtual = (int)date('H');
                        $saudacao = '';
                        if ($horaAtual >= 5 && $horaAtual < 12) {
                            $saudacao = 'Bom dia';
                        } elseif ($horaAtual >= 12 && $horaAtual < 18) {
                            $saudacao = 'Boa tarde';
                        } else {
                            $saudacao = 'Boa noite';
                        }
                    ?>
                    <div class="saudacao"><?php echo htmlspecialchars($saudacao); ?> <span class="icone-sol">☀️</span></div>
                    <div class="data-hora"><?php echo htmlspecialchars($dataHoraFormatada); ?></div>
                </div>
            </header>
            <section class="botoes-dashboard">
                    <a href="CadastroPaciente.php" class="link-botao-dashboard">
                        <div class="botao-dashboard botao-cliente">
                            Cadastrar Cliente
                        </div>
                    </a>
                    <a href="#" class="link-botao-dashboard">
                        <div class="botao-dashboard botao-coleta">
                            Questionário de Coleta
                        </div>
                    </a>
                    <a href="cadastroExames.php" class="link-botao-dashboard">
                        <div class="botao-dashboard botao-resultado">
                            Questionário de resultado de exames
                        </div>
                    </a>
                </section>
                        </main>
                    </div>
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
                </body>
                </html>