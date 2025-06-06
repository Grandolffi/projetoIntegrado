<?php
// Configura o fuso horário para Português do Brasil
// É crucial que o servidor esteja configurado corretamente ou que este fuso horário seja respeitado.
date_default_timezone_set('America/Sao_Paulo');

// Saudação baseada na hora atual do servidor (PHP)
$horaAtual = (int)date('H'); // Pega a hora atual no formato 24h
$saudacao = '';
$icone = ''; // Variável para o ícone

if ($horaAtual >= 5 && $horaAtual < 12) {
    $saudacao = 'Bom dia';
    $icone = '☀️'; // Sol para o dia
} elseif ($horaAtual >= 12 && $horaAtual < 18) {
    $saudacao = 'Boa tarde';
    $icone = '☀️'; // Sol para a tarde
} else {
    $saudacao = 'Boa noite';
    $icone = '🌙'; // Lua para a noite
}

// Data inicial formatada em PHP (sem os segundos que serão atualizados pelo JS)
// Usamos IntlDateFormatter para formatar a data, que é mais robusto para locales.
if (class_exists('IntlDateFormatter')) {
    $formatter = new IntlDateFormatter(
        'pt_BR',
        IntlDateFormatter::LONG, // Estilo da data: "4 de junho de 2025"
        IntlDateFormatter::NONE, // Sem estilo de hora aqui
        'America/Sao_Paulo',
        IntlDateFormatter::GREGORIAN
    );
    $dataInicialFormatada = $formatter->format(new DateTime());
} else {
    // FALLBACK ATUALIZADO: Substitui strftime() por uma combinação de date()
    // e mapeamento manual para nomes de meses em português.
    $nomesMeses = [
        1 => 'janeiro', 2 => 'fevereiro', 3 => 'março', 4 => 'abril',
        5 => 'maio', 6 => 'junho', 7 => 'julho', 8 => 'agosto',
        9 => 'setembro', 10 => 'outubro', 11 => 'novembro', 12 => 'dezembro'
    ];
    $dia = date('d');
    $mesNumero = date('n'); // Retorna o mês sem zero à esquerda (1 a 12)
    $ano = date('Y');
    $dataInicialFormatada = $dia . ' de ' . $nomesMeses[$mesNumero] . ' de ' . $ano;
}

// A hora será atualizada pelo JavaScript
?>

<div class="info-cabecalho">
    <div class="saudacao"><?php echo htmlspecialchars($saudacao); ?> <span class="icone-saudacao"><?php echo $icone; ?></span></div>
    <div class="data-hora" id="dataHoraAtual"></div>
</div>

<script>
    // Função para atualizar a data e hora em tempo real
    function atualizarDataHora() {
        const elementoDataHora = document.getElementById('dataHoraAtual');
        if (elementoDataHora) {
            const agora = new Date();
            const optionsData = { year: 'numeric', month: 'long', day: 'numeric' };
            const optionsHora = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            
            const dataFormatada = agora.toLocaleDateString('pt-BR', optionsData);
            const horaFormatada = agora.toLocaleTimeString('pt-BR', optionsHora);

            elementoDataHora.textContent = `${dataFormatada} ${horaFormatada}`;
        }
    }

    // Chama a função uma vez para exibir a hora imediatamente
    atualizarDataHora();

    // Atualiza a cada segundo
    setInterval(atualizarDataHora, 1000);
</script>