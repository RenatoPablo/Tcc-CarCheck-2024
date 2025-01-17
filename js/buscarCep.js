function buscarCep() {
    var cep = document.getElementById('cep').value;

    // Se o campo de CEP estiver vazio, limpa os campos e retorna
    if (cep === "") {
        document.getElementById('cidade').value = "";
        document.getElementById('estado').value = "";
        document.getElementById('bairro').value = "";
        return;
    }

    // Requisição AJAX para o PHP
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../config/api_cep.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Converte o retorno JSON em objeto JavaScript
            var dados = JSON.parse(xhr.responseText);

            // Verifica se houve erro
            if (dados.erro) {
                alert(dados.mensagem);
            } else {
                // Preenche os inputs de cidade, estado e bairro
                document.getElementById('cidade').value = dados.cidade;
                document.getElementById('estado').value = dados.estado;
                
            }
        }
    };
    // Enviar o CEP para o PHP via POST
    xhr.send('cep=' + cep);
}