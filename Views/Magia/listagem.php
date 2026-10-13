<?php

require_once __DIR__ . "/../../Config/Autenticacao.php";

exigirLogin();

if (!isset($magias)) {
    header("Location: /Controllers/MagiaController.php?acao=listar");
    exit;
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

    <title>Minhas magias — Diário do Caçador</title>
</head>

<body>
    <main class="pagina pagina-listagem" aria-labelledby="titulo-pagina">
        <header class="cabecalho-com-acoes">
            <div class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Grimório do caçador
                </p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    Minhas magias
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Registre magias, elementos e seus efeitos para
                    consultar durante suas jornadas.
                </p>
            </div>

            <nav class="acoes-pagina" aria-label="Ações da página">
                <a class="botao botao--primario" href="/Views/Magia/cadastro.php">
                    Cadastrar magia
                </a>

                <a class="botao botao--secundario" href="/Views/Usuario/painel.php">
                    Voltar ao painel
                </a>
            </nav>
        </header>

        <?php if (($_GET["sucesso"] ?? "") === "atualizada"): ?>
            <div class="mensagem mensagem--sucesso" role="status">
                <p>Magia atualizada com sucesso!</p>
            </div>

        <?php elseif (($_GET["sucesso"] ?? "") === "excluida"): ?>
            <div class="mensagem mensagem--sucesso" role="status">
                <p>Magia excluída com sucesso!</p>
            </div>
        <?php endif; ?>

        <?php if (($_GET["erro"] ?? "") === "magia_nao_encontrada"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Magia não encontrada ou não pertence ao usuário.</p>
            </div>

        <?php elseif (($_GET["erro"] ?? "") === "magia_vinculada"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    Esta magia não pode ser excluída porque está sendo
                    utilizada por uma vantagem ou ficha.
                </p>
            </div>

        <?php elseif (($_GET["erro"] ?? "") === "dados"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Os dados informados são inválidos.</p>
            </div>

        <?php elseif (($_GET["erro"] ?? "") === "exclusao"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Não foi possível excluir a magia.</p>
            </div>
        <?php endif; ?>

        <?php if (empty($magias)): ?>
            <div class="estado-vazio">
                <p>Nenhuma magia cadastrada.</p>

                <p>
                    Cadastre a primeira magia para iniciar este grimório.
                </p>
            </div>
        <?php else: ?>
            <div class="tabela-responsiva">
                <table class="tabela-registro">
                    <caption>Registro de magias</caption>

                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">Elemento</th>
                            <th scope="col">Descrição</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($magias as $magia): ?>
                            <tr>
                                <td class="tabela-registro__nome" data-label="Nome">
                                    <?php
                                    echo htmlspecialchars(
                                        $magia["nome"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );
                                    ?>
                                </td>

                                <td data-label="Elemento">
                                    <?php
                                    echo htmlspecialchars(
                                        $magia["elemento"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );
                                    ?>
                                </td>

                                <td class="tabela-registro__descricao" data-label="Descrição">
                                    <?php
                                    echo nl2br(
                                        htmlspecialchars(
                                            $magia["descricao"] ?? "",
                                            ENT_QUOTES,
                                            "UTF-8"
                                        )
                                    );
                                    ?>
                                </td>

                                <td class="tabela-registro__acoes" data-label="Ações">
                                    <div class="acoes-registro">
                                        <a class="botao botao--secundario botao--pequeno" href="/Controllers/MagiaController.php?acao=editar&id=<?php
                                        echo $magia["id_magia"];
                                        ?>">
                                            Editar
                                        </a>

                                        <form class="acoes-registro__formulario" action="/Controllers/MagiaController.php"
                                            method="POST" onsubmit="return confirm('Deseja realmente excluir esta magia?');">
                                            <input type="hidden" name="acao" value="excluir">

                                            <input type="hidden" name="id_magia" value="<?php
                                            echo $magia["id_magia"];
                                            ?>">

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
</body>

</html>