<?php

$erro = $_GET["erro"] ?? "";
$sucesso = $_GET["sucesso"] ?? "";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro — Diário do Caçador</title>
</head>

<body>
    <h1>Criar conta</h1>

    <?php if ($sucesso === "1"): ?>
        <p>Usuário cadastrado com sucesso!</p>
    <?php endif; ?>

    <?php if ($erro === "campos"): ?>
        <p>Preencha todos os campos.</p>
    <?php elseif ($erro === "email_invalido"): ?>
        <p>Informe um e-mail válido.</p>
    <?php elseif ($erro === "senha_curta"): ?>
        <p>A senha deve possuir pelo menos 6 caracteres.</p>
    <?php elseif ($erro === "email_cadastrado"): ?>
        <p>Este e-mail já está cadastrado.</p>
    <?php elseif ($erro === "cadastro"): ?>
        <p>Não foi possível realizar o cadastro.</p>
    <?php endif; ?>

    <form action="../../Controllers/UsuarioController.php" method="POST">
        <div>
            <label for="nome">Nome:</label>

            <input
                type="text"
                id="nome"
                name="nome"
                maxlength="100"
                required
            >
        </div>

        <div>
            <label for="email">E-mail:</label>

            <input
                type="email"
                id="email"
                name="email"
                maxlength="255"
                required
            >
        </div>

        <div>
            <label for="senha">Senha:</label>

            <input
                type="password"
                id="senha"
                name="senha"
                minlength="6"
                required
            >
        </div>

        <button type="submit">Cadastrar</button>
    </form>
</body>

</html>