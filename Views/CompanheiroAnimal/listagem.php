<?php

require_once __DIR__ . "/../../Config/Autenticacao.php";

exigirLogin();

if (!isset($companheiros)) {
    header(
        "Location: /Controllers/CompanheiroAnimalController.php?acao=listar"
    );
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

    <title>
        Meus companheiros animais — Diário do Caçador
    </title>
</head>

<body>
    <main class="pagina pagina-listagem" aria-labelledby="titulo-pagina">
        <header class="cabecalho-com-acoes">
            <div class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Bestiário do caçador
                </p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    Meus companheiros animais
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Registre criaturas aliadas, seus pontos de vida,
                    capacidades de combate e características.
                </p>
            </div>

            <nav class="acoes-pagina" aria-label="Ações da página">
                <a class="botao botao--primario" href="/Views/CompanheiroAnimal/cadastro.php">
                    Cadastrar companheiro
                </a>

                <a class="botao botao--secundario" href="/Views/Usuario/painel.php">
                    Voltar ao painel
                </a>
            </nav>
        </header>

        <?php if (($_GET["sucesso"] ?? "") === "atualizado"): ?>
            <div class="mensagem mensagem--sucesso" role="status">
                <p>Companheiro atualizado com sucesso!</p>
            </div>

        <?php elseif (($_GET["sucesso"] ?? "") === "excluido"): ?>
            <div class="mensagem mensagem--sucesso" role="status">
                <p>Companheiro excluído com sucesso!</p>
            </div>
        <?php endif; ?>

        <?php
        $erro = $_GET["erro"] ?? "";
        ?>

        <?php if ($erro === "companheiro_nao_encontrado"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    Companheiro não encontrado ou não pertence ao usuário.
                </p>
            </div>

        <?php elseif ($erro === "companheiro_vinculado"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    Este companheiro não pode ser excluído porque está
                    sendo utilizado por uma vantagem ou ficha.
                </p>
            </div>

        <?php elseif ($erro === "dados"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Os dados informados são inválidos.</p>
            </div>

        <?php elseif ($erro === "exclusao"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Não foi possível excluir o companheiro.</p>
            </div>
        <?php endif; ?>

        <?php if (empty($companheiros)): ?>
            <div class="estado-vazio">
                <p>Nenhum companheiro animal cadastrado.</p>

                <p>
                    Cadastre o primeiro companheiro para iniciar
                    este bestiário.
                </p>
            </div>
        <?php else: ?>
            <div class="tabela-responsiva">
                <table class="tabela-registro">
                    <caption>Registro de companheiros animais</caption>

                    <thead>
                        <tr>
                            <th scope="col">Tipo</th>
                            <th scope="col">PV máximo</th>
                            <th scope="col">Dano</th>
                            <th scope="col">Descrição</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($companheiros as $companheiro): ?>
                            <tr>
                                <td class="tabela-registro__tipo" data-label="Tipo">
                                    <?php
                                    echo htmlspecialchars(
                                        $companheiro["tipo"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );
                                    ?>
                                </td>

                                <td data-label="PV máximo">
                                    <?php
                                    echo (int) $companheiro["pv_maximo"];
                                    ?>
                                </td>

                                <td data-label="Dano">
                                    <?php if (($companheiro["dano"] ?? "") !== ""): ?>
                                        <?php
                                        echo htmlspecialchars(
                                            $companheiro["dano"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );
                                        ?>
                                    <?php else: ?>
                                        Sem dano
                                    <?php endif; ?>
                                </td>

                                <td class="tabela-registro__descricao" data-label="Descrição">
                                    <?php
                                    echo nl2br(
                                        htmlspecialchars(
                                            $companheiro["descricao"] ?? "",
                                            ENT_QUOTES,
                                            "UTF-8"
                                        )
                                    );
                                    ?>
                                </td>

                                <td class="tabela-registro__acoes" data-label="Ações">
                                    <div class="acoes-registro">
                                        <a class="botao botao--secundario botao--pequeno" href="/Controllers/CompanheiroAnimalController.php?acao=editar&id=<?php
                                        echo $companheiro["id_companheiro"];
                                        ?>">
                                            Editar
                                        </a>

                                        <form class="acoes-registro__formulario"
                                            action="/Controllers/CompanheiroAnimalController.php" method="POST"
                                            onsubmit="return confirm('Deseja realmente excluir este companheiro?');">
                                            <input type="hidden" name="acao" value="excluir">

                                            <input type="hidden" name="id_companheiro" value="<?php
                                            echo $companheiro[
                                                "id_companheiro"
                                            ];
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