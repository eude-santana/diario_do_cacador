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

    <title>Meus itens — Diário do Caçador</title>
</head>

<body>
    <h1>Meus itens</h1>

    <?php if (($_GET["sucesso"] ?? "") === "atualizado"): ?>
        <p>Item atualizado com sucesso!</p>
    <?php endif; ?>

    <?php if (($_GET["erro"] ?? "") === "item_nao_encontrado"): ?>
        <p>Item não encontrado ou não pertence ao usuário.</p>
    <?php endif; ?>

    <?php if (($_GET["sucesso"] ?? "") === "excluido"): ?>
        <p>Item excluído com sucesso!</p>
    <?php endif; ?>

    <?php if (($_GET["erro"] ?? "") === "item_vinculado"): ?>
        <p>
            Este item não pode ser excluído porque está sendo usado
            por uma profissão ou ficha.
        </p>
    <?php elseif (($_GET["erro"] ?? "") === "exclusao"): ?>
        <p>Não foi possível excluir o item.</p>
    <?php elseif (($_GET["erro"] ?? "") === "dados"): ?>
        <p>Item inválido.</p>
    <?php endif; ?>

    <?php if (empty($itens)): ?>
        <p>Nenhum item cadastrado.</p>
    <?php else: ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($itens as $item): ?>
                    <tr>
                        <td>
                            <?php
                            echo htmlspecialchars(
                                $item["nome"],
                                ENT_QUOTES,
                                "UTF-8"
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $nomesTipos[$item["tipo"]],
                                ENT_QUOTES,
                                "UTF-8"
                            );
                            ?>
                        </td>

                        <td>
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

                        <td>
                            <a href="/Controllers/ItemController.php?acao=editar&id=<?php
                            echo $item["id_item"];
                            ?>">
                                Editar
                            </a>

                            <form action="/Controllers/ItemController.php" method="POST"
                                onsubmit="return confirm('Deseja realmente excluir este item?');">
                                <input type="hidden" name="acao" value="excluir">

                                <input type="hidden" name="id_item" value="<?php echo $item["id_item"]; ?>">

                                <button type="submit">Excluir</button>
                            </form>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p>
        <a href="/Views/Item/cadastro.php">Cadastrar novo item</a>
    </p>

    <p>
        <a href="/Views/Usuario/painel.php">Voltar ao painel</a>
    </p>
</body>

</html>