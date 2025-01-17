function previewImage(event) {
    const imagePreview = document.querySelector('.perfil-foto img'); // Seleciona a imagem no círculo de perfil
    const file = event.target.files[0]; // Pega o arquivo de imagem

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            imagePreview.src = e.target.result; // Altera a src da imagem para a URL gerada
        }

        reader.readAsDataURL(file); // Lê o arquivo e gera uma URL de dados
    }
}
