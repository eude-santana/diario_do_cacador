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
$armasDisponiveis = $armasDisponiveis ?? [];
$vestimentasDisponiveis = $vestimentasDisponiveis ?? [];
$itensDisponiveis = $itensDisponiveis ?? [];
$magiasDisponiveis = $magiasDisponiveis ?? [];

$podeUsarMagias = $podeUsarMagias ?? false;

$buscaItem = $buscaItem ?? '';
$tipoItem = $tipoItem ?? '';

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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&amp;family=Source+Sans+3:wght@400;600;700&amp;display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="/Assets/css/style.css">

    <title>Gerenciar personagem — Diário do Caçador</title>
</head>

<body>
    <main class="pagina pagina-gerenciamento">
        <div class="cabecalho-com-acoes cabecalho-com-acoes--titulo-longo">
            <header class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Ficha de personagem
                </p>

                <h1 class="cabecalho-pagina__titulo">
                    <?php
                    echo escaparGerenciamento(
                        $ficha['nome']
                    );
                    ?>
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Gerencie os atributos, equipamentos e recursos
                    utilizados durante a aventura.
                </p>
            </header>

            <nav class="acoes-pagina" aria-label="Ações da página">
                <a class="botao botao--secundario" href="/Controllers/FichaController.php?acao=listar">
                    Voltar aos personagens
                </a>

                <a class="botao botao--secundario" href="/Views/Usuario/painel.php">
                    Voltar ao painel
                </a>
            </nav>
        </div>

        <?php if ($mensagem !== ''): ?>
            <div class="mensagem mensagem--sucesso" role="status">
                <p>
                    <?php echo escaparGerenciamento($mensagem); ?>
                </p>
            </div>
        <?php endif; ?>

        <?php if ($erro !== ''): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    <?php echo escaparGerenciamento($erro); ?>
                </p>
            </div>
        <?php endif; ?>

        <nav class="navegacao-ficha" aria-label="Seções da ficha">
            <a href="#dados">Dados</a>
            <a href="#profissao">Profissão</a>
            <a href="#vantagens">Vantagens</a>
            <a href="#armas">Armas</a>
            <a href="#vestimentas">Vestimentas</a>
            <a href="#mochila">Mochila</a>
            <a href="#magias">Magias</a>
            <a href="#companheiro">Companheiro</a>
        </nav>

        <section class="secao-ficha" id="dados">
            <header class="secao-ficha__cabecalho">
                <p class="secao-ficha__etapa">
                    Estado atual
                </p>

                <h2 class="secao-ficha__titulo">
                    Dados do personagem
                </h2>

                <p class="secao-ficha__descricao">
                    Atualize as informações que podem mudar durante a partida.
                </p>
            </header>

            <form class="formulario formulario-dados-ficha" action="/Controllers/FichaController.php" method="POST">
                <input type="hidden" name="acao" value="atualizar_dados">

                <input type="hidden" name="id_ficha" value="<?php
                echo (int) $ficha['id_ficha'];
                ?>">

                <div class="campo">
                    <label class="campo__rotulo" for="nome">
                        Nome do personagem:
                    </label>

                    <input class="campo__controle" type="text" name="nome" id="nome" maxlength="100" required value="<?php
                    echo escaparGerenciamento(
                        $ficha['nome']
                    );
                    ?>">
                </div>

                <div class="grade-dados-personagem">
                    <div class="dado-ficha">
                        <span class="dado-ficha__rotulo">
                            Profissão
                        </span>

                        <strong class="dado-ficha__valor">
                            <?php
                            echo escaparGerenciamento(
                                $ficha['nome_profissao']
                            );
                            ?>
                        </strong>

                        <span class="dado-ficha__ajuda">
                            Definida durante a criação do personagem.
                        </span>
                    </div>

                    <div class="campo">
                        <label class="campo__rotulo" for="pv_atual">
                            PV atual:
                        </label>

                        <div class="campo-com-limite">
                            <input class="campo__controle" type="number" name="pv_atual" id="pv_atual" min="0" max="<?php
                            echo (int) $ficha['pv_maximo'];
                            ?>" required value="<?php
                            echo (int) $ficha['pv_atual'];
                            ?>">

                            <span class="campo-com-limite__valor">
                                / <?php
                                echo (int) $ficha['pv_maximo'];
                                ?>
                            </span>
                        </div>
                    </div>

                    <div class="campo">
                        <label class="campo__rotulo" for="fadiga">
                            Fadiga:
                        </label>

                        <div class="campo-com-limite">
                            <input class="campo__controle" type="number" name="fadiga" id="fadiga" min="0" max="6"
                                required value="<?php
                                echo (int) $ficha['fadiga'];
                                ?>">

                            <span class="campo-com-limite__valor">
                                / 6
                            </span>
                        </div>
                    </div>
                </div>

                <div class="formulario__acoes">
                    <button class="botao botao--primario" type="submit">
                        Salvar dados
                    </button>
                </div>
            </form>
        </section>

        <hr>

        <section class="secao-ficha" id="profissao">
            <header class="secao-ficha__cabecalho">
                <p class="secao-ficha__etapa">
                    Origem do personagem
                </p>

                <h2 class="secao-ficha__titulo">
                    Profissão
                </h2>

                <p class="secao-ficha__descricao">
                    A profissão define a base e os recursos do personagem.
                </p>
            </header>

            <article class="registro-detalhe">
                <p class="registro-detalhe__marca">
                    Profissão escolhida
                </p>

                <h3 class="registro-detalhe__titulo">
                    <?php
                    echo escaparGerenciamento(
                        $ficha['nome_profissao']
                    );
                    ?>
                </h3>

                <?php if (
                    !empty($ficha['descricao_profissao'])
                ): ?>
                    <div class="registro-detalhe__descricao">
                        <p>
                            <?php
                            echo nl2br(
                                escaparGerenciamento(
                                    $ficha['descricao_profissao']
                                )
                            );
                            ?>
                        </p>
                    </div>
                <?php endif; ?>

                <p class="observacao-ficha">
                    A profissão não pode ser alterada depois da criação.
                </p>
            </article>
        </section>

        <section class="secao-ficha" id="vantagens">
            <header class="secao-ficha__cabecalho">
                <p class="secao-ficha__etapa">
                    Características permanentes
                </p>

                <h2 class="secao-ficha__titulo">
                    Vantagens
                </h2>

                <p class="secao-ficha__descricao">
                    Vantagens concedidas pela profissão do personagem.
                </p>
            </header>

            <?php if (empty($vantagens)): ?>
                <p class="secao-ficha__vazio">
                    Nenhuma vantagem encontrada.
                </p>
            <?php else: ?>
                <div class="grade-vantagens">
                    <?php foreach ($vantagens as $vantagem): ?>
                        <article class="registro-detalhe">
                            <p class="registro-detalhe__marca">
                                Vantagem
                            </p>

                            <h3 class="registro-detalhe__titulo">
                                <?php
                                echo escaparGerenciamento(
                                    $vantagem['nome']
                                );
                                ?>
                            </h3>

                            <?php if (
                                !empty($vantagem['descricao'])
                            ): ?>
                                <div class="registro-detalhe__descricao">
                                    <p>
                                        <?php
                                        echo nl2br(
                                            escaparGerenciamento(
                                                $vantagem['descricao']
                                            )
                                        );
                                        ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <hr>

        <section class="secao-ficha" id="armas">
            <header class="secao-ficha__cabecalho secao-ficha__cabecalho--com-acao">
                <div class="secao-ficha__introducao">
                    <p class="secao-ficha__etapa">
                        Equipamento de combate
                    </p>

                    <h2 class="secao-ficha__titulo">
                        Armas
                    </h2>

                    <p class="secao-ficha__descricao">
                        O personagem pode carregar até três armas.
                        Uma arma de duas mãos ocupa somente um slot.
                    </p>
                </div>

                <a class="botao botao--secundario botao--pequeno" href="/Controllers/ArmaController.php?acao=novo"
                    target="_blank" rel="noopener">
                    Cadastrar arma
                </a>
            </header>

            <?php
            $armasPorSlot = [];

            foreach ($armas as $armaAtual) {
                $armasPorSlot[(int) $armaAtual['slot']] =
                    $armaAtual;
            }
            ?>

            <div class="grade-slots-ficha">
                <?php for ($slot = 1; $slot <= 3; $slot++): ?>
                    <?php
                    $armaAtual = $armasPorSlot[$slot] ?? null;
                    ?>

                    <article class="slot-ficha">
                        <header class="slot-ficha__cabecalho">
                            <p class="slot-ficha__numero">
                                Slot <?php echo $slot; ?>
                            </p>

                            <h3 class="slot-ficha__titulo">
                                <?php if ($armaAtual === null): ?>
                                    Espaço vazio
                                <?php else: ?>
                                    <?php
                                    echo escaparGerenciamento(
                                        $armaAtual['nome']
                                    );
                                    ?>
                                <?php endif; ?>
                            </h3>
                        </header>

                        <form class="formulario slot-ficha__formulario" action="/Controllers/FichaController.php"
                            method="POST">
                            <input type="hidden" name="acao" value="atualizar_arma">

                            <input type="hidden" name="id_ficha" value="<?php
                            echo (int) $ficha['id_ficha'];
                            ?>">

                            <input type="hidden" name="slot" value="<?php echo $slot; ?>">

                            <div class="campo">
                                <label class="campo__rotulo" for="arma_slot_<?php echo $slot; ?>">
                                    Arma:
                                </label>

                                <select class="campo__controle" name="id_arma" id="arma_slot_<?php echo $slot; ?>">
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
                            </div>

                            <button class="botao botao--primario" type="submit">
                                Salvar slot
                            </button>
                        </form>

                        <?php if ($armaAtual === null): ?>
                            <p class="slot-ficha__vazio">
                                Nenhuma arma equipada neste slot.
                            </p>
                        <?php else: ?>
                            <div class="slot-ficha__dados">
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
                            </div>

                            <?php if (
                                !empty($armaAtual['especial'])
                            ): ?>
                                <div class="slot-ficha__especial">
                                    <strong>Especial:</strong>

                                    <p>
                                        <?php
                                        echo nl2br(
                                            escaparGerenciamento(
                                                $armaAtual['especial']
                                            )
                                        );
                                        ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </article>
                <?php endfor; ?>
            </div>
        </section>

        <hr>

        <section class="secao-ficha" id="vestimentas">
            <header class="secao-ficha__cabecalho secao-ficha__cabecalho--com-acao">
                <div class="secao-ficha__introducao">
                    <p class="secao-ficha__etapa">
                        Proteção do personagem
                    </p>

                    <h2 class="secao-ficha__titulo">
                        Vestimentas
                    </h2>

                    <p class="secao-ficha__descricao">
                        Ao equipar uma vestimenta diferente, ela começa
                        com seus pontos de proteção máximos.
                    </p>
                </div>

                <a class="botao botao--secundario botao--pequeno" href="/Controllers/VestimentaController.php?acao=novo"
                    target="_blank" rel="noopener">
                    Cadastrar vestimenta
                </a>
            </header>

            <?php
            $nomesTiposVestimenta = [
                'ARMADURA' => 'Armadura',
                'ELMO' => 'Elmo',
                'BRACELETES' => 'Braceletes',
                'BOTAS' => 'Botas',
                'ESCUDO' => 'Escudo'
            ];

            $vestimentasPorSlot = [];

            foreach ($vestimentas as $vestimentaAtual) {
                $vestimentasPorSlot[
                    $vestimentaAtual['tipo_slot']
                ] = $vestimentaAtual;
            }
            ?>

            <div class="grade-slots-ficha">
                <?php foreach (
                    $nomesTiposVestimenta
                    as $tipoSlot => $nomeTipo
                ): ?>
                    <?php
                    $vestimentaAtual =
                        $vestimentasPorSlot[$tipoSlot] ?? null;
                    ?>

                    <article class="slot-ficha">
                        <header class="slot-ficha__cabecalho">
                            <p class="slot-ficha__numero">
                                <?php echo escaparGerenciamento($nomeTipo); ?>
                            </p>

                            <h3 class="slot-ficha__titulo">
                                <?php if ($vestimentaAtual === null): ?>
                                    Espaço vazio
                                <?php else: ?>
                                    <?php
                                    echo escaparGerenciamento(
                                        $vestimentaAtual['nome']
                                    );
                                    ?>
                                <?php endif; ?>
                            </h3>
                        </header>

                        <form id="form_equipar_<?php echo escaparGerenciamento($tipoSlot); ?>"
                            class="formulario slot-ficha__formulario" action="/Controllers/FichaController.php"
                            method="POST">
                            <input type="hidden" name="acao" value="equipar_vestimenta">

                            <input type="hidden" name="id_ficha" value="<?php
                            echo (int) $ficha['id_ficha'];
                            ?>">

                            <input type="hidden" name="tipo_slot" value="<?php
                            echo escaparGerenciamento($tipoSlot);
                            ?>">

                            <div class="campo">
                                <label class="campo__rotulo" for="vestimenta_<?php
                                echo escaparGerenciamento($tipoSlot);
                                ?>">
                                    Equipamento:
                                </label>

                                <select class="campo__controle" name="id_vestimenta"
                                    id="vestimenta_<?php echo escaparGerenciamento($tipoSlot); ?>">
                                    <option value="">
                                        Espaço vazio
                                    </option>

                                    <?php foreach (
                                        $vestimentasDisponiveis
                                        as $vestimentaDisponivel
                                    ): ?>
                                        <?php if (
                                            $vestimentaDisponivel['tipo']
                                            !== $tipoSlot
                                        ): ?>
                                            <?php continue; ?>
                                        <?php endif; ?>

                                        <option value="<?php
                                        echo (int) $vestimentaDisponivel[
                                            'id_vestimenta'
                                        ];
                                        ?>" <?php
                                        if (
                                            $vestimentaAtual !== null
                                            && (int) $vestimentaAtual[
                                                'id_vestimenta'
                                            ]
                                            === (int) $vestimentaDisponivel[
                                                'id_vestimenta'
                                            ]
                                        ) {
                                            echo 'selected';
                                        }
                                        ?>>
                                            <?php
                                            echo escaparGerenciamento(
                                                $vestimentaDisponivel['nome']
                                            );
                                            ?>
                                            —
                                            <?php
                                            echo (int) $vestimentaDisponivel[
                                                'pontos_protecao_maximo'
                                            ];
                                            ?>
                                            PP
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>

                        <?php if ($vestimentaAtual === null): ?>
                            <p class="slot-ficha__vazio">
                                Nenhuma vestimenta equipada neste espaço.
                            </p>
                        <?php else: ?>
                            <form id="form_protecao_<?php echo escaparGerenciamento($tipoSlot); ?>"
                                class="formulario slot-ficha__formulario" action="/Controllers/FichaController.php"
                                method="POST">
                                <input type="hidden" name="acao" value="atualizar_protecao">

                                <input type="hidden" name="id_ficha" value="<?php echo (int) $ficha['id_ficha']; ?>">

                                <input type="hidden" name="tipo_slot" value="<?php
                                echo escaparGerenciamento($tipoSlot);
                                ?>">

                                <div class="slot-ficha__dados">
                                    <div class="campo">
                                        <label class="campo__rotulo" for="pp_<?php
                                        echo escaparGerenciamento($tipoSlot);
                                        ?>">
                                            PP atual:
                                        </label>

                                        <div class="campo-com-limite">
                                            <input class="campo__controle" type="number" name="pontos_protecao_atual" id="pp_<?php
                                            echo escaparGerenciamento($tipoSlot);
                                            ?>" min="0" max="<?php
                                            echo (int) $vestimentaAtual[
                                                'pontos_protecao_maximo'
                                            ];
                                            ?>" required value="<?php
                                            echo (int) $vestimentaAtual[
                                                'pontos_protecao_atual'
                                            ];
                                            ?>">

                                            <span class="campo-com-limite__valor">
                                                / <?php
                                                echo (int) $vestimentaAtual[
                                                    'pontos_protecao_maximo'
                                                ];
                                                ?>
                                            </span>
                                        </div>
                                    </div>

                                    <?php if (!empty($vestimentaAtual['dano'])): ?>
                                        <p>
                                            <strong>Dano:</strong>

                                            <?php
                                            echo escaparGerenciamento(
                                                $vestimentaAtual['dano']
                                            );
                                            ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php if (!empty($vestimentaAtual['elemento'])): ?>
                                        <p>
                                            <strong>Elemento:</strong>

                                            <?php
                                            echo escaparGerenciamento(
                                                $vestimentaAtual['elemento']
                                            );
                                            ?>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <p class="observacao-ficha">
                                    Definir o PP como zero destruirá e removerá
                                    a vestimenta.
                                </p>
                            </form>

                            <?php if (!empty($vestimentaAtual['especial'])): ?>
                                <div class="slot-ficha__especial">
                                    <strong>Especial:</strong>

                                    <p>
                                        <?php
                                        echo nl2br(
                                            escaparGerenciamento(
                                                $vestimentaAtual['especial']
                                            )
                                        );
                                        ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <div class="slot-ficha__acoes">
                            <button class="botao botao--secundario" type="submit" form="form_equipar_<?php
                            echo escaparGerenciamento($tipoSlot);
                            ?>">
                                Salvar equipamento
                            </button>

                            <?php if ($vestimentaAtual !== null): ?>
                                <button class="botao botao--primario" type="submit" form="form_protecao_<?php
                                echo escaparGerenciamento($tipoSlot);
                                ?>">
                                    Atualizar proteção
                                </button>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <hr>

        <section class="secao-ficha" id="mochila">
            <header class="secao-ficha__cabecalho secao-ficha__cabecalho--com-acao">
                <div class="secao-ficha__introducao">
                    <p class="secao-ficha__etapa">
                        Inventário do personagem
                    </p>

                    <h2 class="secao-ficha__titulo">
                        Mochila
                    </h2>

                    <p class="secao-ficha__descricao">
                        Consulte os itens carregados e atualize suas quantidades.
                        Definir a quantidade como zero remove o item.
                    </p>
                </div>

                <a class="botao botao--secundario botao--pequeno" href="/Controllers/ItemController.php?acao=novo"
                    target="_blank" rel="noopener">
                    Cadastrar item
                </a>
            </header>

            <?php if (empty($itens)): ?>
                <p class="slot-ficha__vazio">
                    Nenhum item na mochila.
                </p>
            <?php else: ?>
                <div class="tabela-responsiva">
                    <table class="tabela-registro tabela-registro--muitas-colunas">
                        <caption>
                            Itens carregados pelo personagem
                        </caption>

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
                                    <td class="tabela-registro__nome" data-label="Item">
                                        <?php
                                        echo escaparGerenciamento(
                                            $item['nome']
                                        );
                                        ?>
                                    </td>

                                    <td class="tabela-registro__tipo" data-label="Tipo">
                                        <?php
                                        echo escaparGerenciamento(
                                            $item['tipo']
                                        );
                                        ?>
                                    </td>

                                    <td class="tabela-registro__quantidade" data-label="Quantidade">
                                        <form class="controle-quantidade" action="/Controllers/FichaController.php"
                                            method="POST">
                                            <input type="hidden" name="acao" value="atualizar_item">

                                            <input type="hidden" name="id_ficha" value="<?php
                                            echo (int) $ficha['id_ficha'];
                                            ?>">

                                            <input type="hidden" name="id_item" value="<?php
                                            echo (int) $item['id_item'];
                                            ?>">

                                            <input class="campo__controle controle-quantidade__campo" type="number"
                                                name="quantidade" min="0" required value="<?php
                                                echo (int) $item['quantidade'];
                                                ?>">

                                            <button class="botao botao--primario botao--pequeno" type="submit">
                                                Atualizar
                                            </button>
                                        </form>
                                    </td>

                                    <td class="tabela-registro__descricao" data-label="Descrição">
                                        <?php
                                        echo nl2br(
                                            escaparGerenciamento(
                                                $item['descricao'] ?? ''
                                            )
                                        );
                                        ?>
                                    </td>

                                    <td class="tabela-registro__acoes tabela-registro__acoes--uma-acao" data-label="Ações">
                                        <div class="acoes-registro">
                                            <form class="acoes-registro__formulario" action="/Controllers/FichaController.php"
                                                method="POST" onsubmit="
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

                                                <button class="botao botao--perigo botao--pequeno" type="submit">
                                                    Remover
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

            <section class="bloco-mochila">
                <header class="bloco-mochila__cabecalho">
                    <h3 class="bloco-mochila__titulo">
                        Adicionar item
                    </h3>

                    <p class="bloco-mochila__descricao">
                        Localize um item disponível e defina a quantidade
                        total que ficará na mochila.
                    </p>
                </header>

                <div class="filtro-mochila">
                    <h4 class="filtro-mochila__titulo">
                        Filtrar itens disponíveis
                    </h4>

                    <p class="filtro-mochila__descricao">
                        Os filtros abaixo alteram as opções exibidas
                        no seletor de itens.
                    </p>

                    <form class="formulario" action="/Controllers/FichaController.php#mochila" method="GET">
                        <input type="hidden" name="acao" value="gerenciar">

                        <input type="hidden" name="id" value="<?php echo (int) $ficha['id_ficha']; ?>">

                        <div class="formulario__linha formulario__linha--duas-colunas">
                            <div class="campo">
                                <label class="campo__rotulo" for="busca_item">
                                    Nome:
                                </label>

                                <input class="campo__controle" type="search" name="busca_item" id="busca_item" value="<?php
                                echo escaparGerenciamento(
                                    $buscaItem ?? ''
                                );
                                ?>">
                            </div>

                            <div class="campo">
                                <label class="campo__rotulo" for="tipo_item">
                                    Tipo:
                                </label>

                                <select class="campo__controle" name="tipo_item" id="tipo_item">
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
                            </div>
                        </div>

                        <div class="formulario__acoes">
                            <button class="botao botao--primario" type="submit">
                                Aplicar filtros
                            </button>

                            <a class="botao botao--secundario" href="/Controllers/FichaController.php?acao=gerenciar&id=<?php
                            echo (int) $ficha['id_ficha'];
                            ?>#mochila">
                                Limpar filtros
                            </a>
                        </div>
                    </form>
                </div>

                <div class="adicao-mochila">
                    <h4 class="bloco-mochila__subtitulo">
                        Escolher item
                    </h4>

                    <?php if (empty($itensDisponiveis)): ?>
                        <p class="slot-ficha__vazio">
                            Nenhum item encontrado com os filtros informados.
                        </p>
                    <?php else: ?>
                        <form class="formulario" action="/Controllers/FichaController.php" method="POST">
                            <input type="hidden" name="acao" value="atualizar_item">

                            <input type="hidden" name="id_ficha" value="<?php
                            echo (int) $ficha['id_ficha'];
                            ?>">

                            <div class="formulario__linha formulario__linha--duas-colunas">
                                <div class="campo">
                                    <label class="campo__rotulo" for="id_item">
                                        Item:
                                    </label>

                                    <select class="campo__controle" name="id_item" id="id_item" required>
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
                                </div>

                                <div class="campo">
                                    <label class="campo__rotulo" for="quantidade_novo_item">
                                        Quantidade total:
                                    </label>

                                    <input class="campo__controle" type="number" name="quantidade" id="quantidade_novo_item"
                                        min="1" value="1" required>
                                </div>
                            </div>

                            <div class="formulario__acoes">
                                <button class="botao botao--primario" type="submit">
                                    Adicionar à mochila
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </section>

        </section>

        <hr>

        <section class="secao-ficha" id="magias">
            <header class="secao-ficha__cabecalho secao-ficha__cabecalho--com-acao">
                <div class="secao-ficha__introducao">
                    <p class="secao-ficha__etapa">
                        Conhecimentos arcanos
                    </p>

                    <h2 class="secao-ficha__titulo">
                        Magias aprendidas
                    </h2>

                    <p class="secao-ficha__descricao">
                        Consulte as magias conhecidas pelo personagem
                        e aprenda novas magias disponíveis.
                    </p>
                </div>

                <?php if ($podeUsarMagias): ?>
                    <a class="botao botao--secundario botao--pequeno" href="/Controllers/MagiaController.php?acao=novo"
                        target="_blank" rel="noopener">
                        Cadastrar magia
                    </a>
                <?php endif; ?>
            </header>

            <?php if (!$podeUsarMagias): ?>
                <p class="slot-ficha__vazio">
                    A profissão deste personagem não concede acesso a magias.
                </p>
            <?php else: ?>
                <?php if (empty($magias)): ?>
                    <p class="slot-ficha__vazio">
                        O personagem ainda não conhece nenhuma magia.
                    </p>
                <?php else: ?>
                    <div class="grade-magias-ficha">
                        <?php foreach ($magias as $magia): ?>
                            <article class="magia-ficha">
                                <header class="magia-ficha__cabecalho">
                                    <h3 class="magia-ficha__titulo">
                                        <?php
                                        echo escaparGerenciamento(
                                            $magia['nome']
                                        );
                                        ?>
                                    </h3>

                                    <p class="magia-ficha__elemento">
                                        <strong>Elemento:</strong>

                                        <?php
                                        echo escaparGerenciamento(
                                            $magia['elemento']
                                        );
                                        ?>
                                    </p>
                                </header>

                                <?php if (!empty($magia['descricao'])): ?>
                                    <div class="magia-ficha__descricao">
                                        <p>
                                            <?php
                                            echo nl2br(
                                                escaparGerenciamento(
                                                    $magia['descricao']
                                                )
                                            );
                                            ?>
                                        </p>
                                    </div>
                                <?php endif; ?>

                                <div class="magia-ficha__acoes">
                                    <form action="/Controllers/FichaController.php" method="POST" onsubmit="
                                return confirm(
                                    'Remover esta magia da ficha?'
                                );
                            ">
                                        <input type="hidden" name="acao" value="remover_magia">

                                        <input type="hidden" name="id_ficha" value="<?php
                                        echo (int) $ficha['id_ficha'];
                                        ?>">

                                        <input type="hidden" name="id_magia" value="<?php
                                        echo (int) $magia['id_magia'];
                                        ?>">

                                        <button class="botao botao--perigo botao--pequeno" type="submit">
                                            Remover magia
                                        </button>
                                    </form>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="aprendizado-magia">
                    <h3 class="aprendizado-magia__titulo">
                        Aprender uma magia
                    </h3>

                    <p class="aprendizado-magia__descricao">
                        Escolha uma das magias disponíveis para adicioná-la
                        aos conhecimentos do personagem.
                    </p>

                    <?php if (empty($magiasDisponiveis)): ?>
                        <p class="slot-ficha__vazio">
                            Não existem outras magias disponíveis.
                        </p>
                    <?php else: ?>
                        <form class="formulario" action="/Controllers/FichaController.php" method="POST">
                            <input type="hidden" name="acao" value="adicionar_magia">

                            <input type="hidden" name="id_ficha" value="<?php
                            echo (int) $ficha['id_ficha'];
                            ?>">

                            <div class="campo">
                                <label class="campo__rotulo" for="id_magia">
                                    Magia:
                                </label>

                                <select class="campo__controle" name="id_magia" id="id_magia" required>
                                    <option value="">
                                        Selecione uma magia
                                    </option>

                                    <?php foreach (
                                        $magiasDisponiveis
                                        as $magiaDisponivel
                                    ): ?>
                                        <option value="<?php
                                        echo (int) $magiaDisponivel['id_magia'];
                                        ?>">
                                            <?php
                                            echo escaparGerenciamento(
                                                $magiaDisponivel['nome']
                                                . ' — '
                                                . $magiaDisponivel['elemento']
                                            );
                                            ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="formulario__acoes">
                                <button class="botao botao--primario" type="submit">
                                    Aprender magia
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>

        <hr>

        <section class="secao-ficha" id="companheiro">
            <header class="secao-ficha__cabecalho secao-ficha__cabecalho--com-acao">
                <div class="secao-ficha__introducao">
                    <p class="secao-ficha__etapa">
                        Aliado da jornada
                    </p>

                    <h2 class="secao-ficha__titulo">
                        Companheiro animal
                    </h2>

                    <p class="secao-ficha__descricao">
                        Consulte as características do companheiro e atualize
                        as informações que podem mudar durante a aventura.
                    </p>
                </div>

                <a class="botao botao--secundario botao--pequeno"
                    href="/Controllers/CompanheiroAnimalController.php?acao=novo" target="_blank" rel="noopener">
                    Cadastrar companheiro
                </a>
            </header>

            <?php if ($companheiro === null): ?>
                <p class="slot-ficha__vazio">
                    Este personagem não possui companheiro animal.
                </p>
            <?php else: ?>
                <form class="formulario formulario-dados-ficha" action="/Controllers/FichaController.php" method="POST">
                    <input type="hidden" name="acao" value="atualizar_companheiro">

                    <input type="hidden" name="id_ficha" value="<?php echo (int) $ficha['id_ficha']; ?>">

                    <div class="grade-dados-personagem">
                        <div class="campo">
                            <label class="campo__rotulo" for="nome_companheiro">
                                Nome do companheiro:
                            </label>

                            <input class="campo__controle" type="text" name="nome_companheiro" id="nome_companheiro"
                                maxlength="100" required value="<?php
                                echo escaparGerenciamento(
                                    $companheiro['nome']
                                );
                                ?>">
                        </div>

                        <div class="dado-ficha">
                            <span class="dado-ficha__rotulo">
                                Tipo
                            </span>

                            <strong class="dado-ficha__valor">
                                <?php
                                echo escaparGerenciamento(
                                    $companheiro['tipo']
                                );
                                ?>
                            </strong>

                            <span class="dado-ficha__ajuda">
                                Definido pelo companheiro escolhido.
                            </span>
                        </div>

                        <div class="campo">
                            <label class="campo__rotulo" for="pv_companheiro">
                                PV atual:
                            </label>

                            <div class="campo-com-limite">
                                <input class="campo__controle" type="number" name="pv_companheiro" id="pv_companheiro"
                                    min="0" max="<?php
                                    echo (int) $companheiro['pv_maximo'];
                                    ?>" required value="<?php
                                    echo (int) $companheiro['pv_atual'];
                                    ?>">

                                <span class="campo-com-limite__valor">
                                    / <?php
                                    echo (int) $companheiro['pv_maximo'];
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <?php if (
                        !empty($companheiro['dano'])
                        || !empty($companheiro['descricao'])
                    ): ?>
                        <div class="companheiro-ficha__detalhes">
                            <?php if (!empty($companheiro['dano'])): ?>
                                <p class="companheiro-ficha__dano">
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
                                <div class="companheiro-ficha__descricao">
                                    <strong>Descrição:</strong>

                                    <p>
                                        <?php
                                        echo nl2br(
                                            escaparGerenciamento(
                                                $companheiro['descricao']
                                            )
                                        );
                                        ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="formulario__acoes">
                        <button class="botao botao--primario" type="submit">
                            Salvar companheiro
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </section>

    </main>
</body>

</html>