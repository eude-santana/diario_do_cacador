<?php

$fichas = $fichas ?? [];
$erro = $erro ?? '';
$mensagem = $mensagem ?? '';

function escaparListagemFicha(mixed $valor): string
{
    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        'UTF-8'
    );
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Meus personagens</title>
</head>

<body>
    <h1>Meus personagens</h1>

    <?php if ($mensagem !== ''): ?>
        <p>
            <?php echo escaparListagemFicha($mensagem); ?>
        </p>
    <?php endif; ?>

    <?php if ($erro !== ''): ?>
        <p>
            <?php echo escaparListagemFicha($erro); ?>
        </p>
    <?php endif; ?>

    <p>
        <a href="/Controllers/FichaController.php?acao=novo">
            Criar personagem
        </a>
    </p>

    <?php if (empty($fichas)): ?>
        <p>
            Nenhum personagem cadastrado.
        </p>
    <?php else: ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Personagem</th>
                    <th>Profissão</th>
                    <th>PV</th>
                    <th>Fadiga</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($fichas as $ficha): ?>
                    <tr>
                        <td>
                            <?php
                            echo escaparListagemFicha(
                                $ficha['nome']
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo escaparListagemFicha(
                                $ficha['nome_profissao']
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo (int) $ficha['pv_atual'];
                            ?>
                            /
                            <?php
                            echo (int) $ficha['pv_maximo'];
                            ?>
                        </td>

                        <td>
                            <?php
                            echo (int) $ficha['fadiga'];
                            ?>
                            / 6
                        </td>

                        <td>
                            <!--
                            O botão Gerenciar será adicionado quando
                            criarmos a tela de gerenciamento da ficha.
                            -->

                            <form action="/Controllers/FichaController.php" method="POST" onsubmit="
                                    return confirm(
                                        'Deseja realmente excluir esta ficha?'
                                    );
                                ">
                                <input type="hidden" name="acao" value="excluir">

                                <input type="hidden" name="id_ficha" value="<?php
                                echo (int) $ficha['id_ficha'];
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

    <br>

    <a href="/Views/Usuario/painel.php">
        Voltar ao painel
    </a>
</body>

</html>