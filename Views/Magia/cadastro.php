<?php

require_once __DIR__ . "/../../Config/Autenticacao.php";

exigirLogin();

$erro = $_GET["erro"] ?? "";
$sucesso = $_GET["sucesso"] ?? "";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastrar magia — Diário do Caçador</title>
</head>

<body>
    <h1>Cadastrar magia</h1>

    <?php if ($sucesso === "1"): ?>
        <p>Magia cadastrada com sucesso!</p>
    <?php endif; ?>

    <?php if ($erro === "campos"): ?>
        <p>Preencha o nome e o elemento da magia.</p>

    <?php elseif ($erro === "nome_longo"): ?>
        <p>O nome deve possuir no máximo 100 caracteres.</p>

    <?php elseif ($erro === "elemento_longo"): ?>
        <p>O elemento deve possuir no máximo 50 caracteres.</p>

    <?php elseif ($erro === "cadastro"): ?>
        <p>Não foi possível cadastrar a magia.</p>
    <?php endif; ?>

    <form action="../../Controllers/MagiaController.php" method="POST">
        <input type="hidden" name="acao" value="cadastrar">

        <div>
            <label for="nome">Nome:</label>

            <input type="text" id="nome" name="nome" maxlength="100" required>
        </div>

        <div>
            <label for="elemento">Elemento:</label>

            <input type="text" id="elemento" name="elemento" maxlength="50" placeholder="Exemplo: Fogo" required>
        </div>

        <div>
            <label for="descricao">Descrição:</label>

            <textarea id="descricao" name="descricao" rows="5" cols="40"></textarea>
        </div>

        <button type="submit">Cadastrar</button>
    </form>

    <p>
        <a href="/Controllers/MagiaController.php?acao=listar">
            Ver minhas magias
        </a>
    </p>

    <p>
        <a href="../Usuario/painel.php">
            Voltar ao painel
        </a>
    </p>
</body>

</html>