<?php

require_once __DIR__ . "/../Config/Autenticacao.php";
require_once __DIR__ . "/../Models/Profissao.php";
require_once __DIR__ . "/../Models/Vantagem.php";
require_once __DIR__ . "/../Models/Arma.php";
require_once __DIR__ . "/../Models/Vestimenta.php";
require_once __DIR__ . "/../Models/Item.php";

exigirLogin();

$profissaoModel = new Profissao();
$vantagemModel = new Vantagem();
$armaModel = new Arma();
$vestimentaModel = new Vestimenta();
$itemModel = new Item();

$idAutor = (int) $_SESSION["id_usuario"];

$acao = $_POST["acao"] ?? $_GET["acao"] ?? "listar";

/**
 * Carrega os registros que poderão ser relacionados
 * com uma profissão.
 */
function carregarOpcoesProfissao(
    int $idAutor,
    Vantagem $vantagemModel,
    Arma $armaModel,
    Vestimenta $vestimentaModel,
    Item $itemModel
): array {
    return [
        "vantagens" => $vantagemModel->listarPorAutor($idAutor),
        "armas" => $armaModel->listarPorAutor($idAutor),
        "vestimentas" => $vestimentaModel->listarPorAutor($idAutor),
        "itens" => $itemModel->listarPorAutor($idAutor)
    ];
}

/**
 * Organiza os dados recebidos do formulário.
 */
function lerDadosProfissao(): array
{
    $vantagensRecebidas = $_POST["vantagens"] ?? [];
    $armasRecebidas = $_POST["armas"] ?? [];
    $vestimentasRecebidas = $_POST["vestimentas"] ?? [];
    $itensRecebidos = $_POST["itens"] ?? [];

    if (!is_array($vantagensRecebidas)) {
        $vantagensRecebidas = [];
    }

    if (!is_array($armasRecebidas)) {
        $armasRecebidas = [];
    }

    if (!is_array($vestimentasRecebidas)) {
        $vestimentasRecebidas = [];
    }

    if (!is_array($itensRecebidos)) {
        $itensRecebidos = [];
    }

    $vantagens = [
        1 => (int) ($vantagensRecebidas[1] ?? 0),
        2 => (int) ($vantagensRecebidas[2] ?? 0)
    ];

    $armas = [
        1 => (int) ($armasRecebidas[1] ?? 0),
        2 => (int) ($armasRecebidas[2] ?? 0),
        3 => (int) ($armasRecebidas[3] ?? 0)
    ];

    $vestimentas = [
        "ARMADURA" => (int) (
            $vestimentasRecebidas["ARMADURA"] ?? 0
        ),
        "ELMO" => (int) (
            $vestimentasRecebidas["ELMO"] ?? 0
        ),
        "BRACELETES" => (int) (
            $vestimentasRecebidas["BRACELETES"] ?? 0
        ),
        "BOTAS" => (int) (
            $vestimentasRecebidas["BOTAS"] ?? 0
        ),
        "ESCUDO" => (int) (
            $vestimentasRecebidas["ESCUDO"] ?? 0
        )
    ];

    $itens = [];

    foreach ($itensRecebidos as $idItem => $quantidade) {
        $idItem = (int) $idItem;

        if ($idItem > 0) {
            $itens[$idItem] = (int) $quantidade;
        }
    }

    return [
        "nome" => trim($_POST["nome"] ?? ""),
        "descricao" => trim($_POST["descricao"] ?? ""),
        "pv_maximo" => (int) ($_POST["pv_maximo"] ?? 0),
        "visibilidade" => strtoupper(
            trim($_POST["visibilidade"] ?? "PRIVADO")
        ),
        "vantagens" => $vantagens,
        "armas" => $armas,
        "vestimentas" => $vestimentas,
        "itens" => $itens
    ];
}

/**
 * Confere os dados e verifica se todos os registros
 * selecionados pertencem ao usuário autenticado.
 */
function validarDadosProfissao(
    array $dados,
    int $idAutor,
    Vantagem $vantagemModel,
    Arma $armaModel,
    Vestimenta $vestimentaModel,
    Item $itemModel
): string {
    if ($dados["nome"] === "") {
        return "Informe o nome da profissão.";
    }

    if (strlen($dados["nome"]) > 100) {
        return "O nome deve possuir no máximo 100 caracteres.";
    }

    if ($dados["pv_maximo"] <= 0) {
        return "O PV máximo deve ser maior que zero.";
    }

    $visibilidadesPermitidas = [
        "PUBLICO",
        "PRIVADO"
    ];

    if (
        !in_array(
            $dados["visibilidade"],
            $visibilidadesPermitidas,
            true
        )
    ) {
        return "A visibilidade informada é inválida.";
    }

    $vantagem1 = $dados["vantagens"][1];
    $vantagem2 = $dados["vantagens"][2];

    if ($vantagem1 <= 0 || $vantagem2 <= 0) {
        return "Selecione as duas vantagens da profissão.";
    }

    if ($vantagem1 === $vantagem2) {
        return "As duas vantagens devem ser diferentes.";
    }

    foreach ($dados["vantagens"] as $idVantagem) {
        if (
            !$vantagemModel->buscarPorId(
                $idVantagem,
                $idAutor
            )
        ) {
            return "Uma das vantagens selecionadas é inválida.";
        }
    }

    foreach ($dados["armas"] as $idArma) {
        if ($idArma <= 0) {
            continue;
        }

        if (!$armaModel->buscarPorId($idArma, $idAutor)) {
            return "Uma das armas selecionadas é inválida.";
        }
    }

    foreach (
        $dados["vestimentas"] as
        $tipoSlot => $idVestimenta
    ) {
        if ($idVestimenta <= 0) {
            continue;
        }

        $vestimenta = $vestimentaModel->buscarPorId(
            $idVestimenta,
            $idAutor
        );

        if (!$vestimenta) {
            return "Uma das vestimentas selecionadas é inválida.";
        }

        if ($vestimenta["tipo"] !== $tipoSlot) {
            return "Uma vestimenta foi colocada em um slot incompatível.";
        }
    }

    foreach ($dados["itens"] as $idItem => $quantidade) {
        if ($quantidade < 0) {
            return "A quantidade dos itens não pode ser negativa.";
        }

        if ($quantidade === 0) {
            continue;
        }

        if (!$itemModel->buscarPorId($idItem, $idAutor)) {
            return "Um dos itens selecionados é inválido.";
        }
    }

    return "";
}

switch ($acao) {
    case "novo":
        $profissao = [
            "nome" => "",
            "descricao" => "",
            "pv_maximo" => 1,
            "visibilidade" => "PRIVADO"
        ];

        $vantagensSelecionadas = [
            1 => 0,
            2 => 0
        ];

        $armasSelecionadas = [
            1 => 0,
            2 => 0,
            3 => 0
        ];

        $vestimentasSelecionadas = [
            "ARMADURA" => 0,
            "ELMO" => 0,
            "BRACELETES" => 0,
            "BOTAS" => 0,
            "ESCUDO" => 0
        ];

        $itensSelecionados = [];
        $erro = "";

        $opcoes = carregarOpcoesProfissao(
            $idAutor,
            $vantagemModel,
            $armaModel,
            $vestimentaModel,
            $itemModel
        );

        $vantagensDisponiveis = $opcoes["vantagens"];
        $armasDisponiveis = $opcoes["armas"];
        $vestimentasDisponiveis = $opcoes["vestimentas"];
        $itensDisponiveis = $opcoes["itens"];

        require __DIR__ . "/../Views/Profissao/cadastro.php";
        break;

    case "cadastrar":
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header(
                "Location: /Controllers/ProfissaoController.php"
                . "?acao=novo"
            );
            exit;
        }

        $dados = lerDadosProfissao();

        $erro = validarDadosProfissao(
            $dados,
            $idAutor,
            $vantagemModel,
            $armaModel,
            $vestimentaModel,
            $itemModel
        );

        if ($erro === "") {
            try {
                $profissaoModel->cadastrar(
                    $dados["nome"],
                    $dados["descricao"],
                    $dados["pv_maximo"],
                    $dados["visibilidade"],
                    $idAutor,
                    $dados["vantagens"],
                    $dados["armas"],
                    $dados["vestimentas"],
                    $dados["itens"]
                );

                header(
                    "Location: /Controllers/ProfissaoController.php"
                    . "?acao=listar&sucesso=cadastro"
                );
                exit;
            } catch (PDOException $erroInterno) {
                $erro = "Não foi possível cadastrar a profissão.";
            }
        }

        $profissao = [
            "nome" => $dados["nome"],
            "descricao" => $dados["descricao"],
            "pv_maximo" => $dados["pv_maximo"],
            "visibilidade" => $dados["visibilidade"]
        ];

        $vantagensSelecionadas = $dados["vantagens"];
        $armasSelecionadas = $dados["armas"];
        $vestimentasSelecionadas = $dados["vestimentas"];
        $itensSelecionados = $dados["itens"];

        $opcoes = carregarOpcoesProfissao(
            $idAutor,
            $vantagemModel,
            $armaModel,
            $vestimentaModel,
            $itemModel
        );

        $vantagensDisponiveis = $opcoes["vantagens"];
        $armasDisponiveis = $opcoes["armas"];
        $vestimentasDisponiveis = $opcoes["vestimentas"];
        $itensDisponiveis = $opcoes["itens"];

        require __DIR__ . "/../Views/Profissao/cadastro.php";
        break;

    case "editar":
        $idProfissao = filter_input(
            INPUT_GET,
            "id",
            FILTER_VALIDATE_INT
        );

        if (!$idProfissao || $idProfissao <= 0) {
            header(
                "Location: /Controllers/ProfissaoController.php"
                . "?acao=listar&erro=id_invalido"
            );
            exit;
        }

        $profissao = $profissaoModel->buscarPorId(
            $idProfissao,
            $idAutor
        );

        if (!$profissao) {
            header(
                "Location: /Controllers/ProfissaoController.php"
                . "?acao=listar&erro=nao_encontrada"
            );
            exit;
        }

        $vantagensSelecionadas =
            $profissaoModel->buscarVantagens(
                $idProfissao,
                $idAutor
            );

        $armasSelecionadas =
            $profissaoModel->buscarArmas(
                $idProfissao,
                $idAutor
            );

        $vestimentasSelecionadas =
            $profissaoModel->buscarVestimentas(
                $idProfissao,
                $idAutor
            );

        $itensSelecionados =
            $profissaoModel->buscarItens(
                $idProfissao,
                $idAutor
            );

        $vantagensSelecionadas = array_map(
            "intval",
            $vantagensSelecionadas
        );

        $armasSelecionadas = array_map(
            "intval",
            $armasSelecionadas
        );

        $vestimentasSelecionadas = array_map(
            "intval",
            $vestimentasSelecionadas
        );

        $itensSelecionados = array_map(
            "intval",
            $itensSelecionados
        );

        $opcoes = carregarOpcoesProfissao(
            $idAutor,
            $vantagemModel,
            $armaModel,
            $vestimentaModel,
            $itemModel
        );

        $vantagensDisponiveis = $opcoes["vantagens"];
        $armasDisponiveis = $opcoes["armas"];
        $vestimentasDisponiveis = $opcoes["vestimentas"];
        $itensDisponiveis = $opcoes["itens"];

        $erro = "";

        require __DIR__ . "/../Views/Profissao/editar.php";
        break;

    case "atualizar":
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header(
                "Location: /Controllers/ProfissaoController.php"
                . "?acao=listar"
            );
            exit;
        }

        $idProfissao = filter_input(
            INPUT_POST,
            "id_profissao",
            FILTER_VALIDATE_INT
        );

        if (!$idProfissao || $idProfissao <= 0) {
            header(
                "Location: /Controllers/ProfissaoController.php"
                . "?acao=listar&erro=id_invalido"
            );
            exit;
        }

        if (
            !$profissaoModel->buscarPorId(
                $idProfissao,
                $idAutor
            )
        ) {
            header(
                "Location: /Controllers/ProfissaoController.php"
                . "?acao=listar&erro=nao_encontrada"
            );
            exit;
        }

        $dados = lerDadosProfissao();

        $erro = validarDadosProfissao(
            $dados,
            $idAutor,
            $vantagemModel,
            $armaModel,
            $vestimentaModel,
            $itemModel
        );

        if ($erro === "") {
            try {
                $atualizou = $profissaoModel->atualizar(
                    $idProfissao,
                    $dados["nome"],
                    $dados["descricao"],
                    $dados["pv_maximo"],
                    $dados["visibilidade"],
                    $idAutor,
                    $dados["vantagens"],
                    $dados["armas"],
                    $dados["vestimentas"],
                    $dados["itens"]
                );

                if ($atualizou) {
                    header(
                        "Location: /Controllers/ProfissaoController.php"
                        . "?acao=listar&sucesso=edicao"
                    );
                    exit;
                }

                $erro = "Profissão não encontrada.";
            } catch (PDOException $erroInterno) {
                $erro = "Não foi possível atualizar a profissão.";
            }
        }

        $profissao = [
            "id_profissao" => $idProfissao,
            "nome" => $dados["nome"],
            "descricao" => $dados["descricao"],
            "pv_maximo" => $dados["pv_maximo"],
            "visibilidade" => $dados["visibilidade"]
        ];

        $vantagensSelecionadas = $dados["vantagens"];
        $armasSelecionadas = $dados["armas"];
        $vestimentasSelecionadas = $dados["vestimentas"];
        $itensSelecionados = $dados["itens"];

        $opcoes = carregarOpcoesProfissao(
            $idAutor,
            $vantagemModel,
            $armaModel,
            $vestimentaModel,
            $itemModel
        );

        $vantagensDisponiveis = $opcoes["vantagens"];
        $armasDisponiveis = $opcoes["armas"];
        $vestimentasDisponiveis = $opcoes["vestimentas"];
        $itensDisponiveis = $opcoes["itens"];

        require __DIR__ . "/../Views/Profissao/editar.php";
        break;

    case "excluir":
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header(
                "Location: /Controllers/ProfissaoController.php"
                . "?acao=listar"
            );
            exit;
        }

        $idProfissao = filter_input(
            INPUT_POST,
            "id_profissao",
            FILTER_VALIDATE_INT
        );

        if (!$idProfissao || $idProfissao <= 0) {
            header(
                "Location: /Controllers/ProfissaoController.php"
                . "?acao=listar&erro=id_invalido"
            );
            exit;
        }

        try {
            $excluiu = $profissaoModel->excluir(
                $idProfissao,
                $idAutor
            );

            if (!$excluiu) {
                header(
                    "Location: /Controllers/ProfissaoController.php"
                    . "?acao=listar&erro=nao_encontrada"
                );
                exit;
            }

            header(
                "Location: /Controllers/ProfissaoController.php"
                . "?acao=listar&sucesso=exclusao"
            );
            exit;
        } catch (PDOException $erroInterno) {
            if ($erroInterno->getCode() === "23000") {
                header(
                    "Location: /Controllers/ProfissaoController.php"
                    . "?acao=listar&erro=em_uso"
                );
                exit;
            }

            header(
                "Location: /Controllers/ProfissaoController.php"
                . "?acao=listar&erro=exclusao"
            );
            exit;
        }

    case "listar":
    default:
        $profissoes = $profissaoModel->listarPorAutor(
            $idAutor
        );

        require __DIR__ . "/../Views/Profissao/listagem.php";
        break;
}
