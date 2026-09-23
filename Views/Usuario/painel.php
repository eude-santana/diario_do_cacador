<?php

require_once __DIR__ . "/../../Config/Autenticacao.php";

exigirLogin();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Painel — Diário do Caçador</title>
</head>

<body>
    <h1>Diário do Caçador</h1>

    <p>
        Bem-vindo,
        <?php echo htmlspecialchars($_SESSION["nome_usuario"]); ?>!
    </p>

    <p>Login realizado com sucesso.</p>

    <form action="../../Controllers/LogoutController.php" method="POST">
        <button type="submit">Sair</button>
    </form>
</body>

</html>