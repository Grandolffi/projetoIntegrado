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