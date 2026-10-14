<?php

if (!isset($companheiro)) {
    header(
        "Location: /Controllers/"
        . "CompanheiroAnimalController.php?acao=listar"
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

    <link rel="stylesheet" href="/Assets/css/style.css">

    <title>
        Editar companheiro animal — Diário do Caçador
    </title>
</head>

<body>
    <main class="pagina pagina-formulario" aria-labelledby="titulo-pagina">
        <div class="cabecalho-com-acoes
                   cabecalho-com-acoes--titulo-longo">
            <header class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Companheiros de jornada
                </p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    Editar companheiro animal
                </h1>

                <p class="cabecalho-pagina__descricao">
                    O nome pessoal do animal será definido na
                    ficha do personagem.
                </p>
            </header>

            <nav class="acoes-pagina" aria-label="Ações da página">
                <a class="botao botao--secundario" href="/Controllers/CompanheiroAnimalController.php?acao=listar">
                    Cancelar
                </a>
            </nav>
        </div>

        <?php if ($erro === "tipo_longo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    O tipo deve possuir no máximo 100 caracteres.
                </p>
            </div>

        <?php elseif ($erro === "dano_longo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    O dano deve possuir no máximo 50 caracteres.
                </p>
            </div>

        <?php elseif ($erro === "atualizacao"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    Não foi possível atualizar o companheiro.
                </p>
            </div>
        <?php endif; ?>

        <form class="formulario formulario-registro" action="/Controllers/CompanheiroAnimalController.php"
            method="POST">
            <input type="hidden" name="acao" value="atualizar">

            <input type="hidden" name="id_companheiro" value="<?php echo $companheiro["id_companheiro"]; ?>">

            <div class="campo">
                <label class="campo__rotulo" for="tipo">
                    Tipo de animal:
                </label>

                <input class="campo__controle" type="text" id="tipo" name="tipo" maxlength="100" value="<?php
                echo htmlspecialchars(
                    $companheiro["tipo"],
                    ENT_QUOTES,
                    "UTF-8"
                );
                ?>" required>
            </div>

            <div class="formulario__linha
                       formulario__linha--duas-colunas">
                <div class="campo">
                    <label class="campo__rotulo" for="pv_maximo">
                        PV máximo:
                    </label>

                    <input class="campo__controle" type="number" id="pv_maximo" name="pv_maximo" min="1" value="<?php
                    echo (int) $companheiro["pv_maximo"];
                    ?>" required>
                </div>

                <div class="campo">
                    <label class="campo__rotulo" for="dano">
                        Dano:
                    </label>

                    <input class="campo__controle" type="text" id="dano" name="dano" maxlength="50" value="<?php
                    echo htmlspecialchars(
                        $companheiro["dano"] ?? "",
                        ENT_QUOTES,
                        "UTF-8"
                    );
                    ?>">
                </div>
            </div>

            <div class="campo">
                <label class="campo__rotulo" for="descricao">
                    Descrição:
                </label>

                <textarea class="campo__controle" id="descricao" name="descricao" rows="5"><?php
                echo htmlspecialchars(
                    $companheiro["descricao"] ?? "",
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