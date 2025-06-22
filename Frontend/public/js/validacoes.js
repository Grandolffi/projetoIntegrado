// Função para o SUBMENU LATERAL (Exame)
function toggleSubmenu(event, submenuId, parentId) {
    event.preventDefault(); // Previne o link de navegar para "#"
    
    const submenu = document.getElementById(submenuId);
    const parentLi = document.getElementById(parentId);

    if (submenu) {
        submenu.classList.toggle('submenu-visivel');
    }
    if (parentLi) {
        parentLi.classList.toggle('pai-aberto');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const menuPaiExame = document.getElementById('menuPaiExame');
    if (menuPaiExame && menuPaiExame.classList.contains('item-ativo')) {
        const submenuExame = document.getElementById('submenuExame');
        if (submenuExame) {
            submenuExame.classList.add('submenu-visivel');
            menuPaiExame.classList.add('pai-aberto');
        }
    }
});

// Função JavaScript para confirmar exclusão de Laudo
function confirmarExclusaoLaudo(idLaudo) {
    if (confirm('Tem certeza que deseja excluir este laudo e todos os exames associados? Esta ação é irreversível.')) {
        // Se confirmar, redireciona para o controlador PHP que irá lidar com a exclusão
        // O caminho aqui deve apontar para o seu LaudoController.php
        window.location.href = '../controller/LaudoController.php?excluir=' + idLaudo;
    }
}

// Função JavaScript para confirmar exclusão
function confirmarExclusao(idExame) {
    if (confirm('Tem certeza que deseja excluir este resultado de exame? Esta ação é irreversível.')) {
    // Se confirmar, redireciona para o controlador PHP que agora lida com a exclusão via DAO PHP
         window.location.href = '../controller/ExameController.php?excluir=' + idExame; // Ajustado para 'excluir'
     }
}
        