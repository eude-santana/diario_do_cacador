<?php

require_once __DIR__ . "/../Config/Autenticacao.php";
require_once __DIR__ . "/../Models/CompanheiroAnimal.php";

exigirLogin();

$companheiroModel = new CompanheiroAnimal();

$acao = $_POST["acao"] ?? $_GET["acao"] ?? "";
$idAutor = $_SESSION["id_usuario"];

/*
 * LISTAGEM
 */
if ($acao === "listar") {
    $companheiros = $companheiroModel->listarPorAutor(
        $idAutor
    );

    require_once __DIR__
        . "/../Views/CompanheiroAnimal/listagem.php";

    exit;
}

/*
 * CADASTRO
 */
if (
    $acao === "cadastrar"
    && $_SERVER["REQUEST_METHOD"] === "POST"
) {
    $tipo = trim($_POST["tipo"] ?? "");
    $pvMaximo = (int) ($_POST["pv_maximo"] ?? 0);
    $dano = trim($_POST["dano"] ?? "");
    $descricao = trim($_POST["descricao"] ?? "");

    if ($tipo === "" || $pvMaximo <= 0) {
        header(
            "Location: /Views/CompanheiroAnimal/cadastro.php"
            . "?erro=campos"
        );
        exit;
    }

    if (strlen($tipo) > 100) {
        header(
            "Location: /Views/CompanheiroAnimal/cadastro.php"
            . "?erro=tipo_longo"
        );
        exit;
    }

    if (strlen($dano) > 50) {
        header(
            "Location: /Views/CompanheiroAnimal/cadastro.php"
            . "?erro=dano_longo"
        );
        exit;
    }

    $resultado = $companheiroModel->cadastrar(
        $tipo,
        $pvMaximo,
        $dano,
        $descricao,
        $idAutor
    );

    if ($resultado) {
        header(
            "Location: /Views/CompanheiroAnimal/cadastro.php"
            . "?sucesso=1"
        );
        exit;
    }

    header(
        "Location: /Views/CompanheiroAnimal/cadastro.php"
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
    $idCompanheiro = (int) ($_GET["id"] ?? 0);

    $companheiro = $companheiroModel->buscarPorId(
        $idCompanheiro,
        $idAutor
    );

    if (!$companheiro) {
        header(
            "Location: CompanheiroAnimalController.php"
            . "?acao=listar&erro=companheiro_nao_encontrado"
        );
        exit;
    }

    require_once __DIR__
        . "/../Views/CompanheiroAnimal/editar.php";

    exit;
}

/*
 * ATUALIZAÇÃO
 */
if (
    $acao === "atualizar"
    && $_SERVER["REQUEST_METHOD"] === "POST"
) {
    $idCompanheiro = (int) (
        $_POST["id_companheiro"] ?? 0
    );

    $tipo = trim($_POST["tipo"] ?? "");
    $pvMaximo = (int) ($_POST["pv_maximo"] ?? 0);
    $dano = trim($_POST["dano"] ?? "");
    $descricao = trim($_POST["descricao"] ?? "");

    if (
        $idCompanheiro <= 0
        || $tipo === ""
        || $pvMaximo <= 0
    ) {
        header(
            "Location: CompanheiroAnimalController.php"
            . "?acao=listar&erro=dados"
        );
        exit;
    }

    $companheiroExistente = $companheiroModel->buscarPorId(
        $idCompanheiro,
        $idAutor
    );

    if (!$companheiroExistente) {
        header(
            "Location: CompanheiroAnimalController.php"
            . "?acao=listar&erro=companheiro_nao_encontrado"
        );
        exit;
    }

    if (strlen($tipo) > 100) {
        header(
            "Location: CompanheiroAnimalController.php"
            . "?acao=editar&id={$idCompanheiro}"
            . "&erro=tipo_longo"
        );
        exit;
    }

    if (strlen($dano) > 50) {
        header(
            "Location: CompanheiroAnimalController.php"
            . "?acao=editar&id={$idCompanheiro}"
            . "&erro=dano_longo"
        );
        exit;
    }

    $resultado = $companheiroModel->atualizar(
        $idCompanheiro,
        $tipo,
        $pvMaximo,
        $dano,
        $descricao,
        $idAutor
    );

    if ($resultado) {
        header(
            "Location: CompanheiroAnimalController.php"
            . "?acao=listar&sucesso=atualizado"
        );
        exit;
    }

    header(
        "Location: CompanheiroAnimalController.php"
        . "?acao=editar&id={$idCompanheiro}"
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
    $idCompanheiro = (int) (
        $_POST["id_companheiro"] ?? 0
    );

    if ($idCompanheiro <= 0) {
        header(
            "Location: CompanheiroAnimalController.php"
            . "?acao=listar&erro=dados"
        );
        exit;
    }

    try {
        $resultado = $companheiroModel->excluir(
            $idCompanheiro,
            $idAutor
        );

        if ($resultado) {
            header(
                "Location: CompanheiroAnimalController.php"
                . "?acao=listar&sucesso=excluido"
            );
            exit;
        }

        header(
            "Location: CompanheiroAnimalController.php"
            . "?acao=listar&erro=companheiro_nao_encontrado"
        );
        exit;
    } catch (PDOException $erro) {
        if ($erro->getCode() === "23000") {
            header(
                "Location: CompanheiroAnimalController.php"
                . "?acao=listar&erro=companheiro_vinculado"
            );
            exit;
        }

        header(
            "Location: CompanheiroAnimalController.php"
            . "?acao=listar&erro=exclusao"
        );
        exit;
    }
}

header(
    "Location: /Views/CompanheiroAnimal/cadastro.php"
);
exit;