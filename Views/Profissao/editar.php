<?php

$erro = $erro ?? "";
$profissao = $profissao ?? [];

$idProfissao = (int) (
    $profissao["id_profissao"] ?? 0
);

$vantagensSelecionadas = $vantagensSelecionadas ?? [
    1 => 0,
    2 => 0
];

$armasSelecionadas = $armasSelecionadas ?? [
    1 => 0,
    2 => 0,
    3 => 0
];

$vestimentasSelecionadas = $vestimentasSelecionadas ?? [
    "ARMADURA" => 0,
    "ELMO" => 0,
    "BRACELETES" => 0,
    "BOTAS" => 0,
    "ESCUDO" => 0
];

$itensSelecionados = $itensSelecionados ?? [];

$vantagensDisponiveis = $vantagensDisponiveis ?? [];
$armasDisponiveis = $armasDisponiveis ?? [];
$vestimentasDisponiveis = $vestimentasDisponiveis ?? [];
$itensDisponiveis = $itensDisponiveis ?? [];

$tiposVantagem = [
    "NORMAL" => "Normal",
    "MAGIA" => "Concede magia",
    "COMPANHEIRO" => "Concede companheiro"
];

$nomesSlotsVestimenta = [
    "ARMADURA" => "Armadura",
    "ELMO" => "Elmo",
    "BRACELETES" => "Braceletes",
    "BOTAS" => "Botas",
    "ESCUDO" => "Escudo"
];

$nomesTiposItem = [
    "CONSUMIVEL" => "Consumível",
    "MATERIAL" => "Material",
    "UTILITARIO" => "Utilitário",
    "OUTRO" => "Outro"
];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Editar profissão</title>
</head>

<body>
    <main>
        <h1>Editar profissão</h1>

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
            action="ProfissaoController.php"
            method="POST"
            id="form-profissao"
        >
            <input
                type="hidden"
                name="acao"
                value="atualizar"
            >

            <input
                type="hidden"
                name="id_profissao"
                value="<?= $idProfissao ?>"
            >

            <fieldset>
                <legend>Dados da profissão</legend>

                <div>
                    <label for="nome">
                        Nome:
                    </label>

                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        maxlength="100"
                        value="<?= htmlspecialchars(
                            $profissao["nome"] ?? "",
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
                        $profissao["descricao"] ?? "",
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?></textarea>
                </div>

                <div>
                    <label for="pv_maximo">
                        PV máximo:
                    </label>

                    <input
                        type="number"
                        id="pv_maximo"
                        name="pv_maximo"
                        min="1"
                        value="<?= (int) (
                            $profissao["pv_maximo"] ?? 1
                        ) ?>"
                        required
                    >
                </div>

                <div>
                    <label for="visibilidade">
                        Visibilidade:
                    </label>

                    <select
                        id="visibilidade"
                        name="visibilidade"
                        required
                    >
                        <option
                            value="PRIVADO"
                            <?= (
                                $profissao["visibilidade"] ?? ""
                            ) === "PRIVADO" ? "selected" : "" ?>
                        >
                            Privado
                        </option>

                        <option
                            value="PUBLICO"
                            <?= (
                                $profissao["visibilidade"] ?? ""
                            ) === "PUBLICO" ? "selected" : "" ?>
                        >
                            Público
                        </option>
                    </select>
                </div>
            </fieldset>

            <fieldset>
                <legend>Vantagens</legend>

                <p>
                    A profissão deve possuir duas vantagens diferentes.
                </p>

                <?php if (empty($vantagensDisponiveis)): ?>
                    <p>
                        Nenhuma vantagem cadastrada.
                    </p>
                <?php else: ?>
                    <?php for ($slot = 1; $slot <= 2; $slot++): ?>
                        <div>
                            <label for="vantagem_<?= $slot ?>">
                                Vantagem do slot <?= $slot ?>:
                            </label>

                            <select
                                id="vantagem_<?= $slot ?>"
                                name="vantagens[<?= $slot ?>]"
                                required
                            >
                                <option value="">
                                    Selecione uma vantagem
                                </option>

                                <?php foreach (
                                    $vantagensDisponiveis as $vantagem
                                ): ?>
                                    <?php
                                    $idVantagem =
                                        (int) $vantagem["id_vantagem"];

                                    $tipoVantagem =
                                        $vantagem["tipo"] ?? "NORMAL";

                                    $nomeTipo =
                                        $tiposVantagem[$tipoVantagem]
                                        ?? "Normal";
                                    ?>

                                    <option
                                        value="<?= $idVantagem ?>"
                                        <?= $idVantagem ===
                                            (int) (
                                                $vantagensSelecionadas[
                                                    $slot
                                                ] ?? 0
                                            )
                                            ? "selected"
                                            : "" ?>
                                    >
                                        <?= htmlspecialchars(
                                            $vantagem["nome"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>
                                        -
                                        <?= htmlspecialchars(
                                            $nomeTipo,
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endfor; ?>

                    <p id="erro-vantagens"></p>
                <?php endif; ?>
            </fieldset>

            <fieldset>
                <legend>Armas iniciais</legend>

                <p>
                    As armas são opcionais. Existem até três slots.
                </p>

                <?php if (empty($armasDisponiveis)): ?>
                    <p>
                        Nenhuma arma cadastrada.
                    </p>
                <?php else: ?>
                    <?php for ($slot = 1; $slot <= 3; $slot++): ?>
                        <div>
                            <label for="arma_<?= $slot ?>">
                                Arma do slot <?= $slot ?>:
                            </label>

                            <select
                                id="arma_<?= $slot ?>"
                                name="armas[<?= $slot ?>]"
                            >
                                <option value="0">
                                    Nenhuma arma
                                </option>

                                <?php foreach (
                                    $armasDisponiveis as $arma
                                ): ?>
                                    <?php
                                    $idArma = (int) $arma["id_arma"];
                                    ?>

                                    <option
                                        value="<?= $idArma ?>"
                                        <?= $idArma ===
                                            (int) (
                                                $armasSelecionadas[
                                                    $slot
                                                ] ?? 0
                                            )
                                            ? "selected"
                                            : "" ?>
                                    >
                                        <?= htmlspecialchars(
                                            $arma["nome"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>

                                        <?php if (
                                            !empty($arma["tipo"])
                                        ): ?>
                                            -
                                            <?= htmlspecialchars(
                                                $arma["tipo"],
                                                ENT_QUOTES,
                                                "UTF-8"
                                            ) ?>
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endfor; ?>
                <?php endif; ?>
            </fieldset>

            <fieldset>
                <legend>Vestimentas iniciais</legend>

                <p>
                    Cada vestimenta só pode ocupar o slot correspondente.
                </p>

                <?php if (empty($vestimentasDisponiveis)): ?>
                    <p>
                        Nenhuma vestimenta cadastrada.
                    </p>
                <?php else: ?>
                    <?php foreach (
                        $nomesSlotsVestimenta as
                        $tipoSlot => $nomeSlot
                    ): ?>
                        <div>
                            <label for="vestimenta_<?= $tipoSlot ?>">
                                <?= htmlspecialchars(
                                    $nomeSlot,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>:
                            </label>

                            <select
                                id="vestimenta_<?= $tipoSlot ?>"
                                name="vestimentas[<?= $tipoSlot ?>]"
                            >
                                <option value="0">
                                    Nenhuma
                                </option>

                                <?php foreach (
                                    $vestimentasDisponiveis as
                                    $vestimenta
                                ): ?>
                                    <?php
                                    if (
                                        $vestimenta["tipo"] !==
                                        $tipoSlot
                                    ) {
                                        continue;
                                    }

                                    $idVestimenta =
                                        (int) $vestimenta[
                                            "id_vestimenta"
                                        ];
                                    ?>

                                    <option
                                        value="<?= $idVestimenta ?>"
                                        <?= $idVestimenta ===
                                            (int) (
                                                $vestimentasSelecionadas[
                                                    $tipoSlot
                                                ] ?? 0
                                            )
                                            ? "selected"
                                            : "" ?>
                                    >
                                        <?= htmlspecialchars(
                                            $vestimenta["nome"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>

                                        <?php if (
                                            isset(
                                                $vestimenta[
                                                    "pontos_protecao_maximo"
                                                ]
                                            )
                                        ): ?>
                                            -
                                            <?= (int) $vestimenta[
                                                "pontos_protecao_maximo"
                                            ] ?> PP
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </fieldset>

            <fieldset>
                <legend>Itens iniciais</legend>

                <p>
                    Use os filtros para localizar os itens e altere
                    somente as quantidades desejadas.
                </p>

                <?php if (empty($itensDisponiveis)): ?>
                    <p>
                        Nenhum item cadastrado.
                    </p>
                <?php else: ?>
                    <div>
                        <label for="pesquisa_item">
                            Pesquisar item:
                        </label>

                        <input
                            type="search"
                            id="pesquisa_item"
                            placeholder="Digite o nome do item"
                        >
                    </div>

                    <div>
                        <label for="filtro_tipo_item">
                            Tipo:
                        </label>

                        <select id="filtro_tipo_item">
                            <option value="">
                                Todos os tipos
                            </option>

                            <?php foreach (
                                $nomesTiposItem as
                                $valorTipo => $nomeTipo
                            ): ?>
                                <option value="<?= $valorTipo ?>">
                                    <?= htmlspecialchars(
                                        $nomeTipo,
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="lista_itens">
                        <?php foreach (
                            $itensDisponiveis as $item
                        ): ?>
                            <?php
                            $idItem = (int) $item["id_item"];
                            $tipoItem = $item["tipo"] ?? "OUTRO";

                            $nomeTipoItem =
                                $nomesTiposItem[$tipoItem]
                                ?? "Outro";

                            $quantidade = (int) (
                                $itensSelecionados[$idItem] ?? 0
                            );
                            ?>

                            <div
                                class="linha-item"
                                data-nome="<?= htmlspecialchars(
                                    $item["nome"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>"
                                data-tipo="<?= htmlspecialchars(
                                    $tipoItem,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>"
                            >
                                <label for="item_<?= $idItem ?>">
                                    <?= htmlspecialchars(
                                        $item["nome"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>

                                    (
                                    <?= htmlspecialchars(
                                        $nomeTipoItem,
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>
                                    ):
                                </label>

                                <input
                                    type="number"
                                    id="item_<?= $idItem ?>"
                                    name="itens[<?= $idItem ?>]"
                                    min="0"
                                    value="<?= $quantidade ?>"
                                >
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <p id="mensagem_sem_itens" hidden>
                        Nenhum item encontrado com esses filtros.
                    </p>
                <?php endif; ?>
            </fieldset>

            <div>
                <button type="submit">
                    Salvar alterações
                </button>

                <a href="ProfissaoController.php?acao=listar">
                    Cancelar
                </a>
            </div>
        </form>
    </main>

    <script>
        const formProfissao =
            document.getElementById("form-profissao");

        const vantagem1 =
            document.getElementById("vantagem_1");

        const vantagem2 =
            document.getElementById("vantagem_2");

        const erroVantagens =
            document.getElementById("erro-vantagens");

        function validarVantagens() {
            if (!vantagem1 || !vantagem2) {
                return true;
            }

            if (
                vantagem1.value !== "" &&
                vantagem1.value === vantagem2.value
            ) {
                if (erroVantagens) {
                    erroVantagens.textContent =
                        "As duas vantagens devem ser diferentes.";
                }

                vantagem2.setCustomValidity(
                    "Selecione uma vantagem diferente."
                );

                return false;
            }

            if (erroVantagens) {
                erroVantagens.textContent = "";
            }

            vantagem2.setCustomValidity("");

            return true;
        }

        if (vantagem1 && vantagem2) {
            vantagem1.addEventListener(
                "change",
                validarVantagens
            );

            vantagem2.addEventListener(
                "change",
                validarVantagens
            );

            formProfissao.addEventListener(
                "submit",
                validarVantagens
            );
        }

        const pesquisaItem =
            document.getElementById("pesquisa_item");

        const filtroTipoItem =
            document.getElementById("filtro_tipo_item");

        const linhasItens =
            document.querySelectorAll(".linha-item");

        const mensagemSemItens =
            document.getElementById("mensagem_sem_itens");

        function filtrarItens() {
            if (!pesquisaItem || !filtroTipoItem) {
                return;
            }

            const textoPesquisado =
                pesquisaItem.value
                    .trim()
                    .toLocaleLowerCase();

            const tipoSelecionado =
                filtroTipoItem.value;

            let quantidadeVisivel = 0;

            linhasItens.forEach(function (linha) {
                const nomeItem =
                    linha.dataset.nome.toLocaleLowerCase();

                const tipoItem =
                    linha.dataset.tipo;

                const correspondeNome =
                    nomeItem.includes(textoPesquisado);

                const correspondeTipo =
                    tipoSelecionado === "" ||
                    tipoItem === tipoSelecionado;

                const deveMostrar =
                    correspondeNome && correspondeTipo;

                linha.hidden = !deveMostrar;

                if (deveMostrar) {
                    quantidadeVisivel++;
                }
            });

            if (mensagemSemItens) {
                mensagemSemItens.hidden =
                    quantidadeVisivel !== 0;
            }
        }

        if (pesquisaItem && filtroTipoItem) {
            pesquisaItem.addEventListener(
                "input",
                filtrarItens
            );

            filtroTipoItem.addEventListener(
                "change",
                filtrarItens
            );
        }
    </script>
</body>

</html>