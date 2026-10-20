-- =========================================================
-- DIÁRIO DO CAÇADOR - DADOS ORIGINAIS DO RPG
-- =========================================================

USE diario_cacador;

SET NAMES utf8mb4;

START TRANSACTION;

-- =========================================================
-- USUARIOS INICIAIS
-- =========================================================

INSERT INTO usuario
    (nome, email, senha, ativo)
VALUES
    ('Diário do Caçador',
     'diario@cacador.com',
     '$2y$12$ZIzN8KSLLYIAfIU48BdueeaW2NhsxLzs6tnVSfyTNu.tfxQZZXuPa',
     TRUE),
    ('Teste',
     'teste@mail.com',
     '$2y$12$ihwssn0UV28oEucnuHxsQOYl8qFShU/NCylPREy8BFVr8sds4eKB6',
     TRUE),
    ('Jogador',
     'jogador@mail.com',
     '$2y$12$w.RWbslfyCCFentGDd/PDOyYroZmotoZV79/k71zil/4LHoanV5yW',
     TRUE);

SET @id_autor_original = (
    SELECT id_usuario
    FROM usuario
    WHERE email = 'diario@cacador.com'
    LIMIT 1
);

-- =========================================================
-- MAGIAS
-- =========================================================

INSERT INTO magia
    (id_magia, nome, elemento, descricao, id_autor)
VALUES
    (1, 'Bola de Fogo', 'Fogo',
     'Ataque a distância que causa 7 de dano de Fogo.',
     @id_autor_original),
    (2, 'Rajada de Gelo', 'Gelo',
     'Ataque a distância que causa 5 de dano de Gelo e paralisa a parte atingida por um turno.',
     @id_autor_original),
    (3, 'Relâmpago', 'Eletricidade',
     'Ataque a distância que causa 6 de dano de Eletricidade. O próximo ataque da Besta será o mesmo da rodada anterior.',
     @id_autor_original),
    (4, 'Jato de Ácido', 'Ácido',
     'Ataque a distância que causa 4 de dano de Ácido e mais 4 de dano de Ácido no próximo turno.',
     @id_autor_original),
    (5, 'Cura', 'Normal',
     'Recupera 1d6 pontos de vida.',
     @id_autor_original),
    (6, 'Campo de Força', 'Qualquer',
     'Até o final do combate, permite Bloquear ataques como se estivesse usando um escudo. Não ocupa a mão depois de conjurada.',
     @id_autor_original);

-- =========================================================
-- COMPANHEIROS
-- =========================================================

INSERT INTO companheiro_animal
    (id_companheiro, tipo, pv_maximo, dano, descricao, id_autor)
VALUES
    (1, 'Agressivo', 10, '2',
     'Animal de estimação agressivo do Eremita.',
     @id_autor_original),
    (2, 'Voador', 8, '1',
     'Voa e nunca é alvo direto.',
     @id_autor_original),
    (3, 'Rastreador', 10, '1',
     'Permite rerrolar uma vez quando rastrear.',
     @id_autor_original),
    (4, 'Cavalo leal', 15, NULL,
     'Enquanto estiver montado, o Caçador recebe +1 nas rolagens de Esquiva.',
     @id_autor_original);

-- =========================================================
-- VANTAGENS
-- =========================================================

INSERT INTO vantagem
    (id_vantagem, nome, descricao, id_autor)
VALUES
    (1, 'Companheiro',
     'Você possui um animal de estimação. Escolha entre Agressivo, Voador ou Rastreador.',
     @id_autor_original),
    (2, 'Furtividade',
     'Sempre que tentar se esconder da Besta, some 1 ao resultado do Esconderijo.',
     @id_autor_original),
    (3, 'Alquimia',
     'Ao fazer poções usando Ingredientes Alternativos, precisa somente da metade dos ingredientes necessários.',
     @id_autor_original),
    (4, 'Anatomia',
     'Ao dissecar a carcaça da Besta, somente falha ao obter resultado 1 no dado para extrair as partes.',
     @id_autor_original),
    (5, 'Ferragem',
     'Permite gastar metade dos Minérios, arredondando para cima, para produzir equipamentos básicos.',
     @id_autor_original),
    (6, 'Força',
     'Ao escalar a Besta, adicione 1 ao resultado da rolagem de Escalada.',
     @id_autor_original),
    (7, 'Montaria',
     'Você possui um cavalo leal com 15 PV. Enquanto estiver montado, recebe +1 nas rolagens de Esquiva.',
     @id_autor_original),
    (8, 'Status',
     'Quando estiver buscando informações na cidade, pode rolar 6 dados em vez de 3.',
     @id_autor_original),
    (9, 'Incivilizado',
     'Você não é bem visto entre os habitantes das cidades e rola apenas 2 dados na etapa de Investigação.',
     @id_autor_original),
    (10, 'Visão Arcana',
     'Ao encontrar a Besta, identifica imediatamente a Fraqueza dela.',
     @id_autor_original),
    (11, 'Magia',
     'Escolha uma magia. Para conjurar, use uma das mãos e consuma 1 de Fadiga.',
     @id_autor_original);

-- Relacoes das vantagens especiais.
INSERT INTO vantagem_companheiro
    (id_vantagem, id_companheiro)
VALUES
    (1, 1),
    (1, 2),
    (1, 3),
    (7, 4);

INSERT INTO vantagem_magia
    (id_vantagem, id_magia)
VALUES
    (11, 1),
    (11, 2),
    (11, 3),
    (11, 4),
    (11, 5),
    (11, 6);

-- =========================================================
-- ARMAS BASICAS E ARMAS FORJAVEIS
-- =========================================================

INSERT INTO arma
    (id_arma, nome, tipo, maos, dano, especial, id_autor)
VALUES
    (1, 'Adaga', 'CORPORAL', 1, '2',
     'Arremesso. Custo: 3 Minérios.', @id_autor_original),
    (2, 'Sabre', 'CORPORAL', 1, '3',
     'Custo: 4 Minérios.', @id_autor_original),
    (3, 'Espada Simples', 'CORPORAL', 1, '3',
     'Custo: 4 Minérios.', @id_autor_original),
    (4, 'Lança de Guerra', 'CORPORAL', 1, '3',
     'Arremesso. Custo: 5 Minérios.', @id_autor_original),
    (5, 'Maça', 'CORPORAL', 1, '3',
     'Pesado. Custo: 6 Minérios.', @id_autor_original),
    (6, 'Espada Longa', 'CORPORAL', 1, '3',
     'Torque. Custo: 6 Minérios.', @id_autor_original),
    (7, 'Lança de Cavalaria', 'CORPORAL', 1, '5',
     'Longo. Necessita estar montado. Custo: 7 Minérios.', @id_autor_original),
    (8, 'Machado', 'CORPORAL', 2, '6',
     'Pesado. Custo: 9 Minérios.', @id_autor_original),
    (9, 'Marreta', 'CORPORAL', 2, '6',
     'Pesado. Custo: 9 Minérios.', @id_autor_original),
    (10, 'Alabarda', 'CORPORAL', 2, '7',
     'Custo: 12 Minérios.', @id_autor_original),
    (11, 'Arco Simples', 'DISTANCIA', 2, '2',
     'Flechas. Custo: 2 Minérios.', @id_autor_original),
    (12, 'Arco Composto', 'DISTANCIA', 2, '3',
     'Flechas. Custo: 7 Minérios.', @id_autor_original),
    (13, 'Arco de Guerra', 'DISTANCIA', 2, '3',
     'Flechas. Equipamento inicial do Eremita.', @id_autor_original),

    (14, 'Foice de Rexian', 'CORPORAL', 2, '3 + 3 [Elemento]',
     'Parte necessária: Cauda.', @id_autor_original),
    (15, 'Maça de Albivian', 'CORPORAL', 1, '5',
     'Pesado. Parte necessária: Cauda.', @id_autor_original),
    (16, 'Arco de Crokion', 'DISTANCIA', 2, '2 + 1 [Elemento]',
     'Flechas. Parte necessária: Cabeça ou Cauda.', @id_autor_original),
    (17, 'Espada de Crokion', 'CORPORAL', 1, '2 + 1 [Elemento]',
     'Parte necessária: Tronco ou Patas.', @id_autor_original),
    (18, 'Malho de Ermiloth', 'CORPORAL', 2, '7',
     'Pesado. Parte necessária: Pinça.', @id_autor_original),
    (19, 'Lança de Boviatan', 'CORPORAL', 1, '2 + 3 [Elemento]',
     'Arremesso. Parte necessária: Cabeça.', @id_autor_original),
    (20, 'Machado de Claker', 'CORPORAL', 2, '3 + 4 [Elemento]',
     'Pesado. Parte necessária: Cabeça.', @id_autor_original),
    (21, 'Lança de Gaiark', 'CORPORAL', 1, '2 + 2 [Elemento]',
     'Arremesso. Parte necessária: qualquer parte.', @id_autor_original),
    (22, 'Espada de Gaiark', 'CORPORAL', 1, '3 + 2 [Elemento]',
     'Torque. Parte necessária: Cabeça.', @id_autor_original),
    (23, 'Arco de Uborosso', 'DISTANCIA', 2, '2 + 3 [Elemento]',
     'Munição. Parte necessária: Patas ou Cabeça.', @id_autor_original),
    (24, 'Martelo de Uborosso', 'CORPORAL', 1, '4 + 4 [Elemento]',
     'Pesado. Parte necessária: Cauda.', @id_autor_original),
    (25, 'Machado de Gorgantis', 'CORPORAL', 2, '4 + 5 [Elemento]',
     'Pesado. Parte necessária: Patas.', @id_autor_original),
    (26, 'Lança de Euplosor', 'CORPORAL', 1, '4 + 4 [Elemento]',
     'Longo. Parte necessária: Tronco ou Cauda.', @id_autor_original),
    (27, 'Arco de Raidormum', 'DISTANCIA', 2, '4 [Elemento]',
     'Parte necessária: Cauda.', @id_autor_original),
    (28, 'Chicote de Raidormum', 'CORPORAL', 1, '8 [Elemento]',
     'Parte necessária: Tentáculos.', @id_autor_original),
    (29, 'Alabarda de Argoth', 'CORPORAL', 2, '6 + 6 [Elemento]',
     'Parte necessária: Cauda.', @id_autor_original),
    (30, 'Lança de Renotaur', 'CORPORAL', 1, '6 + 4 [Elemento]',
     'Longo. Parte necessária: Cabeça.', @id_autor_original),
    (31, 'Arco de Kandra', 'DISTANCIA', 2, '5',
     'Parte necessária: Cauda.', @id_autor_original),
    (32, 'Marreta de Kingon', 'CORPORAL', 2, '10 + 5 [Elemento]',
     'Pesado. Parte necessária: Braços.', @id_autor_original),
    (33, 'Montante de Mathulu', 'CORPORAL', 2, '10 + 10 [Elemento]',
     'Parte necessária: Presas.', @id_autor_original),
    (34, 'Espada de Fenkeen', 'CORPORAL', 1, '8 + 10 [Elemento]',
     'Parte necessária: Cabeça.', @id_autor_original),
    (35, 'Machado de Goshin', 'CORPORAL', 2, '20 + 10 [Elemento]',
     'Pesado. Parte necessária: Tronco ou Cauda.', @id_autor_original);

-- =========================================================
-- VESTIMENTAS BASICAS E VESTIMENTAS FORJAVEIS
-- =========================================================

INSERT INTO vestimenta
    (id_vestimenta, nome, tipo, pontos_protecao_maximo,
     dano, elemento, especial, id_autor)
VALUES
    (1, 'Escudo', 'ESCUDO', 1, NULL, NULL,
     'Pode Bloquear ataques. PP técnico: o livro não informa PP para este equipamento. Custo: 4 Minérios.',
     @id_autor_original),
    (2, 'Manto de Pele', 'ARMADURA', 4, NULL, 'Gelo',
     'Custo: 6 de Couro.', @id_autor_original),
    (3, 'Cota de Malha', 'ARMADURA', 10, NULL, NULL,
     'Custo: 10 Minérios.', @id_autor_original),
    (4, 'Armadura de Couro', 'ARMADURA', 8, NULL, NULL,
     'Equipamento inicial do Eremita.', @id_autor_original),

    (5, 'Elmo de Rexian', 'ELMO', 5, NULL, 'Elemento da Besta',
     'Parte necessária: Patas ou Cabeça.', @id_autor_original),
    (6, 'Braceletes de Rexian', 'BRACELETES', 2, NULL, 'Elemento da Besta',
     'Parte necessária: Tronco.', @id_autor_original),
    (7, 'Armadura de Rexian', 'ARMADURA', 10, NULL, 'Elemento da Besta',
     'Partes necessárias: Cabeça e Tronco.', @id_autor_original),

    (8, 'Botas de Albivian', 'BOTAS', 1, NULL, NULL,
     'Receba metade do dano ao cair no chão. PP técnico: o livro não informa PP. Parte necessária: Asas.',
     @id_autor_original),
    (9, 'Elmo de Albivian', 'ELMO', 5, NULL, 'Elemento da Besta',
     'Parte necessária: Cabeça.', @id_autor_original),
    (10, 'Armadura de Albivian', 'ARMADURA', 11, NULL, 'Elemento da Besta',
     'Partes necessárias: Cabeça e Tronco.', @id_autor_original),

    (11, 'Bracelete de Crokion', 'BRACELETES', 1, NULL, NULL,
     'Cause +1 de dano ao usar Ataque Poderoso. PP técnico: o livro não informa PP. Parte necessária: Cabeça.',
     @id_autor_original),
    (12, 'Botas de Crokion', 'BOTAS', 2, NULL, 'Elemento da Besta',
     'Parte necessária: Patas.', @id_autor_original),
    (13, 'Armadura de Crokion', 'ARMADURA', 12, NULL, 'Elemento da Besta',
     'Partes necessárias: Cabeça e Cauda.', @id_autor_original),

    (14, 'Escudo de Ermiloth', 'ESCUDO', 1, NULL, NULL,
     'Se for atacado pelo Elemento da Besta, recupere 2 PV. PP técnico: o livro não informa PP. Parte necessária: Tronco ou Pinça.',
     @id_autor_original),
    (15, 'Elmo de Ermiloth', 'ELMO', 6, NULL, NULL,
     'Parte necessária: Pinça.', @id_autor_original),
    (16, 'Bracelete de Ermiloth', 'BRACELETES', 3, NULL, NULL,
     'Parte necessária: Pinça.', @id_autor_original),
    (17, 'Armadura de Ermiloth', 'ARMADURA', 11, NULL, 'Elemento da Besta',
     'Partes necessárias: Tronco e Patas.', @id_autor_original),

    (18, 'Braceletes de Boviatan', 'BRACELETES', 1, NULL, NULL,
     'Ganhe +1 nas rolagens para se esconder. PP técnico: o livro não informa PP. Parte necessária: Tronco.',
     @id_autor_original),
    (19, 'Botas de Boviatan', 'BOTAS', 5, NULL, 'Elemento da Besta',
     'Parte necessária: Patas Frontais ou Patas Traseiras.', @id_autor_original),
    (20, 'Armadura de Boviatan', 'ARMADURA', 12, NULL, 'Elemento da Besta',
     'Partes necessárias: Cabeça e Patas Frontais.', @id_autor_original),

    (21, 'Escudo de Claker', 'ESCUDO', 1, NULL, NULL,
     'Adiciona +1 de dano do Elemento da Besta aos ataques. PP técnico: o livro não informa PP. Parte necessária: Tronco ou Cabeça.',
     @id_autor_original),
    (22, 'Elmo de Claker', 'ELMO', 8, NULL, 'Elemento da Besta',
     'Parte necessária: Cabeça.', @id_autor_original),
    (23, 'Armadura de Claker', 'ARMADURA', 13, NULL, 'Elemento da Besta',
     'Partes necessárias: Cabeça e Tronco.', @id_autor_original),

    (24, 'Botas de Gaiark', 'BOTAS', 1, NULL, NULL,
     'Ganhe +1 nas rolagens para escalar. PP técnico: o livro não informa PP. Parte necessária: qualquer parte.',
     @id_autor_original),
    (25, 'Braceletes de Gaiark', 'BRACELETES', 5, NULL, 'Elemento da Besta',
     'Parte necessária: Tronco.', @id_autor_original),
    (26, 'Armadura de Gaiark', 'ARMADURA', 14, NULL, 'Elemento da Besta',
     'Partes necessárias: Cabeça, Tronco e Cauda.', @id_autor_original),

    (27, 'Botas de Uborosso', 'BOTAS', 6, NULL, NULL,
     'Parte necessária: Tronco.', @id_autor_original),
    (28, 'Armadura de Uborosso', 'ARMADURA', 15, NULL, 'Elemento da Besta',
     'Partes necessárias: Cabeça, Tronco e Cauda.', @id_autor_original),

    (29, 'Braceletes de Gorgantis', 'BRACELETES', 6, NULL, NULL,
     'Parte necessária: Asas.', @id_autor_original),
    (30, 'Elmo de Gorgantis', 'ELMO', 8, NULL, 'Elemento da Besta',
     'Parte necessária: Tronco.', @id_autor_original),
    (31, 'Armadura de Gorgantis', 'ARMADURA', 15, NULL, 'Ácido',
     'Partes necessárias: Abdome e Cabeça.', @id_autor_original),

    (32, 'Elmo de Euplosor', 'ELMO', 11, NULL, 'Elemento da Besta',
     'Parte necessária: Tronco ou Cauda.', @id_autor_original),
    (33, 'Escudo de Euplosor', 'ESCUDO', 1, NULL, NULL,
     'Cause 1 ponto de dano no oponente ao bloquear. PP técnico: o livro não informa PP. Parte necessária: Tronco ou Cauda.',
     @id_autor_original),
    (34, 'Armadura de Euplosor', 'ARMADURA', 20, NULL, NULL,
     'Partes necessárias: Tronco e Cauda.', @id_autor_original),

    (35, 'Botas de Raidormum', 'BOTAS', 10, NULL, 'Elemento da Besta',
     'Parte necessária: Asa Esquerda ou Asa Direita.', @id_autor_original),
    (36, 'Bracelete de Raidormum', 'BRACELETES', 10, NULL, 'Elemento da Besta',
     'Parte necessária: Cabeça ou Tronco.', @id_autor_original),
    (37, 'Armadura de Raidormum', 'ARMADURA', 18, NULL, 'Elemento da Besta',
     'Partes necessárias: quaisquer 4 partes.', @id_autor_original),

    (38, 'Elmo de Argoth', 'ELMO', 15, NULL, 'Elemento da Besta',
     'Parte necessária: Patas ou Cabeça.', @id_autor_original),
    (39, 'Braceletes de Argoth', 'BRACELETES', 1, NULL, NULL,
     'Ataques corporais causam +2 de dano do Elemento da Besta. PP técnico: o livro não informa PP. Parte necessária: Asas.',
     @id_autor_original),
    (40, 'Botas de Argoth', 'BOTAS', 10, NULL, 'Elemento da Besta',
     'Parte necessária: qualquer parte.', @id_autor_original),
    (41, 'Escudo de Argoth', 'ESCUDO', 1, NULL, NULL,
     'Quando um Animal Selvagem aparecer, role um dado. Com 4 ou mais, ele vai embora. PP técnico: o livro não informa PP. Parte necessária: Cabeça.',
     @id_autor_original),
    (42, 'Armadura de Argoth', 'ARMADURA', 25, NULL, 'Elemento da Besta',
     'Partes necessárias: Cabeça e Tronco.', @id_autor_original),

    (43, 'Braceletes de Renotaur', 'BRACELETES', 1, NULL, NULL,
     'Recupere 3 PV adicionais a cada noite de sono. PP técnico: o livro não informa PP. Parte necessária: Cauda.',
     @id_autor_original),
    (44, 'Botas de Renotaur', 'BOTAS', 6, NULL, 'Elemento da Besta',
     'Parte necessária: Patas.', @id_autor_original),
    (45, 'Armadura de Renotaur', 'ARMADURA', 20, NULL, 'Elemento da Besta',
     'Partes necessárias: Tronco e Patas.', @id_autor_original),

    (46, 'Elmo de Kandra', 'ELMO', 12, NULL, 'Elemento da Besta',
     'Parte necessária: Cabeça-Tronco.', @id_autor_original),
    (47, 'Armadura de Kandra', 'ARMADURA', 20, NULL, 'Elemento da Besta',
     'Partes necessárias: Cabeça-Tronco e Asas.', @id_autor_original),

    (48, 'Braceletes de Kingon', 'BRACELETES', 1, NULL, NULL,
     'Ataques corporais causam +4 de dano. PP técnico: o livro não informa PP. Parte necessária: Braços.',
     @id_autor_original),
    (49, 'Botas de Kingon', 'BOTAS', 12, NULL, 'Elemento da Besta',
     'Parte necessária: Cabeça.', @id_autor_original),
    (50, 'Armadura de Kingon', 'ARMADURA', 30, NULL, 'Elemento da Besta',
     'Partes necessárias: Tronco e Cabeça.', @id_autor_original),

    (51, 'Elmo de Mathulu', 'ELMO', 20, NULL, 'Elemento da Besta',
     'Parte necessária: Cabeça.', @id_autor_original),
    (52, 'Armadura de Mathulu', 'ARMADURA', 40, NULL, 'Elemento da Besta',
     'Partes necessárias: Presas, Trombas e Cabeça.', @id_autor_original),

    (53, 'Elmo de Fenkeen', 'ELMO', 20, NULL, 'Elemento da Besta',
     'Parte necessária: Cabeça.', @id_autor_original),
    (54, 'Braceletes de Fenkeen', 'BRACELETES', 12, NULL, 'Elemento da Besta',
     'Parte necessária: Cauda.', @id_autor_original),
    (55, 'Botas de Fenkeen', 'BOTAS', 12, NULL, 'Elemento da Besta',
     'Parte necessária: Patas.', @id_autor_original),
    (56, 'Escudo de Fenkeen', 'ESCUDO', 1, NULL, NULL,
     'Recupere 5 PV ao derrotar um Animal Selvagem. PP técnico: o livro não informa PP. Parte necessária: Tronco.',
     @id_autor_original),
    (57, 'Armadura de Fenkeen', 'ARMADURA', 30, NULL, 'Elemento da Besta',
     'Partes necessárias: Cabeça e Cauda.', @id_autor_original),

    (58, 'Elmo de Goshin', 'ELMO', 30, NULL, 'Elemento da Besta',
     'Parte necessária: Cabeça.', @id_autor_original),
    (59, 'Braceletes de Goshin', 'BRACELETES', 20, NULL, 'Elemento da Besta',
     'Parte necessária: Cauda.', @id_autor_original),
    (60, 'Botas de Goshin', 'BOTAS', 20, NULL, 'Elemento da Besta',
     'Parte necessária: Pernas ou Braços.', @id_autor_original),
    (61, 'Escudo de Goshin', 'ESCUDO', 1, NULL, NULL,
     'Ganhe +3 nas rolagens para se esconder. PP técnico: o livro não informa PP. Parte necessária: Tronco.',
     @id_autor_original),
    (62, 'Armadura de Goshin', 'ARMADURA', 50, NULL, 'Elemento da Besta',
     'Partes necessárias: Tronco e Cabeça.', @id_autor_original);

-- =========================================================
-- ITENS, POÇÕES, MATERIAIS E FLECHAS
-- =========================================================

INSERT INTO item
    (id_item, nome, tipo, descricao, id_autor)
VALUES
    (1, 'Antídoto', 'CONSUMIVEL',
     'Ingrediente: qualquer parte de uma Besta do tipo Ácido. Alternativa: 4 Raízes de Kamun. Remove envenenamento e concede imunidade a essa condição até o fim do combate.',
     @id_autor_original),
    (2, 'Poção da Força', 'CONSUMIVEL',
     'Ingrediente: Tronco de uma Besta de Fogo ou Eletricidade. Alternativas: 10 Flores de Moya ou 8 Porções de Goma de Cactus. Concede +1 de dano aos ataques corporais até o fim do combate.',
     @id_autor_original),
    (3, 'Poção da Resistência', 'CONSUMIVEL',
     'Ingrediente: qualquer parte de uma Besta Normal. Alternativa: 20 Folhas de Belindda. Reduz em 1 todo dano elemental recebido até o fim do combate.',
     @id_autor_original),
    (4, 'Poção de Vida', 'CONSUMIVEL',
     'Ingrediente: Cabeça de uma Besta. Alternativa: 15 Sementes de Osuva. Recupera todos os pontos perdidos de vida e de fadiga.',
     @id_autor_original),
    (5, 'Poção do Lobo Gigante', 'CONSUMIVEL',
     'Ingrediente: Tronco de uma Besta de Gelo ou Ácido. Alternativa: 10 Corações de Lobo Gigante. Transforma quem beber em Lobo Gigante por 1d6 turnos: dano corporal 8 e PV iguais aos do personagem; não permite armas, armaduras ou escalada.',
     @id_autor_original),
    (6, 'Poção do Vento', 'CONSUMIVEL',
     'Ingrediente: Asa de uma Besta. Alternativa: 4 Ovos de Avelha. Permite voar por 1d6 turnos e atacar qualquer parte da Besta.',
     @id_autor_original),
    (7, 'Poção da Aranha', 'CONSUMIVEL',
     'Ingrediente: Abdome de uma Besta. Alternativa: 20 Sementes de Osuva. Concede +3 para Escalar até o fim do combate.',
     @id_autor_original),
    (8, 'Incenso', 'UTILITARIO',
     'Gaste para rerrolar uma vez ao descansar durante a noite. Custo: 4 Ingredientes.',
     @id_autor_original),
    (9, 'Corda de Laçar', 'UTILITARIO',
     'Quando a Besta for Se Afastar, gaste este equipamento para impedir que ela vá embora. Custo: 3 de Couro.',
     @id_autor_original),

    (10, 'Minério', 'MATERIAL',
     'Material utilizado para fabricar equipamentos simples.', @id_autor_original),
    (11, 'Couro', 'MATERIAL',
     'Material extraído de animais selvagens ou de partes de Bestas.', @id_autor_original),
    (12, 'Ingrediente', 'MATERIAL',
     'Ingrediente genérico usado na fabricação e na troca por Minérios.', @id_autor_original),
    (13, 'Raiz de Kamun', 'MATERIAL',
     'Ingrediente alternativo do Antídoto.', @id_autor_original),
    (14, 'Flor de Moya', 'MATERIAL',
     'Ingrediente alternativo da Poção da Força.', @id_autor_original),
    (15, 'Porção de Goma de Cactus', 'MATERIAL',
     'Ingrediente alternativo da Poção da Força.', @id_autor_original),
    (16, 'Folha de Belindda', 'MATERIAL',
     'Ingrediente alternativo da Poção da Resistência.', @id_autor_original),
    (17, 'Semente de Osuva', 'MATERIAL',
     'Ingrediente alternativo da Poção de Vida e da Poção da Aranha.', @id_autor_original),
    (18, 'Coração de Lobo Gigante', 'MATERIAL',
     'Ingrediente alternativo da Poção do Lobo Gigante.', @id_autor_original),
    (19, 'Ovo de Avelha', 'MATERIAL',
     'Ingrediente alternativo da Poção do Vento.', @id_autor_original),

    (20, 'Capa de Kandra', 'UTILITARIO',
     'Ao sofrer uma queda, reduza o dano em 1. Parte necessária: Asas. Cadastrada como item porque o banco não possui o tipo CAPA.',
     @id_autor_original),
    (21, 'Capa de Mathulu', 'UTILITARIO',
     'Concede +3 ao se esconder. Parte necessária: Trombas. Cadastrada como item porque o banco não possui o tipo CAPA.',
     @id_autor_original),

    (22, 'Flecha de Rexian', 'UTILITARIO',
     'Dano adicional +1. Parte necessária: qualquer parte.', @id_autor_original),
    (23, 'Flecha de Albivian', 'UTILITARIO',
     'Dano adicional +1 do Elemento da Besta. Parte necessária: qualquer parte.', @id_autor_original),
    (24, 'Flecha de Boviatan', 'UTILITARIO',
     'Dano adicional +1. Parte necessária: qualquer parte.', @id_autor_original),
    (25, 'Flecha de Claker', 'UTILITARIO',
     'Dano adicional +2 do Elemento da Besta. Parte necessária: Abdome.', @id_autor_original),
    (26, 'Flecha de Uborosso', 'UTILITARIO',
     'Dano adicional +3 do Elemento da Besta. Parte necessária: qualquer parte.', @id_autor_original),
    (27, 'Flecha de Gorgantis', 'UTILITARIO',
     'Dano adicional +1 do Elemento da Besta. Parte necessária: qualquer parte.', @id_autor_original),
    (28, 'Flecha de Euplosor', 'UTILITARIO',
     'Dano adicional +2 do Elemento da Besta. Parte necessária: Tronco ou Cauda.', @id_autor_original),
    (29, 'Flecha de Argoth', 'UTILITARIO',
     'Dano adicional +2 do Elemento da Besta. Parte necessária: qualquer parte.', @id_autor_original),
    (30, 'Flecha de Renotaur', 'UTILITARIO',
     'Dano adicional +5. Parte necessária: Cabeça.', @id_autor_original),
    (31, 'Flecha de Kandra', 'UTILITARIO',
     'Dano adicional +2 do Elemento da Besta. Parte necessária: Patas.', @id_autor_original),
    (32, 'Flecha de Kingon', 'UTILITARIO',
     'Dano adicional +3 do Elemento da Besta. Parte necessária: qualquer parte.', @id_autor_original),
    (33, 'Flecha de Mathulu', 'UTILITARIO',
     'Dano adicional +3 do Elemento da Besta. Parte necessária: qualquer parte.', @id_autor_original),
    (34, 'Flecha de Fenkeen', 'UTILITARIO',
     'Dano adicional +4 do Elemento da Besta. Parte necessária: qualquer parte.', @id_autor_original),
    (35, 'Flecha de Goshin', 'UTILITARIO',
     'Dano adicional +10. Parte necessária: qualquer parte.', @id_autor_original);

-- =========================================================
-- PROFISSOES
-- =========================================================

INSERT INTO profissao
    (id_profissao, nome, descricao, pv_maximo, id_autor, visibilidade)
VALUES
    (1, 'Eremita',
     'Passou a vida nos ermos e prefere a natureza aos confortos das cidades. Pode ser um patrulheiro nomade, alguem que fugiu da cidade ou o ultimo sobrevivente de uma raca extinta.',
     22, @id_autor_original, 'PUBLICO'),
    (2, 'Acadêmico',
     'Passou a vida estudando em livros, em uma academia ou sob a tutela de um sábio, usando seus conhecimentos para fabricar equipamentos e poções mais efetivas.',
     20, @id_autor_original, 'PUBLICO'),
    (3, 'Ferreiro',
     'Passou a vida em trabalhos pesados, fabricando ferraduras, armas e equipamentos, seguindo uma tradição ou buscando vingança contra uma Besta.',
     22, @id_autor_original, 'PUBLICO'),
    (4, 'Nobre',
     'Afastou-se da vida nobre para se tornar um herói, podendo ser um veterano em busca de glória ou o herdeiro de um reino destruído.',
     20, @id_autor_original, 'PUBLICO'),
    (5, 'Bárbaro',
     'Cresceu em uma tribo selvagem, sobrevivendo da caça e da pesca, e partiu em busca de um grande troféu, vingança ou para cumprir uma profecia.',
     30, @id_autor_original, 'PUBLICO'),
    (6, 'Místico',
     'Teve a vida envolvida nas artes arcanas, obtendo seus poderes por experimentos, estudos ou por carregar sangue de dragão.',
     20, @id_autor_original, 'PUBLICO');

-- Duas vantagens de cada profissão.
INSERT INTO profissao_vantagem
    (id_profissao, slot, id_vantagem)
VALUES
    (1, 1, 1),
    (1, 2, 2),
    (2, 1, 3),
    (2, 2, 4),
    (3, 1, 5),
    (3, 2, 6),
    (4, 1, 7),
    (4, 2, 8),
    (5, 1, 9),
    (5, 2, 6),
    (6, 1, 10),
    (6, 2, 11);

-- Armas iniciais.
INSERT INTO profissao_arma
    (id_profissao, slot, id_arma)
VALUES
    (1, 1, 2),
    (1, 2, 1),
    (1, 3, 13),
    (2, 1, 4),
    (2, 2, 11),
    (3, 1, 9),
    (3, 2, 1),
    (3, 3, 11),
    (4, 1, 7),
    (4, 2, 3),
    (4, 3, 11),
    (5, 1, 8),
    (5, 2, 11),
    (6, 1, 5),
    (6, 2, 1),
    (6, 3, 11);

-- Vestimentas iniciais.
INSERT INTO profissao_vestimenta
    (id_profissao, tipo_slot, id_vestimenta)
VALUES
    (1, 'ARMADURA', 4),
    (2, 'ARMADURA', 3),
    (2, 'ESCUDO', 1),
    (3, 'ARMADURA', 3),
    (4, 'ARMADURA', 3),
    (4, 'ESCUDO', 1),
    (6, 'ARMADURA', 3);

-- Itens iniciais.
INSERT INTO profissao_item
    (id_profissao, id_item, quantidade)
VALUES
    (1, 4, 2),
    (2, 4, 2),
    (2, 3, 1),
    (3, 4, 2),
    (4, 4, 2),
    (5, 4, 2),
    (6, 4, 2),
    (6, 3, 1);

-- =========================================================
-- FICHAS DE DEMONSTRACAO
-- =========================================================
--
-- As três fichas pertencem ao usuário diario@cacador.com.
-- O conteúdo inicial foi copiado das respectivas profissões,
-- reproduzindo o resultado do cadastro realizado pela aplicação.

INSERT INTO ficha
    (id_ficha, nome, pv_atual, fadiga, id_profissao, id_usuario)
VALUES
    (1, 'Personagem Comum', 20, 0, 2, @id_autor_original),
    (2, 'Personagem com Companheiro', 22, 0, 1, @id_autor_original),
    (3, 'Personagem com Magia', 20, 0, 6, @id_autor_original);

-- Armas atuais copiadas das profissões.
INSERT INTO ficha_arma
    (id_ficha, slot, id_arma)
VALUES
    (1, 1, 4),
    (1, 2, 11),
    (2, 1, 2),
    (2, 2, 1),
    (2, 3, 13),
    (3, 1, 5),
    (3, 2, 1),
    (3, 3, 11);

-- Vestimentas atuais com o PP inicial completo.
INSERT INTO ficha_vestimenta
    (id_ficha, tipo_slot, id_vestimenta, pontos_protecao_atual)
VALUES
    (1, 'ARMADURA', 3, 10),
    (1, 'ESCUDO', 1, 1),
    (2, 'ARMADURA', 4, 8),
    (3, 'ARMADURA', 3, 10);

-- Itens atuais copiados das profissões.
INSERT INTO ficha_item
    (id_ficha, id_item, quantidade)
VALUES
    (1, 4, 2),
    (1, 3, 1),
    (2, 4, 2),
    (3, 4, 2),
    (3, 3, 1);

-- O Eremita escolheu o companheiro Rastreador e o chamou de Faro.
INSERT INTO ficha_companheiro
    (id_ficha, id_companheiro, nome, pv_atual)
VALUES
    (2, 3, 'Faro', 10);

-- O Místico escolheu Bola de Fogo como magia inicial.
INSERT INTO ficha_magia
    (id_ficha, id_magia)
VALUES
    (3, 1);

COMMIT;
