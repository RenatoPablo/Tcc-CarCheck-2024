let produtosServicos = []; // Lista global de produtos e serviços

function carregarItens() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../config/crud-nota/read.php', true); // URL para carregar dados dos clientes
    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log(xhr.responseText); // Verifica a resposta antes de tentar parsear
            try {
                const manutencao = JSON.parse(xhr.responseText);
                console.log("Dados JSON parseados:", manutencao); // Verifica o JSON retornado para garantir que está correto
                // Verifique e converta `itens_manutencao` para array se necessário
                manutencao.forEach(item => {
                    item.itens_manutencao = Array.isArray(item.itens_manutencao) ? item.itens_manutencao : [item.itens_manutencao];
                });
                // Verifica se o JSON é um array ou objeto
                if (Array.isArray(manutencao)) {
                    console.log("JSON é um array.");
                } else {
                    console.log("JSON é um objeto, não um array.");
                }

                atualizarGrid(manutencao); // Exibe todos os manutencao na grid
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

function processarItensManutencao(itensManutencao) {
    if (Array.isArray(itensManutencao)) {
        itensManutencao.forEach(item => {
            console.log("ID do Serviço/Produto:", item.fk_id_servico_produto);
        });
    } else {
        console.error("itens_manutencao não é um array.");
    }
}

// Exemplo de chamada da função
//processarItensManutencao(item.itens_manutencao);


function atualizarGrid(manutencao) {
    const container = document.getElementById('gridResultadoBusca');
    container.innerHTML = ''; // Limpa a grid antes de atualizar

    // Verifique se `manutencao` é um array; se não, transforme-o em um array
    const manutencaoArray = Array.isArray(manutencao) ? manutencao : [manutencao];

    if (manutencaoArray.length > 0) {
        manutencaoArray.forEach(item => {
            const row = document.createElement('tr'); // Cria uma linha <tr>

            // Coluna de data
            const dataColuna = document.createElement('td');
            const dataOriginal = new Date(item.time_saida);
            const dia = String(dataOriginal.getDate()).padStart(2, '0');
            const mes = String(dataOriginal.getMonth() + 1).padStart(2, '0');
            const ano = String(dataOriginal.getFullYear()).slice(-2);

            dataColuna.textContent = `${dia}-${mes}-${ano}`;
            row.appendChild(dataColuna);

            // Coluna código
            const codigoColuna = document.createElement('td');
            codigoColuna.textContent = item.codigo;
            row.appendChild(codigoColuna);

            // Coluna de nome
            const nomeColuna = document.createElement('td');
            nomeColuna.textContent = item.veiculo.proprietario.nome_pessoa;
            row.appendChild(nomeColuna);

            // Coluna de modelo
            const modeloColuna = document.createElement('td');
            modeloColuna.textContent = item.veiculo.modelo.nome_modelo;
            row.appendChild(modeloColuna);

            // Coluna de placa
            const placaColuna = document.createElement('td');
            placaColuna.textContent = item.veiculo.placa;
            row.appendChild(placaColuna);

            // Coluna de valor total
            const valorColuna = document.createElement('td');
            valorColuna.textContent = `R$ ${parseFloat(item.valor_total).toFixed(2)}`;
            row.appendChild(valorColuna);

            // Coluna de faturamento
            const faturamentoStatus = item.faturamento ? "Faturada" : "Pendente";
            const faturamentoColuna = document.createElement('td');
            faturamentoColuna.textContent = faturamentoStatus;
            row.appendChild(faturamentoColuna);

            // Coluna de Pagamento com o botão "Processar Pagamento"
            const pagamentoColuna = document.createElement('td');
            const pagamentoBtn = document.createElement('a');
            pagamentoBtn.classList.add('btn', 'btn-success', 'btn-sm');
            pagamentoBtn.textContent = 'Adicionar Pagamento';

            const gerenciarPagamentoBtn = document.createElement('a');
            gerenciarPagamentoBtn.classList.add('btn', 'btn-success', 'btn-sm');
            gerenciarPagamentoBtn.textContent = 'Gerenciar Parcelas';

            // Desabilita o botão se o faturamento for "Faturada"
            if (faturamentoStatus === "Faturada") {
                pagamentoBtn.disabled = true;
                pagamentoBtn.classList.add('botao-desabilitado');
            } else {
                pagamentoBtn.onclick = function() {
                    abrirModalPagamento(item.id_manutencao, item.valor_total);
                };
            }

            if (faturamentoStatus === "Faturada") {
                gerenciarPagamentoBtn.onclick = function() {
                    abrirModalGerenciamentoPagamento(item.id_manutencao, item.codigo);
                    console.log("Id Manutencao para pagamento", item.id_manutencao);
                }
            } else {
                gerenciarPagamentoBtn.disabled = true;
                gerenciarPagamentoBtn.classList.add('botao-desabilitado');
            }


            pagamentoColuna.appendChild(pagamentoBtn);
            pagamentoColuna.appendChild(gerenciarPagamentoBtn)
            row.appendChild(pagamentoColuna);

            // Coluna de Ações
            const acoesColuna = document.createElement('td');

            // Botão Editar
            const editarBtn = document.createElement('button');
            editarBtn.classList.add('btn', 'btn-primary', 'btn-sm');
            editarBtn.textContent = 'Editar';


            // Ajustes de estilo adicionais para alinhamento
            editarBtn.style.margin = "3px"; // Remove margens externas
            editarBtn.style.display = "inline-block"; // Garante que o botão seja tratado como elemento 

            // Desabilita o botão Editar se o faturamento for "Faturada"
            if (faturamentoStatus === "Faturada") {
                editarBtn.disabled = true;
                editarBtn.classList.add('botao-desabilitado');
            } else {
                editarBtn.onclick = function() {
                    openModal(
                        'update',
                        item.id_manutencao,
                        item.veiculo.modelo.id_modelo,
                        item.veiculo.proprietario.id_pessoa,
                        item.pagamentos.id_pagamento,
                        Array.isArray(item.itens_manutencao) ? item.itens_manutencao : [item.itens_manutencao],
                        item.codigo,
                        item.time_saida,
                        item.km,
                        item.defeito,
                        item.valor_total,
                        item.veiculo.placa,
                        item.veiculo.modelo.nome_modelo,
                        item.veiculo.proprietario.nome_pessoa
                    );
                };
            }

            acoesColuna.appendChild(editarBtn);

            // Botão Excluir
            const excluirBtn = document.createElement('button');
            excluirBtn.classList.add('btn', 'btn-danger', 'btn-sm');
            excluirBtn.textContent = 'Excluir';
            excluirBtn.onclick = function() {
                openModal('delete', item.id_manutencao);
            };
            acoesColuna.appendChild(excluirBtn);

            row.appendChild(acoesColuna);

            // Ajuste de estilos inline (opcional)
            excluirBtn.style.margin = "3px"; // Remove margens externas
            excluirBtn.style.display = "inline-block"; // Garante que o botão seja tratado como elemento inline

            // Coluna de Visualizar com o botão "Visualizar"
            const visualizarColuna = document.createElement('td');
            const visualizarBtn = document.createElement('button');
            visualizarBtn.classList.add('btn-visualizar');

            // Adiciona o ícone de visualização
            const icon = document.createElement('i');
            icon.classList.add('fa', 'fa-eye'); // Ícone de olho (visualização) do Font Awesome
            visualizarBtn.appendChild(icon);

            // Adiciona o texto "Visualizar" após o ícone

            if (faturamentoStatus !== "Faturada") {
                visualizarBtn.disabled = true;
                visualizarBtn.classList.add('botao-desabilitado');
            } else {
                // Define a ação de clique para redirecionar para outra página com o id de manutenção
                visualizarBtn.onclick = function() {
                    window.location.href = `../pages/relatorio-nota.php?id=${item.id_manutencao}`;
                };
            }
            // Adiciona o botão à coluna de Visualizar
            visualizarColuna.appendChild(visualizarBtn);

            // Adiciona a coluna "Visualizar" com o botão à linha da tabela
            row.appendChild(visualizarColuna);

            // Adiciona a linha completa à tabela
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
    id_manutencao,
    id_modelo,
    id_pessoa,
    id_pagamento,
    itens_manutencao = [],
    codigo = null,
    time_saida = null,
    km = null,
    defeito = null,
    valor_total = null,
    placa = null,
    modelo = null,
    pessoa = null,
    quantidade_itens = null,
    valor_uni_itens = null
) 
{
    // Consolida todos os itens de manutenção e novos produtos/serviços
    const hiddenItemListProduto = document.getElementById('hiddenItemListProdutoUpdate');
    const hiddenItemListServico = document.getElementById('hiddenItemListServicoUpdate');
    const novosProdutos = hiddenItemListProduto ? JSON.parse(hiddenItemListProduto.value || '[]') : [];
    const novosServicos = hiddenItemListServico ? JSON.parse(hiddenItemListServico.value || '[]') : [];
    const todosItens = [...itens_manutencao, ...novosProdutos, ...novosServicos];

    // Atualiza o campo oculto `todosItens` com o JSON dos itens
    const todosItensInput = document.getElementById('todosItens');
    if (todosItensInput) {
        todosItensInput.value = JSON.stringify(todosItens);
    }

    // Código restante para exibir o modal (como você já tinha)
    const modalAcao = new bootstrap.Modal(document.getElementById('modalAcao'));
    const tituloModal = document.getElementById('modalTituloAcao');
    const mensagemModalAcao = document.getElementById('mensagemModalAcao');
    const modalContent = document.getElementById('modalContent');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');


    console.log("Todos os Itens: ", todosItens);
    // Limpa conteúdo anterior do modal
    modalContent.innerHTML = '';
    confirmDeleteBtn.classList.add('d-none');

    if (acao === 'update') {
        tituloModal.textContent = 'Editar Ordem de Serviço';
        mensagemModalAcao.textContent = `Editando a ordem de serviço: ${codigo || ''}`;

        produtosServicos = itens_manutencao;
        // Conteúdo dinâmico para o formulário de edição
        ///////////////////////AQUI VOCE PODE MEXER////////////////////
        modalContent.innerHTML = `
            <form id="updateForm" action="../config/crud-nota/update.php" method="POST" enctype="multipart/form-data" onsubmit="prepararDados()">
    
            <!-- Campo oculto para ID do manutencao -->
            <input type="hidden" id="updateIdManutencao" name="idManutencao" value="${id_manutencao || ''}">

            <!-- Campo oculto para ID da modelo -->
            <input type="hidden" id="updateIdModelo" name="idModelo" value="${id_modelo || ''}">

            <!-- Campo oculto para ID da Pessoa -->
            <input type="hidden" id="updateIdPessoa" name="idPessoa" value="${id_pessoa || ''}">

            <!-- Campo oculto para ID da pagamento -->
            <input type="hidden" id="updateIdPagamento" name="idPagamento" value="${id_pagamento || ''}">

            <!-- Campo oculto para ID da Servico/Produto -->
            <input type="hidden" id="updateIdServicoProduto" name="idUpdateServicoProduto" value='${JSON.stringify(itens_manutencao)}'>
            
            <!-- Hora -->
            <div class="mb-3">
                <label for="updateHora" class="form-label">Codigo:</label>
                <input type="datetime" id="updateHora" name="hora" class="form-control" readonly value="${time_saida || ''}">
            </div>

            <!-- Codigo -->
            <div class="mb-3">
                <label for="updateCodigo" class="form-label">Codigo:</label>
                <input type="text" id="updateCodigo" name="codigo" class="form-control" readonly value="${codigo || ''}">
            </div>

            <!-- KM -->
            <div class="mb-3">
                <label for="updateKm" class="form-label">KM:</label>
                <input type="text" id="updateKm" name="km" class="form-control" value="${km || ''}">
            </div>
            
            <!-- Defeito -->
            <div class="mb-3">
                <label for="updateDefeito" class="form-label">Defeito</label>
                <input type="text" id="updateDefeito" name="defeito" class="form-control" value="${defeito || ''}">
            </div>
            
            <!-- Placa -->
            <div class="mb-3">
                <label for="updatePlaca" class="form-label">Placa</label>
                <input type="text" id="updatePlaca" name="placa" class="form-control" value="${placa || ''}">
            </div>

            <!-- Modelo -->
            <div class="mb-3">
                <label for="updateModelo" class="form-label">Modelo</label>
                <input type="text" id="updateModelo" name="modelo" class="form-control" value="${modelo || ''}">
            </div>
            
            <!-- Proprietario -->
            <div class="mb-3">
                <label for="updateProp" class="form-label">Proprietário</label>
                <input type="text" id="updateProp" name="proprietario" class="form-control" value="${pessoa || ''}">
            </div>
            
            <br>
            <h3 class="subtitulo-ordem">Produtos e Serviços</h3>
            <table id="tabelaEdicaoItens" class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Quantidade</th>
                        <th>Valor Unitário</th>
                        <th>Valor Total</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="tabelaEdicaoCorpo"></tbody>
            </table>



                <!-- Botão para abrir a lista de sugestões -->
                <button type="button" id="abrirListaBtn" onclick="abrirListaSugestoes()">Adicionar Produto/Serviço</button>

                <!-- Campo de busca e sugestões de produtos/serviços -->
                <div id="listaSugestoesContainer" style="display: none; margin-top: 10px;">

                    <div class="input-container">
                    <label for="tipo">Tipo:</label>
                    <select id="tipoEstoque">
                        <option value="1">Serviço</option>
                        <option value="2">Produto</option>
                    </select>

                    <input type="text" id="estoqueProdutoUpdate" name="produto" class="input" onkeyup="buscarEstoque()" placeholder="Buscar item do estoque"/>

                    <ul id="sugestoesUpdate" class="suggestions" ></ul>

                    <!-- Campo para quantidade -->
                    <input type="number" id="quantidadeProdutoUpdate" class="input" placeholder="Quantidade" />

                    <!-- Botão para adicionar item à lista -->
                    <button type="button" id="addItemBtnProdutoUpdate" onclick="adicionarItem()">Adicionar à lista</button>
                </div>

                <!-- Lista temporária de itens adicionados -->
                <ul id="itemListProduto" class="ul-temporaria"></ul>

                <!-- Campo oculto para enviar todos os itens de manutenção -->
                <input type="hidden" id="todosItens" name="todosItens">

                <!-- Input oculto para armazenar a lista de itens em JSON -->
                <input type="hidden" id="hiddenItemListProdutoUpdate" name="itemListProduto">

                <!-- Input oculto para armazenar a lista de itens em JSON -->
                <input type="hidden" id="hiddenItemListServicoUpdate" name="itemListServico">

                
                </div>
                
            <input type="number" id="inputValorTotal" readonly placeholder="Valor Total" name="valor_total" step="0.01">

            <!-- Botão Salvar -->
            <button type="submit" class="btn btn-success">Salvar</button>
        </form>

            
        `;

        // Atualiza a tabela com todos os itens
        atualizarGridProdutosServicos(todosItens);

        document.getElementById('estoqueProdutoUpdate').addEventListener('input', buscarEstoque);
        
        // Carrega produtos e serviços para o ID da manutenção especificado
        carregarProdutosServicos(id_manutencao);
        modalAcao.show();

    } else if (acao === 'delete') {
        tituloModal.textContent = 'Excluir Manutenção';
        mensagemModalAcao.textContent = `Tem certeza que deseja excluir a manutenção: ${codigo || ''}?`;
        console.log("Codigo da manutencao ",codigo);
        // Conteúdo dinâmico para o formulário de exclusão
        modalContent.innerHTML = `
        <form id="deleteForm" action="../config/crud-nota/delete.php" method="POST">
            <!-- Campo oculto para o ID -->
            <input type="hidden" id="deleteId" name="idManutencao" value="${id_manutencao || ''}">
            
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






















function carregarProdutosServicos(id_manutencao) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../config/crud-nota/grid-itens/getProdutosServicos.php?id=' + id_manutencao, true);
 // Backend PHP para buscar produtos e serviços com base no ID da manutenção
    xhr.onload = function () {
        if (xhr.status === 200) {
            const produtosServicos = JSON.parse(xhr.responseText);
            console.log("Servicos retornados: ", produtosServicos)
            atualizarGridProdutosServicos(produtosServicos, id_manutencao);
        }
    };
    xhr.send();
}

function atualizarGridProdutosServicos(todosProdutosServicos = [], id_manutencao) {
    const tbody = document.getElementById('tabelaEdicaoCorpo');
    tbody.innerHTML = ''; // Limpa a tabela antes de atualizar
    let somaTotal = 0; // Variável para armazenar a soma total de todos os itens

    console.log("Todos os Itens: ", todosProdutosServicos);
    todosProdutosServicos.forEach((item, index) => {
        const nome = item.nome || item.nome_servico_produto || 'Nome não informado';
        const descricao = item.descricao || '-';
        const quantidade = parseInt(item.quantidade) || 0;
        const valorUnitario = parseFloat(item.valor || item.valor_servico_produto || 0);
        const valorTotal = valorUnitario * quantidade;

        // Adiciona o valor total do item à soma total
        somaTotal += valorTotal;
        console.log(`Item ${index + 1}: Valor Unitário = ${valorUnitario}, Quantidade = ${quantidade}, Valor Total Item = ${valorTotal}, Soma Total Atual = ${somaTotal}`);

        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td>${nome}</td>
            <td>${descricao}</td>
            <td>${quantidade}</td>
            <td>R$ ${valorUnitario.toFixed(2)}</td>
            <td>R$ ${valorTotal.toFixed(2)}</td>
            <td>
                <button type="button" onclick="removerItemServicoProduto(${item.id_servico_produto || item.id}, ${id_manutencao})" class="btn btn-sm btn-danger">Remover</button>
            </td>
        `;
        tbody.appendChild(row);
    });

    // Atualiza o valor do campo de input com o valor total somado
    const inputValorTotal = document.getElementById('inputValorTotal');
    if (!inputValorTotal) {
        console.error("Elemento inputValorTotal não encontrado no DOM.");
    }
    if (inputValorTotal) {
        inputValorTotal.value = somaTotal.toFixed(2);
        console.log("Soma Total Final: ", somaTotal);
    }
}


function removerItemServicoProduto(itemId, idManutencao) {
    console.log("ID do item a ser removido:", itemId);
    console.log("ID da manutenção:", idManutencao);

    if (!itemId || !idManutencao) {
        console.error("Item ou manutenção não encontrados.");
        return;
    }

    // Configura os dados a serem enviados ao servidor
    const formData = new FormData();
    formData.append('itemId', itemId);
    formData.append('idManutencao', idManutencao);

    // Envia a requisição de remoção usando fetch
    fetch('../config/crud-nota/grid-itens/deleteItem.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // Interpreta a resposta como JSON
    .then(data => {
        if (data && data.success) {
            console.log("Item removido com sucesso!");

            // Exibir uma mensagem de sucesso para o usuário
            const mensagemResposta = document.getElementById('mensagemResposta');
            if (mensagemResposta) {
                mensagemResposta.textContent = "Item removido com sucesso!";
            }

            // Atualizar a grid carregando novamente os itens da manutenção
            carregarProdutosServicos(idManutencao);
        } else {
            console.error("Erro ao remover o item:", data);

            // Exibir uma mensagem de erro para o usuário
            const mensagemResposta = document.getElementById('mensagemResposta');
            if (mensagemResposta) {
                mensagemResposta.textContent = "Erro ao remover o item.";
            }
        }
    })
    .catch(error => {
        console.error("Erro:", error);

        // Exibir uma mensagem de erro de conexão para o usuário
        const mensagemResposta = document.getElementById('mensagemResposta');
        if (mensagemResposta) {
            mensagemResposta.textContent = "Erro de conexão ao tentar remover o item.";
        }
    });
}







// Função para abrir a lista de sugestões e os campos relevantes
function abrirListaSugestoes() {
    console.log("Função abrirLista Sugestoes chamada");
    const listaSugestoesContainer = document.getElementById('listaSugestoesContainer');
    listaSugestoesContainer.style.display = 'block';
}



function buscarEstoque() {
    console.log("Função buscarEstoque chamada.");

    // Definindo a variável `input` para capturar o elemento correto
    const input = document.getElementById('estoqueProdutoUpdate');
    if (!input) {
        console.error("Elemento 'estoqueProdutoUpdate' não encontrado.");
        return;
    }

    console.log("Elemento encontrado:", input); // Exibe o elemento input
    console.log("Valor do input:", input.value); // Exibe o valor do input no momento da chamada


    const sugestoes = document.getElementById('sugestoesUpdate');
    const query = input.value;
    const tipo = document.getElementById('tipoEstoque').value;

    console.log(input.value);
    console.log(query);
    console.log(sugestoes);
    
    if (query.length === 0) {
        console.log("Campo de busca está vazio.");
        sugestoes.innerHTML = '';
        sugestoes.style.display = 'none';
        return;
    }

    console.log("Buscando com o termo:", query, "e tipo:", tipo);

    // Requisição AJAX para buscar produtos ou serviços com base no tipo
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `../config/busca-estoque.php?query=${encodeURIComponent(query)}&tipo=${tipo}`, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            try {
                const resultados = JSON.parse(xhr.responseText);
                console.log("Resultados da busca:", resultados);

                sugestoes.innerHTML = ''; // Limpa sugestões anteriores

                if (resultados.length === 0) {
                    console.log("Nenhum resultado encontrado.");
                    sugestoes.style.display = 'none';
                } else {
                    resultados.forEach(item => {
                        const li = document.createElement('li');
                        li.textContent = `${item.nome_servico_produto} - Valor: ${item.valor_servico_produto}`;
                        li.onclick = function () {
                            console.log("Item selecionado:", item.nome_servico_produto);
                            input.value = item.nome_servico_produto;
                            input.dataset.valor = item.valor_servico_produto;
                            input.dataset.id = item.id_servico_produto;
                            input.dataset.descricao = item.descricao;
                            input.dataset.nome = item.nome_servico_produto;

                            // Verificação adicional
                            console.log("Dados do dataset após seleção:");
                            console.log("Nome: ", input.dataset.nome)
                            console.log("Valor:", input.dataset.valor);
                            console.log("Descrição:", input.dataset.descricao);

                            sugestoes.innerHTML = '';
                            sugestoes.style.display = 'none';

                            // Exibe o botão de adicionar à lista
                            const addItemBtn = document.getElementById('addItemBtnProdutoUpdate');
                            if (addItemBtn) {
                                addItemBtn.style.display = 'inline';
                                console.log("Botão de adicionar exibido."); // Verificação adicional
                                console.log("Estilo de display do botão:", addItemBtn.style.display);
                            } else {
                                console.error("Botão de adicionar à lista não encontrado.");
                            }
                        };
                        sugestoes.appendChild(li);
                    });
                    sugestoes.style.display = 'block';
                }
            } catch (e) {
                console.error("Erro ao analisar JSON:", e);
                console.error("Resposta do servidor:", xhr.responseText);
            }
        } else {
            console.error("Erro na requisição AJAX:", xhr.status);
        }
    };
    xhr.onerror = function () {
        console.error("Erro de conexão AJAX.");
    };
    xhr.send();
}



function adicionarItem() {
    const input = document.getElementById('estoqueProdutoUpdate');
    const quantidadeInput = document.getElementById('quantidadeProdutoUpdate');
    const tipoSelect = document.getElementById('tipoEstoque');

    const id = input.dataset.id;
    const nome = input.value;
    const quantidade = parseInt(quantidadeInput.value);
    const valor = parseFloat(input.dataset.valor);
    const tipo = parseInt(tipoSelect.value);
    const descricao = input.dataset.descricao || 'Descrição padrão';

    if (nome && quantidade > 0 && !isNaN(valor) && id) {
        const item = { id, nome, quantidade, valor, descricao };
        
        // Adiciona o item ao armazenamento oculto de produtos ou serviços
        if (tipo === 1) { // Serviço
            const hiddenInputServico = document.getElementById('hiddenItemListServicoUpdate');
            let listaAtualServico = hiddenInputServico.value ? JSON.parse(hiddenInputServico.value) : [];
            listaAtualServico.push(item);
            hiddenInputServico.value = JSON.stringify(listaAtualServico);
        } else if (tipo === 2) { // Produto
            const hiddenInputProduto = document.getElementById('hiddenItemListProdutoUpdate');
            let listaAtualProduto = hiddenInputProduto.value ? JSON.parse(hiddenInputProduto.value) : [];
            listaAtualProduto.push(item);
            hiddenInputProduto.value = JSON.stringify(listaAtualProduto);
        }

        // Combina todos os itens (produtos e serviços, existentes e novos) para atualizar a tabela
        const todosItens = [
            ...JSON.parse(document.getElementById('hiddenItemListProdutoUpdate').value || '[]'),
            ...JSON.parse(document.getElementById('hiddenItemListServicoUpdate').value || '[]'),
            ...produtosServicos // Inclui os itens existentes da manutenção carregados previamente
        ];

        document.getElementById('todosItens').value = JSON.stringify(todosItens);

        atualizarGridProdutosServicos(todosItens); // Atualiza a exibição da tabela com todos os itens

        // Limpa os campos de entrada
        input.value = '';
        quantidadeInput.value = '';
        tipoSelect.value = '';
        document.getElementById('listaSugestoesContainer').style.display = 'none';
    } else {
        console.error("Preencha todos os campos corretamente.");
    }
}









document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById("modalCadastro");
    const btnAbrirModal = document.getElementById("btnAbrirModalCadastro");
    const btnFecharModal = document.querySelector(".modal-close");

    if (btnAbrirModal) {
        console.log("Botao de abrir modal");    
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



// Função para abrir o modal e preencher o campo ID da manutenção
function abrirModalPagamento(idManutencao, valorManutencao) {
    // Exibe o modal
    const modal = document.getElementById("modalPagamento");
    modal.style.display = "block";

    // Define o ID da manutenção no input oculto
    const inputIdManutencao = document.getElementById("inputIdManutencao");
    inputIdManutencao.value = idManutencao;
    
    const inputValorManutencao = document.getElementById("valorPagamento");
    inputValorManutencao.value = valorManutencao;

    
    
}



// Função para fechar o modal
function fecharModalPagamento() {
    const modal = document.getElementById("modalPagamento");
    modal.style.display = "none";
}


function atualizarBotoesFaturamento(faturamento, idManutencao) {
    // Seleciona os botões de editar e adicionar pagamento para uma manutenção específica
    const botaoEditar = document.querySelector(`.btn-editar[data-id="${idManutencao}"]`);
    const botaoAdicionarPagamento = document.querySelector(`.btn-adicionar-pagamento[data-id="${idManutencao}"]`);

    if (faturamento) {
        // Se faturamento for true, desabilita os botões
        if (botaoEditar) botaoEditar.disabled = true;
        if (botaoAdicionarPagamento) botaoAdicionarPagamento.disabled = true;

        // Opcional: Adiciona uma classe para estilizar o botão como desabilitado
        if (botaoEditar) botaoEditar.classList.add('botao-desabilitado');
        if (botaoAdicionarPagamento) botaoAdicionarPagamento.classList.add('botao-desabilitado');
    } else {
        // Se faturamento for false, garante que os botões estejam habilitados
        if (botaoEditar) botaoEditar.disabled = false;
        if (botaoAdicionarPagamento) botaoAdicionarPagamento.disabled = false;

        // Remove a classe de estilo desabilitado se existir
        if (botaoEditar) botaoEditar.classList.remove('botao-desabilitado');
        if (botaoAdicionarPagamento) botaoAdicionarPagamento.classList.remove('botao-desabilitado');
    }
}

// Função para abrir o modal e carregar os pagamentos
function abrirModalGerenciamentoPagamento(idManutencao, codigo) {
    console.log("Abrindo modal para manutenção ID:", idManutencao);

    // Exibe o modal
    const modal = document.getElementById('modalGerenciamentoPagamento');
    if (!modal) {
        console.error("Modal não encontrado no DOM.");
        return;
    }

    // Atualiza o atributo data-id-manutencao
    modal.setAttribute('data-id-manutencao', idManutencao);
    modal.style.display = 'block';

    // Atualiza o título do modal
    const tituloModal = document.getElementById('tituloModalGerenciamentoPagamento');
    if (tituloModal) {
        tituloModal.textContent = `Código da manutenção: ${codigo}`;
    }

    // Atualiza os pagamentos no modal
    atualizarModalPagamentos(idManutencao);
}

// Função para fechar o modal
function fecharModalGerenciamentoPagamento() {
    const modal = document.getElementById('modalGerenciamentoPagamento');
    modal.style.display = 'none';
}

function atualizarModalPagamentos(idManutencao) {
    fetch(`../config/crud-pagamento/read.php?id_manutencao=${idManutencao}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erro na requisição: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Dados atualizados do backend:', data);

            if (data.success) {
                const tabelaPagamentos = document.getElementById('tabelaPagamentos');
                
                // Certifique-se de limpar apenas o corpo da tabela
                const tbody = tabelaPagamentos.querySelector('tbody');
                if (tbody) {
                    tbody.innerHTML = ''; // Limpa o corpo da tabela
                } else {
                    console.error("Elemento <tbody> não encontrado na tabela.");
                    return;
                }

                // Preenche a tabela com os dados atualizados
                data.pagamentos.forEach(pagamento => {
                    const row = document.createElement('tr');

                    // Verifica se o pagamento já foi realizado
                    const isPago = pagamento.situacao;

                    row.innerHTML = `
                        <td>${pagamento.codigo}</td>
                        <td>R$ ${parseFloat(pagamento.valor_parcela).toFixed(2)}</td>
                        <td>${pagamento.data_parcela}</td>
                        <td>${pagamento.num_parcela}</td>
                        <td>${isPago ? 'Concluído' : 'Pendente'}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" 
                                onclick="abrirModalEditarPagamento(${pagamento.id_pagamento}, '${pagamento.codigo}', '${pagamento.valor_parcela}', '${pagamento.data_parcela}', ${pagamento.num_parcela})"
                                ${isPago ? 'disabled' : ''}>
                                Editar
                            </button>
                        </td>
                        <td>
                            <form onsubmit="pagarPagamento(event, this)" action="../config/crud-pagamento/pagar.php">
                                <input type="hidden" name="id_pagamento" value="${pagamento.id_pagamento}">
                                <button type="submit" class="btn btn-success btn-sm"${isPago ? ' disabled' : ''}>
                                    ${isPago ? 'Pago' : 'Pagar'}
                                </button>
                            </form>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                alert('Erro ao buscar os pagamentos: ' + (data.message || 'Erro desconhecido.'));
            }
        })
        .catch(error => {
            console.error('Erro ao buscar os pagamentos:', error);
            alert('Erro ao buscar os pagamentos. Consulte o console para mais detalhes.');
        });
}


// Função para processar pagamento
function pagarPagamento(event, form) {
    event.preventDefault();

    const formData = new FormData(form);
    const url = form.action || '../config/crud-pagamento/pagar.php';

    fetch(url, {
        method: 'POST',
        body: formData,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Resposta do backend:', data);
        if (data.success) {
            alert('Pagamento realizado com sucesso!');

            // Atualiza o modal
            const modal = document.getElementById('modalGerenciamentoPagamento');
            const idManutencao = modal.getAttribute('data-id-manutencao'); // Obtém o ID da manutenção atual

            if (idManutencao) {
                atualizarModalPagamentos(idManutencao); // Recarrega os dados do modal
            }
        } else {
            alert('Erro ao processar pagamento: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro ao processar pagamento:', error);
        alert('Erro ao processar pagamento. Tente novamente.');
    });
}

// Função para abrir o modal de edição de pagamento
function abrirModalEditarPagamento(idPagamento, codigo, valorParcela, dataParcela, numParcela) {
    const modal = document.getElementById('modalEditarPagamento');
    modal.style.display = 'block';

    document.getElementById('editarIdPagamento').value = idPagamento;
    document.getElementById('editarCodigo').value = codigo;
    document.getElementById('editarValorParcela').value = valorParcela;
    document.getElementById('editarDataParcela').value = dataParcela;
    document.getElementById('editarNumParcela').value = numParcela;
}

// Função para fechar o modal de edição de pagamento
function fecharModalEditar() {
    const modal = document.getElementById('modalEditarPagamento');
    modal.style.display = 'none';
}






carregarItens(); // Chama a função para carregar dados ao iniciar a página

