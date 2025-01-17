function carregarItens() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../config/crud-produto/read.php', true); // URL para carregar dados dos clientes
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

            // Coluna de nome
            const nomeColuna = document.createElement('td');
            nomeColuna.textContent = item.nome_servico_produto;
            row.appendChild(nomeColuna);

            // Coluna de descricao
            const descricaoColuna = document.createElement('td');
            descricaoColuna.textContent = item.descricao;
            row.appendChild(descricaoColuna);

            // Coluna de valor
            const valorColuna = document.createElement('td');
            valorColuna.textContent = item.valor_servico_produto;
            row.appendChild(valorColuna);

            // Coluna de quantidade
            const quantidadeColuna = document.createElement('td');
            quantidadeColuna.textContent = item.quantidade;
            row.appendChild(quantidadeColuna);
            
            // Coluna de valor
            const marcaColuna = document.createElement('td');
            marcaColuna.textContent = item.nome_marca_produto;
            row.appendChild(marcaColuna);
            

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
                    item.id_servico_produto,
                    item.id_marca_produto,
                    item.nome_servico_produto,
                    item.descricao,
                    item.valor_servico_produto,
                    item.quantidade,           
                    item.nome_marca_produto      
                );
                
            };
            
            acoesColuna.appendChild(editarBtn);

            // Botão Excluir
            const excluirBtn = document.createElement('button');
            excluirBtn.classList.add('btn', 'btn-danger', 'btn-sm');
            excluirBtn.textContent = 'Excluir';
            excluirBtn.onclick = function () {
                openModal('delete', item.id_servico_produto, item.nome_servico_produto);
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
    id_servico_produto,
    id_marca_produto,
    nome_servico_produto = null,
    descricao = null,
    valor_servico_produto = null,
    quantidade = null,
    nome_marca_produto = null    
    
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
        tituloModal.textContent = 'Editar Produto';
        mensagemModalAcao.textContent = `Editando o Produto: ${nome_servico_produto || ''}`;

        // Conteúdo dinâmico para o formulário de edição
        ///////////////////////AQUI VOCE PODE MEXER////////////////////
        modalContent.innerHTML = `
            <form id="updateForm" action="../config/crud-produto/update.php" method="POST" enctype="multipart/form-data">
    
            <!-- Campo oculto para ID do Produto -->
            <input type="hidden" id="updateIdServicoProduto" name="idProduto" value="${id_servico_produto || ''}">

            <!-- Campo oculto para ID da marca -->
            <input type="hidden" id="updateIdMarcaProduto" name="idMarca" value="${id_marca_produto || ''}">
            
            <!-- Nome do Produto -->
            <div class="mb-3">
                <label for="updateServico" class="form-label">Nome</label>
                <input type="text" id="updateServico" name="nome" class="form-control" value="${nome_servico_produto || ''}">
            </div>
            
            <!-- Descrição do Produto -->
            <div class="mb-3">
                <label for="updateDescricao" class="form-label">Descrição do Produto</label>
                <input type="text" id="updateDescricao" name="descricao" class="form-control" value="${descricao || ''}">
            </div>
            
            <!-- Valor do Produto -->
            <div class="mb-3">
                <label for="updateValor" class="form-label">Valor</label>
                <div class="input-group">
                    <span class="input-group-text">R$</span>
                    <input type="text" id="updateValor" name="valor" class="form-control" value="${valor_servico_produto || ''}">
                    
                </div>
            </div>

            <!-- Quantidade do Produto -->
            <div class="mb-3">
                <label for="updateQuantidade" class="form-label">Quantidade</label>
                <input type="number" id="updateQuantidade" name="quantidade" class="form-control" value="${quantidade || ''}">
            </div>
            
            <!-- Marca do Produto -->
            <div class="mb-3">
                <label for="updateMarca" class="form-label">Marca</label>
                <input type="text" id="updateMarca" name="marca" class="form-control" value="${nome_marca_produto || ''}">
            </div>

            <!-- Botão Salvar -->
            <button type="submit" class="btn btn-success">Salvar</button>
        </form>

            
        `;
        


    } else if (acao === 'delete') {
        tituloModal.textContent = 'Excluir Veiculo';
        mensagemModalAcao.textContent = `Tem certeza que deseja excluir o produto: ${nome_servico_produto || ''}?`;

        // Conteúdo dinâmico para o formulário de exclusão
        modalContent.innerHTML = `
        <form id="deleteForm" action="../config/crud-produto/delete.php" method="POST">
            <!-- Campo oculto para o ID -->
            <input type="hidden" id="deleteId" name="idProduto" value="${id_servico_produto || ''}">
            
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

