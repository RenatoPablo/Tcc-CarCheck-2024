// Função para visualizar a imagem no campo de visualização
        function previewImage(event) {
            var preview = document.getElementById('imagePreview');
            var file = event.target.files[0]; // Pega o arquivo selecionado
            
            // Verifica se um arquivo foi selecionado
            if (file) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    // Limpa a mensagem de "Imagem não selecionada"
                    preview.innerHTML = '';
                    // Cria um elemento img e define a source como o resultado do reader
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    preview.appendChild(img);
                };
                
                reader.readAsDataURL(file); // Lê o conteúdo do arquivo como uma URL
            } else {
                // Caso o usuário remova a imagem ou não selecione uma
                preview.innerHTML = '<span>Imagem não selecionada</span>';
            }
        }
