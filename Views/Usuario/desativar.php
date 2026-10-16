<?php

require_once __DIR__ . "/../../Config/Autenticacao.php";

exigirLogin();

$erro = $_GET["erro"] ?? "";
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

    <title>Desativar conta — Diário do Caçador</title>
</head>

<body>
    <main class="pagina pagina-formulario" aria-labelledby="titulo-pagina">
        <header class="cabecalho-com-acoes">
            <div class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Configurações da conta
                </p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    Desativar conta
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Esta ação encerra permanentemente o acesso do usuário.
                </p>
            </div>

            <nav class="acoes-pagina" aria-label="Ações da página">
                <a class="botao botao--secundario" href="/Views/Usuario/painel.php">
                    Voltar ao painel
                </a>
            </nav>
        </header>

        <section class="aviso-desativacao" aria-labelledby="titulo-aviso">
            <h2 class="aviso-desativacao__titulo" id="titulo-aviso">
                O que acontecerá com a conta?
            </h2>

            <ul class="aviso-desativacao__lista">
                <li>A sessão será encerrada imediatamente.</li>
                <li>Não será possível entrar ou reativar esta conta.</li>
                <li>
                    As profissões públicas continuarão disponíveis para
                    consulta. Altere a visibilidade delas antes de
                    continuar caso não queira mantê-las públicas.
                </li>
                <li>
                    Fichas e conteúdos privados permanecerão armazenados,
                    mas não poderão mais ser acessados.
                </li>
                <li>
                    O e-mail será liberado para um novo cadastro, porém a
                    nova conta não recuperará os dados anteriores.
                </li>
            </ul>
        </section>

        <?php if ($erro === "campos" || $erro === "senha"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Informe sua senha atual.</p>
            </div>
        <?php elseif ($erro === "confirmacao"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Confirme que compreendeu as consequências.</p>
            </div>
        <?php elseif ($erro === "senha_incorreta"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>A senha informada está incorreta.</p>
            </div>
        <?php elseif ($erro === "desativacao"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Não foi possível desativar a conta.</p>
            </div>
        <?php endif; ?>

        <form class="formulario" action="/Controllers/UsuarioController.php" method="POST">
            <input type="hidden" name="acao" value="desativar">

            <div class="campo">
                <label class="campo__rotulo" for="senha_atual">
                    Senha atual
                </label>

                <input class="campo__controle" type="password" id="senha_atual" name="senha_atual"
                    autocomplete="current-password" required>
            </div>

            <label class="confirmacao-desativacao" for="confirmar_desativacao">
                <input type="checkbox" id="confirmar_desativacao" name="confirmar_desativacao" value="1" required>

                <span>
                    Compreendo que esta conta não poderá ser reativada e
                    que um novo cadastro não recuperará seus dados.
                </span>
            </label>

            <div class="formulario__acoes">
                <button class="botao botao--perigo" type="submit">
                    Desativar conta permanentemente
                </button>

                <a class="botao botao--secundario" href="/Views/Usuario/painel.php">
                    Cancelar
                </a>
            </div>
        </form>
    </main>
</body>

</html>