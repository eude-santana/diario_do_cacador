<?php

require_once __DIR__ . "/../../Config/Autenticacao.php";

exigirLogin();

if (!isset($armas)) {
    header("Location: /Controllers/ArmaController.php?acao=listar");
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

    <title>Minhas armas — Diário do Caçador</title>
</head>

<body>
    <main class="pagina pagina-listagem" aria-labelledby="titulo-pagina">
        <header class="cabecalho-com-acoes">
            <div class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Catálogo de campanha
                </p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    Minhas armas
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Registre armas corporais e à distância para compor
                    o arsenal de suas jornadas.
                </p>
            </div>

            <nav class="acoes-pagina" aria-label="Ações da página">
                <a class="botao botao--primario" href="/Views/Arma/cadastro.php">
                    Cadastrar arma
                </a>

                <a class="botao botao--secundario" href="/Views/Usuario/painel.php">
                    Voltar ao painel
                </a>
            </nav>
        </header>

        <?php if (($_GET["sucesso"] ?? "") === "atualizada"): ?>
            <div class="mensagem mensagem--sucesso" role="status">
                <p>Arma atualizada com sucesso!</p>
            </div>

        <?php elseif (($_GET["sucesso"] ?? "") === "excluida"): ?>
            <div class="mensagem mensagem--sucesso" role="status">
                <p>Arma excluída com sucesso!</p>
            </div>
        <?php endif; ?>

        <?php if (($_GET["erro"] ?? "") === "arma_nao_encontrada"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Arma não encontrada ou não pertence ao usuário.</p>
            </div>

        <?php elseif (($_GET["erro"] ?? "") === "arma_vinculada"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    Esta arma não pode ser excluída porque está sendo
                    utilizada por uma profissão ou ficha.
                </p>
            </div>

        <?php elseif (($_GET["erro"] ?? "") === "dados"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Os dados informados são inválidos.</p>
            </div>

        <?php elseif (($_GET["erro"] ?? "") === "exclusao"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Não foi possível excluir a arma.</p>
            </div>
        <?php endif; ?>

        <?php if (empty($armas)): ?>
            <div class="estado-vazio">
                <p>Nenhuma arma cadastrada.</p>

                <p>
                    Cadastre a primeira arma para iniciar este arsenal.
                </p>
            </div>
        <?php else: ?>
            <div class="tabela-responsiva">
                <table class="tabela-registro">
                    <caption>Registro de armas</caption>

                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Mãos</th>
                            <th scope="col">Dano</th>
                            <th scope="col">Especial</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($armas as $arma): ?>
                            <tr>
                                <td class="tabela-registro__nome" data-label="Nome">
                                    <?php
                                    echo htmlspecialchars(
                                        $arma["nome"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );
                                    ?>
                                </td>

                                <td class="tabela-registro__tipo" data-label="Tipo">
                                    <?php
                                    if ($arma["tipo"] === "CORPORAL") {
                                        echo "Corporal";
                                    } else {
                                        echo "Distância";
                                    }
                                    ?>
                                </td>

                                <td data-label="Mãos">
                                    <?php
                                    if ((int) $arma["maos"] === 1) {
                                        echo "Uma mão";
                                    } else {
                                        echo "Duas mãos";
                                    }
                                    ?>
                                </td>

                                <td data-label="Dano">
                                    <?php
                                    echo htmlspecialchars(
                                        $arma["dano"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );
                                    ?>
                                </td>

                                <td class="tabela-registro__descricao" data-label="Especial">
                                    <?php
                                    echo nl2br(
                                        htmlspecialchars(
                                            $arma["especial"] ?? "",
                                            ENT_QUOTES,
                                            "UTF-8"
                                        )
                                    );
                                    ?>
                                </td>

                                <td class="tabela-registro__acoes" data-label="Ações">
                                    <div class="acoes-registro">
                                        <a class="botao botao--secundario botao--pequeno" href="/Controllers/ArmaController.php?acao=editar&id=<?php
                                        echo $arma["id_arma"];
                                        ?>">
                                            Editar
                                        </a>

                                        <form class="acoes-registro__formulario" action="/Controllers/ArmaController.php"
                                            method="POST" onsubmit="return confirm('Deseja realmente excluir esta arma?');">
                                            <input type="hidden" name="acao" value="excluir">

                                            <input type="hidden" name="id_arma" value="<?php
                                            echo $arma["id_arma"];
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