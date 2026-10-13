<?php

require_once __DIR__ . "/../../Config/Autenticacao.php";

exigirLogin();

if (!isset($itens)) {
    header("Location: /Controllers/ItemController.php?acao=listar");
    exit;
}

$nomesTipos = [
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

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&amp;family=Source+Sans+3:wght@400;600;700&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/Assets/css/style.css">

    <title>Meus itens — Diário do Caçador</title>
</head>

<body>
    <main class="pagina pagina-listagem" aria-labelledby="titulo-pagina">
        <header class="cabecalho-com-acoes">
            <div class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">Catálogo de campanha</p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    Meus itens
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Registre materiais, consumíveis e recursos utilizados em suas jornadas.
                </p>
            </div>

            <nav class="acoes-pagina" aria-label="Ações da página">
                <a class="botao botao--primario" href="/Views/Item/cadastro.php">
                    Cadastrar item
                </a>

                <a class="botao botao--secundario" href="/Views/Usuario/painel.php">
                    Voltar ao painel
                </a>
            </nav>
        </header>

        <?php if (($_GET["sucesso"] ?? "") === "atualizado"): ?>
            <div class="mensagem mensagem--sucesso" role="status">
                <p>Item atualizado com sucesso!</p>
            </div>
        <?php endif; ?>

        <?php if (($_GET["erro"] ?? "") === "item_nao_encontrado"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Item não encontrado ou não pertence ao usuário.</p>
            </div>
        <?php endif; ?>

        <?php if (($_GET["sucesso"] ?? "") === "excluido"): ?>
            <div class="mensagem mensagem--sucesso" role="status">
                <p>Item excluído com sucesso!</p>
            </div>
        <?php endif; ?>

        <?php if (($_GET["erro"] ?? "") === "item_vinculado"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    Este item não pode ser excluído porque está sendo usado
                    por uma profissão ou ficha.
                </p>
            </div>
        <?php elseif (($_GET["erro"] ?? "") === "exclusao"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Não foi possível excluir o item.</p>
            </div>
        <?php elseif (($_GET["erro"] ?? "") === "dados"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Item inválido.</p>
            </div>
        <?php endif; ?>

        <?php if (empty($itens)): ?>
            <div class="estado-vazio">
                <p>Nenhum item cadastrado.</p>
                <p>Cadastre o primeiro item para iniciar este registro.</p>
            </div>
        <?php else: ?>
            <div class="tabela-responsiva">
                <table class="tabela-registro">
                    <caption>Registro de itens</caption>

                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Descrição</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($itens as $item): ?>
                            <tr>
                                <td class="tabela-registro__nome" data-label="Nome">
                                    <?php
                                    echo htmlspecialchars(
                                        $item["nome"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );
                                    ?>
                                </td>

                                <td class="tabela-registro__tipo" data-label="Tipo">
                                    <?php
                                    echo htmlspecialchars(
                                        $nomesTipos[$item["tipo"]],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );
                                    ?>
                                </td>

                                <td class="tabela-registro__descricao" data-label="Descrição">
                                    <?php
                                    echo nl2br(
                                        htmlspecialchars(
                                            $item["descricao"] ?? "",
                                            ENT_QUOTES,
                                            "UTF-8"
                                        )
                                    );
                                    ?>
                                </td>

                                <td class="tabela-registro__acoes" data-label="Ações">
                                    <div class="acoes-registro">
                                        <a class="botao botao--secundario botao--pequeno"
                                            href="/Controllers/ItemController.php?acao=editar&id=<?php echo $item["id_item"]; ?>">
                                            Editar
                                        </a>

                                        <form class="acoes-registro__formulario" action="/Controllers/ItemController.php"
                                            method="POST" onsubmit="return confirm('Deseja realmente excluir este item?');">
                                            <input type="hidden" name="acao" value="excluir">

                                            <input type="hidden" name="id_item" value="<?php echo $item["id_item"]; ?>">

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