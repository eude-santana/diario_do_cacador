<?php

$detalhes = $detalhes ?? [];
$erro = $erro ?? '';
$mensagem = $mensagem ?? '';

$ficha = $detalhes['ficha'] ?? [];
$vantagens = $detalhes['vantagens'] ?? [];
$armas = $detalhes['armas'] ?? [];
$vestimentas = $detalhes['vestimentas'] ?? [];
$itens = $detalhes['itens'] ?? [];
$magias = $detalhes['magias'] ?? [];
$companheiro = $detalhes['companheiro'] ?? null;

function escaparGerenciamento(mixed $valor): string
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

    <title>Gerenciar personagem</title>
</head>

<body>
    <h1>Gerenciar personagem</h1>

    <?php if ($mensagem !== ''): ?>
        <p>
            <?php echo escaparGerenciamento($mensagem); ?>
        </p>
    <?php endif; ?>

    <?php if ($erro !== ''): ?>
        <p>
            <?php echo escaparGerenciamento($erro); ?>
        </p>
    <?php endif; ?>

    <section>
        <h2>Dados do personagem</h2>

        <form action="/Controllers/FichaController.php" method="POST">
            <input type="hidden" name="acao" value="atualizar_dados">

            <input type="hidden" name="id_ficha" value="<?php
            echo (int) $ficha['id_ficha'];
            ?>">

            <p>
                <label for="nome">
                    Nome do personagem:
                </label>

                <input type="text" name="nome" id="nome" maxlength="100" required value="<?php
                echo escaparGerenciamento(
                    $ficha['nome']
                );
                ?>">
            </p>

            <p>
                <strong>Profissão:</strong>

                <?php
                echo escaparGerenciamento(
                    $ficha['nome_profissao']
                );
                ?>
            </p>

            <p>
                <label for="pv_atual">
                    PV atual:
                </label>

                <input type="number" name="pv_atual" id="pv_atual" min="0" max="<?php
                echo (int) $ficha['pv_maximo'];
                ?>" required value="<?php
                echo (int) $ficha['pv_atual'];
                ?>">

                <span>
                    / <?php
                    echo (int) $ficha['pv_maximo'];
                    ?>
                </span>
            </p>

            <p>
                <label for="fadiga">
                    Fadiga:
                </label>

                <input type="number" name="fadiga" id="fadiga" min="0" max="6" required value="<?php
                echo (int) $ficha['fadiga'];
                ?>">

                <span>/ 6</span>
            </p>

            <button type="submit">
                Salvar dados
            </button>
        </form>
    </section>

    <hr>

    <section>
        <h2>Profissão</h2>

        <h3>
            <?php
            echo escaparGerenciamento(
                $ficha['nome_profissao']
            );
            ?>
        </h3>

        <?php if (
            !empty($ficha['descricao_profissao'])
        ): ?>
            <p>
                <?php
                echo nl2br(
                    escaparGerenciamento(
                        $ficha['descricao_profissao']
                    )
                );
                ?>
            </p>
        <?php endif; ?>

        <p>
            A profissão não pode ser alterada depois da criação.
        </p>
    </section>

    <section>
        <h2>Vantagens</h2>

        <?php if (empty($vantagens)): ?>
            <p>Nenhuma vantagem encontrada.</p>
        <?php else: ?>
            <?php foreach ($vantagens as $vantagem): ?>
                <article>
                    <h3>
                        <?php
                        echo escaparGerenciamento(
                            $vantagem['nome']
                        );
                        ?>
                    </h3>

                    <p>
                        <?php
                        echo nl2br(
                            escaparGerenciamento(
                                $vantagem['descricao'] ?? ''
                            )
                        );
                        ?>
                    </p>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <hr>

    <section>
        <h2>Armas equipadas</h2>

        <?php if (empty($armas)): ?>
            <p>Nenhuma arma equipada.</p>
        <?php else: ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Slot</th>
                        <th>Arma</th>
                        <th>Tipo</th>
                        <th>Mãos</th>
                        <th>Dano</th>
                        <th>Especial</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($armas as $arma): ?>
                        <tr>
                            <td>
                                <?php echo (int) $arma['slot']; ?>
                            </td>

                            <td>
                                <?php
                                echo escaparGerenciamento(
                                    $arma['nome']
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo escaparGerenciamento(
                                    $arma['tipo']
                                );
                                ?>
                            </td>

                            <td>
                                <?php echo (int) $arma['maos']; ?>
                            </td>

                            <td>
                                <?php
                                echo escaparGerenciamento(
                                    $arma['dano']
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo nl2br(
                                    escaparGerenciamento(
                                        $arma['especial'] ?? ''
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>

    <hr>

    <section>
        <h2>Vestimentas</h2>

        <?php if (empty($vestimentas)): ?>
            <p>Nenhuma vestimenta equipada.</p>
        <?php else: ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Espaço</th>
                        <th>Vestimenta</th>
                        <th>Proteção</th>
                        <th>Dano</th>
                        <th>Elemento</th>
                        <th>Especial</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach (
                        $vestimentas
                        as $vestimenta
                    ): ?>
                        <tr>
                            <td>
                                <?php
                                echo escaparGerenciamento(
                                    $vestimenta['tipo_slot']
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo escaparGerenciamento(
                                    $vestimenta['nome']
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo (int) $vestimenta[
                                    'pontos_protecao_atual'
                                ];
                                ?>
                                /
                                <?php
                                echo (int) $vestimenta[
                                    'pontos_protecao_maximo'
                                ];
                                ?>
                            </td>

                            <td>
                                <?php
                                echo escaparGerenciamento(
                                    $vestimenta['dano'] ?? ''
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo escaparGerenciamento(
                                    $vestimenta['elemento'] ?? ''
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo nl2br(
                                    escaparGerenciamento(
                                        $vestimenta['especial']
                                        ?? ''
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>

    <hr>

    <section>
        <h2>Itens</h2>

        <?php if (empty($itens)): ?>
            <p>Nenhum item na mochila.</p>
        <?php else: ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Tipo</th>
                        <th>Quantidade</th>
                        <th>Descrição</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($itens as $item): ?>
                        <tr>
                            <td>
                                <?php
                                echo escaparGerenciamento(
                                    $item['nome']
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo escaparGerenciamento(
                                    $item['tipo']
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo (int) $item['quantidade'];
                                ?>
                            </td>

                            <td>
                                <?php
                                echo nl2br(
                                    escaparGerenciamento(
                                        $item['descricao'] ?? ''
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>

    <hr>

    <section>
        <h2>Magias aprendidas</h2>

        <?php if (empty($magias)): ?>
            <p>Nenhuma magia aprendida.</p>
        <?php else: ?>
            <?php foreach ($magias as $magia): ?>
                <article>
                    <h3>
                        <?php
                        echo escaparGerenciamento(
                            $magia['nome']
                        );
                        ?>
                    </h3>

                    <p>
                        <strong>Elemento:</strong>

                        <?php
                        echo escaparGerenciamento(
                            $magia['elemento']
                        );
                        ?>
                    </p>

                    <p>
                        <?php
                        echo nl2br(
                            escaparGerenciamento(
                                $magia['descricao'] ?? ''
                            )
                        );
                        ?>
                    </p>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <hr>

    <section>
        <h2>Companheiro animal</h2>

        <?php if ($companheiro === null): ?>
            <p>Este personagem não possui companheiro.</p>
        <?php else: ?>
            <p>
                <strong>Nome:</strong>

                <?php
                echo escaparGerenciamento(
                    $companheiro['nome']
                );
                ?>
            </p>

            <p>
                <strong>Tipo:</strong>

                <?php
                echo escaparGerenciamento(
                    $companheiro['tipo']
                );
                ?>
            </p>

            <p>
                <strong>PV:</strong>

                <?php
                echo (int) $companheiro['pv_atual'];
                ?>
                /
                <?php
                echo (int) $companheiro['pv_maximo'];
                ?>
            </p>

            <?php if (!empty($companheiro['dano'])): ?>
                <p>
                    <strong>Dano:</strong>

                    <?php
                    echo escaparGerenciamento(
                        $companheiro['dano']
                    );
                    ?>
                </p>
            <?php endif; ?>

            <?php if (
                !empty($companheiro['descricao'])
            ): ?>
                <p>
                    <?php
                    echo nl2br(
                        escaparGerenciamento(
                            $companheiro['descricao']
                        )
                    );
                    ?>
                </p>
            <?php endif; ?>
        <?php endif; ?>
    </section>

    <hr>

    <p>
        <a href="/Controllers/FichaController.php?acao=listar">
            Voltar para meus personagens
        </a>
    </p>

    <p>
        <a href="/Views/Usuario/painel.php">
            Voltar ao painel
        </a>
    </p>
</body>

</html>