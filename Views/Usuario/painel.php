// arquivo temporario para testar a sessão do usuário
<?php

require_once __DIR__ . "/../../Config/Autenticacao.php";

exigirLogin();
?>

<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}
?>

<form action="../../Controllers/LogoutController.php" method="POST">
    <button type="submit">Sair</button>
</form>

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
</body>

</html>