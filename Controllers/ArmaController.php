<?php

require_once __DIR__ . "/../Config/Autenticacao.php";
require_once __DIR__ . "/../Models/Arma.php";

exigirLogin();

$armaModel = new Arma();

$acao = $_POST["acao"] ?? $_GET["acao"] ?? "";
$idAutor = $_SESSION["id_usuario"];

$tiposPermitidos = ["CORPORAL", "DISTANCIA"];
$quantidadesMaosPermitidas = [1, 2];

/*
 * LISTAGEM
 */
if ($acao === "listar") {
    $armas = $armaModel->listarPorAutor($idAutor);

    require_once __DIR__ . "/../Views/Arma/listagem.php";
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
    $tipo = $_POST["tipo"] ?? "";
    $maos = (int) ($_POST["maos"] ?? 0);
    $dano = trim($_POST["dano"] ?? "");
    $especial = trim($_POST["especial"] ?? "");

    if ($nome === "" || $dano === "") {
        header(
            "Location: /Views/Arma/cadastro.php?erro=campos"
        );
        exit;
    }

    if (strlen($nome) > 100) {
        header(
            "Location: /Views/Arma/cadastro.php?erro=nome_longo"
        );
        exit;
    }

    if (strlen($dano) > 50) {
        header(
            "Location: /Views/Arma/cadastro.php?erro=dano_longo"
        );
        exit;
    }

    if (!in_array($tipo, $tiposPermitidos, true)) {
        header(
            "Location: /Views/Arma/cadastro.php?erro=tipo"
        );
        exit;
    }

    if (!in_array($maos, $quantidadesMaosPermitidas, true)) {
        header(
            "Location: /Views/Arma/cadastro.php?erro=maos"
        );
        exit;
    }

    $resultado = $armaModel->cadastrar(
        $nome,
        $tipo,
        $maos,
        $dano,
        $especial,
        $idAutor
    );

    if ($resultado) {
        header(
            "Location: /Views/Arma/cadastro.php?sucesso=1"
        );
        exit;
    }

    header(
        "Location: /Views/Arma/cadastro.php?erro=cadastro"
    );
    exit;
}

/*
 * ABRIR TELA DE EDIÇÃO
 */
if (
    $acao === "editar"
    && $_SERVER["REQUEST_METHOD"] === "GET"
) {
    $idArma = (int) ($_GET["id"] ?? 0);

    $arma = $armaModel->buscarPorId(
        $idArma,
        $idAutor
    );

    if (!$arma) {
        header(
            "Location: ArmaController.php"
            . "?acao=listar&erro=arma_nao_encontrada"
        );
        exit;
    }

    require_once __DIR__ . "/../Views/Arma/editar.php";
    exit;
}

/*
 * ATUALIZAÇÃO
 */
if (
    $acao === "atualizar"
    && $_SERVER["REQUEST_METHOD"] === "POST"
) {
    $idArma = (int) ($_POST["id_arma"] ?? 0);
    $nome = trim($_POST["nome"] ?? "");
    $tipo = $_POST["tipo"] ?? "";
    $maos = (int) ($_POST["maos"] ?? 0);
    $dano = trim($_POST["dano"] ?? "");
    $especial = trim($_POST["especial"] ?? "");

    if ($idArma <= 0 || $nome === "" || $dano === "") {
        header(
            "Location: ArmaController.php"
            . "?acao=listar&erro=dados"
        );
        exit;
    }

    $armaExistente = $armaModel->buscarPorId(
        $idArma,
        $idAutor
    );

    if (!$armaExistente) {
        header(
            "Location: ArmaController.php"
            . "?acao=listar&erro=arma_nao_encontrada"
        );
        exit;
    }

    if (strlen($nome) > 100) {
        header(
            "Location: ArmaController.php"
            . "?acao=editar&id={$idArma}&erro=nome_longo"
        );
        exit;
    }

    if (strlen($dano) > 50) {
        header(
            "Location: ArmaController.php"
            . "?acao=editar&id={$idArma}&erro=dano_longo"
        );
        exit;
    }

    if (!in_array($tipo, $tiposPermitidos, true)) {
        header(
            "Location: ArmaController.php"
            . "?acao=editar&id={$idArma}&erro=tipo"
        );
        exit;
    }

    if (!in_array($maos, $quantidadesMaosPermitidas, true)) {
        header(
            "Location: ArmaController.php"
            . "?acao=editar&id={$idArma}&erro=maos"
        );
        exit;
    }

    $resultado = $armaModel->atualizar(
        $idArma,
        $nome,
        $tipo,
        $maos,
        $dano,
        $especial,
        $idAutor
    );

    if ($resultado) {
        header(
            "Location: ArmaController.php"
            . "?acao=listar&sucesso=atualizada"
        );
        exit;
    }

    header(
        "Location: ArmaController.php"
        . "?acao=editar&id={$idArma}&erro=atualizacao"
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
    $idArma = (int) ($_POST["id_arma"] ?? 0);

    if ($idArma <= 0) {
        header(
            "Location: ArmaController.php"
            . "?acao=listar&erro=dados"
        );
        exit;
    }

    try {
        $resultado = $armaModel->excluir(
            $idArma,
            $idAutor
        );

        if ($resultado) {
            header(
                "Location: ArmaController.php"
                . "?acao=listar&sucesso=excluida"
            );
            exit;
        }

        header(
            "Location: ArmaController.php"
            . "?acao=listar&erro=arma_nao_encontrada"
        );
        exit;
    } catch (PDOException $erro) {
        if ($erro->getCode() === "23000") {
            header(
                "Location: ArmaController.php"
                . "?acao=listar&erro=arma_vinculada"
            );
            exit;
        }

        header(
            "Location: ArmaController.php"
            . "?acao=listar&erro=exclusao"
        );
        exit;
    }
}

header("Location: /Views/Arma/cadastro.php");
exit;