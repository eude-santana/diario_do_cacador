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

    <title>Cadastrar arma — Diário do Caçador</title>
</head>

<body>
    <h1>Cadastrar arma</h1>

    <?php if ($sucesso === "1"): ?>
        <p>Arma cadastrada com sucesso!</p>
    <?php endif; ?>

    <?php if ($erro === "campos"): ?>
        <p>Preencha todos os campos obrigatórios.</p>

    <?php elseif ($erro === "nome_longo"): ?>
        <p>O nome deve possuir no máximo 100 caracteres.</p>

    <?php elseif ($erro === "dano_longo"): ?>
        <p>O dano deve possuir no máximo 50 caracteres.</p>

    <?php elseif ($erro === "tipo"): ?>
        <p>Selecione um tipo de arma válido.</p>

    <?php elseif ($erro === "maos"): ?>
        <p>Selecione uma quantidade de mãos válida.</p>

    <?php elseif ($erro === "cadastro"): ?>
        <p>Não foi possível cadastrar a arma.</p>
    <?php endif; ?>

    <form action="../../Controllers/ArmaController.php" method="POST">
        <input type="hidden" name="acao" value="cadastrar">

        <div>
            <label for="nome">Nome:</label>

            <input type="text" id="nome" name="nome" maxlength="100" required>
        </div>

        <div>
            <label for="tipo">Tipo:</label>

            <select id="tipo" name="tipo" required>
                <option value="">Selecione</option>
                <option value="CORPORAL">Corporal</option>
                <option value="DISTANCIA">Distância</option>
            </select>
        </div>

        <div>
            <label for="maos">Quantidade de mãos:</label>

            <select id="maos" name="maos" required>
                <option value="">Selecione</option>
                <option value="1">Uma mão</option>
                <option value="2">Duas mãos</option>
            </select>
        </div>

        <div>
            <label for="dano">Dano:</label>

            <input type="text" id="dano" name="dano" maxlength="50" placeholder="Exemplo: 1d6" required>
        </div>

        <div>
            <label for="especial">Especial:</label>

            <textarea id="especial" name="especial" rows="5" cols="40"></textarea>
        </div>

        <button type="submit">Cadastrar</button>
    </form>

    <p>
        <a href="/Controllers/ArmaController.php?acao=listar">
            Ver minhas armas
        </a>
    </p>

    <p>
        <a href="../Usuario/painel.php">
            Voltar ao painel
        </a>
    </p>
</body>

</html>