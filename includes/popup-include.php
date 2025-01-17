<div id="popup" class="popup-overlay" style="display: none;">
    <div class="popup-content">
        <span id="popup-close" class="popup-close">&times;</span>
        <h2 id="popup-title"></h2>
        <p id="popup-message"></p>
        <button id="popup-button" class="popup-button">OK</button>
    </div>
</div>

<style>
    /* Estilos para o popup */
    .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .popup-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        max-width: 400px;
        width: 100%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .popup-close {
        position: absolute;
        top: 10px;
        right: 20px;
        cursor: pointer;
        font-size: 20px;
    }

    .popup-button {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
    }

    .popup-button:hover {
        background-color: #0056b3;
    }
</style>

<script>
    // Função para exibir o popup com título e mensagem personalizados
    function showPopup(title, message) {
        document.getElementById('popup-title').innerText = title;
        document.getElementById('popup-message').innerText = message;
        document.getElementById('popup').style.display = 'flex';
    }

    // Função para fechar o popup
    document.getElementById('popup-close').onclick = function() {
        document.getElementById('popup').style.display = 'none';
    };

    document.getElementById('popup-button').onclick = function() {
        document.getElementById('popup').style.display = 'none';
    };
</script>
