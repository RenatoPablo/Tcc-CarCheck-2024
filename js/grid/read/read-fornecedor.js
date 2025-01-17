function carregarItens() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../config/crud-fornecedor/read.php', true); // URL para carregar dados dos clientes
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
            nomeColuna.textContent = item.nome_fantasia;
            row.appendChild(nomeColuna);

            // Coluna de razao
            const razaoColuna = document.createElement('td');
            razaoColuna.textContent = item.razao_social;
            row.appendChild(razaoColuna);

            // Coluna de ie
            const ieColuna = document.createElement('td');
            ieColuna.textContent = item.ie;
            row.appendChild(ieColuna);

            // Coluna de cnpj
            const cnpjColuna = document.createElement('td');
            cnpjColuna.textContent = item.cnpj;
            row.appendChild(cnpjColuna);
            
            
            

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
                    item.id_fornecedor,
                    item.nome_fantasia,
                    item.razao_social,
                    item.ie,
                    item.cnpj
                );
                
            };
            
            acoesColuna.appendChild(editarBtn);

            // Botão Excluir
            const excluirBtn = document.createElement('button');
            excluirBtn.classList.add('btn', 'btn-danger', 'btn-sm');
            excluirBtn.textContent = 'Excluir';
            excluirBtn.onclick = function () {
                openModal('delete', item.id_fornecedor, item.nome_fantasia);
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
    id_fornecedor,
    nome_fantasia = null,
    razao_social = null,
    ie = null,
    cnpj = null
    
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
        tituloModal.textContent = 'Editar Fornecedor';
        mensagemModalAcao.textContent = `Editando o Fornecedor: ${nome_fantasia || ''}`;

        // Conteúdo dinâmico para o formulário de edição
        ///////////////////////AQUI VOCE PODE MEXER////////////////////
        modalContent.innerHTML = `
            <form id="updateForm" action="../config/crud-fornecedor/update.php" method="POST" enctype="multipart/form-data">
    
            <!-- Campo oculto para ID do Fornecedor -->
            <input type="hidden" id="updateIdFornecedor" name="idFornecedor" value="${id_fornecedor || ''}">
            
            <!-- Nome Fantasia -->
            <div class="mb-3">
                <label for="updateFantasia" class="form-label">Nome Fantasia</label>
                <input type="text" id="updateFantasia" name="nome" class="form-control" value="${nome_fantasia || ''}">
            </div>
            
            <!-- Razao Social -->
            <div class="mb-3">
                <label for="updateRazao" class="form-label">Razão Social</label>
                <input type="text" id="updateRazao" name="razao" class="form-control" value="${razao_social || ''}">
            </div>
            
            

            <!-- IE -->
            <div class="mb-3">
                <label for="updateIe" class="form-label">Inscrição Estadual</label>
                <input type="text" id="updateIE" name="ie" class="form-control" value="${ie || ''}">
            </div>
            
            <!-- CNPJ -->
            <div class="mb-3">
                <label for="updateCnpj" class="form-label">CNPJ</label>
                <input type="text" id="updateCnpj" name="cnpj" class="form-control" value="${cnpj || ''}">
            </div>

            <!-- Botão Salvar -->
            <button type="submit" class="btn btn-success">Salvar</button>
        </form>

            
        `;
        


    } else if (acao === 'delete') {
        tituloModal.textContent = 'Excluir Fornecedor';
        mensagemModalAcao.textContent = `Tem certeza que deseja excluir o fornecedor: ${nome_fantasia || ''}?`;

        // Conteúdo dinâmico para o formulário de exclusão
        modalContent.innerHTML = `
        <form id="deleteForm" action="../config/crud-fornecedor/delete.php" method="POST">
            <!-- Campo oculto para o ID -->
            <input type="hidden" id="deleteId" name="idFornecedor" value="${id_fornecedor || ''}">
            
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

