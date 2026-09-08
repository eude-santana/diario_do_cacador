<?php

$erro = $_GET["erro"] ?? "";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login — Diário do Caçador</title>
</head>

<body>
    <h1>Entrar</h1>

    <?php if ($erro === "campos"): ?>
        <p>Preencha todos os campos.</p>
    <?php elseif ($erro === "email_invalido"): ?>
        <p>Informe um e-mail válido.</p>
    <?php elseif ($erro === "login_invalido"): ?>
        <p>E-mail ou senha incorretos.</p>
    <?php elseif ($erro === "usuario_inativo"): ?>
        <p>Este usuário está inativo.</p>
    <?php endif; ?>

    <form action="../../Controllers/LoginController.php" method="POST">
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
                required
            >
        </div>

        <button type="submit">Entrar</button>
    </form>

    <p>
        Ainda não possui uma conta?
        <a href="cadastro.php">Cadastre-se</a>
    </p>
</body>

</html>