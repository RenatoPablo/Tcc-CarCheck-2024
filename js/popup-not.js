// Função para mostrar a notificação
function showNotification() {
    var notification = document.getElementById("notification");
    notification.classList.add("show");

    // Remover notificação após 4 segundos
    setTimeout(function() {
        notification.classList.remove("show");
    }, 40000);
}

// Função para fechar a notificação manualmente
function closeNotification() {
    var notification = document.getElementById("notification");
    notification.classList.remove("show");
}
