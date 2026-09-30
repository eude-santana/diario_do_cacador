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
        <h2>Armas</h2>

        <p>
            O personagem pode carregar até três armas.
            Uma arma de duas mãos continua ocupando apenas um slot.
        </p>

        <?php
        $armasPorSlot = [];

        foreach ($armas as $armaAtual) {
            $armasPorSlot[(int) $armaAtual['slot']] =
                $armaAtual;
        }
        ?>

        <?php for ($slot = 1; $slot <= 3; $slot++): ?>
            <?php
            $armaAtual = $armasPorSlot[$slot] ?? null;
            ?>

            <article>
                <h3>
                    Slot <?php echo $slot; ?>
                </h3>

                <form action="/Controllers/FichaController.php" method="POST">
                    <input type="hidden" name="acao" value="atualizar_arma">

                    <input type="hidden" name="id_ficha" value="<?php
                    echo (int) $ficha['id_ficha'];
                    ?>">

                    <input type="hidden" name="slot" value="<?php echo $slot; ?>">

                    <label for="arma_slot_<?php echo $slot; ?>">
                        Arma:
                    </label>

                    <select name="id_arma" id="arma_slot_<?php echo $slot; ?>">
                        <option value="">
                            Slot vazio
                        </option>

                        <?php foreach (
                            $armasDisponiveis
                            as $armaDisponivel
                        ): ?>
                            <option value="<?php
                            echo (int) $armaDisponivel['id_arma'];
                            ?>" <?php
                            if (
                                $armaAtual !== null
                                && (int) $armaAtual['id_arma']
                                === (int) $armaDisponivel['id_arma']
                            ) {
                                echo 'selected';
                            }
                            ?>>
                                <?php
                                echo escaparGerenciamento(
                                    $armaDisponivel['nome']
                                );
                                ?>
                                —
                                <?php
                                echo escaparGerenciamento(
                                    $armaDisponivel['tipo']
                                );
                                ?>
                                —
                                <?php
                                echo (int) $armaDisponivel['maos'];
                                ?>
                                mão(ões)
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit">
                        Salvar slot
                    </button>
                </form>

                <?php if ($armaAtual === null): ?>
                    <p>Este slot está vazio.</p>
                <?php else: ?>
                    <p>
                        <strong>Arma atual:</strong>

                        <?php
                        echo escaparGerenciamento(
                            $armaAtual['nome']
                        );
                        ?>
                    </p>

                    <p>
                        <strong>Tipo:</strong>

                        <?php
                        echo escaparGerenciamento(
                            $armaAtual['tipo']
                        );
                        ?>
                    </p>

                    <p>
                        <strong>Mãos:</strong>

                        <?php
                        echo (int) $armaAtual['maos'];
                        ?>
                    </p>

                    <p>
                        <strong>Dano:</strong>

                        <?php
                        echo escaparGerenciamento(
                            $armaAtual['dano']
                        );
                        ?>
                    </p>

                    <?php if (
                        !empty($armaAtual['especial'])
                    ): ?>
                        <p>
                            <strong>Especial:</strong>

                            <?php
                            echo nl2br(
                                escaparGerenciamento(
                                    $armaAtual['especial']
                                )
                            );
                            ?>
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
            </article>
        <?php endfor; ?>

        <p>
            <a href="/Controllers/ArmaController.php?acao=novo">
                Cadastrar nova arma
            </a>
        </p>
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
        <h2>Mochila</h2>

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
                        <th>Ações</th>
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
                                <form action="/Controllers/FichaController.php" method="POST">
                                    <input type="hidden" name="acao" value="atualizar_item">

                                    <input type="hidden" name="id_ficha" value="<?php
                                    echo (int) $ficha['id_ficha'];
                                    ?>">

                                    <input type="hidden" name="id_item" value="<?php
                                    echo (int) $item['id_item'];
                                    ?>">

                                    <input type="number" name="quantidade" min="0" required value="<?php
                                    echo (int) $item['quantidade'];
                                    ?>">

                                    <button type="submit">
                                        Atualizar
                                    </button>
                                </form>
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

                            <td>
                                <form action="/Controllers/FichaController.php" method="POST" onsubmit="
                                    return confirm(
                                        'Deseja remover este item da mochila?'
                                    );
                                ">
                                    <input type="hidden" name="acao" value="atualizar_item">

                                    <input type="hidden" name="id_ficha" value="<?php
                                    echo (int) $ficha['id_ficha'];
                                    ?>">

                                    <input type="hidden" name="id_item" value="<?php
                                    echo (int) $item['id_item'];
                                    ?>">

                                    <input type="hidden" name="quantidade" value="0">

                                    <button type="submit">
                                        Remover
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <h3>Localizar item</h3>

        <form action="/Controllers/FichaController.php" method="GET">
            <input type="hidden" name="acao" value="gerenciar">

            <input type="hidden" name="id" value="<?php
            echo (int) $ficha['id_ficha'];
            ?>">

            <p>
                <label for="busca_item">
                    Nome:
                </label>

                <input type="search" name="busca_item" id="busca_item" value="<?php
                echo escaparGerenciamento(
                    $buscaItem ?? ''
                );
                ?>">
            </p>

            <p>
                <label for="tipo_item">
                    Tipo:
                </label>

                <select name="tipo_item" id="tipo_item">
                    <option value="">
                        Todos
                    </option>

                    <option value="CONSUMIVEL" <?php
                    if (($tipoItem ?? '') === 'CONSUMIVEL') {
                        echo 'selected';
                    }
                    ?>>
                        Consumível
                    </option>

                    <option value="MATERIAL" <?php
                    if (($tipoItem ?? '') === 'MATERIAL') {
                        echo 'selected';
                    }
                    ?>>
                        Material
                    </option>

                    <option value="UTILITARIO" <?php
                    if (($tipoItem ?? '') === 'UTILITARIO') {
                        echo 'selected';
                    }
                    ?>>
                        Utilitário
                    </option>

                    <option value="OUTRO" <?php
                    if (($tipoItem ?? '') === 'OUTRO') {
                        echo 'selected';
                    }
                    ?>>
                        Outro
                    </option>
                </select>
            </p>

            <button type="submit">
                Buscar
            </button>

            <a href="/Controllers/FichaController.php?acao=gerenciar&id=<?php
            echo (int) $ficha['id_ficha'];
            ?>">
                Limpar filtros
            </a>
        </form>

        <h3>Adicionar item</h3>

        <?php if (empty($itensDisponiveis)): ?>
            <p>
                Nenhum item encontrado com os filtros informados.
            </p>
        <?php else: ?>
            <form action="/Controllers/FichaController.php" method="POST">
                <input type="hidden" name="acao" value="atualizar_item">

                <input type="hidden" name="id_ficha" value="<?php
                echo (int) $ficha['id_ficha'];
                ?>">

                <p>
                    <label for="id_item">
                        Item:
                    </label>

                    <select name="id_item" id="id_item" required>
                        <option value="">
                            Selecione um item
                        </option>

                        <?php foreach (
                            $itensDisponiveis
                            as $itemDisponivel
                        ): ?>
                            <option value="<?php
                            echo (int) $itemDisponivel['id_item'];
                            ?>">
                                <?php
                                echo escaparGerenciamento(
                                    $itemDisponivel['nome']
                                );
                                ?>
                                —
                                <?php
                                echo escaparGerenciamento(
                                    $itemDisponivel['tipo']
                                );
                                ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>

                <p>
                    <label for="quantidade_novo_item">
                        Quantidade total:
                    </label>

                    <input type="number" name="quantidade" id="quantidade_novo_item" min="1" value="1" required>
                </p>

                <button type="submit">
                    Adicionar à mochila
                </button>
            </form>
        <?php endif; ?>

        <p>
            <a href="/Controllers/ItemController.php?acao=novo">
                Cadastrar novo item
            </a>
        </p>
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
            <p>
                Este personagem não possui companheiro.
            </p>
        <?php else: ?>
            <form action="/Controllers/FichaController.php" method="POST">
                <input type="hidden" name="acao" value="atualizar_companheiro">

                <input type="hidden" name="id_ficha" value="<?php
                echo (int) $ficha['id_ficha'];
                ?>">

                <p>
                    <label for="nome_companheiro">
                        Nome do companheiro:
                    </label>

                    <input type="text" name="nome_companheiro" id="nome_companheiro" maxlength="100" required value="<?php
                    echo escaparGerenciamento(
                        $companheiro['nome']
                    );
                    ?>">
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
                    <label for="pv_companheiro">
                        PV atual:
                    </label>

                    <input type="number" name="pv_companheiro" id="pv_companheiro" min="0" max="<?php
                    echo (int) $companheiro['pv_maximo'];
                    ?>" required value="<?php
                    echo (int) $companheiro['pv_atual'];
                    ?>">

                    <span>
                        / <?php
                        echo (int) $companheiro['pv_maximo'];
                        ?>
                    </span>
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

                <button type="submit">
                    Salvar companheiro
                </button>
            </form>
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