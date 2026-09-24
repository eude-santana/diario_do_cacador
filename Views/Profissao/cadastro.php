<?php

$erro = $erro ?? "";

$profissao = $profissao ?? [
    "nome" => "",
    "descricao" => "",
    "pv_maximo" => 1,
    "visibilidade" => "PRIVADO"
];

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

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastrar profissão</title>
</head>

<body>
    <main>
        <h1>Cadastrar profissão</h1>

        <?php if ($erro !== ""): ?>
            <p>
                <?= htmlspecialchars(
                    $erro,
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>
            </p>
        <?php endif; ?>

        <form action="ProfissaoController.php" method="POST" id="form-profissao">
            <input type="hidden" name="acao" value="cadastrar">

            <fieldset>
                <legend>Dados da profissão</legend>

                <div>
                    <label for="nome">
                        Nome:
                    </label>

                    <input type="text" id="nome" name="nome" maxlength="100" value="<?= htmlspecialchars(
                        $profissao["nome"],
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>" required>
                </div>

                <div>
                    <label for="descricao">
                        Descrição:
                    </label>

                    <textarea id="descricao" name="descricao" rows="5"><?= htmlspecialchars(
                        $profissao["descricao"],
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?></textarea>
                </div>

                <div>
                    <label for="pv_maximo">
                        PV máximo:
                    </label>

                    <input type="number" id="pv_maximo" name="pv_maximo" min="1"
                        value="<?= (int) $profissao["pv_maximo"] ?>" required>
                </div>

                <div>
                    <label for="visibilidade">
                        Visibilidade:
                    </label>

                    <select id="visibilidade" name="visibilidade" required>
                        <option value="PRIVADO" <?= $profissao["visibilidade"] === "PRIVADO"
                            ? "selected"
                            : "" ?>>
                            Privado
                        </option>

                        <option value="PUBLICO" <?= $profissao["visibilidade"] === "PUBLICO"
                            ? "selected"
                            : "" ?>>
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

                    <a href="VantagemController.php?acao=novo">
                        Cadastrar vantagem
                    </a>
                <?php else: ?>
                    <div>
                        <label for="vantagem_1">
                            Vantagem do slot 1:
                        </label>

                        <select id="vantagem_1" name="vantagens[1]" required>
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

                                <option value="<?= $idVantagem ?>" <?= $idVantagem ===
                                      (int) $vantagensSelecionadas[1]
                                      ? "selected"
                                      : "" ?>>
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

                    <div>
                        <label for="vantagem_2">
                            Vantagem do slot 2:
                        </label>

                        <select id="vantagem_2" name="vantagens[2]" required>
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

                                <option value="<?= $idVantagem ?>" <?= $idVantagem ===
                                      (int) $vantagensSelecionadas[2]
                                      ? "selected"
                                      : "" ?>>
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

                    <a href="ArmaController.php?acao=novo">
                        Cadastrar arma
                    </a>
                <?php else: ?>
                    <?php for ($slot = 1; $slot <= 3; $slot++): ?>
                        <div>
                            <label for="arma_<?= $slot ?>">
                                Arma do slot <?= $slot ?>:
                            </label>

                            <select id="arma_<?= $slot ?>" name="armas[<?= $slot ?>]">
                                <option value="0">
                                    Nenhuma arma
                                </option>

                                <?php foreach (
                                    $armasDisponiveis as $arma
                                ): ?>
                                    <?php
                                    $idArma = (int) $arma["id_arma"];
                                    ?>

                                    <option value="<?= $idArma ?>" <?= $idArma ===
                                          (int) (
                                              $armasSelecionadas[$slot]
                                              ?? 0
                                          )
                                          ? "selected"
                                          : "" ?>>
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
                    Cada vestimenta só pode ocupar o slot correspondente
                    ao seu tipo.
                </p>

                <?php if (empty($vestimentasDisponiveis)): ?>
                    <p>
                        Nenhuma vestimenta cadastrada.
                    </p>

                    <a href="VestimentaController.php?acao=novo">
                        Cadastrar vestimenta
                    </a>
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

                            <select id="vestimenta_<?= $tipoSlot ?>" name="vestimentas[<?= $tipoSlot ?>]">
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

                                    <option value="<?= $idVestimenta ?>" <?= $idVestimenta ===
                                          (int) (
                                              $vestimentasSelecionadas[
                                                  $tipoSlot
                                              ] ?? 0
                                          )
                                          ? "selected"
                                          : "" ?>>
                                        <?= htmlspecialchars(
                                            $vestimenta["nome"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>

                                        <?php if (
                                            isset(
                                            $vestimenta["pp_maximo"]
                                        )
                                        ): ?>
                                            -
                                            <?= (int) $vestimenta[
                                                "pp_maximo"
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
                    Informe a quantidade inicial de cada item.
                    Deixe zero para não incluir.
                </p>

                <?php if (empty($itensDisponiveis)): ?>
                    <p>
                        Nenhum item cadastrado.
                    </p>

                    <a href="ItemController.php?acao=novo">
                        Cadastrar item
                    </a>
                <?php else: ?>
                    <?php foreach (
                        $itensDisponiveis as $item
                    ): ?>
                        <?php
                        $idItem = (int) $item["id_item"];

                        $quantidade = (int) (
                            $itensSelecionados[$idItem] ?? 0
                        );
                        ?>

                        <div>
                            <label for="item_<?= $idItem ?>">
                                <?= htmlspecialchars(
                                    $item["nome"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>:
                            </label>

                            <input type="number" id="item_<?= $idItem ?>" name="itens[<?= $idItem ?>]" min="0"
                                value="<?= $quantidade ?>">
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </fieldset>

            <div>
                <button type="submit">
                    Cadastrar profissão
                </button>

                <a href="ProfissaoController.php?acao=listar">
                    Cancelar
                </a>
            </div>
        </form>

        <p>
            <a href="../Views/Usuario/painel.php">
                Voltar ao painel
            </a>
        </p>
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

        if (vantagem1 && vantagem2 && erroVantagens) {
            function validarVantagens() {
                if (
                    vantagem1.value !== "" &&
                    vantagem1.value === vantagem2.value
                ) {
                    erroVantagens.textContent =
                        "As duas vantagens devem ser diferentes.";

                    vantagem2.setCustomValidity(
                        "Selecione uma vantagem diferente."
                    );

                    return false;
                }

                erroVantagens.textContent = "";
                vantagem2.setCustomValidity("");

                return true;
            }

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
    </script>
</body>

</html>