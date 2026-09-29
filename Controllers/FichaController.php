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

    $codigoErro = $_GET['erro'] ?? '';

    if ($codigoErro === 'ficha_invalida') {
        $erro = 'A ficha informada é inválida.';
    } elseif ($codigoErro === 'ficha_nao_encontrada') {
        $erro = 'Ficha não encontrada ou não pertence ao usuário.';
    } elseif ($codigoErro === 'banco') {
        $erro = 'Não foi possível excluir a ficha.';
    }

    $sucesso = $_GET['sucesso'] ?? '';

    if ($sucesso === 'cadastro') {
        $mensagem = 'Ficha cadastrada com sucesso.';
    } elseif ($sucesso === 'exclusao') {
        $mensagem = 'Ficha excluída com sucesso.';
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