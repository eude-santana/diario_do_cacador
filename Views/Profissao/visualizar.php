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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&amp;family=Source+Sans+3:wght@400;600;700&amp;display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="/Assets/css/style.css">

    <title>Visualizar profissão — Diário do Caçador</title>
</head>

<body>
    <main class="pagina pagina-detalhes" aria-labelledby="titulo-pagina">
        <div class="cabecalho-com-acoes">
            <header class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Registro público
                </p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    <?= escaparProfissaoPublica(
                        $profissao["nome"] ?? ""
                    ) ?>
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Consulte as características, vantagens e recursos
                    iniciais desta profissão.
                </p>
            </header>

            <nav class="acoes-pagina pagina-detalhes__navegacao" aria-label="Navegação da profissão">
                <a class="botao botao--secundario" href="/Controllers/ProfissaoController.php?acao=publicas">
                    Voltar às profissões públicas
                </a>
            </nav>
        </div>

        <dl class="resumo-profissao" aria-label="Resumo da profissão">
            <div class="resumo-profissao__item">
                <dt class="resumo-profissao__rotulo">
                    Autor
                </dt>

                <dd class="resumo-profissao__valor">
                    <?= escaparProfissaoPublica(
                        $profissao["nome_autor"] ?? ""
                    ) ?>
                </dd>
            </div>

            <div class="resumo-profissao__item">
                <dt class="resumo-profissao__rotulo">
                    PV máximo
                </dt>

                <dd class="resumo-profissao__valor">
                    <?= (int) ($profissao["pv_maximo"] ?? 0) ?>
                </dd>
            </div>
        </dl>

        <section class="secao-profissao" aria-labelledby="titulo-descricao">
            <h2 class="secao-profissao__titulo" id="titulo-descricao">
                Descrição
            </h2>

            <div class="secao-profissao__texto">
                <?php if (
                    ($profissao["descricao"] ?? "") !== ""
                ): ?>
                    <p>
                        <?= nl2br(
                            escaparProfissaoPublica(
                                $profissao["descricao"]
                            )
                        ) ?>
                    </p>
                <?php else: ?>
                    <p class="secao-profissao__vazio">
                        Sem descrição.
                    </p>
                <?php endif; ?>
            </div>
        </section>

        <section class="secao-profissao" aria-labelledby="titulo-vantagens">
            <h2 class="secao-profissao__titulo" id="titulo-vantagens">
                Vantagens
            </h2>

            <?php if (empty($vantagens)): ?>
                <p class="secao-profissao__vazio">
                    Nenhuma vantagem cadastrada.
                </p>
            <?php else: ?>
                <div class="grade-vantagens">
                    <?php foreach ($vantagens as $vantagem): ?>
                        <?php
                        $tipoVantagem =
                            $vantagem["tipo"] ?? "NORMAL";

                        $nomeTipoVantagem =
                            $tiposVantagem[$tipoVantagem]
                            ?? "Normal";
                        ?>

                        <article class="registro-detalhe">
                            <p class="registro-detalhe__marca">
                                Vantagem do slot
                                <?= (int) $vantagem["slot"] ?>
                            </p>

                            <h3 class="registro-detalhe__titulo">
                                <?= escaparProfissaoPublica(
                                    $vantagem["nome"]
                                ) ?>
                            </h3>

                            <p class="registro-detalhe__informacoes">
                                <strong>Tipo:</strong>

                                <?= escaparProfissaoPublica(
                                    $nomeTipoVantagem
                                ) ?>
                            </p>

                            <?php if (
                                ($vantagem["descricao"] ?? "") !== ""
                            ): ?>
                                <div class="registro-detalhe__descricao">
                                    <p>
                                        <?= nl2br(
                                            escaparProfissaoPublica(
                                                $vantagem["descricao"]
                                            )
                                        ) ?>
                                    </p>
                                </div>
                            <?php endif; ?>

                            <?php if (
                                !empty($vantagem["magias"])
                            ): ?>
                                <div class="registro-detalhe__subsecao">
                                    <h4 class="registro-detalhe__subtitulo">
                                        Magias disponíveis
                                    </h4>

                                    <ul class="registro-detalhe__sublista">
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
                                                                $magia[
                                                                    "descricao"
                                                                ]
                                                            )
                                                        ) ?>
                                                    </p>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <?php if (
                                !empty($vantagem["companheiros"])
                            ): ?>
                                <div class="registro-detalhe__subsecao">
                                    <h4 class="registro-detalhe__subtitulo">
                                        Companheiros disponíveis
                                    </h4>

                                    <ul class="registro-detalhe__sublista">
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
                                                    ($companheiro[
                                                        "descricao"
                                                    ] ?? "") !== ""
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
                                </div>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <div class="grade-recursos">
            <section class="secao-profissao secao-profissao--recurso" aria-labelledby="titulo-armas">
                <h2 class="secao-profissao__titulo" id="titulo-armas">
                    Armas iniciais
                </h2>

                <?php if (empty($armas)): ?>
                    <p class="secao-profissao__vazio">
                        Nenhuma arma inicial.
                    </p>
                <?php else: ?>
                    <ul class="lista-recursos">
                        <?php foreach ($armas as $arma): ?>
                            <?php
                            $nomeTipoArma =
                                $tiposArma[$arma["tipo"]]
                                ?? $arma["tipo"];
                            ?>

                            <li class="lista-recursos__item">
                                <h3 class="lista-recursos__titulo">
                                    Slot <?= (int) $arma["slot"] ?>:

                                    <?= escaparProfissaoPublica(
                                        $arma["nome"]
                                    ) ?>
                                </h3>

                                <div class="lista-recursos__dados">
                                    <span>
                                        <strong>Tipo:</strong>

                                        <?= escaparProfissaoPublica(
                                            $nomeTipoArma
                                        ) ?>
                                    </span>

                                    <span>
                                        <strong>Mãos:</strong>
                                        <?= (int) $arma["maos"] ?>
                                    </span>

                                    <span>
                                        <strong>Dano:</strong>

                                        <?= escaparProfissaoPublica(
                                            $arma["dano"]
                                        ) ?>
                                    </span>
                                </div>

                                <?php if (
                                    ($arma["especial"] ?? "") !== ""
                                ): ?>
                                    <p class="lista-recursos__descricao">
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

            <section class="secao-profissao secao-profissao--recurso" aria-labelledby="titulo-vestimentas">
                <h2 class="secao-profissao__titulo" id="titulo-vestimentas">
                    Vestimentas iniciais
                </h2>

                <?php if (empty($vestimentas)): ?>
                    <p class="secao-profissao__vazio">
                        Nenhuma vestimenta inicial.
                    </p>
                <?php else: ?>
                    <ul class="lista-recursos">
                        <?php foreach ($vestimentas as $vestimenta): ?>
                            <?php
                            $nomeTipoVestimenta =
                                $tiposVestimenta[
                                    $vestimenta["tipo_slot"]
                                ] ?? $vestimenta["tipo_slot"];
                            ?>

                            <li class="lista-recursos__item">
                                <h3 class="lista-recursos__titulo">
                                    <?= escaparProfissaoPublica(
                                        $nomeTipoVestimenta
                                    ) ?>:

                                    <?= escaparProfissaoPublica(
                                        $vestimenta["nome"]
                                    ) ?>
                                </h3>

                                <div class="lista-recursos__dados">
                                    <span>
                                        <strong>PP máximo:</strong>

                                        <?= (int) $vestimenta[
                                            "pontos_protecao_maximo"
                                        ] ?>
                                    </span>

                                    <?php if (
                                        ($vestimenta["dano"] ?? "") !== ""
                                    ): ?>
                                        <span>
                                            <strong>Dano:</strong>

                                            <?= escaparProfissaoPublica(
                                                $vestimenta["dano"]
                                            ) ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if (
                                        ($vestimenta["elemento"] ?? "")
                                        !== ""
                                    ): ?>
                                        <span>
                                            <strong>Elemento:</strong>

                                            <?= escaparProfissaoPublica(
                                                $vestimenta["elemento"]
                                            ) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <?php if (
                                    ($vestimenta["especial"] ?? "") !== ""
                                ): ?>
                                    <p class="lista-recursos__descricao">
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

            <section class="secao-profissao secao-profissao--recurso" aria-labelledby="titulo-itens">
                <h2 class="secao-profissao__titulo" id="titulo-itens">
                    Itens iniciais
                </h2>

                <?php if (empty($itens)): ?>
                    <p class="secao-profissao__vazio">
                        Nenhum item inicial.
                    </p>
                <?php else: ?>
                    <ul class="lista-recursos">
                        <?php foreach ($itens as $item): ?>
                            <?php
                            $nomeTipoItem =
                                $tiposItem[$item["tipo"]]
                                ?? $item["tipo"];
                            ?>

                            <li class="lista-recursos__item">
                                <h3 class="lista-recursos__titulo">
                                    <?= escaparProfissaoPublica(
                                        $item["nome"]
                                    ) ?>
                                </h3>

                                <div class="lista-recursos__dados">
                                    <span>
                                        <strong>Quantidade:</strong>
                                        <?= (int) $item["quantidade"] ?>
                                    </span>

                                    <span>
                                        <strong>Tipo:</strong>

                                        <?= escaparProfissaoPublica(
                                            $nomeTipoItem
                                        ) ?>
                                    </span>
                                </div>

                                <?php if (
                                    ($item["descricao"] ?? "") !== ""
                                ): ?>
                                    <p class="lista-recursos__descricao">
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
        </div>
    </main>
</body>

</html>