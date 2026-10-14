<?php

if (!isset($magia)) {
    header(
        "Location: /Controllers/MagiaController.php?acao=listar"
    );
    exit;
}

$erro = $_GET["erro"] ?? "";
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

    <link rel="stylesheet" href="../../Assets/css/style.css">

    <title>Editar magia — Diário do Caçador</title>
</head>

<body>
    <main class="pagina pagina-formulario" aria-labelledby="titulo-pagina">
        <div class="cabecalho-com-acoes">
            <header class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Registro arcano
                </p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    Editar magia
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Revise as informações e os efeitos registrados para
                    esta magia.
                </p>
            </header>

            <nav class="acoes-pagina" aria-label="Ações da página">
                <a class="botao botao--secundario" href="/Controllers/MagiaController.php?acao=listar">
                    Cancelar
                </a>
            </nav>
        </div>

        <?php if ($erro === "nome_longo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>O nome deve possuir no máximo 100 caracteres.</p>
            </div>
        <?php elseif ($erro === "elemento_longo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>O elemento deve possuir no máximo 50 caracteres.</p>
            </div>
        <?php elseif ($erro === "atualizacao"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Não foi possível atualizar a magia.</p>
            </div>
        <?php endif; ?>

        <form class="formulario formulario-registro" action="/Controllers/MagiaController.php" method="POST">
            <input type="hidden" name="acao" value="atualizar">

            <input type="hidden" name="id_magia" value="<?php echo $magia["id_magia"]; ?>"
            >

            <div class="campo">
                <label class="campo__rotulo" for="nome">
                    Nome
                </label>

                <input class="campo__controle" type="text" id="nome" name="nome" maxlength="100" value="<?php
                echo htmlspecialchars(
                    $magia["nome"],
                    ENT_QUOTES,
                    "UTF-8"
                );
                ?>"
                required
                >
            </div>

            <div class="campo">
                <label class="campo__rotulo" for="elemento">
                    Elemento
                </label>

                <input class="campo__controle" type="text" id="elemento" name="elemento" maxlength="50" value="<?php
                echo htmlspecialchars(
                    $magia["elemento"],
                    ENT_QUOTES,
                    "UTF-8"
                );
                ?>"
                required
                >
            </div>

            <div class="campo">
                <label class="campo__rotulo" for="descricao">
                    Descrição
                </label>

                <small class="campo__ajuda">
                    Campo opcional para explicar os efeitos e características
                    da magia.
                </small>

                <textarea class="campo__controle" id="descricao" name="descricao" rows="5"><?php
                echo htmlspecialchars(
                    $magia["descricao"] ?? "",
                    ENT_QUOTES,
                    "UTF-8"
                );
                ?></textarea>
            </div>

            <div class="formulario__acoes">
                <button class="botao botao--primario" type="submit">
                    Salvar alterações
                </button>
            </div>
        </form>
    </main>
</body>

</html>