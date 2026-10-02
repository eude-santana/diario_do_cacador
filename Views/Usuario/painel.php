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
        <?php echo htmlspecialchars(
            $_SESSION["nome_usuario"] ?? "",
            ENT_QUOTES,
            "UTF-8"
        ); ?>!
    </p>

    <p>Login realizado com sucesso.</p>

    <nav>
        <h2>Menu</h2>

        <ul>
            <li>
                <a href="/Controllers/FichaController.php?acao=listar">
                    Minhas fichas
                </a>
            </li>

            <li>
                <a href="/Controllers/ProfissaoController.php?acao=listar">
                    Minhas profissões
                </a>
            </li>

            <li>
                <a href="/Controllers/ProfissaoController.php?acao=publicas">
                    Profissões públicas
                </a>
            </li>

            <li>
                <a href="/Controllers/VantagemController.php?acao=listar">
                    Vantagens
                </a>
            </li>

            <li>
                <a href="/Controllers/ArmaController.php?acao=listar">
                    Armas
                </a>
            </li>

            <li>
                <a href="/Controllers/VestimentaController.php?acao=listar">
                    Vestimentas
                </a>
            </li>

            <li>
                <a href="/Controllers/ItemController.php?acao=listar">
                    Itens
                </a>
            </li>

            <li>
                <a href="/Controllers/MagiaController.php?acao=listar">
                    Magias
                </a>
            </li>

            <li>
                <a href="/Controllers/CompanheiroAnimalController.php?acao=listar">
                    Companheiros animais
                </a>
            </li>
        </ul>
    </nav>

    <form action="/Controllers/LogoutController.php" method="POST">
        <button type="submit">Sair</button>
    </form>
</body>

</html>