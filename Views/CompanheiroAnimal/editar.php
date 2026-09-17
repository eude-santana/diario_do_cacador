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

    <title>
        Editar companheiro animal — Diário do Caçador
    </title>
</head>

<body>
    <h1>Editar companheiro animal</h1>

    <p>
        O nome pessoal do animal será definido na ficha do
        personagem.
    </p>

    <?php if ($erro === "tipo_longo"): ?>
        <p>O tipo deve possuir no máximo 100 caracteres.</p>

    <?php elseif ($erro === "dano_longo"): ?>
        <p>O dano deve possuir no máximo 50 caracteres.</p>

    <?php elseif ($erro === "atualizacao"): ?>
        <p>Não foi possível atualizar o companheiro.</p>
    <?php endif; ?>

    <form action="/Controllers/CompanheiroAnimalController.php" method="POST">
        <input type="hidden" name="acao" value="atualizar">

        <input type="hidden" name="id_companheiro" value="<?php
        echo $companheiro["id_companheiro"];
        ?>">

        <div>
            <label for="tipo">Tipo de animal:</label>

            <input type="text" id="tipo" name="tipo" maxlength="100" value="<?php
            echo htmlspecialchars($companheiro["tipo"]);
            ?>" required>
        </div>

        <div>
            <label for="pv_maximo">PV máximo:</label>

            <input type="number" id="pv_maximo" name="pv_maximo" min="1" value="<?php
            echo (int) $companheiro["pv_maximo"];
            ?>" required>
        </div>

        <div>
            <label for="dano">Dano:</label>

            <input type="text" id="dano" name="dano" maxlength="50" value="<?php
            echo htmlspecialchars(
                $companheiro["dano"] ?? ""
            );
            ?>">
        </div>

        <div>
            <label for="descricao">Descrição:</label>

            <textarea id="descricao" name="descricao" rows="5" cols="40"><?php
            echo htmlspecialchars(
                $companheiro["descricao"] ?? ""
            );
            ?></textarea>
        </div>

        <button type="submit">
            Salvar alterações
        </button>
    </form>

    <p>
        <a href="/Controllers/CompanheiroAnimalController.php?acao=listar">
            Cancelar
        </a>
    </p>
</body>

</html>