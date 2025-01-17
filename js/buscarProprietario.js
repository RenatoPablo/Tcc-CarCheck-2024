// Função para buscar os proprietários no banco de dados com AJAX
function buscarProprietarios() {
    console.log("Função buscarProprietarios chamada");
    const input = document.getElementById('prop');
    const sugestoes = document.getElementById('sugestoes');
    const query = input.value;

    // Limpar a lista de sugestões e ocultá-la se o campo estiver vazio
    if (query.length === 0) {
        sugestoes.innerHTML = '';
        sugestoes.style.display = 'none'; // Ocultar sugestões
        return;
    }

    // Fazer a requisição AJAX para buscar os proprietários
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../config/buscar_proprietarios.php?query=' + encodeURIComponent(query), true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const proprietarios = JSON.parse(xhr.responseText);
            sugestoes.innerHTML = '';

            // Se não houver sugestões, também ocultar a lista
            if (proprietarios.length === 0) {
                sugestoes.style.display = 'none';
            } else {
                sugestoes.style.display = 'block'; // Mostrar sugestões

                // Limitar a lista a no máximo 10 itens
                const maxItens = Math.min(proprietarios.length, 10);
                for (let i = 0; i < maxItens; i++) {
                    const proprietario = proprietarios[i];
                    const li = document.createElement('li');
                    li.textContent = proprietario.nome_pessoa;
                    li.onclick = function() {
                        input.value = proprietario.nome_pessoa; // Preencher o input com o nome selecionado
                        sugestoes.innerHTML = ''; // Limpar as sugestões
                        sugestoes.style.display = 'none'; // Ocultar a lista de sugestões
                    };
                    sugestoes.appendChild(li);
                }
            }
        }
    };
    xhr.send();
}


