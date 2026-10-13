<?php

require_once __DIR__ . "/../../Config/Autenticacao.php";

exigirLogin();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&amp;family=Source+Sans+3:wght@400;600;700&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/Assets/css/style.css">

    <title>Painel — Diário do Caçador</title>
</head>

<body>
    <main class="pagina" aria-labelledby="titulo-pagina">
        <header class="painel-cabecalho">
            <div class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">Diário de campo</p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    Diário do Caçador
                </h1>

                <p class="painel__saudacao">
                    Bem-vindo,
                    <strong><?php echo htmlspecialchars(
                        $_SESSION["nome_usuario"] ?? "",
                        ENT_QUOTES,
                        "UTF-8"
                    ); ?></strong>!
                </p>
            </div>

            <form class="formulario-saida" action="/Controllers/LogoutController.php" method="POST">
                <button class="botao botao--secundario" type="submit">Sair</button>
            </form>
        </header>

        <div class="mensagem mensagem--sucesso" role="status">
            <p>Login realizado com sucesso.</p>
        </div>

        <nav aria-label="Menu principal">
            <section class="painel-secao" aria-labelledby="menu-personagens">
                <h2 class="painel-secao__titulo" id="menu-personagens">
                    Personagens e profissões
                </h2>

                <p class="painel-secao__descricao">
                    Crie personagens ou consulte as profissões disponíveis.
                </p>

                <ul class="grade-menu">
                    <li>
                        <a class="cartao-menu" href="/Controllers/FichaController.php?acao=listar">
                            <span class="cartao-menu__titulo">Minhas fichas</span>
                            <span class="cartao-menu__descricao">
                                Consulte e gerencie seus personagens.
                            </span>
                        </a>
                    </li>

                    <li>
                        <a class="cartao-menu" href="/Controllers/ProfissaoController.php?acao=listar">
                            <span class="cartao-menu__titulo">Minhas profissões</span>
                            <span class="cartao-menu__descricao">
                                Crie e organize suas profissões.
                            </span>
                        </a>
                    </li>

                    <li>
                        <a class="cartao-menu" href="/Controllers/ProfissaoController.php?acao=publicas">
                            <span class="cartao-menu__titulo">Profissões públicas</span>
                            <span class="cartao-menu__descricao">
                                Conheça opções compartilhadas por outros usuários.
                            </span>
                        </a>
                    </li>
                </ul>
            </section>

            <section class="painel-secao" aria-labelledby="menu-catalogos">
                <h2 class="painel-secao__titulo" id="menu-catalogos">
                    Catálogos
                </h2>

                <p class="painel-secao__descricao">
                    Organize os recursos utilizados nas profissões e fichas.
                </p>

                <ul class="grade-menu">
                    <li>
                        <a class="cartao-menu" href="/Controllers/VantagemController.php?acao=listar">
                            <span class="cartao-menu__titulo">Vantagens</span>
                            <span class="cartao-menu__descricao">
                                Cadastre benefícios normais, mágicos ou de companheiro.
                            </span>
                        </a>
                    </li>

                    <li>
                        <a class="cartao-menu" href="/Controllers/ArmaController.php?acao=listar">
                            <span class="cartao-menu__titulo">Armas</span>
                            <span class="cartao-menu__descricao">
                                Gerencie armas corporais e à distância.
                            </span>
                        </a>
                    </li>

                    <li>
                        <a class="cartao-menu" href="/Controllers/VestimentaController.php?acao=listar">
                            <span class="cartao-menu__titulo">Vestimentas</span>
                            <span class="cartao-menu__descricao">
                                Organize armaduras e equipamentos de proteção.
                            </span>
                        </a>
                    </li>

                    <li>
                        <a class="cartao-menu" href="/Controllers/ItemController.php?acao=listar">
                            <span class="cartao-menu__titulo">Itens</span>
                            <span class="cartao-menu__descricao">
                                Cadastre recursos para profissões e mochilas.
                            </span>
                        </a>
                    </li>

                    <li>
                        <a class="cartao-menu" href="/Controllers/MagiaController.php?acao=listar">
                            <span class="cartao-menu__titulo">Magias</span>
                            <span class="cartao-menu__descricao">
                                Registre magias e seus elementos.
                            </span>
                        </a>
                    </li>

                    <li>
                        <a class="cartao-menu" href="/Controllers/CompanheiroAnimalController.php?acao=listar">
                            <span class="cartao-menu__titulo">Companheiros animais</span>
                            <span class="cartao-menu__descricao">
                                Gerencie os modelos de companheiros.
                            </span>
                        </a>
                    </li>
                </ul>
            </section>
        </nav>
    </main>
</body>

</html>