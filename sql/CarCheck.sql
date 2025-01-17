-- Criação de tabelas com manutenção de colunas de imagens como BLOB e melhorias estruturais

CREATE TABLE ruas (
    id_rua INT AUTO_INCREMENT PRIMARY KEY,
    nome_rua VARCHAR(200) NOT NULL
);

CREATE TABLE estados (
    id_estado INT AUTO_INCREMENT PRIMARY KEY,
    nome_estado VARCHAR(50) NOT NULL
);

CREATE TABLE ufs (
    id_uf INT AUTO_INCREMENT PRIMARY KEY,
    sigla CHAR(2) UNIQUE NOT NULL,
    fk_id_estado INT,
    FOREIGN KEY (fk_id_estado) REFERENCES estados(id_estado) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE cidades (
    id_cidade INT AUTO_INCREMENT PRIMARY KEY,
    nome_cidade VARCHAR(150) NOT NULL,
    fk_id_uf INT,
    FOREIGN KEY (fk_id_uf) REFERENCES ufs(id_uf) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE ceps (
    id_cep INT AUTO_INCREMENT PRIMARY KEY,
    numero_cep VARCHAR(20) NOT NULL,
    fk_id_cidade INT,
    FOREIGN KEY (fk_id_cidade) REFERENCES cidades(id_cidade) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE complementos (
    id_complemento INT AUTO_INCREMENT PRIMARY KEY,
    desc_complemento TEXT NOT NULL
);

CREATE TABLE pontos_referencias (
    id_ponto_ref INT AUTO_INCREMENT PRIMARY KEY,
    desc_ponto_ref TEXT NOT NULL
);

CREATE TABLE numeros_casas (
    id_numero_casa INT AUTO_INCREMENT PRIMARY KEY,
    numero_casa VARCHAR(10) NOT NULL
);

CREATE TABLE bairros (
    id_bairro INT AUTO_INCREMENT PRIMARY KEY,
    nome_bairro VARCHAR(100) NOT NULL
);

CREATE TABLE generos (
    id_genero INT AUTO_INCREMENT PRIMARY KEY,
    sexo VARCHAR(20) NOT NULL
);

CREATE TABLE permissoes (
    id_permissao INT AUTO_INCREMENT PRIMARY KEY,
    tipo_permissao VARCHAR(20) NOT NULL
);

-- Tabela pessoas com coluna foto como BLOB para armazenar imagens
CREATE TABLE pessoas (  
    id_pessoa INT AUTO_INCREMENT PRIMARY KEY,
    nome_pessoa VARCHAR(200) NOT NULL,
    data_nasc DATE,
    foto BLOB,  -- Coluna para armazenar fotos como BLOB
    numero_telefone VARCHAR(20) NOT NULL UNIQUE,
    endereco_email VARCHAR(200) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    token_login VARCHAR(255),
    fk_id_permissao INT,
    fk_id_cep INT,
    fk_id_rua INT,
    fk_id_genero INT,
    fk_id_numero_casa INT,
    fk_id_cidade INT,
    fk_id_bairro INT,
    fk_id_complemento INT,
    fk_id_ponto_ref INT,
    FOREIGN KEY (fk_id_permissao) REFERENCES permissoes(id_permissao) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (fk_id_complemento) REFERENCES complementos(id_complemento) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (fk_id_ponto_ref) REFERENCES pontos_referencias(id_ponto_ref) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (fk_id_cep) REFERENCES ceps(id_cep) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (fk_id_rua) REFERENCES ruas(id_rua) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (fk_id_genero) REFERENCES generos(id_genero) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (fk_id_numero_casa) REFERENCES numeros_casas(id_numero_casa) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (fk_id_cidade) REFERENCES cidades(id_cidade) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (fk_id_bairro) REFERENCES bairros(id_bairro) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE pessoas_fisicas (
    id_pessoa_fisi INT AUTO_INCREMENT PRIMARY KEY,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    rg VARCHAR(20) NOT NULL UNIQUE,
    fk_id_pessoa INT,
    FOREIGN KEY (fk_id_pessoa) REFERENCES pessoas(id_pessoa) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE pessoas_juridicas (
    id_pessoa_juri INT AUTO_INCREMENT PRIMARY KEY,
    cnpj VARCHAR(18) NOT NULL UNIQUE,
    ie VARCHAR(20) NOT NULL UNIQUE,
    razao_social VARCHAR(200),
    nome_fantasia VARCHAR(150),
    fk_id_pessoa INT,
    FOREIGN KEY (fk_id_pessoa) REFERENCES pessoas(id_pessoa) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE cargos (
    id_cargo INT AUTO_INCREMENT PRIMARY KEY,
    nome_cargo VARCHAR(100) NOT NULL
);

CREATE TABLE funcoes (
    id_funcao INT AUTO_INCREMENT PRIMARY KEY,
    nome_funcao VARCHAR(50) NOT NULL
);

CREATE TABLE funcionarios (
    id_funcionario INT AUTO_INCREMENT PRIMARY KEY,
    fk_id_cargo INT,
    fk_id_funcao INT,
    fk_id_pessoa INT,
    FOREIGN KEY (fk_id_cargo) REFERENCES cargos(id_cargo) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (fk_id_funcao) REFERENCES funcoes(id_funcao) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (fk_id_pessoa) REFERENCES pessoas(id_pessoa) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE cores (
    id_cor INT AUTO_INCREMENT PRIMARY KEY,
    nome_cor VARCHAR(15) NOT NULL
);

CREATE TABLE modelos (
    id_modelo INT AUTO_INCREMENT PRIMARY KEY,
    nome_modelo VARCHAR(30) NOT NULL
);

CREATE TABLE marcas (
    id_marca INT AUTO_INCREMENT PRIMARY KEY,
    nome_marca VARCHAR(30) NOT NULL
);

CREATE TABLE tipos_veiculos (
    id_tipo_veiculo INT AUTO_INCREMENT PRIMARY KEY,
    nome_tipo_veiculo VARCHAR(20) NOT NULL
);

CREATE TABLE veiculos (
    id_veiculo INT AUTO_INCREMENT PRIMARY KEY,
    fk_id_pessoa INT,
    placa VARCHAR(8) NOT NULL UNIQUE,
    fk_id_cor INT,
    fk_id_tipo_veiculo INT,
    fk_id_modelo INT,
    fk_id_marca INT,
    FOREIGN KEY (fk_id_pessoa) REFERENCES pessoas(id_pessoa) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (fk_id_cor) REFERENCES cores(id_cor) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (fk_id_tipo_veiculo) REFERENCES tipos_veiculos(id_tipo_veiculo) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (fk_id_modelo) REFERENCES modelos(id_modelo) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (fk_id_marca) REFERENCES marcas(id_marca) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE formas_pagamento (
    id_forma_pagamento INT AUTO_INCREMENT PRIMARY KEY,
    tipo_pagamento VARCHAR(50) NOT NULL
);

CREATE TABLE manutencoes (
    id_manutencao INT AUTO_INCREMENT PRIMARY KEY,
    codigo varchar(20) NOT NULL,
    time_saida TIMESTAMP NULL,
    km VARCHAR(10) NOT NULL,
    defeito VARCHAR(500),
    valor_total DECIMAL(10,2),
    faturamento BOOLEAN,
    fk_id_veiculo INT NOT NULL,
    FOREIGN KEY (fk_id_veiculo) REFERENCES veiculos(id_veiculo) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE pagamentos (
    id_pagamento INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL,
    valor_parcela DECIMAL(10,2) NOT NULL,
    data_parcela DATE NOT NULL,
    valor_pago DECIMAL(10,2) NOT NULL,
    data_pago DATE NOT NULL,
    situacao BOOLEAN NOT NULL,
    num_parcela INT NOT NULL,
    fk_id_forma_pagamento INT,
    fk_id_manutencao INT,
    FOREIGN KEY (fk_id_manutencao) REFERENCES manutencoes(id_manutencao) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (fk_id_forma_pagamento) REFERENCES formas_pagamento(id_forma_pagamento) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE marcas_servicos_produtos (
    id_marca_produto INT AUTO_INCREMENT PRIMARY KEY,
    nome_marca_produto VARCHAR(30) NOT NULL
);

CREATE TABLE tipos_servicos_produtos (
    id_tipo_servico_produto INT AUTO_INCREMENT PRIMARY KEY,
    tipo_servico_produto VARCHAR(50) NOT NULL
);

CREATE TABLE servicos_produtos (
    id_servico_produto INT AUTO_INCREMENT PRIMARY KEY,
    nome_servico_produto VARCHAR(150) NOT NULL,
    descricao TEXT,
    valor_servico_produto DECIMAL(10,2) NOT NULL,
    quantidade INT,
    fk_id_marca_produto INT,
    fk_id_tipo_servico INT,
    FOREIGN KEY (fk_id_marca_produto) REFERENCES marcas_servicos_produtos(id_marca_produto) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (fk_id_tipo_servico) REFERENCES tipos_servicos_produtos(id_tipo_servico_produto) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE itens_manutencoes_servicos (
    fk_id_manutencao INT,
    fk_id_servico_produto INT,
    quantidade INT,
    valor_uni DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (fk_id_servico_produto) REFERENCES servicos_produtos(id_servico_produto) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (fk_id_manutencao) REFERENCES manutencoes(id_manutencao) ON UPDATE CASCADE ON DELETE CASCADE
);