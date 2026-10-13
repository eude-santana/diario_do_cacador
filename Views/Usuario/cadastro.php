<?php

$erro = $_GET["erro"] ?? "";
$sucesso = $_GET["sucesso"] ?? "";
$errosExibidos = [
    "campos",
    "nome_invalido",
    "email_invalido",
    "senha_curta",
    "email_cadastrado",
    "cadastro"
];
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

    <title>Cadastro — Diário do Caçador</title>
</head>

<body class="pagina-autenticacao">
    <main class="autenticacao" aria-labelledby="titulo-pagina">
        <section class="cartao cartao--autenticacao">
            <header class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">Diário do Caçador</p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    Criar conta
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Crie seu acesso para organizar personagens e recursos de campanha.
                </p>
            </header>

            <?php if ($sucesso === "1"): ?>
                <div class="mensagem mensagem--sucesso" role="status">
                    <p>Usuário cadastrado com sucesso!</p>
                </div>
            <?php endif; ?>

            <?php if (in_array($erro, $errosExibidos, true)): ?>
                <div class="mensagem mensagem--erro" role="alert">
                    <?php if ($erro === "campos"): ?>
                        <p>Preencha todos os campos.</p>
                    <?php elseif ($erro === "nome_invalido"): ?>
                        <p>O nome informado excede o limite permitido. Use um nome menor.</p>
                    <?php elseif ($erro === "email_invalido"): ?>
                        <p>Informe um e-mail válido dentro do limite de 255 caracteres.</p>
                    <?php elseif ($erro === "senha_curta"): ?>
                        <p>A senha informada é muito curta.</p>
                    <?php elseif ($erro === "email_cadastrado"): ?>
                        <p>Este e-mail já está cadastrado.</p>
                    <?php elseif ($erro === "cadastro"): ?>
                        <p>Não foi possível realizar o cadastro.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <form class="formulario" action="../../Controllers/UsuarioController.php" method="POST">
                <div class="campo">
                    <label class="campo__rotulo" for="nome">Nome</label>

                    <input class="campo__controle" type="text" id="nome" name="nome" maxlength="100" autocomplete="name"
                        required>
                </div>

                <div class="campo">
                    <label class="campo__rotulo" for="email">E-mail</label>

                    <input class="campo__controle" type="email" id="email" name="email" maxlength="255"
                        autocomplete="email" required>
                </div>

                <div class="campo">
                    <label class="campo__rotulo" for="senha">Senha</label>

                    <input class="campo__controle" type="password" id="senha" name="senha" minlength="6"
                        autocomplete="new-password" aria-describedby="senha-ajuda" required>

                    <small class="campo__ajuda" id="senha-ajuda">
                        Use pelo menos 6 caracteres.
                    </small>
                </div>

                <button class="botao botao--primario botao--largura-total" type="submit">
                    Cadastrar
                </button>
            </form>

            <p class="autenticacao__alternativa">
                Já possui uma conta?
                <a href="login.php">Entrar</a>
            </p>
        </section>
    </main>
</body>

</html>