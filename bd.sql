-- =========================================================
-- DIÁIO DO CAÇADOR
-- =========================================================

CREATE DATABASE IF NOT EXISTS diario_cacador
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE diario_cacador;

-- =========================================================
-- USUÁRIO
--
-- id_usuario: identificador interno.
-- email: identificador único para autenticação.
-- nome_exibicao: apelido público, podendo se repetir.
-- =========================================================

CREATE TABLE usuario (
  id_usuario INT UNSIGNED AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL,
  senha VARCHAR(255) NOT NULL,
  ativo BOOLEAN NOT NULL DEFAULT TRUE,

  CONSTRAINT pk_usuario
    PRIMARY KEY (id_usuario),

  CONSTRAINT uq_usuario_email
    UNIQUE (email)
) ENGINE = InnoDB;

-- =========================================================
-- PROFISSÃO
--
-- A profissão é a unidade compartilhável do sistema.
-- Quando pública, suas informações relacionadas poderão ser
-- exibidas em conjunto pela aplicação.
-- =========================================================

CREATE TABLE profissao (
  id_profissao INT UNSIGNED AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL,
  descricao TEXT NULL,
  pv_maximo INT UNSIGNED NOT NULL CHECK (pv_maximo > 0),
  id_autor INT UNSIGNED NOT NULL,
  visibilidade ENUM('PUBLICO', 'PRIVADO')
    NOT NULL DEFAULT 'PRIVADO',

  CONSTRAINT pk_profissao
    PRIMARY KEY (id_profissao),

  INDEX idx_profissao_autor (id_autor),

  CONSTRAINT fk_profissao_autor
    FOREIGN KEY (id_autor)
    REFERENCES usuario (id_usuario)
    ON DELETE RESTRICT
) ENGINE = InnoDB;

-- =========================================================
-- VANTAGEM
--
-- O tipo da vantagem é inferido pelas tabelas:
-- vantagem_companheiro e vantagem_magia.
-- Sem relação nessas tabelas, a vantagem é normal.
-- =========================================================

CREATE TABLE vantagem (
  id_vantagem INT UNSIGNED AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL,
  descricao TEXT NULL,
  id_autor INT UNSIGNED NOT NULL,

  CONSTRAINT pk_vantagem
    PRIMARY KEY (id_vantagem),

  INDEX idx_vantagem_autor (id_autor),

  CONSTRAINT fk_vantagem_autor
    FOREIGN KEY (id_autor)
    REFERENCES usuario (id_usuario)
    ON DELETE RESTRICT
) ENGINE = InnoDB;

-- =========================================================
-- ARMA
-- =========================================================

CREATE TABLE arma (
  id_arma INT UNSIGNED AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL,
  tipo ENUM('CORPORAL', 'DISTANCIA') NOT NULL,
  maos TINYINT NOT NULL CHECK (maos IN (1, 2)),
  dano VARCHAR(50) NOT NULL,
  especial TEXT NULL,
  id_autor INT UNSIGNED NOT NULL,

  CONSTRAINT pk_arma
    PRIMARY KEY (id_arma),

  INDEX idx_arma_autor (id_autor),

  CONSTRAINT fk_arma_autor
    FOREIGN KEY (id_autor)
    REFERENCES usuario (id_usuario)
    ON DELETE RESTRICT
) ENGINE = InnoDB;

-- =========================================================
-- VESTIMENTA
--
-- pontos_protecao_maximo pertence ao modelo da vestimenta.
-- pontos_protecao_atual pertence à vestimenta equipada na ficha.
-- dano, elemento e especial permanecem porque fazem parte da
-- estrutura das vestimentas no Diário do Caçador.
-- =========================================================

CREATE TABLE vestimenta (
  id_vestimenta INT UNSIGNED AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL,
  tipo ENUM(
    'ARMADURA',
    'ELMO',
    'BRACELETES',
    'BOTAS',
    'ESCUDO'
  ) NOT NULL,
  pontos_protecao_maximo INT UNSIGNED NOT NULL
    CHECK (pontos_protecao_maximo > 0),
  dano VARCHAR(50) NULL,
  elemento VARCHAR(50) NULL,
  especial TEXT NULL,
  id_autor INT UNSIGNED NOT NULL,

  CONSTRAINT pk_vestimenta
    PRIMARY KEY (id_vestimenta),

  INDEX idx_vestimenta_autor (id_autor),

  CONSTRAINT fk_vestimenta_autor
    FOREIGN KEY (id_autor)
    REFERENCES usuario (id_usuario)
    ON DELETE RESTRICT
) ENGINE = InnoDB;

-- =========================================================
-- ITEM
-- =========================================================

CREATE TABLE item (
  id_item INT UNSIGNED AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL,
  tipo ENUM('CONSUMIVEL', 'MATERIAL', 'UTILITARIO', 'OUTRO') NOT NULL DEFAULT 'OUTRO',
  descricao TEXT NULL,
  id_autor INT UNSIGNED NOT NULL,

  CONSTRAINT pk_item
    PRIMARY KEY (id_item),

  INDEX idx_item_autor (id_autor),

  CONSTRAINT fk_item_autor
    FOREIGN KEY (id_autor)
    REFERENCES usuario (id_usuario)
    ON DELETE RESTRICT
) ENGINE = InnoDB;

-- =========================================================
-- COMPANHEIRO ANIMAL
--
-- tipo representa o animal: Cavalo, Lobo, Falcão etc.
-- O nome pessoal pertence à relação ficha_companheiro.
-- =========================================================

CREATE TABLE companheiro_animal (
  id_companheiro INT UNSIGNED AUTO_INCREMENT,
  tipo VARCHAR(100) NOT NULL,
  pv_maximo INT UNSIGNED NOT NULL CHECK (pv_maximo > 0),
  dano VARCHAR(50) NULL,
  descricao TEXT NULL,
  id_autor INT UNSIGNED NOT NULL,

  CONSTRAINT pk_companheiro_animal
    PRIMARY KEY (id_companheiro),

  INDEX idx_companheiro_autor (id_autor),

  CONSTRAINT fk_companheiro_autor
    FOREIGN KEY (id_autor)
    REFERENCES usuario (id_usuario)
    ON DELETE RESTRICT
) ENGINE = InnoDB;

-- =========================================================
-- MAGIA
-- =========================================================

CREATE TABLE magia (
  id_magia INT UNSIGNED AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL,
  elemento VARCHAR(50) NOT NULL,
  descricao TEXT NULL,
  id_autor INT UNSIGNED NOT NULL,

  CONSTRAINT pk_magia
    PRIMARY KEY (id_magia),

  INDEX idx_magia_autor (id_autor),

  CONSTRAINT fk_magia_autor
    FOREIGN KEY (id_autor)
    REFERENCES usuario (id_usuario)
    ON DELETE RESTRICT
) ENGINE = InnoDB;

-- =========================================================
-- FICHA
--
-- A ficha pertence a um usuário e permanece privada.
-- =========================================================

CREATE TABLE ficha (
  id_ficha INT UNSIGNED AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL,
  pv_atual INT UNSIGNED NOT NULL,
  fadiga TINYINT NOT NULL DEFAULT 0
    CHECK (fadiga BETWEEN 0 AND 6),
  id_profissao INT UNSIGNED NOT NULL,
  id_usuario INT UNSIGNED NOT NULL,

  CONSTRAINT pk_ficha
    PRIMARY KEY (id_ficha),

  INDEX idx_ficha_profissao (id_profissao),
  INDEX idx_ficha_usuario (id_usuario),

  CONSTRAINT fk_ficha_profissao
    FOREIGN KEY (id_profissao)
    REFERENCES profissao (id_profissao)
    ON DELETE RESTRICT,

  CONSTRAINT fk_ficha_usuario
    FOREIGN KEY (id_usuario)
    REFERENCES usuario (id_usuario)
    ON DELETE RESTRICT
) ENGINE = InnoDB;

-- =========================================================
-- DUAS VANTAGENS DA PROFISSÃO
--
-- Os slots limitam a profissão a no máximo duas vantagens.
-- A aplicação deverá verificar se os dois slots estão
-- preenchidos antes de permitir publicar ou utilizar a profissão.
-- =========================================================

CREATE TABLE profissao_vantagem (
  id_profissao INT UNSIGNED NOT NULL,
  slot TINYINT NOT NULL CHECK (slot BETWEEN 1 AND 2),
  id_vantagem INT UNSIGNED NOT NULL,

  CONSTRAINT pk_profissao_vantagem
    PRIMARY KEY (id_profissao, slot),

  CONSTRAINT uq_profissao_vantagem
    UNIQUE (id_profissao, id_vantagem),

  INDEX idx_profissao_vantagem_vantagem (id_vantagem),

  CONSTRAINT fk_profissao_vantagem_profissao
    FOREIGN KEY (id_profissao)
    REFERENCES profissao (id_profissao)
    ON DELETE CASCADE,

  CONSTRAINT fk_profissao_vantagem_vantagem
    FOREIGN KEY (id_vantagem)
    REFERENCES vantagem (id_vantagem)
    ON DELETE RESTRICT
) ENGINE = InnoDB;

-- =========================================================
-- VANTAGEM QUE CONCEDE COMPANHEIRO
-- =========================================================

CREATE TABLE vantagem_companheiro (
  id_vantagem INT UNSIGNED NOT NULL,
  id_companheiro INT UNSIGNED NOT NULL,

  CONSTRAINT pk_vantagem_companheiro
    PRIMARY KEY (id_vantagem, id_companheiro),

  INDEX idx_vantagem_companheiro_companheiro (id_companheiro),

  CONSTRAINT fk_vantagem_companheiro_vantagem
    FOREIGN KEY (id_vantagem)
    REFERENCES vantagem (id_vantagem)
    ON DELETE CASCADE,

  CONSTRAINT fk_vantagem_companheiro_companheiro
    FOREIGN KEY (id_companheiro)
    REFERENCES companheiro_animal (id_companheiro)
    ON DELETE RESTRICT
) ENGINE = InnoDB;

-- =========================================================
-- VANTAGEM QUE CONCEDE MAGIA
-- =========================================================

CREATE TABLE vantagem_magia (
  id_vantagem INT UNSIGNED NOT NULL,
  id_magia INT UNSIGNED NOT NULL,

  CONSTRAINT pk_vantagem_magia
    PRIMARY KEY (id_vantagem, id_magia),

  INDEX idx_vantagem_magia_magia (id_magia),

  CONSTRAINT fk_vantagem_magia_vantagem
    FOREIGN KEY (id_vantagem)
    REFERENCES vantagem (id_vantagem)
    ON DELETE CASCADE,

  CONSTRAINT fk_vantagem_magia_magia
    FOREIGN KEY (id_magia)
    REFERENCES magia (id_magia)
    ON DELETE RESTRICT
) ENGINE = InnoDB;

-- =========================================================
-- COMPANHEIRO ATUAL DA FICHA
--
-- id_ficha como chave primária limita a ficha a um companheiro.
-- nome é o nome pessoal dado pelo personagem.
-- =========================================================

CREATE TABLE ficha_companheiro (
  id_ficha INT UNSIGNED NOT NULL,
  id_companheiro INT UNSIGNED NOT NULL,
  nome VARCHAR(100) NOT NULL,
  pv_atual INT UNSIGNED NOT NULL,

  CONSTRAINT pk_ficha_companheiro
    PRIMARY KEY (id_ficha),

  INDEX idx_ficha_companheiro_companheiro (id_companheiro),

  CONSTRAINT fk_ficha_companheiro_ficha
    FOREIGN KEY (id_ficha)
    REFERENCES ficha (id_ficha)
    ON DELETE CASCADE,

  CONSTRAINT fk_ficha_companheiro_companheiro
    FOREIGN KEY (id_companheiro)
    REFERENCES companheiro_animal (id_companheiro)
    ON DELETE RESTRICT
) ENGINE = InnoDB;

-- =========================================================
-- MAGIAS APRENDIDAS PELA FICHA
-- =========================================================

CREATE TABLE ficha_magia (
  id_ficha INT UNSIGNED NOT NULL,
  id_magia INT UNSIGNED NOT NULL,

  CONSTRAINT pk_ficha_magia
    PRIMARY KEY (id_ficha, id_magia),

  INDEX idx_ficha_magia_magia (id_magia),

  CONSTRAINT fk_ficha_magia_ficha
    FOREIGN KEY (id_ficha)
    REFERENCES ficha (id_ficha)
    ON DELETE CASCADE,

  CONSTRAINT fk_ficha_magia_magia
    FOREIGN KEY (id_magia)
    REFERENCES magia (id_magia)
    ON DELETE RESTRICT
) ENGINE = InnoDB;

-- =========================================================
-- ARMAS INICIAIS DA PROFISSÃO
-- =========================================================

CREATE TABLE profissao_arma (
  id_profissao INT UNSIGNED NOT NULL,
  slot TINYINT NOT NULL CHECK (slot BETWEEN 1 AND 3),
  id_arma INT UNSIGNED NOT NULL,

  CONSTRAINT pk_profissao_arma
    PRIMARY KEY (id_profissao, slot),

  INDEX idx_profissao_arma_arma (id_arma),

  CONSTRAINT fk_profissao_arma_profissao
    FOREIGN KEY (id_profissao)
    REFERENCES profissao (id_profissao)
    ON DELETE CASCADE,

  CONSTRAINT fk_profissao_arma_arma
    FOREIGN KEY (id_arma)
    REFERENCES arma (id_arma)
    ON DELETE RESTRICT
) ENGINE = InnoDB;

-- =========================================================
-- ARMAS ATUAIS DA FICHA
-- =========================================================

CREATE TABLE ficha_arma (
  id_ficha INT UNSIGNED NOT NULL,
  slot TINYINT NOT NULL CHECK (slot BETWEEN 1 AND 3),
  id_arma INT UNSIGNED NOT NULL,

  CONSTRAINT pk_ficha_arma
    PRIMARY KEY (id_ficha, slot),

  INDEX idx_ficha_arma_arma (id_arma),

  CONSTRAINT fk_ficha_arma_ficha
    FOREIGN KEY (id_ficha)
    REFERENCES ficha (id_ficha)
    ON DELETE CASCADE,

  CONSTRAINT fk_ficha_arma_arma
    FOREIGN KEY (id_arma)
    REFERENCES arma (id_arma)
    ON DELETE RESTRICT
) ENGINE = InnoDB;

-- =========================================================
-- VESTIMENTAS INICIAIS DA PROFISSÃO
-- =========================================================

CREATE TABLE profissao_vestimenta (
  id_profissao INT UNSIGNED NOT NULL,
  tipo_slot ENUM(
    'ARMADURA',
    'ELMO',
    'BRACELETES',
    'BOTAS',
    'ESCUDO'
  ) NOT NULL,
  id_vestimenta INT UNSIGNED NOT NULL,

  CONSTRAINT pk_profissao_vestimenta
    PRIMARY KEY (id_profissao, tipo_slot),

  INDEX idx_profissao_vestimenta_vestimenta (id_vestimenta),

  CONSTRAINT fk_profissao_vestimenta_profissao
    FOREIGN KEY (id_profissao)
    REFERENCES profissao (id_profissao)
    ON DELETE CASCADE,

  CONSTRAINT fk_profissao_vestimenta_vestimenta
    FOREIGN KEY (id_vestimenta)
    REFERENCES vestimenta (id_vestimenta)
    ON DELETE RESTRICT
) ENGINE = InnoDB;

-- =========================================================
-- VESTIMENTAS ATUAIS DA FICHA
--
-- tipo_slot controla a posição ocupada.
-- pontos_protecao_atual guarda o estado mutável da vestimenta.
-- Quando chegar a zero, a aplicação deverá remover a relação,
-- pois a vestimenta foi destruída.
-- =========================================================

CREATE TABLE ficha_vestimenta (
  id_ficha INT UNSIGNED NOT NULL,
  tipo_slot ENUM(
    'ARMADURA',
    'ELMO',
    'BRACELETES',
    'BOTAS',
    'ESCUDO'
  ) NOT NULL,
  id_vestimenta INT UNSIGNED NOT NULL,
  pontos_protecao_atual INT UNSIGNED NOT NULL
    CHECK (pontos_protecao_atual >= 0),

  CONSTRAINT pk_ficha_vestimenta
    PRIMARY KEY (id_ficha, tipo_slot),

  INDEX idx_ficha_vestimenta_vestimenta (id_vestimenta),

  CONSTRAINT fk_ficha_vestimenta_ficha
    FOREIGN KEY (id_ficha)
    REFERENCES ficha (id_ficha)
    ON DELETE CASCADE,

  CONSTRAINT fk_ficha_vestimenta_vestimenta
    FOREIGN KEY (id_vestimenta)
    REFERENCES vestimenta (id_vestimenta)
    ON DELETE RESTRICT
) ENGINE = InnoDB;

-- =========================================================
-- ITENS INICIAIS DA PROFISSÃO
-- =========================================================

CREATE TABLE profissao_item (
  id_profissao INT UNSIGNED NOT NULL,
  id_item INT UNSIGNED NOT NULL,
  quantidade INT UNSIGNED NOT NULL DEFAULT 1
    CHECK (quantidade >= 0),

  CONSTRAINT pk_profissao_item
    PRIMARY KEY (id_profissao, id_item),

  INDEX idx_profissao_item_item (id_item),

  CONSTRAINT fk_profissao_item_profissao
    FOREIGN KEY (id_profissao)
    REFERENCES profissao (id_profissao)
    ON DELETE CASCADE,

  CONSTRAINT fk_profissao_item_item
    FOREIGN KEY (id_item)
    REFERENCES item (id_item)
    ON DELETE RESTRICT
) ENGINE = InnoDB;

-- =========================================================
-- ITENS ATUAIS DA FICHA
-- =========================================================

CREATE TABLE ficha_item (
  id_ficha INT UNSIGNED NOT NULL,
  id_item INT UNSIGNED NOT NULL,
  quantidade INT UNSIGNED NOT NULL DEFAULT 1
    CHECK (quantidade >= 0),

  CONSTRAINT pk_ficha_item
    PRIMARY KEY (id_ficha, id_item),

  INDEX idx_ficha_item_item (id_item),

  CONSTRAINT fk_ficha_item_ficha
    FOREIGN KEY (id_ficha)
    REFERENCES ficha (id_ficha)
    ON DELETE CASCADE,

  CONSTRAINT fk_ficha_item_item
    FOREIGN KEY (id_item)
    REFERENCES item (id_item)
    ON DELETE RESTRICT
) ENGINE = InnoDB;

-- =========================================================
-- REGRAS DE APLICAÇÃO PREVISTAS PARA O BACKEND
--
-- 1. Somente o autor pode editar ou excluir seus conteúdos.
-- 2. Conteúdo de uma profissão pública pode ser visualizado
--    em conjunto com a profissão.
-- 3. Conteúdo público de outro autor ainda não pode ser usado.
-- 4. A profissão deve ter os dois slots de vantagem preenchidos.
-- 5. tipo_slot deve corresponder ao tipo da vestimenta.
-- 6. PV e PP atuais não devem ultrapassar seus valores máximos.
-- 7. Uma vantagem não deve ser simultaneamente de magia
--    e de companheiro.
-- =========================================================