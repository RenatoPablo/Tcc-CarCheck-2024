function carregarItens() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../config/crud-veiculo/read.php', true); // URL para carregar dados dos clientes
    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log(xhr.responseText); // Verifica a resposta antes de tentar parsear
            try {
                const resultados = JSON.parse(xhr.responseText);
                console.log(resultados); // Verifica o JSON retornado para garantir que está correto
                atualizarGrid(resultados); // Exibe todos os resultados na grid
            } catch (error) {
                console.error("Erro ao processar os dados:", error);
            }
        } else {
            console.error("Erro na requisição: " + xhr.status);
        }
    };
    xhr.send();
}

// Função para filtrar a grid com base no valor digitado
document.getElementById('inputBusca').addEventListener('keyup', function () {
    const valorBusca = this.value.toLowerCase();
    const linhas = document.querySelectorAll('#gridResultadoBusca tr'); // Seleciona todas as linhas da tabela

    linhas.forEach(function (linha) {
        const textoLinha = linha.textContent.toLowerCase(); // Pega todo o texto da linha
        if (textoLinha.includes(valorBusca)) {
            linha.style.display = ''; // Exibe a linha se houver correspondência
        } else {
            linha.style.display = 'none'; // Oculta a linha se não houver correspondência
        }
    });
});


function atualizarGrid(resultados) {
    const container = document.getElementById('gridResultadoBusca');
    container.innerHTML = ''; // Limpa a grid antes de atualizar

    if (resultados.length > 0) {
        resultados.forEach(item => {
            const row = document.createElement('tr'); // Cria uma linha <tr>

            // Coluna de modelo
            const modeloColuna = document.createElement('td');
            modeloColuna.textContent = item.nome_modelo;
            row.appendChild(modeloColuna);

            // Coluna de placa
            const placaColuna = document.createElement('td');
            placaColuna.textContent = item.placa;
            row.appendChild(placaColuna);

            // Coluna de proprietario
            const telefoneColuna = document.createElement('td');
            telefoneColuna.textContent = item.nome_pessoa;
            row.appendChild(telefoneColuna);

            // Coluna de Status
            const statusColuna = document.createElement('td');
            statusColuna.textContent = item.status ? 'Sim' : 'Não';
            row.appendChild(statusColuna);

            

            // Coluna de Ações
            const acoesColuna = document.createElement('td');

            // Botão Editar
            const editarBtn = document.createElement('button');
            editarBtn.classList.add('btn', 'btn-primary', 'btn-sm');
            editarBtn.textContent = 'Editar';

            // Chama o modal de edição com todos os dados
            editarBtn.onclick = function () {
                openModal(
                    'update',
                    item.id_veiculo,
                    item.id_pessoa,
                    item.id_cor,
                    item.id_tipo_veiculo,
                    item.id_modelo,
                    item.id_marca,                    
                    item.placa,
                    item.status,
                    item.nome_pessoa,
                    item.nome_cor,
                    item.nome_tipo_veiculo,
                    item.nome_modelo,
                    item.nome_marca,                    
                );
                
            };
            
            acoesColuna.appendChild(editarBtn);

            // Botão Excluir
            const excluirBtn = document.createElement('button');
            excluirBtn.classList.add('btn', 'btn-danger', 'btn-sm');
            excluirBtn.textContent = 'Excluir';
            excluirBtn.onclick = function () {
                openModal('delete', item.id_veiculo, item.nome_modelo);
            };
            acoesColuna.appendChild(excluirBtn);

            row.appendChild(acoesColuna);
            container.appendChild(row);
        });
    } else {
        // Caso não tenha nenhum item
        const noData = document.createElement('div');
        noData.classList.add('text-center', 'py-3');
        noData.textContent = 'Nenhum item encontrado.';
        container.appendChild(noData);
    }
}






function openModal(
    acao, 
    id_veiculo,
    id_pessoa,
    id_cor,
    id_tipo_veiculo, 
    id_modelo, 
    id_marca, 
    placa = null,
    status = null,
    nome_pessoa = null, 
    nome_cor = null, 
    nome_tipo_veiculo = null, 
    nome_modelo = null, 
    nome_marca = null, 
    
) 
{
    const modalAcao = new bootstrap.Modal(document.getElementById('modalAcao'));
    const tituloModal = document.getElementById('modalTituloAcao');
    const mensagemModalAcao = document.getElementById('mensagemModalAcao');
    const modalContent = document.getElementById('modalContent');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    // Limpa conteúdo anterior do modal
    modalContent.innerHTML = '';
    confirmDeleteBtn.classList.add('d-none');

    if (acao === 'update') {
        tituloModal.textContent = 'Editar Veiculo';
        mensagemModalAcao.textContent = `Editando o veiculo: ${nome_modelo || ''}`;

        // Conteúdo dinâmico para o formulário de edição
        ///////////////////////AQUI VOCE PODE MEXER////////////////////
        modalContent.innerHTML = `
            <form id="updateForm" action="../config/crud-veiculo/update.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="updateIdVeiculo" name="idVeiculo" value="${id_veiculo || ''}">
                <input type="hidden" id="updateIdPessoa" name="idPessoa" value="${id_pessoa || ''}">
                <input type="hidden" id="updateIdCor" name="idCor" value="${id_cor || ''}">
                <input type="hidden" id="updateIdTipoVeiculo" name="idTipoVeiculo" value="${id_tipo_veiculo || ''}">
                <input type="hidden" id="updateIdModelo" name="idModelo" value="${id_modelo || ''}">
                <input type="hidden" id="updateIdMarca" name="idMarca" value="${id_marca || ''}">
                

                

                
                <!-- Proprietário -->
                <label for="updateProprietario">Proprietário</label>
                <input type="text" id="prop" name="proprietario" class="form-control" class="input" onkeyup="buscarProprietarios()" autocomplete="off" value="${nome_pessoa || ''}">
                <ul id="sugestoes" class="suggestions"></ul>

                <!-- PLaca -->
                <label for="updatePlaca">Placa</label>
                <input type="text" id="updatePlaca" name="placa" class="form-control" class="input" oninput="mascaraPlacaVeiculo(this)" value="${placa || ''}">

                <!-- Status -->
                <label for="updateStatus">Status</label>
                <select id="updateStatus" name="status" class="form-control" class="input">
                    <option value="ativo" ${status === 'ativo' ? 'selected' : ''}>Ativo</option>
                    <option value="inativo" ${status === 'inativo' ? 'selected' : ''}>Inativo</option>
                </select>

                
                <label for="updateModelo">Modelo</label>
                <input type="text" id="updateModelo" name="modelo" class="form-control" class="input" value="${nome_modelo || ''}">
                
                <label for="updateTipoVeiculo">Tipo Veiculo</label>
                <input type="text" id="updateTipoVeiculo" name="tipoVeiculo" class="form-control" class="input" value="${nome_tipo_veiculo || ''}">              

                <label for="updateCor">Cor</label>
                <input type="text" id="updateCor" name="cor" class="form-control" class="input" value="${nome_cor || ''}">

                <label for="updateMarca">Marca</label>
                <input type="text" id="updateMarca" name="marca" class="form-control" class="input" value="${nome_marca || ''}">

                

                <button type="submit" class="btn btn-success">Salvar</button>
            </form>
            
        `;
        // Adiciona o evento para buscar proprietários ao campo Proprietário
        document.getElementById('prop').addEventListener('keyup', buscarProprietarios);

        // Adiciona o evento para aplicar a máscara ao campo Placa
        document.getElementById('updatePlaca').addEventListener('input', function() {
            mascaraPlacaVeiculo(this);
        });


    } else if (acao === 'delete') {
        tituloModal.textContent = 'Excluir Veiculo';
        mensagemModalAcao.textContent = `Tem certeza que deseja excluir o veiculo: ${nome_modelo || ''}?`;

        // Conteúdo dinâmico para o formulário de exclusão
        modalContent.innerHTML = `
        <form id="deleteForm" action="../config/crud-veiculo/delete.php" method="POST">
            <!-- Campo oculto para o ID -->
            <input type="hidden" id="deleteId" name="idVeiculo" value="${id_veiculo || ''}">
            
            <!-- Botão para confirmar a exclusão -->
            <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
            
        </form>
        `;

        
    }

    modalAcao.show();
}

// Função para pré-visualizar a foto antes do upload
function previewFoto(event) {
    const fotoInput = event.target;
    const previewElement = document.getElementById('fotoPreview');
    const previewText = document.getElementById('fotoPreviewText');

    if (fotoInput.files && fotoInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            if (previewElement) {
                previewElement.src = e.target.result;
            } else {
                const img = document.createElement('img');
                img.id = 'fotoPreview';
                img.src = e.target.result;
                img.alt = 'Pré-visualização da nova foto';
                img.classList.add('img-thumbnail', 'mb-3');
                img.style.width = '100px';
                img.style.height = '100px';
                previewText.replaceWith(img);
            }
        };
        reader.readAsDataURL(fotoInput.files[0]);
    }
}
















document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById("modalCadastro");
    const btnAbrirModal = document.getElementById("btnAbrirModalCadastro");
    const btnFecharModal = document.querySelector(".modal-close");

    if (btnAbrirModal) {
        btnAbrirModal.addEventListener("click", function() {
            modal.style.display = "flex";
        });
    }

    if (btnFecharModal) {
        btnFecharModal.addEventListener("click", function() {
            modal.style.display = "none";
        });
    }

    window.addEventListener("click", function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });
});

carregarItens(); // Chama a função para carregar dados ao iniciar a página

