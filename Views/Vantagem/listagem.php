<?php

require_once __DIR__ . "/../../Config/Autenticacao.php";

exigirLogin();

if (!isset($vantagens)) {
    header("Location: /Controllers/VantagemController.php?acao=listar");
    exit;
}

$mensagensSucesso = [
    "cadastro" => "Vantagem cadastrada com sucesso.",
    "edicao" => "Vantagem atualizada com sucesso.",
    "exclusao" => "Vantagem excluída com sucesso."
];

$mensagensErro = [
    "id_invalido" => "A vantagem informada é inválida.",
    "nao_encontrada" => "Vantagem não encontrada.",
    "em_uso" => "A vantagem está sendo utilizada por uma profissão.",
    "exclusao" => "Não foi possível excluir a vantagem."
];

$tipos = [
    "NORMAL" => "Normal",
    "MAGIA" => "Concede magia",
    "COMPANHEIRO" => "Concede companheiro animal"
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

    <title>Minhas vantagens — Diário do Caçador</title>
</head>

<body>
    <main class="pagina pagina-listagem" aria-labelledby="titulo-pagina">
        <header class="cabecalho-com-acoes">
            <div class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Recursos de personagem
                </p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    Minhas vantagens
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Registre benefícios e habilidades que poderão
                    compor as profissões de seus personagens.
                </p>
            </div>

            <nav class="acoes-pagina" aria-label="Ações da página">
                <a class="botao botao--primario" href="/Controllers/VantagemController.php?acao=novo">
                    Cadastrar vantagem
                </a>

                <a class="botao botao--secundario" href="/Views/Usuario/painel.php">
                    Voltar ao painel
                </a>
            </nav>
        </header>

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

        <?php if (empty($vantagens)): ?>
            <div class="estado-vazio">
                <p>Nenhuma vantagem cadastrada.</p>

                <p>
                    Cadastre a primeira vantagem para iniciar
                    este registro de habilidades.
                </p>
            </div>
        <?php else: ?>
            <div class="tabela-responsiva">
                <table class="tabela-registro">
                    <caption>Registro de vantagens</caption>

                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">Descrição</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($vantagens as $vantagem): ?>
                            <?php
                            $idVantagem =
                                (int) $vantagem["id_vantagem"];

                            $tipoVantagem =
                                $vantagem["tipo"] ?? "NORMAL";

                            $nomeTipo =
                                $tipos[$tipoVantagem]
                                ?? "Tipo desconhecido";
                            ?>

                            <tr>
                                <td class="tabela-registro__nome" data-label="Nome">
                                    <?= htmlspecialchars(
                                        $vantagem["nome"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>
                                </td>

                                <td class="tabela-registro__descricao" data-label="Descrição">
                                    <?php
                                    $descricao =
                                        $vantagem["descricao"] ?? "";
                                    ?>

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

                                <td class="tabela-registro__tipo" data-label="Tipo">
                                    <?= htmlspecialchars(
                                        $nomeTipo,
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>
                                </td>

                                <td class="tabela-registro__acoes" data-label="Ações">
                                    <div class="acoes-registro">
                                        <a class="botao botao--secundario botao--pequeno"
                                            href="/Controllers/VantagemController.php?acao=editar&id=<?= $idVantagem ?>">
                                            Editar
                                        </a>

                                        <form class="acoes-registro__formulario" action="/Controllers/VantagemController.php"
                                            method="POST" onsubmit="return confirmarExclusao();">
                                            <input type="hidden" name="acao" value="excluir">

                                            <input type="hidden" name="id_vantagem" value="<?= $idVantagem ?>">

                                            <button class="botao botao--perigo botao--pequeno" type="submit">
                                                Excluir
                                            </button>
                                        </form>
                                    </div>
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
                "Deseja realmente excluir esta vantagem?"
            );
        }
    </script>
</body>

</html>