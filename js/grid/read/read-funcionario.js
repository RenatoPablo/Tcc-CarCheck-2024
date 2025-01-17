function carregarItens() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../config/crud-funcionario/read.php', true); // URL para carregar dados dos clientes
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

            // Coluna de Nome
            const nomeColuna = document.createElement('td');
            nomeColuna.textContent = item.nome_pessoa;
            row.appendChild(nomeColuna);

            // Coluna de Email
            const emailColuna = document.createElement('td');
            emailColuna.textContent = item.endereco_email;
            row.appendChild(emailColuna);

            // Coluna de Telefone
            const telefoneColuna = document.createElement('td');
            telefoneColuna.textContent = item.numero_telefone;
            row.appendChild(telefoneColuna);

            // Coluna de Cargo
            const cargoColuna = document.createElement('td');
            cargoColuna.textContent = item.nome_cargo;
            row.appendChild(cargoColuna);

            // Coluna de Funcionario
            const funcaoColuna = document.createElement('td');
            funcaoColuna.textContent = item.nome_funcao;
            row.appendChild(funcaoColuna);

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
                    item.id_funcionario,
                    item.id_cargo,
                    item.id_funcao,
                    item.id_pessoa,
                    item.id_bairro,
                    item.id_cep,
                    item.id_cidade,
                    item.id_estado,
                    item.id_uf,
                    item.id_complemento,
                    item.id_numero_casa,
                    item.id_ponto_ref,
                    item.id_rua,
                    item.nome_cargo,
                    item.nome_funcao,
                    item.nome_pessoa,
                    item.endereco_email,
                    item.numero_telefone,
                    item.cpf,
                    item.rg,
                    item.cnpj,
                    item.ie,
                    item.razao_social,
                    item.nome_fantasia,
                    item.genero,
                    item.nome_rua,
                    item.numero_casa,
                    item.nome_bairro,
                    item.nome_cidade,    // Cidade
                    item.uf,             // UF
                    item.nome_estado,    // Estado
                    item.numero_cep,
                    item.desc_complemento,
                    item.desc_ponto_ref,
                    item.foto,           // Foto
                    item.data_nasc // Data de nascimento
                );
                
            };
            
            acoesColuna.appendChild(editarBtn);

            // Botão Excluir
            const excluirBtn = document.createElement('button');
            excluirBtn.classList.add('btn', 'btn-danger', 'btn-sm');
            excluirBtn.textContent = 'Excluir';
            excluirBtn.onclick = function () {
                openModal('delete', item.id_funcionario, item.nome_pessoa);
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
    id_funcionario,
    id_cargo,
    id_funcao,
    id, 
    id_bairro, 
    id_cep, 
    id_cidade, 
    id_estado,
    id_uf,
    id_complemento, 
    id_numero_casa, 
    id_ponto_ref, 
    id_rua, 
    nome_cargo = null,
    nome_funcao = null,
    nome = null, 
    email = null, 
    telefone = null, 
    cpf = null, 
    rg = null, 
    cnpj = null, 
    ie = null, 
    razao_social = null, 
    nome_fantasia = null, 
    genero = null, 
    rua = null, 
    numero_casa = null, 
    bairro = null, 
    cidade = null,     
    uf = null,        
    estado = null,    
    cep = null, 
    complemento = null, 
    ponto_referencia = null,
    foto = null,        
    data_nascimento = null 
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
        tituloModal.textContent = 'Editar Funcionário';
        mensagemModalAcao.textContent = `Editando o funcionário: ${nome || ''}`;

        // Conteúdo dinâmico para o formulário de edição
        ///////////////////////AQUI VOCE PODE MEXER////////////////////
        modalContent.innerHTML = `
            <form id="updateForm" action="../config/crud-funcionario/update.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="updateIdFuncionario" name="idFuncionario" value="${id_funcionario || ''}">
                <input type="hidden" id="updateIdCargo" name="idCargo" value="${id_cargo || ''}">
                <input type="hidden" id="updateIdFuncao" name="idFuncao" value="${id_funcao || ''}">
                <input type="hidden" id="updateId" name="id" value="${id || ''}">
                <input type="hidden" id="updateIdBairro" name="idBairro" value="${id_bairro || ''}">
                <input type="hidden" id="updateIdCep" name="idCep" value="${id_cep || ''}">
                <input type="hidden" id="updateIdCidade" name="idCidade" value="${id_cidade || ''}">
                <input type="hidden" id="updateIdEstado" name="idEstado" value="${id_estado || ''}">
                <input type="hidden" id="updateIdUf" name="idUf" value="${id_uf || ''}">
                <input type="hidden" id="updateIdComplemento" name="idComplemento" value="${id_complemento || ''}">
                <input type="hidden" id="updateIdNumeroCasa" name="idNumeroCasa" value="${id_numero_casa || ''}">
                <input type="hidden" id="updateIdPontoRef" name="idPontoRef" value="${id_ponto_ref || ''}">
                <input type="hidden" id="updateIdRua" name="idRua" value="${id_rua || ''}">

                <!-- Foto da pessoa -->
                ${foto ? `<img src="${foto}" alt="Foto de ${nome}" class="img-thumbnail mb-3" id="fotoPreview" style="width: 100px; height: 100px;">` : '<p id="fotoPreviewText">Sem foto disponível</p>'}
                
                <!-- Campo para seleção da foto -->
                <input type="file" id="updateFoto" name="foto" class="form-control mb-3" accept="image/*" onchange="previewFoto(event)">

                <!-- Data de nascimento -->
                <div class="input-group">
                <label for="updateFoto" class="custom-file-upload">Selecionar Foto</label>
                <label for="updateDataNascimento">Data de Nascimento</label>
                <input type="date" id="updateDataNascimento" class="input" name="dataNascimento" class="form-control">
                </div>

                <!-- Outros campos -->
                <div class="input-group">
                <label for="updateNome">Nome</label>
                <input type="text" id="updateNome" name="nome" class="form-control" value="${nome || ''}">
                </div>

                <div class="input-group">
                <label for="updateEmail">Email</label>
                <input type="email" id="updateEmail" name="email" class="form-control" value="${email || ''}">
                </div>

                <div class="input-group">
                <label for="updateTelefone">Telefone</label>
                <input type="text" id="updateTelefone" name="telefone" class="form-control" value="${telefone || ''}">
                </div>

                ${cpf ? `
                    <div class="input-group">
                    <label for="updateCPF">CPF</label>
                    <input type="text" id="updateCPF" name="cpf" class="form-control" value="${cpf || ''}">
                    </div>    

                    <div class="input-group">
                    <label for="updateRG">RG</label>
                    <input type="text" id="updateRG" name="rg" class="form-control" value="${rg || ''}">
                    </div>

                ` : ''}

                ${cnpj ? `
                    <div class="input-group">
                    <label for="updateCNPJ">CNPJ</label>
                    <input type="text" id="updateCNPJ" name="cnpj" class="form-control" value="${cnpj || ''}">
                    </div>

                    <div class="input-group">
                    <label for="updateIE">Inscrição Estadual (IE)</label>
                    <input type="text" id="updateIE" name="ie" class="form-control" value="${ie || ''}">
                    </div>

                    <div class="input-group">
                    <label for="updateRazaoSocial">Razão Social</label>
                    <input type="text" id="updateRazaoSocial" name="razao" class="form-control" value="${razao_social || ''}">
                    </div>
                    
                    <div class="input-group">
                    <label for="updateNomeFantasia">Nome Fantasia</label>
                    <input type="text" id="updateNomeFantasia" name="fantasia" class="form-control" value="${nome_fantasia || ''}">
                    </div>

                ` : ''}

                <div class="input-group">
                <label for="updateCargo">Cargo</label>
                <input type="text" id="updateCargo" name="cargo" class="form-control" value="${nome_cargo || ''}">
                </div>

                <div class="input-group">
                <label for="updateFuncao">Função</label>
                <input type="text" id="updateFuncao" name="funcao" class="form-control" value="${nome_funcao || ''}">
                </div>

                <div class="input-group">
                <label for="updateRua">Rua</label>
                <input type="text" id="updateRua" name="rua" class="form-control" value="${rua || ''}">
                </div>

                <div class="input-group">
                <label for="updateNumeroCasa">Número da Casa</label>
                <input type="text" id="updateNumeroCasa" name="numero" class="form-control" value="${numero_casa || ''}">
                </div>

                <div class="input-group">
                <label for="updateBairro">Bairro</label>
                <input type="text" id="updateBairro" name="bairro" class="form-control" value="${bairro || ''}">
                </div>

                <div class="input-group">
                <label for="updateCidade">Cidade</label>
                <input type="text" id="updateCidade" name="cidade" class="form-control" value="${cidade || ''}">
                </div>

                <div class="input-group">
                <label for="updateEstado">Estado</label>
                <input type="text" id="updateEstado" name="estado" class="form-control" value="${estado || ''}">
                </div>

                <div class="input-group">
                <label for="updateUf">UF</label>
                <input type="text" id="updateUf" name="uf" class="form-control" value="${uf || ''}">
                </div>

                <div class="input-group">
                <label for="updateCEP">CEP</label>
                <input type="text" id="updateCEP" name="cep" class="form-control" value="${cep || ''}">
                </div>

                <div class="input-group">
                <label for="updateComplemento">Complemento</label>
                <input type="text" id="updateComplemento" name="complemento" class="form-control" value="${complemento || ''}">
                </div>

                <div class="input-group">
                <label for="updatePontoReferencia">Ponto de Referência</label>
                <input type="text" id="updatePontoReferencia" name="referencia" class="form-control" value="${ponto_referencia || ''}">
                </div>
                
                <button type="submit" class="btn btn-success">Salvar</button>
            </form>
        `;

        console.log("Numero Casa ", numero_casa);
        console.log("Nome Rua ", rua);
        console.log("Nome Bairro ", bairro);
        console.log("CEP ", cep);
        // Adiciona o evento que define o valor do campo data de nascimento após o modal ser mostrado
        const modalElement = document.getElementById('modalAcao');
        modalElement.addEventListener('shown.bs.modal', function () {
            document.getElementById('updateDataNascimento').value = data_nascimento || '';
        }, { once: true }); // Usa { once: true } para garantir que o evento só execute uma vez

    } else if (acao === 'delete') {
        tituloModal.textContent = 'Excluir Funcionário';
        mensagemModalAcao.textContent = `Tem certeza que deseja excluir o funcionário: ${nome || ''}?`;

        // Conteúdo dinâmico para o formulário de exclusão
        modalContent.innerHTML = `
        <form id="deleteForm" action="../config/crud-funcionario/delete.php" method="POST">
            <!-- Campo oculto para o ID -->
            <input type="hidden" id="deleteId" name="idFuncionario" value="${id_funcionario || ''}">
            
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
