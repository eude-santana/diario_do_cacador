<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Minhas magias — Diário do Caçador</title>
</head>

<body>
    <h1>Minhas magias</h1>

    <?php if (($_GET["sucesso"] ?? "") === "atualizada"): ?>
        <p>Magia atualizada com sucesso!</p>

    <?php elseif (($_GET["sucesso"] ?? "") === "excluida"): ?>
        <p>Magia excluída com sucesso!</p>
    <?php endif; ?>

    <?php if (($_GET["erro"] ?? "") === "magia_nao_encontrada"): ?>
        <p>Magia não encontrada ou não pertence ao usuário.</p>

    <?php elseif (($_GET["erro"] ?? "") === "magia_vinculada"): ?>
        <p>
            Esta magia não pode ser excluída porque está sendo
            utilizada por uma vantagem ou ficha.
        </p>

    <?php elseif (($_GET["erro"] ?? "") === "dados"): ?>
        <p>Os dados informados são inválidos.</p>

    <?php elseif (($_GET["erro"] ?? "") === "exclusao"): ?>
        <p>Não foi possível excluir a magia.</p>
    <?php endif; ?>

    <?php if (empty($magias)): ?>
        <p>Nenhuma magia cadastrada.</p>
    <?php else: ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Elemento</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($magias as $magia): ?>
                    <tr>
                        <td>
                            <?php
                            echo htmlspecialchars($magia["nome"]);
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars($magia["elemento"]);
                            ?>
                        </td>

                        <td>
                            <?php
                            echo nl2br(
                                htmlspecialchars(
                                    $magia["descricao"] ?? ""
                                )
                            );
                            ?>
                        </td>

                        <td>
                            <a href="/Controllers/MagiaController.php?acao=editar&id=<?php
                            echo $magia["id_magia"];
                            ?>">
                                Editar
                            </a>

                            <form action="/Controllers/MagiaController.php" method="POST" onsubmit="return confirm(
                                    'Deseja realmente excluir esta magia?'
                                );">
                                <input type="hidden" name="acao" value="excluir">

                                <input type="hidden" name="id_magia" value="<?php
                                echo $magia["id_magia"];
                                ?>">

                                <button type="submit">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p>
        <a href="/Views/Magia/cadastro.php">
            Cadastrar nova magia
        </a>
    </p>

    <p>
        <a href="/Views/Usuario/painel.php">
            Voltar ao painel
        </a>
    </p>
</body>

</html>