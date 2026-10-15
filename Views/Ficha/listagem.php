<?php

$fichas = $fichas ?? [];
$erro = $erro ?? '';
$mensagem = $mensagem ?? '';

function escaparListagemFicha($valor): string
{
    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        'UTF-8'
    );
}
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

    <title>Meus personagens — Diário do Caçador</title>
</head>

<body>
    <main class="pagina pagina-listagem">
        <div class="cabecalho-com-acoes">
            <header class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Diário do Caçador
                </p>

                <h1 class="cabecalho-pagina__titulo">
                    Meus personagens
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Consulte e gerencie os personagens da sua jornada.
                </p>
            </header>

            <nav class="acoes-pagina" aria-label="Ações da página">
                <a class="botao botao--primario" href="/Controllers/FichaController.php?acao=novo">
                    Criar personagem
                </a>

                <a class="botao botao--secundario" href="/Views/Usuario/painel.php">
                    Voltar ao painel
                </a>
            </nav>
        </div>

        <?php if ($mensagem !== ''): ?>
            <div class="mensagem mensagem--sucesso" role="status">
                <p>
                    <?php echo escaparListagemFicha($mensagem); ?>
                </p>
            </div>
        <?php endif; ?>

        <?php if ($erro !== ''): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    <?php echo escaparListagemFicha($erro); ?>
                </p>
            </div>
        <?php endif; ?>

        <?php if (empty($fichas)): ?>
            <div class="estado-vazio">
                <p>Nenhum personagem cadastrado.</p>

                <p>
                    Crie seu primeiro personagem para iniciar uma jornada.
                </p>
            </div>
        <?php else: ?>
            <section class="grade-personagens" aria-label="Personagens cadastrados">
                <?php foreach ($fichas as $ficha): ?>
                    <article class="cartao-personagem">
                        <header class="cartao-personagem__cabecalho">
                            <p class="cartao-personagem__marca">
                                Registro de personagem
                            </p>

                            <h2 class="cartao-personagem__titulo">
                                <?php
                                echo escaparListagemFicha(
                                    $ficha['nome']
                                );
                                ?>
                            </h2>

                            <p class="cartao-personagem__profissao">
                                <?php
                                echo escaparListagemFicha(
                                    $ficha['nome_profissao']
                                );
                                ?>
                            </p>
                        </header>

                        <dl class="cartao-personagem__atributos">
                            <div class="cartao-personagem__atributo">
                                <dt>PV</dt>

                                <dd>
                                    <?php echo (int) $ficha['pv_atual']; ?>
                                    /
                                    <?php echo (int) $ficha['pv_maximo']; ?>
                                </dd>
                            </div>

                            <div class="cartao-personagem__atributo">
                                <dt>Fadiga</dt>

                                <dd>
                                    <?php echo (int) $ficha['fadiga']; ?>
                                    / 6
                                </dd>
                            </div>
                        </dl>

                        <div class="cartao-personagem__acoes">
                            <a class="botao botao--primario" href="/Controllers/FichaController.php?acao=gerenciar&amp;id=<?php
                            echo (int) $ficha['id_ficha'];
                            ?>">
                                Gerenciar
                            </a>

                            <form class="cartao-personagem__formulario" action="/Controllers/FichaController.php" method="POST"
                                onsubmit="
                                    return confirm(
                                        'Deseja realmente excluir esta ficha?'
                                    );
                                ">
                                <input type="hidden" name="acao" value="excluir">

                                <input type="hidden" name="id_ficha" value="<?php
                                echo (int) $ficha['id_ficha'];
                                ?>">

                                <button class="botao botao--perigo" type="submit">
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </main>
</body>

</html>