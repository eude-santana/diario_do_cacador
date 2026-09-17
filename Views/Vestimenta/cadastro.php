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
        Cadastrar vestimenta — Diário do Caçador
    </title>
</head>

<body>
    <h1>Cadastrar vestimenta</h1>

    <?php if ($sucesso === "1"): ?>
        <p>Vestimenta cadastrada com sucesso!</p>
    <?php endif; ?>

    <?php if ($erro === "campos"): ?>
        <p>
            Preencha o nome e informe uma proteção máxima válida.
        </p>

    <?php elseif ($erro === "nome_longo"): ?>
        <p>O nome deve possuir no máximo 100 caracteres.</p>

    <?php elseif ($erro === "tipo"): ?>
        <p>Selecione um tipo de vestimenta válido.</p>

    <?php elseif ($erro === "dano_longo"): ?>
        <p>O dano deve possuir no máximo 50 caracteres.</p>

    <?php elseif ($erro === "elemento_longo"): ?>
        <p>O elemento deve possuir no máximo 50 caracteres.</p>

    <?php elseif ($erro === "cadastro"): ?>
        <p>Não foi possível cadastrar a vestimenta.</p>
    <?php endif; ?>

    <form action="../../Controllers/VestimentaController.php" method="POST">
        <input type="hidden" name="acao" value="cadastrar">

        <div>
            <label for="nome">Nome:</label>

            <input type="text" id="nome" name="nome" maxlength="100" required>
        </div>

        <div>
            <label for="tipo">Tipo:</label>

            <select id="tipo" name="tipo" required>
                <option value="">Selecione</option>
                <option value="ARMADURA">Armadura</option>
                <option value="ELMO">Elmo</option>
                <option value="BRACELETES">Braceletes</option>
                <option value="BOTAS">Botas</option>
                <option value="ESCUDO">Escudo</option>
            </select>
        </div>

        <div>
            <label for="pontos_protecao_maximo">
                Pontos de proteção máximos:
            </label>

            <input type="number" id="pontos_protecao_maximo" name="pontos_protecao_maximo" min="1" required>
        </div>

        <div>
            <label for="dano">Dano:</label>

            <input type="text" id="dano" name="dano" maxlength="50" placeholder="Preencha somente quando aplicável">
        </div>

        <div>
            <label for="elemento">Elemento:</label>

            <input type="text" id="elemento" name="elemento" maxlength="50"
                placeholder="Preencha somente quando aplicável">
        </div>

        <div>
            <label for="especial">Especial:</label>

            <textarea id="especial" name="especial" rows="5" cols="40"></textarea>
        </div>

        <button type="submit">
            Cadastrar
        </button>
    </form>

    <p>
        <a href="/Controllers/VestimentaController.php?acao=listar">
            Ver minhas vestimentas
        </a>
    </p>

    <p>
        <a href="../Usuario/painel.php">
            Voltar ao painel
        </a>
    </p>
</body>

</html>