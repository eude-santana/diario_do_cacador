<?php

require_once __DIR__ . "/../Config/Autenticacao.php";
require_once __DIR__ . "/../Models/Magia.php";

exigirLogin();

$magiaModel = new Magia();

$acao = $_POST["acao"] ?? $_GET["acao"] ?? "";
$idAutor = $_SESSION["id_usuario"];

/*
 * LISTAGEM
 */
if ($acao === "listar") {
    $magias = $magiaModel->listarPorAutor($idAutor);

    require_once __DIR__ . "/../Views/Magia/listagem.php";
    exit;
}

/*
 * CADASTRO
 */
if (
    $acao === "cadastrar"
    && $_SERVER["REQUEST_METHOD"] === "POST"
) {
    $nome = trim($_POST["nome"] ?? "");
    $elemento = trim($_POST["elemento"] ?? "");
    $descricao = trim($_POST["descricao"] ?? "");

    if ($nome === "" || $elemento === "") {
        header(
            "Location: /Views/Magia/cadastro.php?erro=campos"
        );
        exit;
    }

    if (strlen($nome) > 100) {
        header(
            "Location: /Views/Magia/cadastro.php?erro=nome_longo"
        );
        exit;
    }

    if (strlen($elemento) > 50) {
        header(
            "Location: /Views/Magia/cadastro.php?erro=elemento_longo"
        );
        exit;
    }

    $resultado = $magiaModel->cadastrar(
        $nome,
        $elemento,
        $descricao,
        $idAutor
    );

    if ($resultado) {
        header(
            "Location: /Views/Magia/cadastro.php?sucesso=1"
        );
        exit;
    }

    header(
        "Location: /Views/Magia/cadastro.php?erro=cadastro"
    );
    exit;
}

/*
 * ABRIR A TELA DE EDIÇÃO
 */
if (
    $acao === "editar"
    && $_SERVER["REQUEST_METHOD"] === "GET"
) {
    $idMagia = (int) ($_GET["id"] ?? 0);

    $magia = $magiaModel->buscarPorId(
        $idMagia,
        $idAutor
    );

    if (!$magia) {
        header(
            "Location: MagiaController.php"
            . "?acao=listar&erro=magia_nao_encontrada"
        );
        exit;
    }

    require_once __DIR__ . "/../Views/Magia/editar.php";
    exit;
}

/*
 * ATUALIZAÇÃO
 */
if (
    $acao === "atualizar"
    && $_SERVER["REQUEST_METHOD"] === "POST"
) {
    $idMagia = (int) ($_POST["id_magia"] ?? 0);
    $nome = trim($_POST["nome"] ?? "");
    $elemento = trim($_POST["elemento"] ?? "");
    $descricao = trim($_POST["descricao"] ?? "");

    if ($idMagia <= 0 || $nome === "" || $elemento === "") {
        header(
            "Location: MagiaController.php"
            . "?acao=listar&erro=dados"
        );
        exit;
    }

    $magiaExistente = $magiaModel->buscarPorId(
        $idMagia,
        $idAutor
    );

    if (!$magiaExistente) {
        header(
            "Location: MagiaController.php"
            . "?acao=listar&erro=magia_nao_encontrada"
        );
        exit;
    }

    if (strlen($nome) > 100) {
        header(
            "Location: MagiaController.php"
            . "?acao=editar&id={$idMagia}&erro=nome_longo"
        );
        exit;
    }

    if (strlen($elemento) > 50) {
        header(
            "Location: MagiaController.php"
            . "?acao=editar&id={$idMagia}&erro=elemento_longo"
        );
        exit;
    }

    $resultado = $magiaModel->atualizar(
        $idMagia,
        $nome,
        $elemento,
        $descricao,
        $idAutor
    );

    if ($resultado) {
        header(
            "Location: MagiaController.php"
            . "?acao=listar&sucesso=atualizada"
        );
        exit;
    }

    header(
        "Location: MagiaController.php"
        . "?acao=editar&id={$idMagia}&erro=atualizacao"
    );
    exit;
}

/*
 * EXCLUSÃO
 */
if (
    $acao === "excluir"
    && $_SERVER["REQUEST_METHOD"] === "POST"
) {
    $idMagia = (int) ($_POST["id_magia"] ?? 0);

    if ($idMagia <= 0) {
        header(
            "Location: MagiaController.php"
            . "?acao=listar&erro=dados"
        );
        exit;
    }

    try {
        $resultado = $magiaModel->excluir(
            $idMagia,
            $idAutor
        );

        if ($resultado) {
            header(
                "Location: MagiaController.php"
                . "?acao=listar&sucesso=excluida"
            );
            exit;
        }

        header(
            "Location: MagiaController.php"
            . "?acao=listar&erro=magia_nao_encontrada"
        );
        exit;
    } catch (PDOException $erro) {
        if ($erro->getCode() === "23000") {
            header(
                "Location: MagiaController.php"
                . "?acao=listar&erro=magia_vinculada"
            );
            exit;
        }

        header(
            "Location: MagiaController.php"
            . "?acao=listar&erro=exclusao"
        );
        exit;
    }
}

header("Location: /Views/Magia/cadastro.php");
exit;