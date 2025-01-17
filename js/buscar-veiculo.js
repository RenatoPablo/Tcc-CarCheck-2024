function buscarVeiculo() {
    const placa = document.getElementById('placa').value;

    // Verifica se o campo de placa não está vazio
    if (placa.length === 0) {
        document.getElementById('veiculo').value = ''; // Limpa o campo veículo
        return;
    }

    // Faz a requisição AJAX para buscar o veículo com base na placa
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../config/buscar-veiculo.php?placa=' + encodeURIComponent(placa), true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const resposta = xhr.responseText;

            // Verifica se a resposta contém o nome do veículo
            if (resposta) {
                document.getElementById('veiculo').value = resposta; // Preenche o campo veículo
            } else {
                document.getElementById('veiculo').value = ''; // Se não encontrou, limpa o campo
            }
        }
    };
    xhr.send();
}
