<?php

require_once __DIR__ . "/../../Config/Autenticacao.php";

exigirLogin();

if (!isset($vantagem)) {
    header("Location: /Controllers/VantagemController.php?acao=listar");
    exit;
}

$erro = $erro ?? "";
$vantagem = $vantagem ?? [];

$tipo = $tipo ?? "NORMAL";
$magias = $magias ?? [];
$companheiros = $companheiros ?? [];

$idsMagiasSelecionadas = array_map(
    "intval",
    $idsMagiasSelecionadas ?? []
);

$idsCompanheirosSelecionados = array_map(
    "intval",
    $idsCompanheirosSelecionados ?? []
);

$idVantagem = (int) ($vantagem["id_vantagem"] ?? 0);
$nome = $vantagem["nome"] ?? "";
$descricao = $vantagem["descricao"] ?? "";

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
        Editar vantagem — Diário do Caçador
    </title>
</head>

<body>
    <main class="pagina pagina-formulario" aria-labelledby="titulo-pagina">
        <div class="cabecalho-com-acoes">
            <header class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Talentos e habilidades
                </p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    Editar vantagem
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Atualize o benefício e os recursos concedidos
                    por esta vantagem.
                </p>
            </header>

            <nav class="acoes-pagina" aria-label="Ações da página">
                <a class="botao botao--secundario" href="/Controllers/VantagemController.php?acao=listar">
                    Cancelar
                </a>
            </nav>
        </div>

        <?php if ($erro !== ""): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    <?= htmlspecialchars(
                        $erro,
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>
                </p>
            </div>
        <?php endif; ?>

        <form class="formulario formulario-registro" action="/Controllers/VantagemController.php" method="POST">
            <input type="hidden" name="acao" value="atualizar">

            <input type="hidden" name="id_vantagem" value="<?= $idVantagem ?>">

            <div class="campo">
                <label class="campo__rotulo" for="nome">
                    Nome:
                </label>

                <input class="campo__controle" type="text" id="nome" name="nome" maxlength="100" value="<?= htmlspecialchars(
                    $nome,
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>" required>
            </div>

            <div class="campo">
                <label class="campo__rotulo" for="descricao">
                    Descrição:
                </label>

                <textarea class="campo__controle" id="descricao" name="descricao" rows="5"><?= htmlspecialchars(
                    $descricao,
                    ENT_QUOTES,
                    "UTF-8"
                ) ?></textarea>
            </div>

            <fieldset class="grupo-opcoes">
                <legend class="grupo-opcoes__titulo">
                    Tipo da vantagem
                </legend>

                <div class="grupo-opcoes__lista
                           grupo-opcoes__lista--tipos">
                    <div class="grupo-opcoes__item">
                        <input type="radio" id="tipo_normal" name="tipo" value="NORMAL" <?= $tipo === "NORMAL"
                            ? "checked"
                            : "" ?>>

                        <label for="tipo_normal">
                            Normal
                        </label>
                    </div>

                    <div class="grupo-opcoes__item">
                        <input type="radio" id="tipo_magia" name="tipo" value="MAGIA" <?= $tipo === "MAGIA"
                            ? "checked"
                            : "" ?>>

                        <label for="tipo_magia">
                            Concede magia
                        </label>
                    </div>

                    <div class="grupo-opcoes__item">
                        <input type="radio" id="tipo_companheiro" name="tipo" value="COMPANHEIRO"
                            <?= $tipo === "COMPANHEIRO"
                                ? "checked"
                                : "" ?>>

                        <label for="tipo_companheiro">
                            Concede companheiro animal
                        </label>
                    </div>
                </div>
            </fieldset>

            <fieldset class="grupo-opcoes" id="grupo_magias">
                <legend class="grupo-opcoes__titulo">
                    Magias concedidas
                </legend>

                <?php if (empty($magias)): ?>
                    <p class="grupo-opcoes__vazio">
                        Nenhuma magia cadastrada.
                    </p>

                    <a class="botao botao--secundario botao--pequeno" href="/Controllers/MagiaController.php?acao=novo">
                        Cadastrar magia
                    </a>
                <?php else: ?>
                    <div class="grupo-opcoes__lista">
                        <?php foreach ($magias as $magia): ?>
                            <?php
                            $idMagia = (int) $magia["id_magia"];

                            $selecionada = in_array(
                                $idMagia,
                                $idsMagiasSelecionadas,
                                true
                            );
                            ?>

                            <div class="grupo-opcoes__item">
                                <input type="checkbox" class="opcao-magia" id="magia_<?= $idMagia ?>" name="ids_magias[]"
                                    value="<?= $idMagia ?>" <?= $selecionada
                                          ? "checked"
                                          : "" ?>>

                                <label for="magia_<?= $idMagia ?>">
                                    <?= htmlspecialchars(
                                        $magia["nome"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </fieldset>

            <fieldset class="grupo-opcoes" id="grupo_companheiros">
                <legend class="grupo-opcoes__titulo">
                    Companheiros concedidos
                </legend>

                <?php if (empty($companheiros)): ?>
                    <p class="grupo-opcoes__vazio">
                        Nenhum companheiro animal cadastrado.
                    </p>

                    <a class="botao botao--secundario botao--pequeno"
                        href="/Controllers/CompanheiroAnimalController.php?acao=novo">
                        Cadastrar companheiro animal
                    </a>
                <?php else: ?>
                    <div class="grupo-opcoes__lista">
                        <?php foreach (
                            $companheiros as $companheiro
                        ): ?>
                            <?php
                            $idCompanheiro =
                                (int) $companheiro[
                                    "id_companheiro"
                                ];

                            $selecionado = in_array(
                                $idCompanheiro,
                                $idsCompanheirosSelecionados,
                                true
                            );
                            ?>

                            <div class="grupo-opcoes__item">
                                <input type="checkbox" class="opcao-companheiro" id="companheiro_<?= $idCompanheiro ?>"
                                    name="ids_companheiros[]" value="<?= $idCompanheiro ?>" <?= $selecionado
                                          ? "checked"
                                          : "" ?>>

                                <label for="companheiro_<?= $idCompanheiro ?>">
                                    <?= htmlspecialchars(
                                        $companheiro["tipo"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </fieldset>

            <div class="formulario__acoes">
                <button class="botao botao--primario" type="submit">
                    Salvar alterações
                </button>
            </div>
        </form>
    </main>

    <script>
        const tipos = document.querySelectorAll(
            'input[name="tipo"]'
        );

        const grupoMagias =
            document.getElementById("grupo_magias");

        const grupoCompanheiros =
            document.getElementById("grupo_companheiros");

        const opcoesMagias =
            document.querySelectorAll(".opcao-magia");

        const opcoesCompanheiros =
            document.querySelectorAll(".opcao-companheiro");

        function atualizarCampos() {
            const tipoSelecionado = document.querySelector(
                'input[name="tipo"]:checked'
            ).value;

            const mostrarMagias =
                tipoSelecionado === "MAGIA";

            const mostrarCompanheiros =
                tipoSelecionado === "COMPANHEIRO";

            grupoMagias.hidden = !mostrarMagias;
            grupoCompanheiros.hidden = !mostrarCompanheiros;

            opcoesMagias.forEach(function (opcao) {
                opcao.disabled = !mostrarMagias;
            });

            opcoesCompanheiros.forEach(function (opcao) {
                opcao.disabled = !mostrarCompanheiros;
            });
        }

        tipos.forEach(function (tipo) {
            tipo.addEventListener(
                "change",
                atualizarCampos
            );
        });

        atualizarCampos();
    </script>
</body>

</html>