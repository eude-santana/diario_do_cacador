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

    <title>Editar item — Diário do Caçador</title>
</head>

<body>
    <h1>Editar item</h1>

    <?php if ($erro === "nome_longo"): ?>
        <p>O nome informado excede o limite permitido. Use um nome menor.</p>
    <?php elseif ($erro === "atualizacao"): ?>
        <p>Não foi possível atualizar o item.</p>
    <?php elseif ($erro === "tipo"): ?>
        <p>Selecione um tipo de item válido.</p>
    <?php endif; ?>

    <form action="/Controllers/ItemController.php" method="POST">
        <input type="hidden" name="acao" value="atualizar">

        <input type="hidden" name="id_item" value="<?php echo $item["id_item"]; ?>">

        <div>
            <label for="nome">Nome:</label>

            <input type="text" id="nome" name="nome" maxlength="100"
                value="<?php echo htmlspecialchars($item["nome"]); ?>" required>
        </div>

        <div>
            <label for="tipo">Tipo:</label>

            <select id="tipo" name="tipo" required>
                <option value="CONSUMIVEL" <?php
                if ($item["tipo"] === "CONSUMIVEL") {
                    echo "selected";
                } ?>>Consumível
                </option>

                <option value="MATERIAL" <?php
                if ($item["tipo"] === "MATERIAL") {
                    echo "selected";
                } ?>>Material
                </option>

                <option value="UTILITARIO" <?php
                if ($item["tipo"] === "UTILITARIO") {
                    echo "selected";
                } ?>>Utilitário
                </option>

                <option value="OUTRO" <?php
                if ($item["tipo"] === "OUTRO") {
                    echo "selected";
                } ?>>Outro
                </option>
            </select>
        </div>

        <div>
            <label for="descricao">Descrição:</label>

            <textarea id="descricao" name="descricao" rows="5"
                cols="40"><?php echo htmlspecialchars($item["descricao"] ?? ""); ?></textarea>
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