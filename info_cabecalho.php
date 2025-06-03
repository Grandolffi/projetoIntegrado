<?php
// Configura o fuso horário e o locale para Português do Brasil (se ainda não estiver globalmente configurado)
if (date_default_timezone_get() !== 'America/Sao_Paulo') { // Evita redefinir se já estiver correto
    date_default_timezone_set('America/Sao_Paulo');
}
// setlocale para nomes de meses em português. Pode ser necessário verificar se o locale está disponível no servidor.
// A configuração do locale pode variar entre sistemas operacionais.
$current_locale = setlocale(LC_TIME, 'pt_BR.utf8', 'pt_BR.UTF-8', 'pt_BR', 'portuguese');

// Data e hora dinâmicas (usando IntlDateFormatter)
// Verifique se a extensão intl está habilitada no seu PHP.
if (class_exists('IntlDateFormatter')) {
    $formatter = new IntlDateFormatter(
        'pt_BR',
        IntlDateFormatter::FULL, // Estilo da data (não usado diretamente aqui, mas necessário)
        IntlDateFormatter::FULL, // Estilo da hora (não usado diretamente aqui, mas necessário)
        'America/Sao_Paulo',     // Fuso horário
        IntlDateFormatter::GREGORIAN,
        'dd MMMM yyyy HH:mm:ss' // Formato desejado
    );
    $dataHoraFormatada = $formatter->format(new DateTime());
} else {
    // Fallback simples se IntlDateFormatter não estiver disponível (sem nome do mês traduzido)
    $dataHoraFormatada = date('d M Y H:i:s');
}


// Saudação baseada na hora atual do servidor
$horaAtual = (int)date('H'); // Pega a hora atual no formato 24h
$saudacao = '';
if ($horaAtual >= 5 && $horaAtual < 12) {
    $saudacao = 'Bom dia';
} elseif ($horaAtual >= 12 && $horaAtual < 18) {
    $saudacao = 'Boa tarde';
} else {
    $saudacao = 'Boa noite';
}
?>
<div class="info-cabecalho">
    <div class="saudacao"><?php echo htmlspecialchars($saudacao); ?> <span class="icone-sol">☀️</span></div>
    <div class="data-hora"><?php echo htmlspecialchars($dataHoraFormatada); ?></div>
</div>