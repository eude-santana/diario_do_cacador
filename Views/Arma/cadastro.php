<?php

require_once __DIR__ . "/../../Config/Autenticacao.php";

exigirLogin();

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

    <link rel="stylesheet" href="../../Assets/css/style.css">

    <title>Cadastrar arma — Diário do Caçador</title>
</head>

<body>
    <main class="pagina pagina-formulario" aria-labelledby="titulo-pagina">
        <div class="cabecalho-com-acoes">
            <header class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Arsenal do caçador
                </p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    Cadastrar arma
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Registre uma arma que poderá fazer parte do equipamento
                    inicial das profissões.
                </p>
            </header>

            <nav class="acoes-pagina" aria-label="Ações da página">
                <a class="botao botao--secundario" href="/Controllers/ArmaController.php?acao=listar">
                    Ver minhas armas
                </a>

                <a class="botao botao--secundario" href="../Usuario/painel.php">
                    Voltar ao painel
                </a>
            </nav>
        </div>

        <?php if ($sucesso === "1"): ?>
            <div class="mensagem mensagem--sucesso" role="status">
                <p>Arma cadastrada com sucesso!</p>
            </div>
        <?php endif; ?>

        <?php if ($erro === "campos"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Preencha todos os campos obrigatórios.</p>
            </div>
        <?php elseif ($erro === "nome_longo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>O nome deve possuir no máximo 100 caracteres.</p>
            </div>
        <?php elseif ($erro === "dano_longo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>O dano deve possuir no máximo 50 caracteres.</p>
            </div>
        <?php elseif ($erro === "tipo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Selecione um tipo de arma válido.</p>
            </div>
        <?php elseif ($erro === "maos"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Selecione uma quantidade de mãos válida.</p>
            </div>
        <?php elseif ($erro === "cadastro"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Não foi possível cadastrar a arma.</p>
            </div>
        <?php endif; ?>

        <form class="formulario formulario-registro" action="../../Controllers/ArmaController.php" method="POST">
            <input type="hidden" name="acao" value="cadastrar">

            <div class="campo">
                <label class="campo__rotulo" for="nome">
                    Nome
                </label>

                <input class="campo__controle" type="text" id="nome" name="nome" maxlength="100" required>
            </div>

            <div class="formulario__linha formulario__linha--tres-colunas">
                <div class="campo">
                    <label class="campo__rotulo" for="tipo">
                        Tipo
                    </label>

                    <select class="campo__controle" id="tipo" name="tipo" required>
                        <option value="">Selecione</option>
                        <option value="CORPORAL">Corporal</option>
                        <option value="DISTANCIA">Distância</option>
                    </select>
                </div>

                <div class="campo">
                    <label class="campo__rotulo" for="maos">
                        Quantidade de mãos
                    </label>

                    <select class="campo__controle" id="maos" name="maos" required>
                        <option value="">Selecione</option>
                        <option value="1">Uma mão</option>
                        <option value="2">Duas mãos</option>
                    </select>
                </div>

                <div class="campo">
                    <label class="campo__rotulo" for="dano">
                        Dano
                    </label>

                    <input class="campo__controle" type="text" id="dano" name="dano" maxlength="50"
                        placeholder="Exemplo: 1d6" required>
                </div>
            </div>

            <div class="campo">
                <label class="campo__rotulo" for="especial">
                    Especial
                </label>

                <small class="campo__ajuda">
                    Campo opcional para efeitos ou propriedades especiais.
                </small>

                <textarea class="campo__controle" id="especial" name="especial" rows="5"></textarea>
            </div>

            <div class="formulario__acoes">
                <button class="botao botao--primario" type="submit">
                    Cadastrar arma
                </button>
            </div>
        </form>
    </main>
</body>

</html>