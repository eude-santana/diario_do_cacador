<?php

$profissao = $profissao ?? [];

$vantagens = $profissao["vantagens"] ?? [];
$armas = $profissao["armas"] ?? [];
$vestimentas = $profissao["vestimentas"] ?? [];
$itens = $profissao["itens"] ?? [];

$tiposVantagem = [
    "NORMAL" => "Normal",
    "MAGIA" => "Concede magia",
    "COMPANHEIRO" => "Concede companheiro animal"
];

$tiposArma = [
    "CORPORAL" => "Corporal",
    "DISTANCIA" => "Distância"
];

$tiposVestimenta = [
    "ARMADURA" => "Armadura",
    "ELMO" => "Elmo",
    "BRACELETES" => "Braceletes",
    "BOTAS" => "Botas",
    "ESCUDO" => "Escudo"
];

$tiposItem = [
    "CONSUMIVEL" => "Consumível",
    "MATERIAL" => "Material",
    "UTILITARIO" => "Utilitário",
    "OUTRO" => "Outro"
];

function escaparProfissaoPublica($valor): string
{
    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        "UTF-8"
    );
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Visualizar profissão</title>
</head>

<body>
    <main>
        <h1>
            <?= escaparProfissaoPublica(
                $profissao["nome"] ?? ""
            ) ?>
        </h1>

        <p>
            <strong>Autor:</strong>

            <?= escaparProfissaoPublica(
                $profissao["nome_autor"] ?? ""
            ) ?>
        </p>

        <p>
            <strong>PV máximo:</strong>
            <?= (int) ($profissao["pv_maximo"] ?? 0) ?>
        </p>

        <section>
            <h2>Descrição</h2>

            <?php if (($profissao["descricao"] ?? "") !== ""): ?>
                <p>
                    <?= nl2br(
                        escaparProfissaoPublica(
                            $profissao["descricao"]
                        )
                    ) ?>
                </p>
            <?php else: ?>
                <p>Sem descrição.</p>
            <?php endif; ?>
        </section>

        <section>
            <h2>Vantagens</h2>

            <?php if (empty($vantagens)): ?>
                <p>Nenhuma vantagem cadastrada.</p>
            <?php else: ?>
                <?php foreach ($vantagens as $vantagem): ?>
                    <?php
                    $tipoVantagem =
                        $vantagem["tipo"] ?? "NORMAL";

                    $nomeTipoVantagem =
                        $tiposVantagem[$tipoVantagem]
                        ?? "Normal";
                    ?>

                    <article>
                        <h3>
                            Slot <?= (int) $vantagem["slot"] ?>:

                            <?= escaparProfissaoPublica(
                                $vantagem["nome"]
                            ) ?>
                        </h3>

                        <p>
                            <strong>Tipo:</strong>
                            <?= escaparProfissaoPublica(
                                $nomeTipoVantagem
                            ) ?>
                        </p>

                        <?php if (
                            ($vantagem["descricao"] ?? "") !== ""
                        ): ?>
                            <p>
                                <?= nl2br(
                                    escaparProfissaoPublica(
                                        $vantagem["descricao"]
                                    )
                                ) ?>
                            </p>
                        <?php endif; ?>

                        <?php if (!empty($vantagem["magias"])): ?>
                            <h4>Magias disponíveis</h4>

                            <ul>
                                <?php foreach (
                                    $vantagem["magias"] as $magia
                                ): ?>
                                    <li>
                                        <strong>
                                            <?= escaparProfissaoPublica(
                                                $magia["nome"]
                                            ) ?>
                                        </strong>

                                        —

                                        <?= escaparProfissaoPublica(
                                            $magia["elemento"]
                                        ) ?>

                                        <?php if (
                                            ($magia["descricao"] ?? "")
                                            !== ""
                                        ): ?>
                                            <p>
                                                <?= nl2br(
                                                    escaparProfissaoPublica(
                                                        $magia["descricao"]
                                                    )
                                                ) ?>
                                            </p>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <?php if (
                            !empty($vantagem["companheiros"])
                        ): ?>
                            <h4>Companheiros disponíveis</h4>

                            <ul>
                                <?php foreach (
                                    $vantagem["companheiros"] as
                                    $companheiro
                                ): ?>
                                    <li>
                                        <strong>
                                            <?= escaparProfissaoPublica(
                                                $companheiro["tipo"]
                                            ) ?>
                                        </strong>

                                        — PV máximo:
                                        <?= (int) $companheiro[
                                            "pv_maximo"
                                        ] ?>

                                        <?php if (
                                            ($companheiro["dano"] ?? "")
                                            !== ""
                                        ): ?>
                                            — Dano:
                                            <?= escaparProfissaoPublica(
                                                $companheiro["dano"]
                                            ) ?>
                                        <?php endif; ?>

                                        <?php if (
                                            ($companheiro["descricao"] ?? "")
                                            !== ""
                                        ): ?>
                                            <p>
                                                <?= nl2br(
                                                    escaparProfissaoPublica(
                                                        $companheiro[
                                                            "descricao"
                                                        ]
                                                    )
                                                ) ?>
                                            </p>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <section>
            <h2>Armas iniciais</h2>

            <?php if (empty($armas)): ?>
                <p>Nenhuma arma inicial.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($armas as $arma): ?>
                        <?php
                        $nomeTipoArma =
                            $tiposArma[$arma["tipo"]] ?? $arma["tipo"];
                        ?>

                        <li>
                            <strong>
                                Slot <?= (int) $arma["slot"] ?>:

                                <?= escaparProfissaoPublica(
                                    $arma["nome"]
                                ) ?>
                            </strong>

                            — <?= escaparProfissaoPublica(
                                $nomeTipoArma
                            ) ?>

                            — Mãos: <?= (int) $arma["maos"] ?>

                            — Dano:
                            <?= escaparProfissaoPublica(
                                $arma["dano"]
                            ) ?>

                            <?php if (
                                ($arma["especial"] ?? "") !== ""
                            ): ?>
                                <p>
                                    <?= nl2br(
                                        escaparProfissaoPublica(
                                            $arma["especial"]
                                        )
                                    ) ?>
                                </p>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>

        <section>
            <h2>Vestimentas iniciais</h2>

            <?php if (empty($vestimentas)): ?>
                <p>Nenhuma vestimenta inicial.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($vestimentas as $vestimenta): ?>
                        <?php
                        $nomeTipoVestimenta =
                            $tiposVestimenta[
                                $vestimenta["tipo_slot"]
                            ] ?? $vestimenta["tipo_slot"];
                        ?>

                        <li>
                            <strong>
                                <?= escaparProfissaoPublica(
                                    $nomeTipoVestimenta
                                ) ?>:

                                <?= escaparProfissaoPublica(
                                    $vestimenta["nome"]
                                ) ?>
                            </strong>

                            — PP máximo:
                            <?= (int) $vestimenta[
                                "pontos_protecao_maximo"
                            ] ?>

                            <?php if (
                                ($vestimenta["dano"] ?? "") !== ""
                            ): ?>
                                — Dano:
                                <?= escaparProfissaoPublica(
                                    $vestimenta["dano"]
                                ) ?>
                            <?php endif; ?>

                            <?php if (
                                ($vestimenta["elemento"] ?? "") !== ""
                            ): ?>
                                — Elemento:
                                <?= escaparProfissaoPublica(
                                    $vestimenta["elemento"]
                                ) ?>
                            <?php endif; ?>

                            <?php if (
                                ($vestimenta["especial"] ?? "") !== ""
                            ): ?>
                                <p>
                                    <?= nl2br(
                                        escaparProfissaoPublica(
                                            $vestimenta["especial"]
                                        )
                                    ) ?>
                                </p>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>

        <section>
            <h2>Itens iniciais</h2>

            <?php if (empty($itens)): ?>
                <p>Nenhum item inicial.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($itens as $item): ?>
                        <?php
                        $nomeTipoItem =
                            $tiposItem[$item["tipo"]]
                            ?? $item["tipo"];
                        ?>

                        <li>
                            <strong>
                                <?= escaparProfissaoPublica(
                                    $item["nome"]
                                ) ?>
                            </strong>

                            — Quantidade:
                            <?= (int) $item["quantidade"] ?>

                            — <?= escaparProfissaoPublica(
                                $nomeTipoItem
                            ) ?>

                            <?php if (
                                ($item["descricao"] ?? "") !== ""
                            ): ?>
                                <p>
                                    <?= nl2br(
                                        escaparProfissaoPublica(
                                            $item["descricao"]
                                        )
                                    ) ?>
                                </p>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>

        <p>
            <a href="/Controllers/ProfissaoController.php?acao=publicas">
                Voltar às profissões públicas
            </a>
        </p>
    </main>
</body>

</html>