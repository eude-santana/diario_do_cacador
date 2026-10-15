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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&amp;family=Source+Sans+3:wght@400;600;700&amp;display=swap"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="/Assets/css/style.css">

    <title>Editar profissão — Diário do Caçador</title>
</head>

<body>
    <main class="pagina pagina-formulario pagina-formulario--larga">
        <div class="cabecalho-com-acoes">
            <header class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Catálogo do caçador
                </p>

                <h1 class="cabecalho-pagina__titulo">
                    Editar profissão
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Atualize os dados e os recursos iniciais da profissão.
                </p>
            </header>

            <nav class="acoes-pagina" aria-label="Ações da página">
                <a
                    class="botao botao--secundario"
                    href="/Controllers/ProfissaoController.php?acao=listar"
                >
                    Voltar à listagem
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

        <form
            class="formulario formulario-registro"
            action="/Controllers/ProfissaoController.php"
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

            <fieldset class="secao-formulario">
                <legend class="secao-formulario__titulo">
                    Dados da profissão
                </legend>

                <div class="secao-formulario__conteudo">
                    <div class="campo">
                        <label class="campo__rotulo" for="nome">
                            Nome:
                        </label>

                        <input
                            class="campo__controle"
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

                    <div class="campo">
                        <label class="campo__rotulo" for="descricao">
                            Descrição:
                        </label>

                        <textarea
                            class="campo__controle"
                            id="descricao"
                            name="descricao"
                            rows="5"
                        ><?= htmlspecialchars(
                            $profissao["descricao"] ?? "",
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?></textarea>
                    </div>

                    <div class="formulario__linha formulario__linha--duas-colunas">
                        <div class="campo">
                            <label class="campo__rotulo" for="pv_maximo">
                                PV máximo:
                            </label>

                            <input
                                class="campo__controle"
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

                        <div class="campo">
                            <label class="campo__rotulo" for="visibilidade">
                                Visibilidade:
                            </label>

                            <select
                                class="campo__controle"
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
                    </div>
                </div>
            </fieldset>

            <fieldset class="secao-formulario">
                <legend class="secao-formulario__titulo">
                    Vantagens
                </legend>

                <div class="secao-formulario__conteudo">
                    <div class="secao-formulario__cabecalho">
                        <p class="secao-formulario__descricao">
                            A profissão deve possuir duas vantagens diferentes.
                        </p>

                        <a
                            class="botao botao--secundario botao--pequeno"
                            href="/Controllers/VantagemController.php?acao=novo"
                            target="_blank"
                            rel="noopener"
                        >
                            Cadastrar nova vantagem
                        </a>
                    </div>

                    <?php if (empty($vantagensDisponiveis)): ?>
                        <p class="secao-formulario__vazio">
                            Nenhuma vantagem cadastrada.
                        </p>
                    <?php else: ?>
                        <div class="formulario__linha formulario__linha--duas-colunas formulario__linha--campos-longos">
                            <?php for ($slot = 1; $slot <= 2; $slot++): ?>
                                <div class="campo">
                                    <label
                                        class="campo__rotulo"
                                        for="vantagem_<?= $slot ?>"
                                    >
                                        Vantagem do slot <?= $slot ?>:
                                    </label>

                                    <select
                                        class="campo__controle"
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
                        </div>

                        <p
                            class="campo__erro"
                            id="erro-vantagens"
                        ></p>
                    <?php endif; ?>
                </div>
            </fieldset>

            <fieldset class="secao-formulario">
                <legend class="secao-formulario__titulo">
                    Armas iniciais
                </legend>

                <div class="secao-formulario__conteudo">
                    <div class="secao-formulario__cabecalho">
                        <p class="secao-formulario__descricao">
                            As armas são opcionais. Existem até três slots.
                        </p>

                        <a
                            class="botao botao--secundario botao--pequeno"
                            href="/Controllers/ArmaController.php?acao=novo"
                            target="_blank"
                            rel="noopener"
                        >
                            Cadastrar nova arma
                        </a>
                    </div>

                    <?php if (empty($armasDisponiveis)): ?>
                        <p class="secao-formulario__vazio">
                            Nenhuma arma cadastrada.
                        </p>
                    <?php else: ?>
                        <div class="formulario__linha formulario__linha--tres-colunas formulario__linha--campos-longos">
                            <?php for ($slot = 1; $slot <= 3; $slot++): ?>
                                <div class="campo">
                                    <label
                                        class="campo__rotulo"
                                        for="arma_<?= $slot ?>"
                                    >
                                        Arma do slot <?= $slot ?>:
                                    </label>

                                    <select
                                        class="campo__controle"
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
                        </div>
                    <?php endif; ?>
                </div>
            </fieldset>

            <fieldset class="secao-formulario">
                <legend class="secao-formulario__titulo">
                    Vestimentas iniciais
                </legend>

                <div class="secao-formulario__conteudo">
                    <div class="secao-formulario__cabecalho">
                        <p class="secao-formulario__descricao">
                            Cada vestimenta só pode ocupar o slot correspondente.
                        </p>

                        <a
                            class="botao botao--secundario botao--pequeno"
                            href="/Controllers/VestimentaController.php?acao=novo"
                            target="_blank"
                            rel="noopener"
                        >
                            Cadastrar nova vestimenta
                        </a>
                    </div>

                    <?php if (empty($vestimentasDisponiveis)): ?>
                        <p class="secao-formulario__vazio">
                            Nenhuma vestimenta cadastrada.
                        </p>
                    <?php else: ?>
                        <div class="formulario__linha formulario__linha--tres-colunas formulario__linha--campos-longos">
                            <?php foreach (
                                $nomesSlotsVestimenta as
                                $tipoSlot => $nomeSlot
                            ): ?>
                                <div class="campo">
                                    <label
                                        class="campo__rotulo"
                                        for="vestimenta_<?= $tipoSlot ?>"
                                    >
                                        <?= htmlspecialchars(
                                            $nomeSlot,
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>:
                                    </label>

                                    <select
                                        class="campo__controle"
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
                        </div>
                    <?php endif; ?>
                </div>
            </fieldset>

            <fieldset class="secao-formulario">
                <legend class="secao-formulario__titulo">
                    Itens iniciais
                </legend>

                <div class="secao-formulario__conteudo">
                    <div class="secao-formulario__cabecalho">
                        <p class="secao-formulario__descricao">
                            Use os filtros para localizar os itens e altere
                            somente as quantidades desejadas.
                        </p>

                        <a
                            class="botao botao--secundario botao--pequeno"
                            href="/Controllers/ItemController.php?acao=novo"
                            target="_blank"
                            rel="noopener"
                        >
                            Cadastrar novo item
                        </a>
                    </div>

                    <?php if (empty($itensDisponiveis)): ?>
                        <p class="secao-formulario__vazio">
                            Nenhum item cadastrado.
                        </p>
                    <?php else: ?>
                        <div class="formulario__linha formulario__linha--duas-colunas formulario__linha--campos-longos">
                            <div class="campo">
                                <label
                                    class="campo__rotulo"
                                    for="pesquisa_item"
                                >
                                    Pesquisar item:
                                </label>

                                <input
                                    class="campo__controle"
                                    type="search"
                                    id="pesquisa_item"
                                    placeholder="Digite o nome do item"
                                >
                            </div>

                            <div class="campo">
                                <label
                                    class="campo__rotulo"
                                    for="filtro_tipo_item"
                                >
                                    Tipo:
                                </label>

                                <select
                                    class="campo__controle"
                                    id="filtro_tipo_item"
                                >
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
                        </div>

                        <div class="lista-itens" id="lista_itens">
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
                                    <label
                                        class="linha-item__nome"
                                        for="item_<?= $idItem ?>"
                                    >
                                        <?= htmlspecialchars(
                                            $item["nome"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>

                                        <span>
                                            <?= htmlspecialchars(
                                                $nomeTipoItem,
                                                ENT_QUOTES,
                                                "UTF-8"
                                            ) ?>
                                        </span>
                                    </label>

                                    <input
                                        class="campo__controle linha-item__quantidade"
                                        type="number"
                                        id="item_<?= $idItem ?>"
                                        name="itens[<?= $idItem ?>]"
                                        min="0"
                                        value="<?= $quantidade ?>"
                                        aria-label="Quantidade de <?= htmlspecialchars(
                                            $item["nome"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>"
                                    >
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <p
                            class="secao-formulario__vazio"
                            id="mensagem_sem_itens"
                            hidden
                        >
                            Nenhum item encontrado com esses filtros.
                        </p>
                    <?php endif; ?>
                </div>
            </fieldset>

            <div class="formulario__acoes">
                <button
                    class="botao botao--primario"
                    type="submit"
                >
                    Salvar alterações
                </button>

                <a
                    class="botao botao--secundario"
                    href="/Controllers/ProfissaoController.php?acao=listar"
                >
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