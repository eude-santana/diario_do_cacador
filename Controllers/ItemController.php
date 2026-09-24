<?php

require_once __DIR__ . "/../Config/Autenticacao.php";
require_once __DIR__ . "/../Models/Item.php";

exigirLogin();

$itemModel = new Item();

$acao = $_POST["acao"] ?? $_GET["acao"] ?? "";

$tiposPermitidos = [
    "CONSUMIVEL",
    "MATERIAL",
    "UTILITARIO",
    "OUTRO"
];

if ($acao === "listar") {
    $itens = $itemModel->listarPorAutor(
        $_SESSION["id_usuario"]
    );

    require_once __DIR__ . "/../Views/Item/listagem.php";
    exit;
}

if ($acao === "cadastrar" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome"] ?? "";
    $tipo = $_POST["tipo"] ?? "";
    $descricao = $_POST["descricao"] ?? "";

    if (!is_string($nome) || !is_string($descricao)) {
        header("Location: ../Views/Item/cadastro.php?erro=cadastro");
        exit;
    }

    $nome = trim($nome);
    $descricao = trim($descricao);

    if ($nome === "") {
        header("Location: ../Views/Item/cadastro.php?erro=nome");
        exit;
    }

    if (strlen($nome) > 100) {
        header("Location: ../Views/Item/cadastro.php?erro=nome_longo");
        exit;
    }


    if (!in_array($tipo, $tiposPermitidos, true)) {
        header("Location: ../Views/Item/cadastro.php?erro=tipo");
        exit;
    }

    try {
        $resultado = $itemModel->cadastrar(
            $nome,
            $tipo,
            $descricao,
            $_SESSION["id_usuario"]
        );

        if ($resultado) {
            header("Location: ../Views/Item/cadastro.php?sucesso=1");
            exit;
        }

    } catch (PDOException $erro) {
        error_log($erro->getMessage());
    }

    header("Location: ../Views/Item/cadastro.php?erro=cadastro");
    exit;

}

if ($acao === "editar" && $_SERVER["REQUEST_METHOD"] === "GET") {
    $idItem = (int) ($_GET["id"] ?? 0);

    $item = $itemModel->buscarPorId(
        $idItem,
        $_SESSION["id_usuario"]
    );

    if (!$item) {
        header(
            "Location: ItemController.php?acao=listar&erro=item_nao_encontrado"
        );
        exit;
    }

    require_once __DIR__ . "/../Views/Item/editar.php";
    exit;
}

if ($acao === "atualizar" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $idItem = (int) ($_POST["id_item"] ?? 0);
    $nome = $_POST["nome"] ?? "";
    $tipo = $_POST["tipo"] ?? "";
    $descricao = $_POST["descricao"] ?? "";

    if (!is_string($nome) || !is_string($descricao)) {
        header("Location: ItemController.php?acao=listar&erro=dados");
        exit;
    }

    $nome = trim($nome);
    $descricao = trim($descricao);

    if ($idItem <= 0 || $nome === "") {
        header(
            "Location: ItemController.php?acao=listar&erro=dados"
        );
        exit;
    }

    if (strlen($nome) > 100) {
        header(
            "Location: ItemController.php?acao=editar&id="
            . $idItem
            . "&erro=nome_longo"
        );
        exit;
    }

    try {
        $item = $itemModel->buscarPorId(
            $idItem,
            $_SESSION["id_usuario"]
        );

        if (!$item) {
            header(
                "Location: ItemController.php?acao=listar&erro=item_nao_encontrado"
            );
            exit;
        }

        if (!in_array($tipo, $tiposPermitidos, true)) {
            header("Location: ItemController.php?acao=editar&id=" . $idItem . "&erro=tipo");
            exit;
        }

        $resultado = $itemModel->atualizar(
            $idItem,
            $nome,
            $tipo,
            $descricao,
            $_SESSION["id_usuario"]
        );

        if ($resultado) {
            header(
                "Location: ItemController.php?acao=listar&sucesso=atualizado"
            );
            exit;
        }

    } catch (PDOException $erro) {
        error_log($erro->getMessage());
    }

    header("Location: ItemController.php?acao=editar&id=" . $idItem . "&erro=atualizacao");
    exit;
}

if ($acao === "excluir" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $idItem = (int) ($_POST["id_item"] ?? 0);

    if ($idItem <= 0) {
        header(
            "Location: ItemController.php?acao=listar&erro=dados"
        );
        exit;
    }

    try {
        $resultado = $itemModel->excluir(
            $idItem,
            $_SESSION["id_usuario"]
        );

        if ($resultado) {
            header(
                "Location: ItemController.php?acao=listar&sucesso=excluido"
            );
            exit;
        }

        header(
            "Location: ItemController.php?acao=listar&erro=item_nao_encontrado"
        );
        exit;
    } catch (PDOException $erro) {
        if ($erro->getCode() === "23000") {
            header(
                "Location: ItemController.php?acao=listar&erro=item_vinculado"
            );
            exit;
        }

        header(
            "Location: ItemController.php?acao=listar&erro=exclusao"
        );
        exit;
    }
}

header("Location: ../Views/Item/cadastro.php");
exit;