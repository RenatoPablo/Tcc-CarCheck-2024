<style>
    /* Modal */
.modal-mensagem {
    display: none; /* Inicialmente escondido */
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7); /* Fundo escuro */
    justify-content: center;
    align-items: center;
}

.modal-conteudo {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    max-width: 300px;
    margin: 0 auto;
}

.modal-fechar {
    position: absolute;
    right: 15px;
    top: 10px;
    cursor: pointer;
    font-size: 20px;
}

</style>

<!-- Modal de Mensagem -->
<div id="popupMensagem" class="modal-mensagem">
    <div class="modal-conteudo">
        <span id="fecharModal" class="modal-fechar">&times;</span>
        <p id="mensagemTexto"></p>
    </div>
</div>


<script>
    // Função para exibir o modal com a mensagem e desaparecer automaticamente
    function mostrarPopupMensagem(mensagem) {
        const modal = document.getElementById('popupMensagem');
        const mensagemTexto = document.getElementById('mensagemTexto');
    
        // Define o texto da mensagem
        mensagemTexto.textContent = mensagem;
    
        // Exibe o modal
        modal.style.display = 'flex';
    
        // Fecha o modal após 3 segundos
        setTimeout(function() {
            modal.style.display = 'none';
        }, 3000); // 3000 ms = 3 segundos

        // Adiciona um evento para fechar o modal ao clicar fora dele
        window.addEventListener('click', function fecharModalAoClicarFora(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
                window.removeEventListener('click', fecharModalAoClicarFora); // Remove o evento após fechar o modal
            }
        });
    }
    
    // Fechar o modal ao clicar no botão de fechar (X)
    document.getElementById('fecharModal').onclick = function() {
        const modal = document.getElementById('popupMensagem');
        modal.style.display = 'none';
    };
    
    // Exemplo de uso
    window.onload = function() {
        <?php if (isset($_SESSION['mensagem'])) : ?>
            mostrarPopupMensagem("<?php echo $_SESSION['mensagem']; ?>");
            <?php unset($_SESSION['mensagem']); // Limpa a mensagem após exibir ?>
        <?php endif; ?>
    };
</script>
