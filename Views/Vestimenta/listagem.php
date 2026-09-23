<?php

require_once __DIR__ . "/../../Config/Autenticacao.php";

exigirLogin();

if (!isset($vestimentas)) {
    header("Location: /Controllers/VestimentaController.php?acao=listar");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Minhas vestimentas — Diário do Caçador
    </title>
</head>

<body>
    <h1>Minhas vestimentas</h1>

    <?php if (($_GET["sucesso"] ?? "") === "atualizada"): ?>
        <p>Vestimenta atualizada com sucesso!</p>

    <?php elseif (($_GET["sucesso"] ?? "") === "excluida"): ?>
        <p>Vestimenta excluída com sucesso!</p>
    <?php endif; ?>

    <?php
    $erro = $_GET["erro"] ?? "";
    ?>

    <?php if ($erro === "vestimenta_nao_encontrada"): ?>
        <p>
            Vestimenta não encontrada ou não pertence ao usuário.
        </p>

    <?php elseif ($erro === "vestimenta_vinculada"): ?>
        <p>
            Esta vestimenta não pode ser excluída porque está
            sendo utilizada por uma profissão ou ficha.
        </p>

    <?php elseif ($erro === "dados"): ?>
        <p>Os dados informados são inválidos.</p>

    <?php elseif ($erro === "exclusao"): ?>
        <p>Não foi possível excluir a vestimenta.</p>
    <?php endif; ?>

    <?php if (empty($vestimentas)): ?>
        <p>Nenhuma vestimenta cadastrada.</p>
    <?php else: ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>PP máximo</th>
                    <th>Dano</th>
                    <th>Elemento</th>
                    <th>Especial</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($vestimentas as $vestimenta): ?>
                    <tr>
                        <td>
                            <?php
                            echo htmlspecialchars(
                                $vestimenta["nome"]
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            $nomesTipos = [
                                "ARMADURA" => "Armadura",
                                "ELMO" => "Elmo",
                                "BRACELETES" => "Braceletes",
                                "BOTAS" => "Botas",
                                "ESCUDO" => "Escudo"
                            ];

                            echo $nomesTipos[
                                $vestimenta["tipo"]
                            ];
                            ?>
                        </td>

                        <td>
                            <?php
                            echo (int) $vestimenta[
                                "pontos_protecao_maximo"
                            ];
                            ?>
                        </td>

                        <td>
                            <?php if (($vestimenta["dano"] ?? "") !== ""): ?>
                                <?php
                                echo htmlspecialchars(
                                    $vestimenta["dano"]
                                );
                                ?>
                            <?php else: ?>
                                Não se aplica
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (($vestimenta["elemento"] ?? "") !== ""): ?>
                                <?php
                                echo htmlspecialchars(
                                    $vestimenta["elemento"]
                                );
                                ?>
                            <?php else: ?>
                                Não se aplica
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php
                            echo nl2br(
                                htmlspecialchars(
                                    $vestimenta["especial"] ?? ""
                                )
                            );
                            ?>
                        </td>

                        <td>
                            <a href="/Controllers/VestimentaController.php?acao=editar&id=<?php
                            echo $vestimenta["id_vestimenta"];
                            ?>">
                                Editar
                            </a>

                            <form action="/Controllers/VestimentaController.php" method="POST" onsubmit="return confirm(
                                    'Deseja realmente excluir esta vestimenta?'
                                );">
                                <input type="hidden" name="acao" value="excluir">

                                <input type="hidden" name="id_vestimenta" value="<?php
                                echo $vestimenta[
                                    "id_vestimenta"
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
        <a href="/Views/Vestimenta/cadastro.php">
            Cadastrar nova vestimenta
        </a>
    </p>

    <p>
        <a href="/Views/Usuario/painel.php">
            Voltar ao painel
        </a>
    </p>
</body>

</html>