<?php

$profissoes = $profissoes ?? [];

$mensagensSucesso = [
    "cadastro" => "Profissão cadastrada com sucesso.",
    "edicao" => "Profissão atualizada com sucesso.",
    "exclusao" => "Profissão excluída com sucesso."
];

$mensagensErro = [
    "id_invalido" => "A profissão informada é inválida.",
    "nao_encontrada" => "Profissão não encontrada.",
    "em_uso" => "A profissão está sendo utilizada por uma ficha.",
    "exclusao" => "Não foi possível excluir a profissão."
];

$codigoSucesso = $_GET["sucesso"] ?? "";
$codigoErro = $_GET["erro"] ?? "";

$mensagemSucesso =
    $mensagensSucesso[$codigoSucesso] ?? "";

$mensagemErro =
    $mensagensErro[$codigoErro] ?? "";

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

    <title>Minhas profissões — Diário do Caçador</title>
</head>

<body>
    <main class="pagina pagina-listagem" aria-labelledby="titulo-pagina">
        <div class="cabecalho-com-acoes cabecalho-com-acoes--muitas-acoes">
            <header class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Arquivo do caçador
                </p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    Minhas profissões
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Crie e gerencie as profissões utilizadas pelos
                    seus personagens.
                </p>
            </header>

            <nav class="acoes-pagina" aria-label="Ações da página">
                <a class="botao botao--primario" href="/Controllers/ProfissaoController.php?acao=novo">
                    Cadastrar nova profissão
                </a>

                <a class="botao botao--secundario" href="/Controllers/ProfissaoController.php?acao=publicas">
                    Ver profissões públicas
                </a>

                <a class="botao botao--secundario" href="/Views/Usuario/painel.php">
                    Voltar ao painel
                </a>
            </nav>
        </div>

        <?php if ($mensagemSucesso !== ""): ?>
            <div class="mensagem mensagem--sucesso" role="status">
                <p>
                    <?= htmlspecialchars(
                        $mensagemSucesso,
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>
                </p>
            </div>
        <?php endif; ?>

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

        <?php if (empty($profissoes)): ?>
            <section class="estado-vazio">
                <p>Nenhuma profissão cadastrada.</p>

                <p>
                    Cadastre uma profissão para definir vantagens e
                    recursos iniciais dos seus personagens.
                </p>
            </section>
        <?php else: ?>
            <div class="tabela-responsiva">
                <table class="tabela-registro tabela-registro--muitas-colunas">
                    <caption>
                        Profissões cadastradas
                    </caption>

                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">Descrição</th>
                            <th scope="col">PV máximo</th>
                            <th scope="col">Visibilidade</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($profissoes as $profissao): ?>
                            <?php
                            $emUso = (bool) (
                                $profissao["em_uso"] ?? false
                            );

                            $idProfissao =
                                (int) $profissao["id_profissao"];

                            $descricao =
                                $profissao["descricao"] ?? "";

                            $visibilidade =
                                $profissao["visibilidade"]
                                ?? "PRIVADO";

                            $nomeVisibilidade =
                                $visibilidade === "PUBLICO"
                                ? "Público"
                                : "Privado";
                            ?>

                            <tr>
                                <td class="tabela-registro__nome" data-label="Nome">
                                    <?= htmlspecialchars(
                                        $profissao["nome"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>
                                </td>

                                <td class="tabela-registro__descricao" data-label="Descrição">
                                    <?php if ($descricao !== ""): ?>
                                        <?= nl2br(
                                            htmlspecialchars(
                                                $descricao,
                                                ENT_QUOTES,
                                                "UTF-8"
                                            )
                                        ) ?>
                                    <?php else: ?>
                                        Sem descrição
                                    <?php endif; ?>
                                </td>

                                <td class="tabela-registro__tipo" data-label="PV máximo">
                                    <?= (int) $profissao["pv_maximo"] ?>
                                </td>

                                <td class="tabela-registro__tipo" data-label="Visibilidade">
                                    <?= htmlspecialchars(
                                        $nomeVisibilidade,
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>
                                </td>

                                <td class="tabela-registro__acoes" data-label="Ações">
                                    <?php if ($emUso): ?>
                                        <span class="estado-registro">
                                            <strong>Em uso.</strong>

                                            Esta profissão não pode ser
                                            alterada ou excluída.
                                        </span>
                                    <?php else: ?>
                                        <div class="acoes-registro">
                                            <a class="botao botao--secundario botao--pequeno"
                                                href="/Controllers/ProfissaoController.php?acao=editar&amp;id=<?= $idProfissao ?>">
                                                Editar
                                            </a>

                                            <form class="acoes-registro__formulario" action="/Controllers/ProfissaoController.php"
                                                method="POST" onsubmit="return confirmarExclusao();">
                                                <input type="hidden" name="acao" value="excluir">

                                                <input type="hidden" name="id_profissao" value="<?= $idProfissao ?>">

                                                <button class="botao botao--perigo botao--pequeno" type="submit">
                                                    Excluir
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>

    <script>
        function confirmarExclusao() {
            return confirm(
                "Deseja realmente excluir esta profissão?"
            );
        }
    </script>
</body>

</html>