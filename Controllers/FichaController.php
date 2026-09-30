<?php

require_once __DIR__ . '/../Config/Autenticacao.php';
require_once __DIR__ . '/../Models/Ficha.php';

exigirLogin();

$fichaModel = new Ficha();

$idUsuario = (int) $_SESSION['id_usuario'];

$acao = $_POST['acao']
    ?? $_GET['acao']
    ?? 'listar';

switch ($acao) {
    case 'novo':
        exibirCadastroFicha(
            $fichaModel,
            $idUsuario
        );
        break;

    case 'cadastrar':
        cadastrarFicha(
            $fichaModel,
            $idUsuario
        );
        break;

    case 'listar':
        listarFichas(
            $fichaModel,
            $idUsuario
        );
        break;

    case 'excluir':
        excluirFicha(
            $fichaModel,
            $idUsuario
        );
        break;

    case 'gerenciar':
        gerenciarFicha(
            $fichaModel,
            $idUsuario
        );
        break;

    case 'atualizar_dados':
        atualizarDadosFicha(
            $fichaModel,
            $idUsuario
        );
        break;

    case 'atualizar_companheiro':
        atualizarCompanheiroFicha(
            $fichaModel,
            $idUsuario
        );
        break;

    case 'atualizar_item':
        atualizarItemFicha(
            $fichaModel,
            $idUsuario
        );
        break;

    case 'atualizar_arma':
        atualizarArmaFicha(
            $fichaModel,
            $idUsuario
        );
        break;

    default:
        header(
            'Location: /Controllers/FichaController.php?acao=listar'
        );
        exit;
}

/*
 * Exibe o cadastro.
 *
 * Quando id_profissao estiver na URL, carrega os dados
 * da profissão para mostrar ao jogador.
 */
function exibirCadastroFicha(
    Ficha $fichaModel,
    int $idUsuario,
    string $erro = '',
    array $dadosFormulario = []
): void {
    try {
        $profissoes = $fichaModel->listarProfissoesDisponiveis(
            $idUsuario
        );

        $nomeFicha = $dadosFormulario['nome'] ?? '';

        $idProfissaoSelecionada = $dadosFormulario['id_profissao']
            ?? lerIdFicha($_GET['id_profissao'] ?? null);

        $idMagiaSelecionada = $dadosFormulario['id_magia']
            ?? null;

        $idCompanheiroSelecionado =
            $dadosFormulario['id_companheiro'] ?? null;

        $nomeCompanheiro =
            $dadosFormulario['nome_companheiro'] ?? '';

        $dadosProfissao = null;

        if ($idProfissaoSelecionada !== null) {
            $dadosProfissao = $fichaModel->buscarDadosIniciais(
                $idProfissaoSelecionada,
                $idUsuario
            );

            if (!$dadosProfissao && $erro === '') {
                $erro = 'A profissão escolhida não está disponível.';
            }
        }

        require __DIR__ . '/../Views/Ficha/cadastro.php';
    } catch (PDOException $excecao) {
        error_log($excecao->getMessage());

        $profissoes = [];
        $dadosProfissao = null;

        $nomeFicha = $dadosFormulario['nome'] ?? '';

        $idProfissaoSelecionada =
            $dadosFormulario['id_profissao'] ?? null;

        $idMagiaSelecionada =
            $dadosFormulario['id_magia'] ?? null;

        $idCompanheiroSelecionado =
            $dadosFormulario['id_companheiro'] ?? null;

        $nomeCompanheiro =
            $dadosFormulario['nome_companheiro'] ?? '';

        $erro = 'Não foi possível carregar os dados do cadastro.';

        require __DIR__ . '/../Views/Ficha/cadastro.php';
    }
}

function cadastrarFicha(
    Ficha $fichaModel,
    int $idUsuario
): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header(
            'Location: /Controllers/FichaController.php?acao=novo'
        );
        exit;
    }

    $nome = trim($_POST['nome'] ?? '');

    $idProfissao = lerIdFicha(
        $_POST['id_profissao'] ?? null
    );

    $idMagia = lerIdFicha(
        $_POST['id_magia'] ?? null
    );

    $idCompanheiro = lerIdFicha(
        $_POST['id_companheiro'] ?? null
    );

    $nomeCompanheiro = trim(
        $_POST['nome_companheiro'] ?? ''
    );

    $dadosFormulario = [
        'nome' => $nome,
        'id_profissao' => $idProfissao,
        'id_magia' => $idMagia,
        'id_companheiro' => $idCompanheiro,
        'nome_companheiro' => $nomeCompanheiro
    ];

    if ($idProfissao === null) {
        exibirCadastroFicha(
            $fichaModel,
            $idUsuario,
            'Escolha uma profissão válida.',
            $dadosFormulario
        );

        return;
    }

    try {
        $fichaModel->cadastrar(
            $nome,
            $idProfissao,
            $idUsuario,
            $idMagia,
            $idCompanheiro,
            $nomeCompanheiro
        );

        header(
            'Location: /Controllers/FichaController.php'
            . '?acao=listar&sucesso=cadastro'
        );
        exit;
    } catch (InvalidArgumentException $excecao) {
        exibirCadastroFicha(
            $fichaModel,
            $idUsuario,
            $excecao->getMessage(),
            $dadosFormulario
        );
    } catch (PDOException $excecao) {
        error_log($excecao->getMessage());

        exibirCadastroFicha(
            $fichaModel,
            $idUsuario,
            'Não foi possível cadastrar a ficha.',
            $dadosFormulario
        );
    }
}

function listarFichas(
    Ficha $fichaModel,
    int $idUsuario
): void {
    $erro = '';
    $mensagem = '';

    $sucesso = $_GET['sucesso'] ?? '';
    $codigoErro = $_GET['erro'] ?? '';

    if ($sucesso === 'cadastro') {
        $mensagem = 'Personagem cadastrado com sucesso.';
    } elseif ($sucesso === 'exclusao') {
        $mensagem = 'Ficha excluída com sucesso.';
    }

    if ($codigoErro === 'ficha_invalida') {
        $erro = 'A ficha informada é inválida.';
    } elseif ($codigoErro === 'ficha_nao_encontrada') {
        $erro = 'Ficha não encontrada ou pertencente a outro usuário.';
    } elseif ($codigoErro === 'banco') {
        $erro = 'Não foi possível excluir a ficha.';
    }

    try {
        $fichas = $fichaModel->listarPorUsuario(
            $idUsuario
        );
    } catch (PDOException $excecao) {
        error_log($excecao->getMessage());

        $fichas = [];
        $erro = 'Não foi possível carregar as fichas.';
    }

    require __DIR__ . '/../Views/Ficha/listagem.php';
}

function excluirFicha(
    Ficha $fichaModel,
    int $idUsuario
): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header(
            'Location: /Controllers/FichaController.php?acao=listar'
        );
        exit;
    }

    $idFicha = lerIdFicha(
        $_POST['id_ficha'] ?? null
    );

    if ($idFicha === null) {
        header(
            'Location: /Controllers/FichaController.php'
            . '?acao=listar&erro=ficha_invalida'
        );
        exit;
    }

    try {
        $excluiu = $fichaModel->excluir(
            $idFicha,
            $idUsuario
        );

        if (!$excluiu) {
            header(
                'Location: /Controllers/FichaController.php'
                . '?acao=listar&erro=ficha_nao_encontrada'
            );
            exit;
        }

        header(
            'Location: /Controllers/FichaController.php'
            . '?acao=listar&sucesso=exclusao'
        );
        exit;
    } catch (PDOException $excecao) {
        error_log($excecao->getMessage());

        header(
            'Location: /Controllers/FichaController.php'
            . '?acao=listar&erro=banco'
        );
        exit;
    }
}

function gerenciarFicha(
    Ficha $fichaModel,
    int $idUsuario
): void {
    $idFicha = lerIdFicha(
        $_GET['id'] ?? null
    );

    if ($idFicha === null) {
        header(
            'Location: /Controllers/FichaController.php'
            . '?acao=listar&erro=ficha_invalida'
        );
        exit;
    }

    $mensagem = '';
    $erro = '';

    $sucesso = $_GET['sucesso'] ?? '';

    if ($sucesso === 'dados') {
        $mensagem = 'Dados do personagem atualizados com sucesso.';
    } elseif ($sucesso === 'companheiro') {
        $mensagem = 'Companheiro atualizado com sucesso.';
    } elseif ($sucesso === 'item') {
        $mensagem = 'Mochila atualizada com sucesso.';
    } elseif ($sucesso === 'arma') {
        $mensagem = 'Slot de arma atualizado com sucesso.';
    }

    try {
        exibirGerenciamentoFicha(
            $fichaModel,
            $idUsuario,
            $idFicha,
            $erro,
            $mensagem
        );
    } catch (PDOException $excecao) {
        error_log($excecao->getMessage());

        header(
            'Location: /Controllers/FichaController.php'
            . '?acao=listar&erro=banco'
        );
        exit;
    }
}

function atualizarCompanheiroFicha(
    Ficha $fichaModel,
    int $idUsuario
): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header(
            'Location: /Controllers/FichaController.php?acao=listar'
        );
        exit;
    }

    $idFicha = lerIdFicha(
        $_POST['id_ficha'] ?? null
    );

    if ($idFicha === null) {
        header(
            'Location: /Controllers/FichaController.php'
            . '?acao=listar&erro=ficha_invalida'
        );
        exit;
    }

    $nomeCompanheiro = trim(
        $_POST['nome_companheiro'] ?? ''
    );

    $pvAtual = lerInteiroNaoNegativoFicha(
        $_POST['pv_companheiro'] ?? null
    );

    if ($pvAtual === null) {
        try {
            exibirGerenciamentoFicha(
                $fichaModel,
                $idUsuario,
                $idFicha,
                'Informe um valor válido para o PV do companheiro.'
            );
        } catch (PDOException $excecao) {
            error_log($excecao->getMessage());

            header(
                'Location: /Controllers/FichaController.php'
                . '?acao=listar&erro=banco'
            );
        }

        return;
    }

    try {
        $fichaModel->atualizarCompanheiro(
            $idFicha,
            $idUsuario,
            $nomeCompanheiro,
            $pvAtual
        );

        header(
            'Location: /Controllers/FichaController.php'
            . '?acao=gerenciar&id=' . $idFicha
            . '&sucesso=companheiro'
        );
        exit;
    } catch (InvalidArgumentException $excecao) {
        exibirGerenciamentoFicha(
            $fichaModel,
            $idUsuario,
            $idFicha,
            $excecao->getMessage()
        );
    } catch (PDOException $excecao) {
        error_log($excecao->getMessage());

        try {
            exibirGerenciamentoFicha(
                $fichaModel,
                $idUsuario,
                $idFicha,
                'Não foi possível atualizar o companheiro.'
            );
        } catch (PDOException $novoErro) {
            error_log($novoErro->getMessage());

            header(
                'Location: /Controllers/FichaController.php'
                . '?acao=listar&erro=banco'
            );
            exit;
        }
    }
}

function atualizarItemFicha(
    Ficha $fichaModel,
    int $idUsuario
): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header(
            'Location: /Controllers/FichaController.php?acao=listar'
        );
        exit;
    }

    $idFicha = lerIdFicha(
        $_POST['id_ficha'] ?? null
    );

    $idItem = lerIdFicha(
        $_POST['id_item'] ?? null
    );

    $quantidade = lerInteiroNaoNegativoFicha(
        $_POST['quantidade'] ?? null
    );

    if ($idFicha === null || $idItem === null) {
        header(
            'Location: /Controllers/FichaController.php'
            . '?acao=listar&erro=ficha_invalida'
        );
        exit;
    }

    if ($quantidade === null) {
        try {
            exibirGerenciamentoFicha(
                $fichaModel,
                $idUsuario,
                $idFicha,
                'Informe uma quantidade válida para o item.'
            );
        } catch (PDOException $excecao) {
            error_log($excecao->getMessage());

            header(
                'Location: /Controllers/FichaController.php'
                . '?acao=listar&erro=banco'
            );
        }

        return;
    }

    try {
        $fichaModel->atualizarItemDaFicha(
            $idFicha,
            $idUsuario,
            $idItem,
            $quantidade
        );

        header(
            'Location: /Controllers/FichaController.php'
            . '?acao=gerenciar&id=' . $idFicha
            . '&sucesso=item'
        );
        exit;
    } catch (InvalidArgumentException $excecao) {
        exibirGerenciamentoFicha(
            $fichaModel,
            $idUsuario,
            $idFicha,
            $excecao->getMessage()
        );
    } catch (PDOException $excecao) {
        error_log($excecao->getMessage());

        try {
            exibirGerenciamentoFicha(
                $fichaModel,
                $idUsuario,
                $idFicha,
                'Não foi possível atualizar o item.'
            );
        } catch (PDOException $novoErro) {
            error_log($novoErro->getMessage());

            header(
                'Location: /Controllers/FichaController.php'
                . '?acao=listar&erro=banco'
            );
            exit;
        }
    }
}

function atualizarArmaFicha(
    Ficha $fichaModel,
    int $idUsuario
): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header(
            'Location: /Controllers/FichaController.php?acao=listar'
        );
        exit;
    }

    $idFicha = lerIdFicha(
        $_POST['id_ficha'] ?? null
    );

    $slot = lerIdFicha(
        $_POST['slot'] ?? null
    );

    /*
     * Campo vazio representa um slot sem arma.
     */
    $idArma = lerIdFicha(
        $_POST['id_arma'] ?? null
    );

    if ($idFicha === null || $slot === null) {
        header(
            'Location: /Controllers/FichaController.php'
            . '?acao=listar&erro=ficha_invalida'
        );
        exit;
    }

    try {
        $fichaModel->atualizarArmaDaFicha(
            $idFicha,
            $idUsuario,
            $slot,
            $idArma
        );

        header(
            'Location: /Controllers/FichaController.php'
            . '?acao=gerenciar&id=' . $idFicha
            . '&sucesso=arma'
        );
        exit;
    } catch (InvalidArgumentException $excecao) {
        exibirGerenciamentoFicha(
            $fichaModel,
            $idUsuario,
            $idFicha,
            $excecao->getMessage()
        );
    } catch (PDOException $excecao) {
        error_log($excecao->getMessage());

        try {
            exibirGerenciamentoFicha(
                $fichaModel,
                $idUsuario,
                $idFicha,
                'Não foi possível atualizar o slot de arma.'
            );
        } catch (PDOException $novoErro) {
            error_log($novoErro->getMessage());

            header(
                'Location: /Controllers/FichaController.php'
                . '?acao=listar&erro=banco'
            );
            exit;
        }
    }
}

function atualizarDadosFicha(
    Ficha $fichaModel,
    int $idUsuario
): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header(
            'Location: /Controllers/FichaController.php?acao=listar'
        );
        exit;
    }

    $idFicha = lerIdFicha(
        $_POST['id_ficha'] ?? null
    );

    if ($idFicha === null) {
        header(
            'Location: /Controllers/FichaController.php'
            . '?acao=listar&erro=ficha_invalida'
        );
        exit;
    }

    $nomePersonagem = trim(
        $_POST['nome'] ?? ''
    );

    $pvAtual = lerInteiroNaoNegativoFicha(
        $_POST['pv_atual'] ?? null
    );

    $fadiga = lerInteiroNaoNegativoFicha(
        $_POST['fadiga'] ?? null
    );

    if ($pvAtual === null) {
        try {
            exibirGerenciamentoFicha(
                $fichaModel,
                $idUsuario,
                $idFicha,
                'Informe um valor válido para o PV atual.'
            );
        } catch (PDOException $excecao) {
            error_log($excecao->getMessage());

            header(
                'Location: /Controllers/FichaController.php'
                . '?acao=listar&erro=banco'
            );
        }

        return;
    }

    if ($fadiga === null) {
        try {
            exibirGerenciamentoFicha(
                $fichaModel,
                $idUsuario,
                $idFicha,
                'Informe um valor válido para a fadiga.'
            );
        } catch (PDOException $excecao) {
            error_log($excecao->getMessage());

            header(
                'Location: /Controllers/FichaController.php'
                . '?acao=listar&erro=banco'
            );
        }

        return;
    }

    try {
        $fichaModel->atualizarDadosBasicos(
            $idFicha,
            $idUsuario,
            $nomePersonagem,
            $pvAtual,
            $fadiga
        );

        header(
            'Location: /Controllers/FichaController.php'
            . '?acao=gerenciar&id=' . $idFicha
            . '&sucesso=dados'
        );
        exit;
    } catch (InvalidArgumentException $excecao) {
        exibirGerenciamentoFicha(
            $fichaModel,
            $idUsuario,
            $idFicha,
            $excecao->getMessage()
        );
    } catch (PDOException $excecao) {
        error_log($excecao->getMessage());

        try {
            exibirGerenciamentoFicha(
                $fichaModel,
                $idUsuario,
                $idFicha,
                'Não foi possível atualizar os dados do personagem.'
            );
        } catch (PDOException $novoErro) {
            error_log($novoErro->getMessage());

            header(
                'Location: /Controllers/FichaController.php'
                . '?acao=listar&erro=banco'
            );
            exit;
        }
    }
}

function exibirGerenciamentoFicha(
    Ficha $fichaModel,
    int $idUsuario,
    int $idFicha,
    string $erro = '',
    string $mensagem = ''
): void {
    $detalhes = $fichaModel->buscarDetalhes(
        $idFicha,
        $idUsuario
    );

    if ($detalhes === null) {
        header(
            'Location: /Controllers/FichaController.php'
            . '?acao=listar&erro=ficha_nao_encontrada'
        );
        exit;
    }

    $buscaItem = trim(
        $_GET['busca_item'] ?? ''
    );

    $tipoItem = trim(
        $_GET['tipo_item'] ?? ''
    );

    $tiposPermitidos = [
        'CONSUMIVEL',
        'MATERIAL',
        'UTILITARIO',
        'OUTRO'
    ];

    if (
        $tipoItem !== ''
        && !in_array(
            $tipoItem,
            $tiposPermitidos,
            true
        )
    ) {
        $tipoItem = '';
    }

    $itensDisponiveis =
        $fichaModel->listarItensDisponiveis(
            $idUsuario,
            $buscaItem,
            $tipoItem
        );

    $armasDisponiveis =
        $fichaModel->listarArmasDisponiveis(
            $idUsuario
        );

    require __DIR__ . '/../Views/Ficha/gerenciar.php';
}

function lerInteiroNaoNegativoFicha(
    mixed $valor
): ?int {
    $numero = filter_var(
        $valor,
        FILTER_VALIDATE_INT,
        [
            'options' => [
                'min_range' => 0
            ]
        ]
    );

    if ($numero === false) {
        return null;
    }

    return $numero;
}

/*
 * Converte um valor em ID positivo.
 *
 * Campos vazios retornam null, o que é necessário porque
 * magia e companheiro são opcionais dependendo da profissão.
 */
function lerIdFicha(mixed $valor): ?int
{
    if ($valor === null || $valor === '') {
        return null;
    }

    $id = filter_var(
        $valor,
        FILTER_VALIDATE_INT,
        [
            'options' => [
                'min_range' => 1
            ]
        ]
    );

    if ($id === false) {
        return null;
    }

    return $id;
}