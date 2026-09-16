<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Minhas armas — Diário do Caçador</title>
</head>

<body>
    <h1>Minhas armas</h1>

    <?php if (($_GET["sucesso"] ?? "") === "atualizada"): ?>
        <p>Arma atualizada com sucesso!</p>
    <?php elseif (($_GET["sucesso"] ?? "") === "excluida"): ?>
        <p>Arma excluída com sucesso!</p>
    <?php endif; ?>

    <?php if (($_GET["erro"] ?? "") === "arma_nao_encontrada"): ?>
        <p>Arma não encontrada ou não pertence ao usuário.</p>

    <?php elseif (($_GET["erro"] ?? "") === "arma_vinculada"): ?>
        <p>
            Esta arma não pode ser excluída porque está sendo
            utilizada por uma profissão ou ficha.
        </p>

    <?php elseif (($_GET["erro"] ?? "") === "dados"): ?>
        <p>Os dados informados são inválidos.</p>

    <?php elseif (($_GET["erro"] ?? "") === "exclusao"): ?>
        <p>Não foi possível excluir a arma.</p>
    <?php endif; ?>

    <?php if (empty($armas)): ?>
        <p>Nenhuma arma cadastrada.</p>
    <?php else: ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Mãos</th>
                    <th>Dano</th>
                    <th>Especial</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($armas as $arma): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($arma["nome"]); ?>
                        </td>

                        <td>
                            <?php
                            if ($arma["tipo"] === "CORPORAL") {
                                echo "Corporal";
                            } else {
                                echo "Distância";
                            }
                            ?>
                        </td>

                        <td>
                            <?php
                            if ((int) $arma["maos"] === 1) {
                                echo "Uma mão";
                            } else {
                                echo "Duas mãos";
                            }
                            ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($arma["dano"]); ?>
                        </td>

                        <td>
                            <?php
                            echo nl2br(
                                htmlspecialchars($arma["especial"] ?? "")
                            );
                            ?>
                        </td>

                        <td>
                            <a href="/Controllers/ArmaController.php?acao=editar&id=<?php
                            echo $arma["id_arma"];
                            ?>">
                                Editar
                            </a>

                            <form action="/Controllers/ArmaController.php" method="POST" onsubmit="return confirm(
                                    'Deseja realmente excluir esta arma?'
                                );">
                                <input type="hidden" name="acao" value="excluir">

                                <input type="hidden" name="id_arma" value="<?php
                                echo $arma["id_arma"];
                                ?>">

                                <button type="submit">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p>
        <a href="/Views/Arma/cadastro.php">
            Cadastrar nova arma
        </a>
    </p>

    <p>
        <a href="/Views/Usuario/painel.php">
            Voltar ao painel
        </a>
    </p>
</body>

</html>