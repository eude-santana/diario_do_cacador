<?php

require_once __DIR__ . "/../Config/Autenticacao.php";
require_once __DIR__ . "/../Models/Vestimenta.php";

exigirLogin();

$vestimentaModel = new Vestimenta();

$acao = $_POST["acao"] ?? $_GET["acao"] ?? "";
$idAutor = $_SESSION["id_usuario"];

$tiposPermitidos = [
    "ARMADURA",
    "ELMO",
    "BRACELETES",
    "BOTAS",
    "ESCUDO"
];

/*
 * LISTAGEM
 */
if ($acao === "listar") {
    $vestimentas = $vestimentaModel->listarPorAutor(
        $idAutor
    );

    require_once __DIR__
        . "/../Views/Vestimenta/listagem.php";

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

    $pontosProtecaoMaximo = (int) (
        $_POST["pontos_protecao_maximo"] ?? 0
    );

    $dano = trim($_POST["dano"] ?? "");
    $elemento = trim($_POST["elemento"] ?? "");
    $especial = trim($_POST["especial"] ?? "");

    if ($nome === "" || $pontosProtecaoMaximo <= 0) {
        header(
            "Location: /Views/Vestimenta/cadastro.php"
            . "?erro=campos"
        );
        exit;
    }

    if (strlen($nome) > 100) {
        header(
            "Location: /Views/Vestimenta/cadastro.php"
            . "?erro=nome_longo"
        );
        exit;
    }

    if (!in_array($tipo, $tiposPermitidos, true)) {
        header(
            "Location: /Views/Vestimenta/cadastro.php"
            . "?erro=tipo"
        );
        exit;
    }

    if (strlen($dano) > 50) {
        header(
            "Location: /Views/Vestimenta/cadastro.php"
            . "?erro=dano_longo"
        );
        exit;
    }

    if (strlen($elemento) > 50) {
        header(
            "Location: /Views/Vestimenta/cadastro.php"
            . "?erro=elemento_longo"
        );
        exit;
    }

    $resultado = $vestimentaModel->cadastrar(
        $nome,
        $tipo,
        $pontosProtecaoMaximo,
        $dano,
        $elemento,
        $especial,
        $idAutor
    );

    if ($resultado) {
        header(
            "Location: /Views/Vestimenta/cadastro.php"
            . "?sucesso=1"
        );
        exit;
    }

    header(
        "Location: /Views/Vestimenta/cadastro.php"
        . "?erro=cadastro"
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
    $idVestimenta = (int) ($_GET["id"] ?? 0);

    $vestimenta = $vestimentaModel->buscarPorId(
        $idVestimenta,
        $idAutor
    );

    if (!$vestimenta) {
        header(
            "Location: VestimentaController.php"
            . "?acao=listar&erro=vestimenta_nao_encontrada"
        );
        exit;
    }

    require_once __DIR__
        . "/../Views/Vestimenta/editar.php";

    exit;
}

/*
 * ATUALIZAÇÃO
 */
if (
    $acao === "atualizar"
    && $_SERVER["REQUEST_METHOD"] === "POST"
) {
    $idVestimenta = (int) (
        $_POST["id_vestimenta"] ?? 0
    );

    $nome = trim($_POST["nome"] ?? "");
    $tipo = $_POST["tipo"] ?? "";

    $pontosProtecaoMaximo = (int) (
        $_POST["pontos_protecao_maximo"] ?? 0
    );

    $dano = trim($_POST["dano"] ?? "");
    $elemento = trim($_POST["elemento"] ?? "");
    $especial = trim($_POST["especial"] ?? "");

    if (
        $idVestimenta <= 0
        || $nome === ""
        || $pontosProtecaoMaximo <= 0
    ) {
        header(
            "Location: VestimentaController.php"
            . "?acao=listar&erro=dados"
        );
        exit;
    }

    $vestimentaExistente = $vestimentaModel->buscarPorId(
        $idVestimenta,
        $idAutor
    );

    if (!$vestimentaExistente) {
        header(
            "Location: VestimentaController.php"
            . "?acao=listar&erro=vestimenta_nao_encontrada"
        );
        exit;
    }

    if (strlen($nome) > 100) {
        header(
            "Location: VestimentaController.php"
            . "?acao=editar&id={$idVestimenta}"
            . "&erro=nome_longo"
        );
        exit;
    }

    if (!in_array($tipo, $tiposPermitidos, true)) {
        header(
            "Location: VestimentaController.php"
            . "?acao=editar&id={$idVestimenta}"
            . "&erro=tipo"
        );
        exit;
    }

    if (strlen($dano) > 50) {
        header(
            "Location: VestimentaController.php"
            . "?acao=editar&id={$idVestimenta}"
            . "&erro=dano_longo"
        );
        exit;
    }

    if (strlen($elemento) > 50) {
        header(
            "Location: VestimentaController.php"
            . "?acao=editar&id={$idVestimenta}"
            . "&erro=elemento_longo"
        );
        exit;
    }

    $resultado = $vestimentaModel->atualizar(
        $idVestimenta,
        $nome,
        $tipo,
        $pontosProtecaoMaximo,
        $dano,
        $elemento,
        $especial,
        $idAutor
    );

    if ($resultado) {
        header(
            "Location: VestimentaController.php"
            . "?acao=listar&sucesso=atualizada"
        );
        exit;
    }

    header(
        "Location: VestimentaController.php"
        . "?acao=editar&id={$idVestimenta}"
        . "&erro=atualizacao"
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
    $idVestimenta = (int) (
        $_POST["id_vestimenta"] ?? 0
    );

    if ($idVestimenta <= 0) {
        header(
            "Location: VestimentaController.php"
            . "?acao=listar&erro=dados"
        );
        exit;
    }

    try {
        $resultado = $vestimentaModel->excluir(
            $idVestimenta,
            $idAutor
        );

        if ($resultado) {
            header(
                "Location: VestimentaController.php"
                . "?acao=listar&sucesso=excluida"
            );
            exit;
        }

        header(
            "Location: VestimentaController.php"
            . "?acao=listar&erro=vestimenta_nao_encontrada"
        );
        exit;
    } catch (PDOException $erro) {
        if ($erro->getCode() === "23000") {
            header(
                "Location: VestimentaController.php"
                . "?acao=listar&erro=vestimenta_vinculada"
            );
            exit;
        }

        header(
            "Location: VestimentaController.php"
            . "?acao=listar&erro=exclusao"
        );
        exit;
    }
}

header("Location: /Views/Vestimenta/cadastro.php");
exit;