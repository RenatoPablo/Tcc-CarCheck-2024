INSERT INTO ruas(nome_rua)
VALUES
	('rua belgica'),
    ('rua alberto alvez de andrade'),
    ('rua mario camargo');
    
INSERT INTO estados(nome_estado)
VALUES
	('São Paulo');
    
INSERT INTO ufs(sigla, fk_id_estado)
VALUES 
	('SP', 1);
    
INSERT INTO complementos(desc_complemento)
VALUES
	('casa');
    
INSERT INTO pontos_referencias(desc_ponto_ref)
VALUES
	('n/a');

INSERT INTO numeros_casas(numero_casa)
VALUES
	(21),
    (353),
    (101);
    
INSERT INTO cidades(nome_cidade, fk_id_uf)
VALUES
	('Santa Fé do Sul', 1),
    ('Suzanápolis', 1);
    
INSERT INTO bairros(nome_bairro)
VALUES
	('Jardim Europa 3'),
    ('Centro'),
    ('Coronel Araujo');
    
INSERT INTO ceps(numero_cep, fk_id_cidade)
VALUES
	(15775000, 1),
    (15380000, 2);

INSERT INTO generos(sexo)
VALUES
	('Masculino'),
    ('Feminino'),
    ('Outro');

INSERT INTO permissoes(tipo_permissao)
VALUES
    ('cliente'),
    ('funcionario'),
    ('admin');

INSERT INTO pessoas_fisicas(cpf, rg)
VALUES
    ('452.449.738-27','58.000.000-5'),
    ('123.123.123.12','00.000.000-x');
    
    
INSERT INTO pessoas(nome_pessoa, numero_telefone, endereco_email, senha, data_nasc, fk_id_permissao, fk_id_cep, fk_id_rua, fk_id_genero, fk_id_numero_casa, fk_id_cidade, fk_id_bairro, fk_id_complemento, fk_id_ponto_ref)
VALUES
	('Renato Pablo Cuim Ferrarezi', '17997782137', 'renatocuim@gmail.com', '$2y$10$H4ZkVAC3yoXb7EGLxMkQGOeKFfGq.ALhIM4PMA9Ps6d6pIMH6BE.6', '2004-03-26', 3, 1, 1, 1, 1, 1, 1, 1, 1),
    ('Gabriela Batista Matos', '18997610162', 'gabi_batistamatos@hotmail.com', '$2y$10$H4ZkVAC3yoXb7EGLxMkQGOeKFfGq.ALhIM4PMA9Ps6d6pIMH6BE.6', '2003-01-26', 1, 2, 2, 2, 2, 2, 2, 1, 1),
    ('Joâo Pedro Gonçalves Pinheirinho ', '17997713350', 'trove.eu69@gmail.com', '$2y$10$H4ZkVAC3yoXb7EGLxMkQGOeKFfGq.ALhIM4PMA9Ps6d6pIMH6BE.6', '2002-10-07', 2, 1, 3, 3, 3, 1, 3, 1, 1);

INSERT INTO tipos_servicos_produtos(tipo_servico_produto) VALUES ('servico'), ('peca');

INSERT INTO pessoas_juridicas(cnpj, ie, razao_social, nome_fantasia, fk_id_pessoa) 
VALUES 
    ('02.125.254/0001-00','12.121.121', 'razao social', 'nome_fantasia', 1);

INSERT INTO pessoas_fisicas(cpf, rg, fk_id_pessoa)
VALUES 
    ('123.123.123-00', '211234561', 2),
    ('123.123.123-01', '211234000', 3);

