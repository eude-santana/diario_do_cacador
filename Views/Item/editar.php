<?php

$erro = $_GET["erro"] ?? "";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Editar item — Diário do Caçador</title>
</head>

<body>
    <h1>Editar item</h1>

    <?php if ($erro === "nome_longo"): ?>
        <p>O nome deve possuir no máximo 100 caracteres.</p>
    <?php elseif ($erro === "atualizacao"): ?>
        <p>Não foi possível atualizar o item.</p>
    <?php endif; ?>

    <form action="/Controllers/ItemController.php" method="POST">
        <input type="hidden" name="acao" value="atualizar">

        <input
            type="hidden"
            name="id_item"
            value="<?php echo $item["id_item"]; ?>"
        >

        <div>
            <label for="nome">Nome:</label>

            <input
                type="text"
                id="nome"
                name="nome"
                maxlength="100"
                value="<?php echo htmlspecialchars($item["nome"]); ?>"
                required
            >
        </div>

        <div>
            <label for="descricao">Descrição:</label>

            <textarea
                id="descricao"
                name="descricao"
                rows="5"
                cols="40"
            ><?php echo htmlspecialchars($item["descricao"] ?? ""); ?></textarea>
        </div>

        <button type="submit">Salvar alterações</button>
    </form>

    <p>
        <a href="/Controllers/ItemController.php?acao=listar">
            Cancelar
        </a>
    </p>
</body>

</html>