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

    <title>Editar arma — Diário do Caçador</title>
</head>

<body>
    <h1>Editar arma</h1>

    <?php if ($erro === "nome_longo"): ?>
        <p>O nome deve possuir no máximo 100 caracteres.</p>

    <?php elseif ($erro === "dano_longo"): ?>
        <p>O dano deve possuir no máximo 50 caracteres.</p>

    <?php elseif ($erro === "tipo"): ?>
        <p>Selecione um tipo de arma válido.</p>

    <?php elseif ($erro === "maos"): ?>
        <p>Selecione uma quantidade de mãos válida.</p>

    <?php elseif ($erro === "atualizacao"): ?>
        <p>Não foi possível atualizar a arma.</p>
    <?php endif; ?>

    <form action="/Controllers/ArmaController.php" method="POST">
        <input type="hidden" name="acao" value="atualizar">

        <input type="hidden" name="id_arma" value="<?php echo $arma["id_arma"]; ?>">

        <div>
            <label for="nome">Nome:</label>

            <input type="text" id="nome" name="nome" maxlength="100" value="<?php
            echo htmlspecialchars($arma["nome"]);
            ?>" required>
        </div>

        <div>
            <label for="tipo">Tipo:</label>

            <select id="tipo" name="tipo" required>
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

        <div>
            <label for="maos">Quantidade de mãos:</label>

            <select id="maos" name="maos" required>
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

        <div>
            <label for="dano">Dano:</label>

            <input type="text" id="dano" name="dano" maxlength="50" value="<?php
            echo htmlspecialchars($arma["dano"]);
            ?>" required>
        </div>

        <div>
            <label for="especial">Especial:</label>

            <textarea id="especial" name="especial" rows="5" cols="40"><?php
            echo htmlspecialchars($arma["especial"] ?? "");
            ?></textarea>
        </div>

        <button type="submit">Salvar alterações</button>
    </form>

    <p>
        <a href="/Controllers/ArmaController.php?acao=listar">
            Cancelar
        </a>
    </p>
</body>

</html>