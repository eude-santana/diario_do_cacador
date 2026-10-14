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

    <link rel="stylesheet" href="/Assets/css/style.css">

    <title>
        Cadastrar vestimenta — Diário do Caçador
    </title>
</head>

<body>
    <main class="pagina pagina-formulario" aria-labelledby="titulo-pagina">
        <div class="cabecalho-com-acoes
                   cabecalho-com-acoes--muitas-acoes">
            <header class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Equipamentos de proteção
                </p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    Cadastrar vestimenta
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Registre armaduras e outros equipamentos
                    utilizados pelos personagens.
                </p>
            </header>

            <nav class="acoes-pagina" aria-label="Ações da página">
                <a class="botao botao--secundario" href="/Controllers/VestimentaController.php?acao=listar">
                    Minhas vestimentas
                </a>

                <a class="botao botao--secundario" href="../Usuario/painel.php">
                    Voltar ao painel
                </a>
            </nav>
        </div>

        <?php if ($sucesso === "1"): ?>
            <div class="mensagem mensagem--sucesso" role="status">
                <p>Vestimenta cadastrada com sucesso!</p>
            </div>
        <?php endif; ?>

        <?php if ($erro === "campos"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    Preencha o nome e informe uma proteção
                    máxima válida.
                </p>
            </div>

        <?php elseif ($erro === "nome_longo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    O nome deve possuir no máximo 100 caracteres.
                </p>
            </div>

        <?php elseif ($erro === "tipo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    Selecione um tipo de vestimenta válido.
                </p>
            </div>

        <?php elseif ($erro === "dano_longo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    O dano deve possuir no máximo 50 caracteres.
                </p>
            </div>

        <?php elseif ($erro === "elemento_longo"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    O elemento deve possuir no máximo
                    50 caracteres.
                </p>
            </div>

        <?php elseif ($erro === "cadastro"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    Não foi possível cadastrar a vestimenta.
                </p>
            </div>
        <?php endif; ?>

        <form class="formulario formulario-registro" action="../../Controllers/VestimentaController.php" method="POST">
            <input type="hidden" name="acao" value="cadastrar">

            <div class="campo">
                <label class="campo__rotulo" for="nome">
                    Nome:
                </label>

                <input class="campo__controle" type="text" id="nome" name="nome" maxlength="100" required>
            </div>

            <div class="formulario__linha
                       formulario__linha--duas-colunas">
                <div class="campo">
                    <label class="campo__rotulo" for="tipo">
                        Tipo:
                    </label>

                    <select class="campo__controle" id="tipo" name="tipo" required>
                        <option value="">Selecione</option>
                        <option value="ARMADURA">Armadura</option>
                        <option value="ELMO">Elmo</option>
                        <option value="BRACELETES">
                            Braceletes
                        </option>
                        <option value="BOTAS">Botas</option>
                        <option value="ESCUDO">Escudo</option>
                    </select>
                </div>

                <div class="campo">
                    <label class="campo__rotulo" for="pontos_protecao_maximo">
                        Pontos de proteção máximos:
                    </label>

                    <input class="campo__controle" type="number" id="pontos_protecao_maximo"
                        name="pontos_protecao_maximo" min="1" required>
                </div>
            </div>

            <div class="formulario__linha
                       formulario__linha--duas-colunas">
                <div class="campo">
                    <label class="campo__rotulo" for="dano">
                        Dano:
                    </label>

                    <input class="campo__controle" type="text" id="dano" name="dano" maxlength="50"
                        placeholder="Preencha quando aplicável">
                </div>

                <div class="campo">
                    <label class="campo__rotulo" for="elemento">
                        Elemento:
                    </label>

                    <input class="campo__controle" type="text" id="elemento" name="elemento" maxlength="50"
                        placeholder="Preencha quando aplicável">
                </div>
            </div>

            <div class="campo">
                <label class="campo__rotulo" for="especial">
                    Especial:
                </label>

                <textarea class="campo__controle" id="especial" name="especial" rows="5"></textarea>
            </div>

            <div class="formulario__acoes">
                <button class="botao botao--primario" type="submit">
                    Cadastrar
                </button>
            </div>
        </form>
    </main>
</body>

</html>