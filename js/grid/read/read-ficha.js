document.getElementById('inputBusca').addEventListener('input', function () {
    const valorBusca = this.value.trim();

    if (valorBusca.length === 0) {
        limparGrid();
        return;
    }

    fetch(`../config/ficha-veiculo/read.php?placa=${encodeURIComponent(valorBusca)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erro na requisição: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                atualizarGrid(data.data);
            } else {
                console.error('Erro do backend:', data.message);
                limparGrid();
            }
        })
        .catch(error => {
            console.error('Erro ao buscar veículos:', error);
            limparGrid();
        });
});

function atualizarGrid(veiculos) {
    const grid = document.getElementById('gridResultadoBusca');
    grid.innerHTML = ''; // Limpa os resultados anteriores

    if (veiculos.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = `<td colspan="4" class="text-center">Nenhum veículo encontrado.</td>`;
        grid.appendChild(row);
        return;
    }

    veiculos.forEach(veiculo => {
        const row = document.createElement('tr');

        row.innerHTML = `
            <td>${veiculo.modelo}</td>
            <td>${veiculo.placa}</td>
            <td>${veiculo.cor}</td>
            <td>
                <button class="btn btn-info btn-sm" onclick="visualizarFicha('${veiculo.placa}')">Visualizar</button>
            </td>
        `;
        grid.appendChild(row);
    });
}

function limparGrid() {
    const grid = document.getElementById('gridResultadoBusca');
    grid.innerHTML = `<tr><td colspan="4" class="text-center">Nenhum veículo encontrado.</td></tr>`;
}

function visualizarFicha(placa) {
    // Redireciona para outra página, passando a placa como parâmetro na URL
    const url = `../pages/detalhes-ficha.php?placa=${encodeURIComponent(placa)}`;
    window.location.href = url;
}
