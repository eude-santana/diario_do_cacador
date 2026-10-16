<?php

$erro = $_GET["erro"] ?? "";

$sucesso = $_GET["sucesso"] ?? "";

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&amp;family=Source+Sans+3:wght@400;600;700&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/Assets/css/style.css">

    <title>Login — Diário do Caçador</title>
</head>

<body class="pagina-autenticacao">
    <main class="autenticacao" aria-labelledby="titulo-pagina">
        <section class="cartao cartao--autenticacao">
            <header class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">Diário do Caçador</p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    Entrar
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Acesse suas fichas, profissões e registros de campanha.
                </p>
            </header>

            <?php if ($sucesso === "conta_desativada"): ?>
                <div class="mensagem mensagem--sucesso" role="status">
                    <p>
                        Conta desativada. Seu acesso foi encerrado e o
                        e-mail pode ser utilizado em um novo cadastro.
                    </p>
                </div>
            <?php endif; ?>

            <?php if ($erro === "campos"): ?>
                <div class="mensagem mensagem--erro" role="alert">
                    <p>Preencha todos os campos.</p>
                </div>
            <?php elseif ($erro === "email_invalido"): ?>
                <div class="mensagem mensagem--erro" role="alert">
                    <p>Informe um e-mail válido.</p>
                </div>
            <?php elseif ($erro === "login_invalido"): ?>
                <div class="mensagem mensagem--erro" role="alert">
                    <p>E-mail ou senha incorretos.</p>
                </div>
            <?php elseif ($erro === "usuario_inativo"): ?>
                <div class="mensagem mensagem--erro" role="alert">
                    <p>Este usuário está inativo.</p>
                </div>
            <?php elseif ($erro === "consulta"): ?>
                <div class="mensagem mensagem--erro" role="alert">
                    <p>Não foi possível realizar o login. Tente novamente mais tarde.</p>
                </div>
            <?php endif; ?>

            <form class="formulario" action="../../Controllers/LoginController.php" method="POST">
                <div class="campo">
                    <label class="campo__rotulo" for="email">E-mail</label>

                    <input class="campo__controle" type="email" id="email" name="email" maxlength="255"
                        autocomplete="email" required>
                </div>

                <div class="campo">
                    <label class="campo__rotulo" for="senha">Senha</label>

                    <input class="campo__controle" type="password" id="senha" name="senha"
                        autocomplete="current-password" required>
                </div>

                <button class="botao botao--primario botao--largura-total" type="submit">
                    Entrar
                </button>
            </form>

            <p class="autenticacao__alternativa">
                Ainda não possui uma conta?
                <a href="cadastro.php">Cadastre-se</a>
            </p>
        </section>
    </main>
</body>

</html>