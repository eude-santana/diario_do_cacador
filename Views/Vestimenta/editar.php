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

    <title>
        Editar vestimenta — Diário do Caçador
    </title>
</head>

<body>
    <h1>Editar vestimenta</h1>

    <?php if ($erro === "nome_longo"): ?>
        <p>O nome deve possuir no máximo 100 caracteres.</p>

    <?php elseif ($erro === "tipo"): ?>
        <p>Selecione um tipo de vestimenta válido.</p>

    <?php elseif ($erro === "dano_longo"): ?>
        <p>O dano deve possuir no máximo 50 caracteres.</p>

    <?php elseif ($erro === "elemento_longo"): ?>
        <p>O elemento deve possuir no máximo 50 caracteres.</p>

    <?php elseif ($erro === "atualizacao"): ?>
        <p>Não foi possível atualizar a vestimenta.</p>
    <?php endif; ?>

    <form action="/Controllers/VestimentaController.php" method="POST">
        <input type="hidden" name="acao" value="atualizar">

        <input type="hidden" name="id_vestimenta" value="<?php
        echo $vestimenta["id_vestimenta"];
        ?>">

        <div>
            <label for="nome">Nome:</label>

            <input type="text" id="nome" name="nome" maxlength="100" value="<?php
            echo htmlspecialchars(
                $vestimenta["nome"],
                ENT_QUOTES,
                "UTF-8"
            );
            ?>" required>
        </div>

        <div>
            <label for="tipo">Tipo:</label>

            <select id="tipo" name="tipo" required>
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

        <div>
            <label for="pontos_protecao_maximo">
                Pontos de proteção máximos:
            </label>

            <input type="number" id="pontos_protecao_maximo" name="pontos_protecao_maximo" min="1" value="<?php
            echo (int) $vestimenta[
                "pontos_protecao_maximo"
            ];
            ?>" required>
        </div>

        <div>
            <label for="dano">Dano:</label>

            <input type="text" id="dano" name="dano" maxlength="50" value="<?php
            echo htmlspecialchars(
                $vestimenta["dano"] ?? "",
                ENT_QUOTES,
                "UTF-8"
            );
            ?>">
        </div>

        <div>
            <label for="elemento">Elemento:</label>

            <input type="text" id="elemento" name="elemento" maxlength="50" value="<?php
            echo htmlspecialchars(
                $vestimenta["elemento"] ?? "",
                ENT_QUOTES,
                "UTF-8"
            );
            ?>">
        </div>

        <div>
            <label for="especial">Especial:</label>

            <textarea id="especial" name="especial" rows="5" cols="40"><?php
            echo htmlspecialchars(
                $vestimenta["especial"] ?? "",
                ENT_QUOTES,
                "UTF-8"
            );
            ?></textarea>
        </div>

        <button type="submit">
            Salvar alterações
        </button>
    </form>

    <p>
        <a href="/Controllers/VestimentaController.php?acao=listar">
            Cancelar
        </a>
    </p>
</body>

</html>