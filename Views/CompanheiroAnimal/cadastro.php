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

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&amp;family=Source+Sans+3:wght@400;600;700&amp;display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="../../Assets/css/style.css">

    <title>
        Cadastrar companheiro animal — Diário do Caçador
    </title>
</head>

<body>
    <main class="pagina pagina-formulario" aria-labelledby="titulo-pagina">
        <div class="cabecalho-com-acoes cabecalho-com-acoes--titulo-longo">
            <header class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Companheiros de jornada
                </p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    Cadastrar companheiro animal
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Cadastre o tipo de animal. O nome pessoal será definido
                    posteriormente na ficha do personagem.
                </p>
            </header>

            <nav class="acoes-pagina" aria-label="Ações da página">
                <a class="botao botao--secundario" href="/Controllers/CompanheiroAnimalController.php?acao=listar">
                    Ver meus companheiros
                </a>

                <a class="botao botao--secundario" href="../Usuario/painel.php">
                    Voltar ao painel
                </a>
            </nav>
        </div>

        <?php if ($sucesso === "1"): ?>
            <div class="mensagem mensagem--sucesso" role="status">
                <p>Companheiro animal cadastrado com sucesso!</p>
            </div>
        <?php endif; ?>

        <?php if ($erro === "campos"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Preencha o tipo e informe um PV máximo válido.</p>
            </div>
        <?php elseif ($erro === "tipo_longo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>O tipo deve possuir no máximo 100 caracteres.</p>
            </div>
        <?php elseif ($erro === "dano_longo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>O dano deve possuir no máximo 50 caracteres.</p>
            </div>
        <?php elseif ($erro === "cadastro"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Não foi possível cadastrar o companheiro.</p>
            </div>
        <?php endif; ?>

        <form class="formulario formulario-registro" action="../../Controllers/CompanheiroAnimalController.php"
            method="POST">
            <input type="hidden" name="acao" value="cadastrar">

            <div class="campo">
                <label class="campo__rotulo" for="tipo">
                    Tipo de animal
                </label>

                <input class="campo__controle" type="text" id="tipo" name="tipo" maxlength="100"
                    placeholder="Exemplo: Lobo" required>
            </div>

            <div class="formulario__linha formulario__linha--duas-colunas">
                <div class="campo">
                    <label class="campo__rotulo" for="pv_maximo">
                        PV máximo
                    </label>

                    <input class="campo__controle" type="number" id="pv_maximo" name="pv_maximo" min="1" required>
                </div>

                <div class="campo">
                    <label class="campo__rotulo" for="dano">
                        Dano
                    </label>

                    <input class="campo__controle" type="text" id="dano" name="dano" maxlength="50"
                        placeholder="Exemplo: 1d6">
                </div>
            </div>

            <div class="campo">
                <label class="campo__rotulo" for="descricao">
                    Descrição
                </label>

                <small class="campo__ajuda">
                    Campo opcional para características e informações
                    adicionais sobre o animal.
                </small>

                <textarea class="campo__controle" id="descricao" name="descricao" rows="5"></textarea>
            </div>

            <div class="formulario__acoes">
                <button class="botao botao--primario" type="submit">
                    Cadastrar companheiro
                </button>
            </div>
        </form>
    </main>
</body>

</html>