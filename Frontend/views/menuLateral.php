<?php
// Pega o nome do arquivo da página que está sendo acessada no momento
$paginaAtual = basename($_SERVER['PHP_SELF']);

// --- DADOS DO USUÁRIO ---
$nomeUsuarioLogin = "Fernanda";
$cargoUsuarioLogin = "Super Admin";
// Caminho do avatar: Ajuste se a imagem não estiver acessível do navegador via placeholder
$avatarUsuarioLogin = "https://via.placeholder.com/50/1A2A3A/FFFFFF?Text=F";

// --- LINKS DO MENU ---
// Todos os links agora são NOME_DO_ARQUIVO.php porque todas as views estão na mesma pasta
$linkDashboard = "dashboard.php";
$linkCadastroPaciente = 'CadastroPaciente.php';
$linkListaPaciente = 'ListarPaciente.php';

// Links da seção Exame
$linkListaExames = "lista_de_exames.php";
$linkSolicitarNovoExame = "solicitacao_form.php";
// ALTERADO: O link "Cadastrar Resultado" agora aponta para a lista de solicitações pendentes
$linkCadastrarResultadoExame = "lista_solicitacoes_pendentes.php"; // Novo nome da página

$linkUsuario = "sua_pagina_de_usuarios.php"; // Certifique-se de criar esta página na pasta views
$linkEstoque = "AcessarEstoque.php"; // Certifique-se de que esta página está na pasta views

// Lógica para destacar o item "Exame" (pai) se qualquer página da seção Exame estiver ativa
$secaoExameAtiva = (
    $paginaAtual == basename($linkListaExames) ||
    $paginaAtual == basename($linkSolicitarNovoExame) ||
    $paginaAtual == basename($linkCadastrarResultadoExame) // Note que agora ele referencia a nova página
);
?>

<div class="menu-lateral">
    <div class="menu-cabecalho">
        <h1><span class="icone-bio">Bio</span>Diagnóstico</h1>
    </div>
    <div class="perfil-usuario">
        <img src="<?php echo htmlspecialchars($avatarUsuarioLogin); ?>" alt="Avatar do Usuário">
        <div class="info-usuario">
            <div class="nome"><?php echo htmlspecialchars($nomeUsuarioLogin); ?></div>
            <div class="cargo"><?php echo htmlspecialchars($cargoUsuarioLogin); ?></div>
        </div>
        <div class="menu-kebab">⋮</div>
    </div>
    <nav class="navegacao-menu">
        <ul>
            <li class="<?php if ($paginaAtual == basename($linkDashboard)) echo 'item-ativo'; ?>">
                <a href="<?php echo $linkDashboard; ?>">Dashboard</a>
            </li>
            <li class="<?php if ($paginaAtual == basename($linkCadastroPaciente)) echo 'item-ativo'; ?>">
                <a href="<?php echo $linkCadastroPaciente; ?>">Cadastrar Paciente</a>
            </li>

            <li class="tem-submenu <?php if ($secaoExameAtiva) echo 'item-ativo'; ?>" id="menuPaiExame">
                <a href="#" onclick="toggleSubmenu(event, 'submenuExame', 'menuPaiExame')">Exame <span class="icone-seta">▼</span></a>
                <ul class="submenu-nav" id="submenuExame">
                    <li class="<?php if ($paginaAtual == basename($linkListaExames)) echo 'item-ativo'; ?>">
                        <a href="<?php echo $linkListaExames; ?>">Lista de exames</a>
                    </li>
                    <li class="<?php if ($paginaAtual == basename($linkSolicitarNovoExame)) echo 'item-ativo'; ?>">
                        <a href="<?php echo $linkSolicitarNovoExame; ?>">Solicitar novo exame</a>
                    </li>
                    <li class="<?php if ($paginaAtual == basename($linkCadastrarResultadoExame)) echo 'item-ativo'; ?>">
                        <a href="<?php echo $linkCadastrarResultadoExame; ?>">Preencher Resultados de Exames</a>
                    </li>
                </ul>
            </li>

            <li class="<?php if ($paginaAtual == basename($linkUsuario)) echo 'item-ativo'; ?>">
                <a href="<?php echo $linkUsuario; ?>">Usuário</a>
            </li>
            <li class="<?php if ($paginaAtual == basename($linkEstoque)) echo 'item-ativo'; ?>">
                <a href="<?php echo $linkEstoque; ?>">Estoque</a>
            </li>
            <li class="<?php if ($paginaAtual == basename($linkListaPaciente)) echo 'item-ativo'; ?>">
                <a href="<?php echo $linkListaPaciente; ?>">Listar Paciente</a>
            </li>
        </ul>
    </nav>
</div>

<script>
// A função toggleSubmenu precisa estar definida e ser acessível por todas as páginas.
// Se ela está em validacoes.js, certifique-se de que validacoes.js é carregado em CADA PÁGINA que usa o menu.
// Se ela não estiver em validacoes.js, você pode colocá-la diretamente aqui.

// Exemplo da função (se não estiver em validacoes.js ou para debug)
// function toggleSubmenu(event, submenuId, parentMenuId) {
//     event.preventDefault(); // Impede o comportamento padrão do link '#'
//     const submenu = document.getElementById(submenuId);
//     const parentMenuItem = document.getElementById(parentMenuId);

//     if (submenu && parentMenuItem) {
//         // Alterna a exibição do submenu
//         if (submenu.style.display === 'block') {
//             submenu.style.display = 'none';
//             parentMenuItem.classList.remove('submenu-ativo'); // Remova uma classe se o submenu estiver fechado
//         } else {
//             submenu.style.display = 'block';
//             parentMenuItem.classList.add('submenu-ativo'); // Adicione uma classe se o submenu estiver aberto
//         }
//     }
// }

// Lógica para abrir o submenu se a seção "Exame" estiver ativa ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    const secaoExameAtiva = <?php echo json_encode($secaoExameAtiva); ?>;
    const submenuExame = document.getElementById('submenuExame');
    const menuPaiExame = document.getElementById('menuPaiExame');

    if (secaoExameAtiva && submenuExame && menuPaiExame) {
        submenuExame.style.display = 'block'; // Mostra o submenu
        menuPaiExame.classList.add('submenu-ativo'); // Adiciona classe para estilização de "aberto"
    }
});
</script>