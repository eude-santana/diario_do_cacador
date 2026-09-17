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

    <title>Editar magia — Diário do Caçador</title>
</head>

<body>
    <h1>Editar magia</h1>

    <?php if ($erro === "nome_longo"): ?>
        <p>O nome deve possuir no máximo 100 caracteres.</p>

    <?php elseif ($erro === "elemento_longo"): ?>
        <p>O elemento deve possuir no máximo 50 caracteres.</p>

    <?php elseif ($erro === "atualizacao"): ?>
        <p>Não foi possível atualizar a magia.</p>
    <?php endif; ?>

    <form action="/Controllers/MagiaController.php" method="POST">
        <input type="hidden" name="acao" value="atualizar">

        <input type="hidden" name="id_magia" value="<?php echo $magia["id_magia"]; ?>">

        <div>
            <label for="nome">Nome:</label>

            <input type="text" id="nome" name="nome" maxlength="100" value="<?php
            echo htmlspecialchars($magia["nome"]);
            ?>" required>
        </div>

        <div>
            <label for="elemento">Elemento:</label>

            <input type="text" id="elemento" name="elemento" maxlength="50" value="<?php
            echo htmlspecialchars($magia["elemento"]);
            ?>" required>
        </div>

        <div>
            <label for="descricao">Descrição:</label>

            <textarea id="descricao" name="descricao" rows="5" cols="40"><?php
            echo htmlspecialchars($magia["descricao"] ?? "");
            ?></textarea>
        </div>

        <button type="submit">Salvar alterações</button>
    </form>

    <p>
        <a href="/Controllers/MagiaController.php?acao=listar">
            Cancelar
        </a>
    </p>
</body>

</html>