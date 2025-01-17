// Função que torna os inputs readonly
function tornarProdutoReadOnly() {
    document.getElementById('nomeProduto').readOnly = true;
    document.getElementById('descrProduto').readOnly = true;
    document.getElementById('valorProduto').readOnly = true;
    document.getElementById('marcaProduto').readOnly = true;
}

// Função que torna os inputs readonly
function tornarServicoReadOnly() {
    document.getElementById('nomeServico').readOnly = true;
    document.getElementById('descrServico').readOnly = true;
    document.getElementById('valorServico').readOnly = true;
    document.getElementById('marcaServico').readOnly = true;
}

// Função que remove o readonly e permite edição
function removerProdutoReadOnly() {
    document.getElementById('nomeProduto').readOnly = false;
    document.getElementById('descrProduto').readOnly = false;
    document.getElementById('valorProduto').readOnly = false;
    document.getElementById('marcaProduto').readOnly = false;
}

// Função que remove o readonly e permite edição
function removerServicoReadOnly() {
    document.getElementById('nomeServico').readOnly = false;
    document.getElementById('descrServico').readOnly = false;
    document.getElementById('valorServico').readOnly = false;
    document.getElementById('marcaServico').readOnly = false;
}