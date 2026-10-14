<?php

require_once __DIR__ . "/../../Config/Autenticacao.php";

exigirLogin();

if (!isset($item) || !$item) {
    header("Location: /Controllers/ItemController.php?acao=listar");
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
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Source+Sans+3:wght@400;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="../../Assets/css/style.css">

    <title>Editar item — Diário do Caçador</title>
</head>

<body>
    <main class="pagina pagina-formulario" aria-labelledby="titulo-pagina">
        <div class="cabecalho-com-acoes">
            <header class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Catálogo de recursos
                </p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    Editar item
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Revise as informações registradas para este item.
                </p>
            </header>

            <nav class="acoes-pagina" aria-label="Ações da página">
                <a class="botao botao--secundario" href="/Controllers/ItemController.php?acao=listar">
                    Cancelar
                </a>
            </nav>
        </div>

        <?php if ($erro === "nome_longo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    O nome informado excede o limite permitido.
                    Use um nome menor.
                </p>
            </div>
        <?php elseif ($erro === "atualizacao"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Não foi possível atualizar o item.</p>
            </div>
        <?php elseif ($erro === "tipo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Selecione um tipo de item válido.</p>
            </div>
        <?php endif; ?>

        <form class="formulario formulario-registro" action="/Controllers/ItemController.php" method="POST">
            <input type="hidden" name="acao" value="atualizar">

            <input type="hidden" name="id_item" value="<?php echo $item["id_item"]; ?>">

            <div class="campo">
                <label class="campo__rotulo" for="nome">
                    Nome
                </label>

                <input class="campo__controle" type="text" id="nome" name="nome" maxlength="100" value="<?php
                echo htmlspecialchars(
                    $item["nome"],
                    ENT_QUOTES,
                    "UTF-8"
                );
                ?>" required>
            </div>

            <div class="campo">
                <label class="campo__rotulo" for="tipo">
                    Tipo
                </label>

                <select class="campo__controle" id="tipo" name="tipo" required>
                    <option value="CONSUMIVEL" <?php
                    if ($item["tipo"] === "CONSUMIVEL") {
                        echo "selected";
                    }
                    ?>>
                        Consumível
                    </option>

                    <option value="MATERIAL" <?php
                    if ($item["tipo"] === "MATERIAL") {
                        echo "selected";
                    }
                    ?>>
                        Material
                    </option>

                    <option value="UTILITARIO" <?php
                    if ($item["tipo"] === "UTILITARIO") {
                        echo "selected";
                    }
                    ?>>
                        Utilitário
                    </option>

                    <option value="OUTRO" <?php
                    if ($item["tipo"] === "OUTRO") {
                        echo "selected";
                    }
                    ?>>
                        Outro
                    </option>
                </select>
            </div>

            <div class="campo">
                <label class="campo__rotulo" for="descricao">
                    Descrição
                </label>

                <small class="campo__ajuda">
                    Campo opcional para informações adicionais sobre o item.
                </small>

                <textarea class="campo__controle" id="descricao" name="descricao" rows="5"><?php
                echo htmlspecialchars(
                    $item["descricao"] ?? "",
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