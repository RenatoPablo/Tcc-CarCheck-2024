///////////////////ESTADO///////////////////
// Função para abrir o popup
function openPopupEstado() {
    document.getElementById("modal-estado").classList.add("show");
}

// Função para fechar o popup
function closePopupEstado() {
    document.getElementById("modal-estado").classList.remove("show");
}

// Fechar o modal ao clicar fora dele
window.onclick = function(event) {
    let modal = document.getElementById("modal-estado");
    if (event.target === modal) {
        closePopupEstado();  // Fecha o popup se clicar fora da área do modal
    }
}

///////////////////RUA///////////////////
// Função para abrir o popup
function openPopupRua() {
    document.getElementById("modal-rua").classList.add("show");
}

// Função para fechar o popup
function closePopupRua() {
    document.getElementById("modal-rua").classList.remove("show");
}

// Fechar o modal ao clicar fora dele
window.onclick = function(event) {
    let modal = document.getElementById("modal-rua");
    if (event.target === modal) {
        closePopup();  // Fecha o popup se clicar fora da área do modal
    }
}
