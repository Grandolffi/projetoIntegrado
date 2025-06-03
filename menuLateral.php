<?php
// Pega o nome do arquivo da página que está sendo acessada no momento
// Isso é usado para destacar o link correto no menu.
$paginaAtual = basename($_SERVER['PHP_SELF']);

// --- DADOS DO USUÁRIO ---
// No futuro, estes dados podem vir de uma sessão de login ou banco de dados.
$nomeUsuarioLogin = "Kleber";         
$cargoUsuarioLogin = "Super Admin";   
$avatarUsuarioLogin = "https://via.placeholder.com/50/A0AEC0/FFFFFF?Text=S"; // Link para a imagem do avatar

// --- LINKS DO MENU ---
$linkDashboard = "dashboard.php";                 
$linkCadastroPaciente = 'CadastroPaciente.php';
$linkExames = "cadastroExames.php";
$linkUsuario = "sua_pagina_de_usuarios.php";  
$linkEstoque = "AcessarEstoque.php";
?>

<div class="menu-lateral">
    <div class="menu-cabecalho">
        <h1><span class="icone-bio">&#128736;</span>BioDiagnóstico</h1> </div>
    <div class="perfil-usuario">
        <img src="<?php echo htmlspecialchars($avatarUsuarioLogin); ?>" alt="Avatar do Usuário">
        <div class="info-usuario">
            <div class="nome"><?php echo htmlspecialchars($nomeUsuarioLogin); ?></div>
            <div class="cargo"><?php echo htmlspecialchars($cargoUsuarioLogin); ?></div>
        </div>
        <div class="menu-kebab">⋮</div> </div>
    <nav class="navegacao-menu">
        <ul>
            <li class="<?php if ($paginaAtual == basename($linkDashboard)) echo 'item-ativo'; ?>">
                <a href="<?php echo $linkDashboard; ?>">Dashboard</a>
            </li>
            <li class="<?php if ($paginaAtual == basename($linkCadastroPaciente)) echo 'item-ativo'; ?>">
                <a href="<?php echo $linkCadastroPaciente; ?>">Cadastrar Paciente</a>
            </li>
            <li class="<?php if ($paginaAtual == basename($linkExames)) echo 'item-ativo'; ?>">
                <a href="<?php echo $linkExames; ?>">Exame <span class="icone-seta">▼</span></a>
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