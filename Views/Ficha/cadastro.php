<?php

require_once __DIR__ . '/../../Config/Autenticacao.php';

exigirLogin();

if (!isset($profissoes)) {
    header('Location: /Controllers/FichaController.php?acao=novo');
    exit;
}

$profissoes = $profissoes ?? [];
$dadosProfissao = $dadosProfissao ?? null;

$nomeFicha = $nomeFicha ?? '';
$nomeCompanheiro = $nomeCompanheiro ?? '';

$idProfissaoSelecionada =
    $idProfissaoSelecionada ?? null;

$idMagiaSelecionada =
    $idMagiaSelecionada ?? null;

$idCompanheiroSelecionado =
    $idCompanheiroSelecionado ?? null;

$erro = $erro ?? '';

function escaparFicha($valor): string
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

    <title>Criar personagem — Diário do Caçador</title>
</head>

<body>
    <main class="pagina pagina-formulario pagina-formulario--larga">
        <div class="cabecalho-com-acoes">
            <header class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Diário do Caçador
                </p>

                <h1 class="cabecalho-pagina__titulo">
                    Criar personagem
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Escolha uma profissão e confira os recursos iniciais
                    do personagem.
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

        <?php if ($erro !== ''): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    <?php echo escaparFicha($erro); ?>
                </p>
            </div>
        <?php endif; ?>

        <?php if (empty($profissoes)): ?>
            <div class="estado-vazio">
                <p>
                    Nenhuma profissão própria e completa está disponível.
                </p>

                <p>
                    Para criar um personagem, a profissão precisa possuir
                    exatamente duas vantagens.
                </p>

                <a class="botao botao--primario" href="/Controllers/ProfissaoController.php?acao=novo">
                    Cadastrar profissão
                </a>
            </div>

        <?php else: ?>

            <section class="seletor-profissao formulario-registro">
                <header class="seletor-profissao__cabecalho">
                    <p class="seletor-profissao__etapa">
                        Etapa 1
                    </p>

                    <h2 class="seletor-profissao__titulo">
                        Escolha da profissão
                    </h2>

                    <p class="seletor-profissao__descricao">
                        A profissão define os atributos e recursos iniciais.
                    </p>
                </header>

                <form class="formulario" action="/Controllers/FichaController.php" method="GET">
                    <input type="hidden" name="acao" value="novo">

                    <div class="campo">
                        <label class="campo__rotulo" for="id_profissao">
                            Profissão:
                        </label>

                        <select class="campo__controle" name="id_profissao" id="id_profissao" required
                            onchange="this.form.submit()">
                            <option value="">
                                Selecione uma profissão
                            </option>

                            <?php foreach ($profissoes as $profissao): ?>
                                <option value="<?php
                                echo (int) $profissao['id_profissao'];
                                ?>" <?php
                                if (
                                    (int) $idProfissaoSelecionada
                                    === (int) $profissao['id_profissao']
                                ) {
                                    echo 'selected';
                                }
                                ?>>
                                    <?php
                                    echo escaparFicha($profissao['nome']);
                                    ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <noscript>
                        <button class="botao botao--primario" type="submit">
                            Carregar profissão
                        </button>
                    </noscript>
                </form>
            </section>

            <?php if ($dadosProfissao !== null): ?>
                <form class="formulario formulario-registro formulario-ficha" action="/Controllers/FichaController.php"
                    method="POST">
                    <input type="hidden" name="acao" value="cadastrar">

                    <input type="hidden" name="id_profissao" value="<?php
                    echo (int) $dadosProfissao['id_profissao'];
                    ?>">

                    <section class="secao-ficha">
                        <header class="secao-ficha__cabecalho">
                            <p class="secao-ficha__etapa">Etapa 2</p>

                            <h2 class="secao-ficha__titulo">
                                Dados do personagem
                            </h2>
                        </header>

                        <div class="campo">
                            <label class="campo__rotulo" for="nome">
                                Nome do personagem:
                            </label>

                            <input class="campo__controle" type="text" name="nome" id="nome" maxlength="100" required value="<?php
                            echo escaparFicha($nomeFicha);
                            ?>">
                        </div>
                    </section>

                    <section class="secao-ficha">
                        <h2 class="secao-ficha__titulo">
                            Profissão escolhida
                        </h2>

                        <div class="resumo-ficha">
                            <div class="resumo-ficha__destaque">
                                <p class="resumo-ficha__rotulo">Profissão</p>

                                <p class="resumo-ficha__valor">
                                    <?php
                                    echo escaparFicha(
                                        $dadosProfissao['nome']
                                    );
                                    ?>
                                </p>
                            </div>

                            <div class="resumo-ficha__atributos">
                                <p>
                                    <strong>PV inicial:</strong>
                                    <?php
                                    echo (int) $dadosProfissao['pv_maximo'];
                                    ?>
                                </p>

                                <p>
                                    <strong>Fadiga inicial:</strong>
                                    0
                                </p>
                            </div>

                            <div class="resumo-ficha__descricao">
                                <p>
                                    <strong>Descrição:</strong>
                                </p>

                                <p>
                                    <?php
                                    echo nl2br(
                                        escaparFicha(
                                            $dadosProfissao['descricao'] ?? ''
                                        )
                                    );
                                    ?>
                                </p>
                            </div>
                        </div>
                    </section>

                    <section class="secao-ficha">
                        <h2 class="secao-ficha__titulo">Vantagens</h2>

                        <?php if (
                            empty($dadosProfissao['vantagens'])
                        ): ?>
                            <p class="secao-ficha__vazio">
                                Nenhuma vantagem encontrada.
                            </p>
                        <?php else: ?>
                            <div class="grade-detalhes-ficha">
                                <?php foreach (
                                    $dadosProfissao['vantagens']
                                    as $vantagem
                                ): ?>
                                    <article class="registro-detalhe">
                                        <h3 class="registro-detalhe__titulo">
                                            <?php
                                            echo escaparFicha(
                                                $vantagem['nome']
                                            );
                                            ?>
                                        </h3>

                                        <div class="registro-detalhe__descricao">
                                            <p>
                                                <?php
                                                echo nl2br(
                                                    escaparFicha(
                                                        $vantagem['descricao']
                                                        ?? ''
                                                    )
                                                );
                                                ?>
                                            </p>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </section>

                    <section class="secao-ficha">
                        <h2 class="secao-ficha__titulo">Magia inicial</h2>

                        <?php
                        $magias = $dadosProfissao['magias'] ?? [];
                        ?>

                        <?php if (empty($magias)): ?>
                            <p class="secao-ficha__vazio">
                                Esta profissão não possui magia inicial.
                            </p>

                        <?php elseif (count($magias) === 1): ?>
                            <?php $magia = $magias[0]; ?>

                            <input type="hidden" name="id_magia" value="<?php
                            echo (int) $magia['id_magia'];
                            ?>">

                            <article class="registro-detalhe">
                                <p class="registro-detalhe__marca">
                                    Magia concedida
                                </p>

                                <h3 class="registro-detalhe__titulo">
                                    <?php
                                    echo escaparFicha($magia['nome']);
                                    ?>
                                </h3>

                                <p class="registro-detalhe__informacoes">
                                    <strong>Elemento:</strong>

                                    <?php
                                    echo escaparFicha(
                                        $magia['elemento']
                                    );
                                    ?>
                                </p>

                                <div class="registro-detalhe__descricao">
                                    <p>
                                        <?php
                                        echo nl2br(
                                            escaparFicha(
                                                $magia['descricao'] ?? ''
                                            )
                                        );
                                        ?>
                                    </p>
                                </div>
                            </article>

                        <?php else: ?>
                            <p class="secao-ficha__descricao">
                                Escolha uma das magias disponíveis:
                            </p>

                            <div class="grade-escolhas-ficha">
                                <?php foreach ($magias as $magia): ?>
                                    <article class="opcao-ficha">
                                        <label class="opcao-ficha__cabecalho">
                                            <input type="radio" name="id_magia" value="<?php
                                            echo (int) $magia['id_magia'];
                                            ?>" required <?php
                                            if (
                                                (int) $idMagiaSelecionada
                                                === (int) $magia['id_magia']
                                            ) {
                                                echo 'checked';
                                            }
                                            ?>>

                                            <strong>
                                                <?php
                                                echo escaparFicha(
                                                    $magia['nome']
                                                );
                                                ?>
                                            </strong>
                                        </label>

                                        <p class="opcao-ficha__informacao">
                                            <strong>Elemento:</strong>

                                            <?php
                                            echo escaparFicha(
                                                $magia['elemento']
                                            );
                                            ?>
                                        </p>

                                        <p class="opcao-ficha__descricao">
                                            <?php
                                            echo nl2br(
                                                escaparFicha(
                                                    $magia['descricao'] ?? ''
                                                )
                                            );
                                            ?>
                                        </p>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </section>

                    <section class="secao-ficha">
                        <h2 class="secao-ficha__titulo">
                            Companheiro animal
                        </h2>

                        <?php
                        $companheiros =
                            $dadosProfissao['companheiros'] ?? [];
                        ?>

                        <?php if (empty($companheiros)): ?>
                            <p class="secao-ficha__vazio">
                                Esta profissão não possui companheiro.
                            </p>

                        <?php else: ?>
                            <?php if (count($companheiros) === 1): ?>
                                <?php
                                $companheiro = $companheiros[0];
                                ?>

                                <input type="hidden" name="id_companheiro" value="<?php
                                echo (int) $companheiro[
                                    'id_companheiro'
                                ];
                                ?>">

                                <article class="registro-detalhe">
                                    <p class="registro-detalhe__marca">
                                        Companheiro concedido
                                    </p>

                                    <h3 class="registro-detalhe__titulo">
                                        <?php
                                        echo escaparFicha(
                                            $companheiro['tipo']
                                        );
                                        ?>
                                    </h3>

                                    <p class="registro-detalhe__informacoes">
                                        <strong>PV máximo:</strong>

                                        <?php
                                        echo (int) $companheiro[
                                            'pv_maximo'
                                        ];
                                        ?>
                                    </p>

                                    <?php if (
                                        !empty($companheiro['dano'])
                                    ): ?>
                                        <p class="registro-detalhe__informacoes">
                                            <strong>Dano:</strong>

                                            <?php
                                            echo escaparFicha(
                                                $companheiro['dano']
                                            );
                                            ?>
                                        </p>
                                    <?php endif; ?>

                                    <div class="registro-detalhe__descricao">
                                        <p>
                                            <?php
                                            echo nl2br(
                                                escaparFicha(
                                                    $companheiro['descricao']
                                                    ?? ''
                                                )
                                            );
                                            ?>
                                        </p>
                                    </div>
                                </article>

                            <?php else: ?>
                                <p class="secao-ficha__descricao">
                                    Escolha um dos companheiros:
                                </p>

                                <div class="grade-escolhas-ficha">
                                    <?php foreach (
                                        $companheiros
                                        as $companheiro
                                    ): ?>
                                        <article class="opcao-ficha">
                                            <label class="opcao-ficha__cabecalho">
                                                <input type="radio" name="id_companheiro" value="<?php
                                                echo (int) $companheiro[
                                                    'id_companheiro'
                                                ];
                                                ?>" required <?php
                                                if (
                                                    (int) $idCompanheiroSelecionado
                                                    === (int) $companheiro[
                                                        'id_companheiro'
                                                    ]
                                                ) {
                                                    echo 'checked';
                                                }
                                                ?>>

                                                <strong>
                                                    <?php
                                                    echo escaparFicha(
                                                        $companheiro['tipo']
                                                    );
                                                    ?>
                                                </strong>
                                            </label>

                                            <p class="opcao-ficha__informacao">
                                                <strong>PV máximo:</strong>

                                                <?php
                                                echo (int) $companheiro[
                                                    'pv_maximo'
                                                ];
                                                ?>
                                            </p>

                                            <?php if (
                                                !empty($companheiro['dano'])
                                            ): ?>
                                                <p class="opcao-ficha__informacao">
                                                    <strong>Dano:</strong>

                                                    <?php
                                                    echo escaparFicha(
                                                        $companheiro['dano']
                                                    );
                                                    ?>
                                                </p>
                                            <?php endif; ?>

                                            <p class="opcao-ficha__descricao">
                                                <?php
                                                echo nl2br(
                                                    escaparFicha(
                                                        $companheiro[
                                                            'descricao'
                                                        ] ?? ''
                                                    )
                                                );
                                                ?>
                                            </p>
                                        </article>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div class="campo">
                                <label class="campo__rotulo" for="nome_companheiro">
                                    Nome do companheiro:
                                </label>

                                <input class="campo__controle" type="text" name="nome_companheiro" id="nome_companheiro"
                                    maxlength="100" required value="<?php
                                    echo escaparFicha(
                                        $nomeCompanheiro
                                    );
                                    ?>">
                            </div>
                        <?php endif; ?>
                    </section>

                    <section class="secao-ficha">
                        <h2 class="secao-ficha__titulo">Armas iniciais</h2>

                        <?php if (
                            empty($dadosProfissao['armas'])
                        ): ?>
                            <p class="secao-ficha__vazio">
                                Nenhuma arma inicial.
                            </p>
                        <?php else: ?>
                            <div class="grade-detalhes-ficha">
                                <?php foreach (
                                    $dadosProfissao['armas']
                                    as $arma
                                ): ?>
                                    <article class="registro-detalhe">
                                        <p class="registro-detalhe__marca">
                                            Slot
                                            <?php echo (int) $arma['slot']; ?>
                                        </p>

                                        <h3 class="registro-detalhe__titulo">
                                            <?php
                                            echo escaparFicha($arma['nome']);
                                            ?>
                                        </h3>

                                        <p class="registro-detalhe__informacoes">
                                            <strong>Tipo:</strong>

                                            <?php
                                            echo escaparFicha($arma['tipo']);
                                            ?>
                                        </p>

                                        <p class="registro-detalhe__informacoes">
                                            <strong>Mãos:</strong>

                                            <?php
                                            echo (int) $arma['maos'];
                                            ?>
                                        </p>

                                        <p class="registro-detalhe__informacoes">
                                            <strong>Dano:</strong>

                                            <?php
                                            echo escaparFicha($arma['dano']);
                                            ?>
                                        </p>

                                        <?php if (
                                            !empty($arma['especial'])
                                        ): ?>
                                            <div class="registro-detalhe__descricao">
                                                <p>
                                                    <strong>Especial:</strong>

                                                    <?php
                                                    echo nl2br(
                                                        escaparFicha(
                                                            $arma['especial']
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

                    <section class="secao-ficha">
                        <h2 class="secao-ficha__titulo">
                            Vestimentas iniciais
                        </h2>

                        <?php if (
                            empty($dadosProfissao['vestimentas'])
                        ): ?>
                            <p class="secao-ficha__vazio">
                                Nenhuma vestimenta inicial.
                            </p>
                        <?php else: ?>
                            <div class="grade-detalhes-ficha">
                                <?php foreach (
                                    $dadosProfissao['vestimentas']
                                    as $vestimenta
                                ): ?>
                                    <article class="registro-detalhe">
                                        <p class="registro-detalhe__marca">
                                            <?php
                                            echo escaparFicha(
                                                $vestimenta['tipo_slot']
                                            );
                                            ?>
                                        </p>

                                        <h3 class="registro-detalhe__titulo">
                                            <?php
                                            echo escaparFicha(
                                                $vestimenta['nome']
                                            );
                                            ?>
                                        </h3>

                                        <p class="registro-detalhe__informacoes">
                                            <strong>Proteção:</strong>

                                            <?php
                                            echo (int) $vestimenta[
                                                'pontos_protecao_maximo'
                                            ];
                                            ?>
                                        </p>

                                        <?php if (
                                            !empty($vestimenta['dano'])
                                        ): ?>
                                            <p class="registro-detalhe__informacoes">
                                                <strong>Dano:</strong>

                                                <?php
                                                echo escaparFicha(
                                                    $vestimenta['dano']
                                                );
                                                ?>
                                            </p>
                                        <?php endif; ?>

                                        <?php if (
                                            !empty($vestimenta['elemento'])
                                        ): ?>
                                            <p class="registro-detalhe__informacoes">
                                                <strong>Elemento:</strong>

                                                <?php
                                                echo escaparFicha(
                                                    $vestimenta['elemento']
                                                );
                                                ?>
                                            </p>
                                        <?php endif; ?>

                                        <?php if (
                                            !empty($vestimenta['especial'])
                                        ): ?>
                                            <div class="registro-detalhe__descricao">
                                                <p>
                                                    <strong>Especial:</strong>

                                                    <?php
                                                    echo nl2br(
                                                        escaparFicha(
                                                            $vestimenta['especial']
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

                    <section class="secao-ficha">
                        <h2 class="secao-ficha__titulo">Itens iniciais</h2>

                        <?php if (
                            empty($dadosProfissao['itens'])
                        ): ?>
                            <p class="secao-ficha__vazio">
                                Nenhum item inicial.
                            </p>
                        <?php else: ?>
                            <ul class="lista-itens-ficha">
                                <?php foreach (
                                    $dadosProfissao['itens']
                                    as $item
                                ): ?>
                                    <li class="lista-itens-ficha__item">
                                        <p class="lista-itens-ficha__titulo">
                                            <strong>
                                                <?php
                                                echo (int) $item['quantidade'];
                                                ?>
                                                ×
                                                <?php
                                                echo escaparFicha($item['nome']);
                                                ?>
                                            </strong>

                                            <span>
                                                <?php
                                                echo escaparFicha($item['tipo']);
                                                ?>
                                            </span>
                                        </p>

                                        <?php if (!empty($item['descricao'])): ?>
                                            <p class="lista-itens-ficha__descricao">
                                                <?php
                                                echo nl2br(
                                                    escaparFicha(
                                                        $item['descricao']
                                                    )
                                                );
                                                ?>
                                            </p>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </section>

                    <div class="formulario__acoes">
                        <button class="botao botao--primario" type="submit">
                            Criar personagem
                        </button>

                        <a class="botao botao--secundario" href="/Controllers/FichaController.php?acao=listar">
                            Cancelar
                        </a>
                    </div>
                </form>
            <?php else: ?>
                <div class="estado-vazio estado-vazio--selecao">
                    <p>Escolha uma profissão.</p>

                    <p>
                        Os dados iniciais do personagem serão exibidos
                        depois da seleção.
                    </p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </main>
</body>

</html>