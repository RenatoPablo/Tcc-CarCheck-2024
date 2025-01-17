

// Função para abrir/fechar a sidebar e ajustar os ícones
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('open');

    // Adicione ou remova a classe ajustada para os ícones
    const icons = document.querySelector('.icons');
    icons.classList.toggle('adjusted');
}

