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

    <title>
        Meus companheiros animais — Diário do Caçador
    </title>
</head>

<body>
    <h1>Meus companheiros animais</h1>

    <?php if (($_GET["sucesso"] ?? "") === "atualizado"): ?>
        <p>Companheiro atualizado com sucesso!</p>

    <?php elseif (($_GET["sucesso"] ?? "") === "excluido"): ?>
        <p>Companheiro excluído com sucesso!</p>
    <?php endif; ?>

    <?php
    $erro = $_GET["erro"] ?? "";
    ?>

    <?php if ($erro === "companheiro_nao_encontrado"): ?>
        <p>
            Companheiro não encontrado ou não pertence ao usuário.
        </p>

    <?php elseif ($erro === "companheiro_vinculado"): ?>
        <p>
            Este companheiro não pode ser excluído porque está
            sendo utilizado por uma vantagem ou ficha.
        </p>

    <?php elseif ($erro === "dados"): ?>
        <p>Os dados informados são inválidos.</p>

    <?php elseif ($erro === "exclusao"): ?>
        <p>Não foi possível excluir o companheiro.</p>
    <?php endif; ?>

    <?php if (empty($companheiros)): ?>
        <p>Nenhum companheiro animal cadastrado.</p>
    <?php else: ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>PV máximo</th>
                    <th>Dano</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($companheiros as $companheiro): ?>
                    <tr>
                        <td>
                            <?php
                            echo htmlspecialchars(
                                $companheiro["tipo"]
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo (int) $companheiro["pv_maximo"];
                            ?>
                        </td>

                        <td>
                            <?php if (($companheiro["dano"] ?? "") !== ""): ?>
                                <?php
                                echo htmlspecialchars(
                                    $companheiro["dano"]
                                );
                                ?>
                            <?php else: ?>
                                Sem dano
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php
                            echo nl2br(
                                htmlspecialchars(
                                    $companheiro["descricao"] ?? ""
                                )
                            );
                            ?>
                        </td>

                        <td>
                            <a href="/Controllers/CompanheiroAnimalController.php?acao=editar&id=<?php
                            echo $companheiro["id_companheiro"];
                            ?>">
                                Editar
                            </a>

                            <form action="/Controllers/CompanheiroAnimalController.php" method="POST" onsubmit="return confirm(
                                    'Deseja realmente excluir este companheiro?'
                                );">
                                <input type="hidden" name="acao" value="excluir">

                                <input type="hidden" name="id_companheiro" value="<?php
                                echo $companheiro[
                                    "id_companheiro"
                                ];
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
        <a href="/Views/CompanheiroAnimal/cadastro.php">
            Cadastrar novo companheiro
        </a>
    </p>

    <p>
        <a href="/Views/Usuario/painel.php">
            Voltar ao painel
        </a>
    </p>
</body>

</html>