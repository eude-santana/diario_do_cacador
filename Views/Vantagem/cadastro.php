<?php

require_once __DIR__ . "/../../Config/Autenticacao.php";

exigirLogin();

if (!isset($magias) || !isset($companheiros)) {
    header("Location: /Controllers/VantagemController.php?acao=novo");
    exit;
}

$erro = $erro ?? "";
$nome = $nome ?? "";
$descricao = $descricao ?? "";
$tipo = $tipo ?? "NORMAL";

$magias = $magias ?? [];
$companheiros = $companheiros ?? [];

$idsMagiasSelecionadas = $idsMagiasSelecionadas ?? [];
$idsCompanheirosSelecionados = $idsCompanheirosSelecionados ?? [];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Cadastrar vantagem</title>
</head>

<body>
    <main>
        <h1>Cadastrar vantagem</h1>

        <?php if ($erro !== ""): ?>
            <p>
                <?= htmlspecialchars(
                    $erro,
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>
            </p>
        <?php endif; ?>


        <form
            action="/Controllers/VantagemController.php"
            method="POST"
        >
            <input type="hidden" name="acao" value="cadastrar">

            <div>
                <label for="nome">Nome:</label>

                <input
                    type="text"
                    id="nome"
                    name="nome"
                    maxlength="100"
                    value="<?= htmlspecialchars(
                        $nome,
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>"
                    required
                >
            </div>

            <div>
                <label for="descricao">
                    Descrição:
                </label>

                <textarea
                    id="descricao"
                    name="descricao"
                    rows="5"
                ><?= htmlspecialchars(
                    $descricao,
                    ENT_QUOTES,
                    "UTF-8"
                ) ?></textarea>
            </div>

            <fieldset>
                <legend>Tipo da vantagem</legend>

                <div>
                    <input
                        type="radio"
                        id="tipo_normal"
                        name="tipo"
                        value="NORMAL"
                        <?= $tipo === "NORMAL" ? "checked" : "" ?>
                    >

                    <label for="tipo_normal">
                        Normal
                    </label>
                </div>

                <div>
                    <input
                        type="radio"
                        id="tipo_magia"
                        name="tipo"
                        value="MAGIA"
                        <?= $tipo === "MAGIA" ? "checked" : "" ?>
                    >

                    <label for="tipo_magia">
                        Concede magia
                    </label>
                </div>

                <div>
                    <input
                        type="radio"
                        id="tipo_companheiro"
                        name="tipo"
                        value="COMPANHEIRO"
                        <?= $tipo === "COMPANHEIRO"
                            ? "checked"
                            : "" ?>
                    >

                    <label for="tipo_companheiro">
                        Concede companheiro animal
                    </label>
                </div>
            </fieldset>

            <fieldset id="grupo_magias">
                <legend>Magias concedidas</legend>

                <?php if (empty($magias)): ?>
                    <p>
                        Nenhuma magia cadastrada.
                    </p>

                    <a
                        href="/Controllers/MagiaController.php?acao=novo"
                    >
                        Cadastrar magia
                    </a>
                <?php else: ?>
                    <?php foreach ($magias as $magia): ?>
                        <?php
                        $idMagia = (int) $magia["id_magia"];

                        $selecionada = in_array(
                            $idMagia,
                            $idsMagiasSelecionadas,
                            true
                        );
                        ?>

                        <div>
                            <input
                                type="checkbox"
                                class="opcao-magia"
                                id="magia_<?= $idMagia ?>"
                                name="ids_magias[]"
                                value="<?= $idMagia ?>"
                                <?= $selecionada ? "checked" : "" ?>
                            >

                            <label for="magia_<?= $idMagia ?>">
                                <?= htmlspecialchars(
                                    $magia["nome"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </fieldset>

            <fieldset id="grupo_companheiros">
                <legend>Companheiros concedidos</legend>

                <?php if (empty($companheiros)): ?>
                    <p>
                        Nenhum companheiro animal cadastrado.
                    </p>

                    <a
                        href="/Controllers/CompanheiroAnimalController.php?acao=novo"
                    >
                        Cadastrar companheiro animal
                    </a>
                    
                <?php else: ?>
                    <?php foreach ($companheiros as $companheiro): ?>
                        <?php
                        $idCompanheiro =
                            (int) $companheiro["id_companheiro"];

                        $selecionado = in_array(
                            $idCompanheiro,
                            $idsCompanheirosSelecionados,
                            true
                        );
                        ?>

                        <div>
                            <input
                                type="checkbox"
                                class="opcao-companheiro"
                                id="companheiro_<?= $idCompanheiro ?>"
                                name="ids_companheiros[]"
                                value="<?= $idCompanheiro ?>"
                                <?= $selecionado ? "checked" : "" ?>
                            >

                            <label
                                for="companheiro_<?= $idCompanheiro ?>"
                            >
                                <?= htmlspecialchars(
                                    $companheiro["tipo"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </fieldset>

            <div>
                <button type="submit">
                    Cadastrar
                </button>

                <a
                    href="/Controllers/VantagemController.php?acao=listar"
                >
                    Cancelar
                </a>
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
