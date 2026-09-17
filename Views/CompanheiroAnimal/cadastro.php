<?php

require_once __DIR__
    . "/../../Config/Autenticacao.php";

exigirLogin();

$erro = $_GET["erro"] ?? "";
$sucesso = $_GET["sucesso"] ?? "";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Cadastrar companheiro animal — Diário do Caçador
    </title>
</head>

<body>
    <h1>Cadastrar companheiro animal</h1>

    <p>
        Cadastre o tipo de animal. O nome pessoal será definido
        posteriormente na ficha do personagem.
    </p>

    <?php if ($sucesso === "1"): ?>
        <p>Companheiro animal cadastrado com sucesso!</p>
    <?php endif; ?>

    <?php if ($erro === "campos"): ?>
        <p>Preencha o tipo e informe um PV máximo válido.</p>

    <?php elseif ($erro === "tipo_longo"): ?>
        <p>O tipo deve possuir no máximo 100 caracteres.</p>

    <?php elseif ($erro === "dano_longo"): ?>
        <p>O dano deve possuir no máximo 50 caracteres.</p>

    <?php elseif ($erro === "cadastro"): ?>
        <p>Não foi possível cadastrar o companheiro.</p>
    <?php endif; ?>

    <form action="../../Controllers/CompanheiroAnimalController.php" method="POST">
        <input type="hidden" name="acao" value="cadastrar">

        <div>
            <label for="tipo">Tipo de animal:</label>

            <input type="text" id="tipo" name="tipo" maxlength="100" placeholder="Exemplo: Lobo" required>
        </div>

        <div>
            <label for="pv_maximo">PV máximo:</label>

            <input type="number" id="pv_maximo" name="pv_maximo" min="1" required>
        </div>

        <div>
            <label for="dano">Dano:</label>

            <input type="text" id="dano" name="dano" maxlength="50" placeholder="Exemplo: 1d6">
        </div>

        <div>
            <label for="descricao">Descrição:</label>

            <textarea id="descricao" name="descricao" rows="5" cols="40"></textarea>
        </div>

        <button type="submit">
            Cadastrar
        </button>
    </form>

    <p>
        <a href="/Controllers/CompanheiroAnimalController.php?acao=listar">
            Ver meus companheiros
        </a>
    </p>

    <p>
        <a href="../Usuario/painel.php">
            Voltar ao painel
        </a>
    </p>
</body>

</html>