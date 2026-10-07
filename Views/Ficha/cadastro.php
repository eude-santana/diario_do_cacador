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

    <title>Cadastrar ficha</title>
</head>

<body>
    <h1>Cadastrar ficha</h1>

    <?php if ($erro !== ''): ?>
        <p>
            <?php echo escaparFicha($erro); ?>
        </p>
    <?php endif; ?>

    <?php if (empty($profissoes)): ?>
        <p>
            Nenhuma profissão própria e completa está disponível.
        </p>

        <p>
            Para criar uma ficha, a profissão precisa possuir
            exatamente duas vantagens.
        </p>

        <a href="/Controllers/ProfissaoController.php?acao=novo">
            Cadastrar profissão
        </a>

        <br><br>

        <a href="/Views/Usuario/painel.php">
            Voltar ao painel
        </a>

    <?php else: ?>

        <section>
            <h2>Escolha da profissão</h2>

            <form action="/Controllers/FichaController.php" method="GET">
                <input type="hidden" name="acao" value="novo">

                <label for="id_profissao">
                    Profissão:
                </label>

                <select name="id_profissao" id="id_profissao" required onchange="this.form.submit()">
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

                <noscript>
                    <button type="submit">
                        Carregar profissão
                    </button>
                </noscript>
            </form>
        </section>

        <?php if ($dadosProfissao !== null): ?>
            <hr>

            <form action="/Controllers/FichaController.php" method="POST">
                <input type="hidden" name="acao" value="cadastrar">

                <input type="hidden" name="id_profissao" value="<?php
                echo (int) $dadosProfissao['id_profissao'];
                ?>">

                <section>
                    <h2>Dados da ficha</h2>

                    <p>
                        <label for="nome">
                            Nome do Personagem:
                        </label>

                        <input type="text" name="nome" id="nome" maxlength="100" required value="<?php
                        echo escaparFicha($nomeFicha);
                        ?>">
                    </p>
                </section>

                <section>
                    <h2>Profissão escolhida</h2>

                    <p>
                        <strong>Nome:</strong>

                        <?php
                        echo escaparFicha(
                            $dadosProfissao['nome']
                        );
                        ?>
                    </p>

                    <p>
                        <strong>Descrição:</strong>

                        <?php
                        echo nl2br(
                            escaparFicha(
                                $dadosProfissao['descricao'] ?? ''
                            )
                        );
                        ?>
                    </p>

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
                </section>

                <section>
                    <h2>Vantagens</h2>

                    <?php if (
                        empty($dadosProfissao['vantagens'])
                    ): ?>
                        <p>Nenhuma vantagem encontrada.</p>
                    <?php else: ?>
                        <?php foreach (
                            $dadosProfissao['vantagens']
                            as $vantagem
                        ): ?>
                            <article>
                                <h3>
                                    <?php
                                    echo escaparFicha(
                                        $vantagem['nome']
                                    );
                                    ?>
                                </h3>

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
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </section>

                <section>
                    <h2>Magia inicial</h2>

                    <?php
                    $magias = $dadosProfissao['magias'] ?? [];
                    ?>

                    <?php if (empty($magias)): ?>
                        <p>
                            Esta profissão não possui magia inicial.
                        </p>

                    <?php elseif (count($magias) === 1): ?>
                        <?php $magia = $magias[0]; ?>

                        <input type="hidden" name="id_magia" value="<?php
                        echo (int) $magia['id_magia'];
                        ?>">

                        <article>
                            <h3>
                                <?php
                                echo escaparFicha($magia['nome']);
                                ?>
                            </h3>

                            <p>
                                <strong>Elemento:</strong>

                                <?php
                                echo escaparFicha(
                                    $magia['elemento']
                                );
                                ?>
                            </p>

                            <p>
                                <?php
                                echo nl2br(
                                    escaparFicha(
                                        $magia['descricao'] ?? ''
                                    )
                                );
                                ?>
                            </p>
                        </article>

                    <?php else: ?>
                        <p>
                            Escolha uma das magias disponíveis:
                        </p>

                        <?php foreach ($magias as $magia): ?>
                            <article>
                                <label>
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

                                <p>
                                    <strong>Elemento:</strong>

                                    <?php
                                    echo escaparFicha(
                                        $magia['elemento']
                                    );
                                    ?>
                                </p>

                                <p>
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
                    <?php endif; ?>
                </section>

                <section>
                    <h2>Companheiro animal</h2>

                    <?php
                    $companheiros =
                        $dadosProfissao['companheiros'] ?? [];
                    ?>

                    <?php if (empty($companheiros)): ?>
                        <p>
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

                            <article>
                                <h3>
                                    <?php
                                    echo escaparFicha(
                                        $companheiro['tipo']
                                    );
                                    ?>
                                </h3>

                                <p>
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
                                    <p>
                                        <strong>Dano:</strong>

                                        <?php
                                        echo escaparFicha(
                                            $companheiro['dano']
                                        );
                                        ?>
                                    </p>
                                <?php endif; ?>

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
                            </article>

                        <?php else: ?>
                            <p>
                                Escolha um dos companheiros:
                            </p>

                            <?php foreach (
                                $companheiros
                                as $companheiro
                            ): ?>
                                <article>
                                    <label>
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

                                    <p>
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
                                        <p>
                                            <strong>Dano:</strong>

                                            <?php
                                            echo escaparFicha(
                                                $companheiro['dano']
                                            );
                                            ?>
                                        </p>
                                    <?php endif; ?>

                                    <p>
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
                        <?php endif; ?>

                        <p>
                            <label for="nome_companheiro">
                                Nome do companheiro:
                            </label>

                            <input type="text" name="nome_companheiro" id="nome_companheiro" maxlength="100" required value="<?php
                            echo escaparFicha(
                                $nomeCompanheiro
                            );
                            ?>">
                        </p>
                    <?php endif; ?>
                </section>

                <section>
                    <h2>Armas iniciais</h2>

                    <?php if (
                        empty($dadosProfissao['armas'])
                    ): ?>
                        <p>Nenhuma arma inicial.</p>
                    <?php else: ?>
                        <?php foreach (
                            $dadosProfissao['armas']
                            as $arma
                        ): ?>
                            <article>
                                <h3>
                                    Slot
                                    <?php echo (int) $arma['slot']; ?>:
                                    <?php
                                    echo escaparFicha($arma['nome']);
                                    ?>
                                </h3>

                                <p>
                                    <strong>Tipo:</strong>

                                    <?php
                                    echo escaparFicha($arma['tipo']);
                                    ?>
                                </p>

                                <p>
                                    <strong>Mãos:</strong>

                                    <?php
                                    echo (int) $arma['maos'];
                                    ?>
                                </p>

                                <p>
                                    <strong>Dano:</strong>

                                    <?php
                                    echo escaparFicha($arma['dano']);
                                    ?>
                                </p>

                                <?php if (
                                    !empty($arma['especial'])
                                ): ?>
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
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </section>

                <section>
                    <h2>Vestimentas iniciais</h2>

                    <?php if (
                        empty($dadosProfissao['vestimentas'])
                    ): ?>
                        <p>Nenhuma vestimenta inicial.</p>
                    <?php else: ?>
                        <?php foreach (
                            $dadosProfissao['vestimentas']
                            as $vestimenta
                        ): ?>
                            <article>
                                <h3>
                                    <?php
                                    echo escaparFicha(
                                        $vestimenta['tipo_slot']
                                    );
                                    ?>:
                                    <?php
                                    echo escaparFicha(
                                        $vestimenta['nome']
                                    );
                                    ?>
                                </h3>

                                <p>
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
                                    <p>
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
                                    <p>
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
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </section>

                <section>
                    <h2>Itens iniciais</h2>

                    <?php if (
                        empty($dadosProfissao['itens'])
                    ): ?>
                        <p>Nenhum item inicial.</p>
                    <?php else: ?>
                        <ul>
                            <?php foreach (
                                $dadosProfissao['itens']
                                as $item
                            ): ?>
                                <li>
                                    <?php
                                    echo (int) $item['quantidade'];
                                    ?>
                                    ×
                                    <?php
                                    echo escaparFicha($item['nome']);
                                    ?>

                                    —
                                    <?php
                                    echo escaparFicha($item['tipo']);
                                    ?>

                                    <?php if (!empty($item['descricao'])): ?>
                                        <p>
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

                <br>

                <button type="submit">
                    Cadastrar ficha
                </button>

                <a href="/Controllers/FichaController.php?acao=listar">
                    Cancelar
                </a>
            </form>
        <?php else: ?>
            <p>
                Escolha uma profissão para visualizar os dados
                iniciais da ficha.
            </p>
        <?php endif; ?>

        <br>

        <a href="/Views/Usuario/painel.php">
            Voltar ao painel
        </a>
    <?php endif; ?>
</body>

</html>