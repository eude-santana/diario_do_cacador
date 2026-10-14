<?php

if (!isset($vestimenta)) {
    header(
        "Location: /Controllers/"
        . "VestimentaController.php?acao=listar"
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
        Editar vestimenta — Diário do Caçador
    </title>
</head>

<body>
    <main class="pagina pagina-formulario" aria-labelledby="titulo-pagina">
        <div class="cabecalho-com-acoes">
            <header class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Equipamentos de proteção
                </p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    Editar vestimenta
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Atualize as características e os efeitos
                    deste equipamento.
                </p>
            </header>

            <nav class="acoes-pagina" aria-label="Ações da página">
                <a class="botao botao--secundario" href="/Controllers/VestimentaController.php?acao=listar">
                    Cancelar
                </a>
            </nav>
        </div>

        <?php if ($erro === "nome_longo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    O nome deve possuir no máximo 100 caracteres.
                </p>
            </div>

        <?php elseif ($erro === "tipo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    Selecione um tipo de vestimenta válido.
                </p>
            </div>

        <?php elseif ($erro === "dano_longo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    O dano deve possuir no máximo 50 caracteres.
                </p>
            </div>

        <?php elseif ($erro === "elemento_longo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    O elemento deve possuir no máximo
                    50 caracteres.
                </p>
            </div>

        <?php elseif ($erro === "atualizacao"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    Não foi possível atualizar a vestimenta.
                </p>
            </div>
        <?php endif; ?>

        <form class="formulario formulario-registro" action="/Controllers/VestimentaController.php" method="POST">
            <input type="hidden" name="acao" value="atualizar">

            <input type="hidden" name="id_vestimenta" value="<?php
            echo $vestimenta["id_vestimenta"];
            ?>">

            <div class="campo">
                <label class="campo__rotulo" for="nome">
                    Nome:
                </label>

                <input class="campo__controle" type="text" id="nome" name="nome" maxlength="100" value="<?php
                echo htmlspecialchars(
                    $vestimenta["nome"],
                    ENT_QUOTES,
                    "UTF-8"
                );
                ?>" required>
            </div>

            <div class="formulario__linha
                       formulario__linha--duas-colunas">
                <div class="campo">
                    <label class="campo__rotulo" for="tipo">
                        Tipo:
                    </label>

                    <select class="campo__controle" id="tipo" name="tipo" required>
                        <option value="ARMADURA" <?php
                        if ($vestimenta["tipo"] === "ARMADURA") {
                            echo "selected";
                        }
                        ?>>
                            Armadura
                        </option>

                        <option value="ELMO" <?php
                        if ($vestimenta["tipo"] === "ELMO") {
                            echo "selected";
                        }
                        ?>>
                            Elmo
                        </option>

                        <option value="BRACELETES" <?php
                        if ($vestimenta["tipo"] === "BRACELETES") {
                            echo "selected";
                        }
                        ?>>
                            Braceletes
                        </option>

                        <option value="BOTAS" <?php
                        if ($vestimenta["tipo"] === "BOTAS") {
                            echo "selected";
                        }
                        ?>>
                            Botas
                        </option>

                        <option value="ESCUDO" <?php
                        if ($vestimenta["tipo"] === "ESCUDO") {
                            echo "selected";
                        }
                        ?>>
                            Escudo
                        </option>
                    </select>
                </div>

                <div class="campo">
                    <label class="campo__rotulo" for="pontos_protecao_maximo">
                        Pontos de proteção máximos:
                    </label>

                    <input class="campo__controle" type="number" id="pontos_protecao_maximo"
                        name="pontos_protecao_maximo" min="1" value="<?php
                        echo (int) $vestimenta[
                            "pontos_protecao_maximo"
                        ];
                        ?>" required>
                </div>
            </div>

            <div class="formulario__linha
                       formulario__linha--duas-colunas">
                <div class="campo">
                    <label class="campo__rotulo" for="dano">
                        Dano:
                    </label>

                    <input class="campo__controle" type="text" id="dano" name="dano" maxlength="50" value="<?php
                    echo htmlspecialchars(
                        $vestimenta["dano"] ?? "",
                        ENT_QUOTES,
                        "UTF-8"
                    );
                    ?>">
                </div>

                <div class="campo">
                    <label class="campo__rotulo" for="elemento">
                        Elemento:
                    </label>

                    <input class="campo__controle" type="text" id="elemento" name="elemento" maxlength="50" value="<?php
                    echo htmlspecialchars(
                        $vestimenta["elemento"] ?? "",
                        ENT_QUOTES,
                        "UTF-8"
                    );
                    ?>">
                </div>
            </div>

            <div class="campo">
                <label class="campo__rotulo" for="especial">
                    Especial:
                </label>

                <textarea class="campo__controle" id="especial" name="especial" rows="5"><?php
                echo htmlspecialchars(
                    $vestimenta["especial"] ?? "",
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