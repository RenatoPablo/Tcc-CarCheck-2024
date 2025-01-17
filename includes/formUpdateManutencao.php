<form id="updateForm" action="../config/crud-nota/update.php" method="POST" enctype="multipart/form-data">
    
            <!-- Campo oculto para ID do manutencao -->
            <input type="hidden" id="updateIdManutencao" name="idManutencao" value="${id_manutencao || ''}">

            <!-- Campo oculto para ID da modelo -->
            <input type="hidden" id="updateIdModelo" name="idModelo" value="${id_modelo || ''}">

            <!-- Campo oculto para ID da Pessoa -->
            <input type="hidden" id="updateIdPessoa" name="idPessoa" value="${id_pessoa || ''}">

            <!-- Campo oculto para ID da pagamento -->
            <input type="hidden" id="updateIdPagamento" name="idPagamento" value="${id_pagamento || ''}">

            <!-- Campo oculto para ID da Servico/Produto -->
            <input type="hidden" id="updateIdServicoProduto" name="idServicoProduto" value="${fk_id_servico_produto || ''}">
            
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

                    <input type="text" id="estoqueProduto" name="produto" class="input" onkeyup="buscarEstoqueProduto()" placeholder="Buscar item do estoque" />

                    <ul id="sugestoesProduto" class="suggestions" style="display: none;"></ul>

                    <!-- Campo para quantidade -->
                    <input type="number" id="quantidadeProduto" class="input" placeholder="Quantidade" />

                    <!-- Botão para adicionar item à lista -->
                    <button type="button" id="addItemBtnProduto" onclick="adicionarItemProduto()">Adicionar à lista</button>
                </div>

                <!-- Lista temporária de itens adicionados -->
                <ul id="itemListProduto" class="ul-temporaria"></ul>

                <!-- Input oculto para armazenar a lista de itens em JSON -->
                <input type="hidden" id="hiddenItemListProduto" name="itemListProduto">

       
        <!-- Botão Salvar -->
        <button type="submit" class="btn btn-success">Salvar</button>
</form>