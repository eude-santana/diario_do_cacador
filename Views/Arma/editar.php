<?php

if (!isset($arma)) {
    header(
        "Location: /Controllers/ArmaController.php?acao=listar"
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

    <title>Editar arma — Diário do Caçador</title>
</head>

<body>
    <main class="pagina pagina-formulario" aria-labelledby="titulo-pagina">
        <div class="cabecalho-com-acoes">
            <header class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Arsenal do caçador
                </p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    Editar arma
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Revise as características registradas para esta arma.
                </p>
            </header>

            <nav class="acoes-pagina" aria-label="Ações da página">
                <a class="botao botao--secundario" href="/Controllers/ArmaController.php?acao=listar">
                    Cancelar
                </a>
            </nav>
        </div>

        <?php if ($erro === "nome_longo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>O nome deve possuir no máximo 100 caracteres.</p>
            </div>
        <?php elseif ($erro === "dano_longo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>O dano deve possuir no máximo 50 caracteres.</p>
            </div>
        <?php elseif ($erro === "tipo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Selecione um tipo de arma válido.</p>
            </div>
        <?php elseif ($erro === "maos"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Selecione uma quantidade de mãos válida.</p>
            </div>
        <?php elseif ($erro === "atualizacao"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Não foi possível atualizar a arma.</p>
            </div>
        <?php endif; ?>

        <form class="formulario formulario-registro" action="/Controllers/ArmaController.php" method="POST">
            <input type="hidden" name="acao" value="atualizar">

            <input type="hidden" name="id_arma" value="<?php echo $arma["id_arma"]; ?>">

            <div class="campo">
                <label class="campo__rotulo" for="nome">
                    Nome
                </label>

                <input class="campo__controle" type="text" id="nome" name="nome" maxlength="100" value="<?php
                echo htmlspecialchars(
                    $arma["nome"],
                    ENT_QUOTES,
                    "UTF-8"
                );
                ?>" required>
            </div>

            <div class="formulario__linha formulario__linha--tres-colunas">
                <div class="campo">
                    <label class="campo__rotulo" for="tipo">
                        Tipo
                    </label>

                    <select class="campo__controle" id="tipo" name="tipo" required>
                        <option value="CORPORAL" <?php
                        if ($arma["tipo"] === "CORPORAL") {
                            echo "selected";
                        }
                        ?>>
                            Corporal
                        </option>

                        <option value="DISTANCIA" <?php
                        if ($arma["tipo"] === "DISTANCIA") {
                            echo "selected";
                        }
                        ?>>
                            Distância
                        </option>
                    </select>
                </div>

                <div class="campo">
                    <label class="campo__rotulo" for="maos">
                        Quantidade de mãos
                    </label>

                    <select class="campo__controle" id="maos" name="maos" required>
                        <option value="1" <?php
                        if ((int) $arma["maos"] === 1) {
                            echo "selected";
                        }
                        ?>>
                            Uma mão
                        </option>

                        <option value="2" <?php
                        if ((int) $arma["maos"] === 2) {
                            echo "selected";
                        }
                        ?>>
                            Duas mãos
                        </option>
                    </select>
                </div>

                <div class="campo">
                    <label class="campo__rotulo" for="dano">
                        Dano
                    </label>

                    <input class="campo__controle" type="text" id="dano" name="dano" maxlength="50" value="<?php
                    echo htmlspecialchars(
                        $arma["dano"],
                        ENT_QUOTES,
                        "UTF-8"
                    );
                    ?>" required>
                </div>
            </div>

            <div class="campo">
                <label class="campo__rotulo" for="especial">
                    Especial
                </label>

                <small class="campo__ajuda">
                    Campo opcional para efeitos ou propriedades especiais.
                </small>

                <textarea class="campo__controle" id="especial" name="especial" rows="5"><?php
                echo htmlspecialchars(
                    $arma["especial"] ?? "",
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