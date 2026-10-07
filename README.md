# Diário do Caçador

Sistema web desenvolvido como produto de Trabalho de Conclusão de Curso para criação e gerenciamento de personagens do RPG solo **Diário do Caçador**.

O projeto permite cadastrar os elementos utilizados pelas profissões, montar profissões com equipamentos e vantagens iniciais, criar personagens e gerenciar os dados mutáveis de suas fichas durante o jogo.

## Informações acadêmicas

- **Autor:** Eude Santana
- **Curso:** Tecnologia em Análise e Desenvolvimento de Sistemas
- **Instituição:** Instituto Federal do Paraná — Campus Umuarama
- **Situação do projeto:** MVP funcional em revisão final

## Funcionalidades implementadas

- Cadastro e autenticação de usuários.
- Encerramento da sessão por logout.
- CRUD de itens, armas, vestimentas, magias e companheiros animais.
- CRUD de vantagens e seus relacionamentos com magias ou companheiros.
- Cadastro e gerenciamento de profissões.
- Definição de vantagens, armas, vestimentas e itens iniciais de uma profissão.
- Visualização de profissões públicas.
- Criação de personagens a partir de uma profissão.
- Gerenciamento dos pontos de vida e da fadiga do personagem.
- Gerenciamento dos slots de armas e vestimentas.
- Gerenciamento de itens, magias e companheiro animal da ficha.
- Controle de autoria dos conteúdos cadastrados.

## Tecnologias utilizadas

- PHP 8.2
- MariaDB
- PDO e prepared statements
- HTML
- JavaScript básico para confirmações na interface
- Arquitetura MVC
- Servidor de desenvolvimento integrado do PHP

## Estrutura do projeto

```text
diario_do_cacador/
├── Config/          # Conexão com o banco e controle de autenticação
├── Controllers/     # Recebimento das requisições e regras de fluxo
├── Models/          # Regras de negócio e acesso ao banco com PDO
├── Views/           # Telas apresentadas ao usuário
├── bd.sql           # Estrutura do banco de dados
├── index.php        # Ponto de entrada da aplicação
├── LICENSE
└── README.md
```

O projeto não utiliza uma camada DAO separada. Por decisão de arquitetura, os próprios Models realizam o acesso ao banco de dados com PDO.

## Requisitos

Para executar o projeto localmente, é necessário possuir:

- PHP 8.2 ou superior;
- extensão `pdo_mysql` habilitada no PHP;
- MariaDB;
- navegador web;
- Git, caso o projeto seja obtido pelo repositório.

## Instalação e execução

### 1. Obter o projeto

Clone o repositório e entre na pasta criada:

```bash
git clone URL_DO_REPOSITORIO
cd diario_do_cacador
```

Também é possível baixar o projeto pelo botão **Code > Download ZIP** do GitHub e extrair o arquivo.

### 2. Criar o banco de dados

O arquivo `bd.sql` cria o banco `diario_cacador` e suas tabelas.

No terminal, execute:

```bash
mariadb -u root -p < bd.sql
```

Informe a senha do MariaDB quando ela for solicitada.

O mesmo arquivo também pode ser importado utilizando uma interface gráfica compatível com MariaDB.

### 3. Configurar a conexão

Abra o arquivo `Config/Connection.php` e informe os dados do MariaDB instalado na máquina:

```php
private string $host = "localhost";
private string $database = "diario_cacador";
private string $usuario = "root";
private string $senha = "SUA_SENHA";
```

### 4. Iniciar a aplicação

Dentro da pasta raiz do projeto, execute:

```bash
php -S localhost:8000
```

Depois, acesse no navegador:

```text
http://localhost:8000
```

### 5. Criar o primeiro usuário

Na tela inicial, abra o cadastro de usuário. Após o cadastro, utilize o e-mail e a senha informados para entrar no sistema.

## Roteiro sugerido para conferência

Para verificar o fluxo principal do sistema:

1. Cadastre um usuário e realize o login.
2. Cadastre itens, armas, vestimentas, magias e companheiros animais.
3. Cadastre duas ou mais vantagens.
4. Crie uma profissão e escolha suas duas vantagens e seus recursos iniciais.
5. Crie um personagem utilizando essa profissão.
6. Abra a opção de gerenciamento da ficha.
7. Altere PV, fadiga, armas, vestimentas, itens e demais recursos disponíveis.
8. Crie uma profissão pública e confira sua visualização na listagem pública.
9. Termine o teste utilizando o botão de logout.

## Regras principais do sistema

- Cada conteúdo possui um usuário autor.
- Somente o autor pode alterar ou excluir seus conteúdos.
- Uma profissão precisa possuir duas vantagens diferentes.
- Uma vantagem pode ser normal, conceder acesso a magias ou conceder um companheiro animal.
- Uma vantagem não pode conceder magia e companheiro simultaneamente.
- Cada ficha pertence ao usuário que a criou.
- O PV atual não pode ultrapassar o PV máximo definido pela profissão.
- O PP atual não pode ultrapassar o PP máximo da vestimenta.
- Quantidade zero remove um item da ficha.
- PP igual a zero remove a vestimenta da ficha.
- Apenas personagens cuja profissão concede magia podem gerenciar magias.

## Verificação de sintaxe

Para verificar a sintaxe de todos os arquivos PHP pelo terminal:

```bash
find . -type f -name '*.php' -print0 | xargs -0 -n1 php -l
```

Cada arquivo válido deve apresentar a mensagem `No syntax errors detected`.

## Escopo

Este projeto é um MVP acadêmico. A implementação prioriza as funcionalidades principais, a aplicação dos conhecimentos desenvolvidos durante o curso e a organização em MVC.

O servidor integrado do PHP é utilizado apenas para desenvolvimento e avaliação local. O projeto ainda não contempla recursos próprios de uma aplicação destinada a produção, como recuperação de senha, expiração automática de sessão e implantação em servidor público.

## Licença

Este projeto utiliza a licença MIT. Consulte o arquivo `LICENSE` para mais informações.