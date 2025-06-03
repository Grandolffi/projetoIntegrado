<?php
// Pega o nome do arquivo da página que está sendo acessada no momento
$paginaAtual = basename($_SERVER['PHP_SELF']);

// --- DADOS DO USUÁRIO ---
// Mantenha os dados do usuário que você preferir (Fernanda ou Kleber)
$nomeUsuarioLogin = "Fernanda"; // Ou "Kleber", conforme sua preferência
$cargoUsuarioLogin = "Super Admin";
// Atualize o link do avatar se necessário
$avatarUsuarioLogin = "https://via.placeholder.com/50/1A2A3A/FFFFFF?Text=F"; // Placeholder para Fernanda (ou K para Kleber)

// --- LINKS DO MENU ---
$linkDashboard = "dashboard.php";
$linkCadastroPaciente = 'CadastroPaciente.php';

// Links da seção Exame
$linkListaExames = "lista_de_exames.php";
$linkSolicitarNovoExame = "NewExamePaciente.php"; // Ou o nome correto da sua página de solicitar exame
$linkCadastrarResultadoExame = "cadastroExames.php"; 

$linkUsuario = "sua_pagina_de_usuarios.php"; // Crie esta página
$linkEstoque = "AcessarEstoque.php";

// Lógica para destacar o item "Exame" (pai) se qualquer página da seção Exame estiver ativa
$secaoExameAtiva = (
    $paginaAtual == basename($linkListaExames) ||
    $paginaAtual == basename($linkSolicitarNovoExame) ||
    $paginaAtual == basename($linkCadastrarResultadoExame)
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
                        <a href="<?php echo $linkCadastrarResultadoExame; ?>">Cadastrar Resultado</a>
                    </li>
                </ul>
            </li>

            <li class="<?php if ($paginaAtual == basename($linkUsuario)) echo 'item-ativo'; ?>">
                <a href="<?php echo $linkUsuario; ?>">Usuário</a>
            </li>
            <li class="<?php if ($paginaAtual == basename($linkEstoque)) echo 'item-ativo'; ?>">
                <a href="<?php echo $linkEstoque; ?>">Estoque</a>
            </li>
        </ul>
    </nav>
</div>