<?php

$profissoesPublicas = $profissoesPublicas ?? [];

$mensagensErro = [
    "id_invalido" => "A profissão informada é inválida.",
    "nao_encontrada" => "A profissão pública não foi encontrada."
];

$codigoErro = $_GET["erro"] ?? "";
$mensagemErro = $mensagensErro[$codigoErro] ?? "";

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

    <title>Profissões públicas — Diário do Caçador</title>
</head>

<body>
    <main class="pagina pagina-listagem" aria-labelledby="titulo-pagina">
        <header class="cabecalho-com-acoes">
            <div class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Arquivo de profissões
                </p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    Profissões públicas
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Consulte profissões compartilhadas por outros autores.
                    Somente seus autores podem editá-las ou utilizá-las
                    em suas fichas.
                </p>
            </div>

            <nav class="acoes-pagina" aria-label="Ações da página">
                <a class="botao botao--primario" href="/Controllers/ProfissaoController.php?acao=listar">
                    Minhas profissões
                </a>

                <a class="botao botao--secundario" href="/Views/Usuario/painel.php">
                    Voltar ao painel
                </a>
            </nav>
        </header>

        <?php if ($mensagemErro !== ""): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    <?= htmlspecialchars(
                        $mensagemErro,
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>
                </p>
            </div>
        <?php endif; ?>

        <?php if (empty($profissoesPublicas)): ?>
            <div class="estado-vazio">
                <p>Nenhuma profissão pública disponível.</p>

                <p>
                    Quando uma profissão for compartilhada, ela
                    aparecerá neste arquivo para consulta.
                </p>
            </div>
        <?php else: ?>
            <section class="grade-profissoes" aria-label="Profissões públicas disponíveis">
                <?php foreach (
                    $profissoesPublicas as $profissao
                ): ?>
                    <?php
                    $idProfissao =
                        (int) $profissao["id_profissao"];

                    $descricao =
                        $profissao["descricao"] ?? "";
                    ?>

                    <a class="cartao-menu cartao-profissao"
                        href="/Controllers/ProfissaoController.php?acao=visualizar&id=<?= $idProfissao ?>">
                        <p class="cartao-profissao__marca">
                            Profissão pública
                        </p>

                        <h2 class="cartao-menu__titulo cartao-profissao__titulo">
                            <?= htmlspecialchars(
                                $profissao["nome"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </h2>

                        <div class="cartao-profissao__dados">
                            <p>
                                <strong>Autor:</strong>
                                <?= htmlspecialchars(
                                    $profissao["nome_autor"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>
                            </p>

                            <p>
                                <strong>PV máximo:</strong>
                                <?= (int) $profissao["pv_maximo"] ?>
                            </p>
                        </div>

                        <div class="cartao-menu__descricao cartao-profissao__descricao">
                            <?php if ($descricao !== ""): ?>
                                <p>
                                    <?= nl2br(
                                        htmlspecialchars(
                                            $descricao,
                                            ENT_QUOTES,
                                            "UTF-8"
                                        )
                                    ) ?>
                                </p>
                            <?php else: ?>
                                <p>Sem descrição.</p>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </main>
</body>

</html>