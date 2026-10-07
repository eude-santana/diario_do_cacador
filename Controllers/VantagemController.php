<?php

require_once __DIR__ . "/../Config/Autenticacao.php";
require_once __DIR__ . "/../Models/Vantagem.php";
require_once __DIR__ . "/../Models/Magia.php";
require_once __DIR__ . "/../Models/CompanheiroAnimal.php";

exigirLogin();

$vantagemModel = new Vantagem();
$magiaModel = new Magia();
$companheiroModel = new CompanheiroAnimal();

$idAutor = (int) $_SESSION["id_usuario"];
$acao = $_POST["acao"] ?? $_GET["acao"] ?? "listar";

$tiposPermitidos = [
    "NORMAL",
    "MAGIA",
    "COMPANHEIRO"
];

/**
 * Converte os valores recebidos pelo formulário em uma lista
 * de identificadores inteiros, positivos e sem duplicações.
 */
function normalizarIds($ids): array
{
    if (!is_array($ids)) {
        return [];
    }

    $idsNormalizados = [];

    foreach ($ids as $id) {
        $id = (int) $id;

        if (
            $id > 0
            && !in_array($id, $idsNormalizados, true)
        ) {
            $idsNormalizados[] = $id;
        }
    }

    return $idsNormalizados;
}

/**
 * Verifica se todas as magias selecionadas pertencem
 * ao usuário autenticado.
 */
function validarMagias(
    array $idsMagias,
    int $idAutor,
    Magia $magiaModel
): bool {
    foreach ($idsMagias as $idMagia) {
        if (!$magiaModel->buscarPorId($idMagia, $idAutor)) {
            return false;
        }
    }

    return true;
}

/**
 * Verifica se todos os companheiros selecionados pertencem
 * ao usuário autenticado.
 */
function validarCompanheiros(
    array $idsCompanheiros,
    int $idAutor,
    CompanheiroAnimal $companheiroModel
): bool {
    foreach ($idsCompanheiros as $idCompanheiro) {
        if (
            !$companheiroModel->buscarPorId(
                $idCompanheiro,
                $idAutor
            )
        ) {
            return false;
        }
    }

    return true;
}

/**
 * Carrega os dados necessários para os formulários
 * de cadastro e edição.
 */
function carregarOpcoes(
    int $idAutor,
    Magia $magiaModel,
    CompanheiroAnimal $companheiroModel
): array {
    return [
        "magias" => $magiaModel->listarPorAutor($idAutor),
        "companheiros" => $companheiroModel->listarPorAutor($idAutor)
    ];
}

switch ($acao) {
    case "novo":
        $opcoes = carregarOpcoes(
            $idAutor,
            $magiaModel,
            $companheiroModel
        );

        $magias = $opcoes["magias"];
        $companheiros = $opcoes["companheiros"];

        $nome = "";
        $descricao = "";
        $tipo = "NORMAL";
        $idsMagiasSelecionadas = [];
        $idsCompanheirosSelecionados = [];
        $erro = "";

        require __DIR__ . "/../Views/Vantagem/cadastro.php";
        break;

    case "cadastrar":
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header(
                "Location: /Controllers/VantagemController.php"
                . "?acao=novo"
            );
            exit;
        }

        $nome = trim($_POST["nome"] ?? "");
        $descricao = trim($_POST["descricao"] ?? "");
        $tipo = strtoupper(trim($_POST["tipo"] ?? "NORMAL"));

        $idsMagiasSelecionadas = normalizarIds(
            $_POST["ids_magias"] ?? []
        );

        $idsCompanheirosSelecionados = normalizarIds(
            $_POST["ids_companheiros"] ?? []
        );

        $erro = "";
        $idsRelacionados = [];

        if ($nome === "") {
            $erro = "Informe o nome da vantagem.";
        } elseif (strlen($nome) > 100) {
            $erro = "O nome deve possuir no máximo 100 caracteres.";
        } elseif (!in_array($tipo, $tiposPermitidos, true)) {
            $erro = "O tipo de vantagem informado é inválido.";
        } elseif ($tipo === "MAGIA") {
            if (empty($idsMagiasSelecionadas)) {
                $erro = "Selecione pelo menos uma magia.";
            } elseif (
                !validarMagias(
                    $idsMagiasSelecionadas,
                    $idAutor,
                    $magiaModel
                )
            ) {
                $erro = "Uma das magias selecionadas é inválida.";
            } else {
                $idsRelacionados = $idsMagiasSelecionadas;
            }
        } elseif ($tipo === "COMPANHEIRO") {
            if (empty($idsCompanheirosSelecionados)) {
                $erro = "Selecione pelo menos um companheiro animal.";
            } elseif (
                !validarCompanheiros(
                    $idsCompanheirosSelecionados,
                    $idAutor,
                    $companheiroModel
                )
            ) {
                $erro = "Um dos companheiros selecionados é inválido.";
            } else {
                $idsRelacionados = $idsCompanheirosSelecionados;
            }
        }

        if ($erro === "") {
            try {
                $vantagemModel->cadastrar(
                    $nome,
                    $descricao,
                    $tipo,
                    $idsRelacionados,
                    $idAutor
                );

                header(
                    "Location: /Controllers/VantagemController.php"
                    . "?acao=listar&sucesso=cadastro"
                );
                exit;
            } catch (PDOException $erroInterno) {
                $erro = "Não foi possível cadastrar a vantagem.";
            }
        }

        $opcoes = carregarOpcoes(
            $idAutor,
            $magiaModel,
            $companheiroModel
        );

        $magias = $opcoes["magias"];
        $companheiros = $opcoes["companheiros"];

        require __DIR__ . "/../Views/Vantagem/cadastro.php";
        break;

    case "editar":
        $idVantagem = filter_input(
            INPUT_GET,
            "id",
            FILTER_VALIDATE_INT
        );

        if (!$idVantagem || $idVantagem <= 0) {
            header(
                "Location: /Controllers/VantagemController.php"
                . "?acao=listar&erro=id_invalido"
            );
            exit;
        }

        $vantagem = $vantagemModel->buscarPorId(
            $idVantagem,
            $idAutor
        );

        if (!$vantagem) {
            header(
                "Location: /Controllers/VantagemController.php"
                . "?acao=listar&erro=nao_encontrada"
            );
            exit;
        }

        $idsMagiasSelecionadas =
            $vantagemModel->buscarIdsMagias($idVantagem);

        $idsCompanheirosSelecionados =
            $vantagemModel->buscarIdsCompanheiros($idVantagem);

        if (!empty($idsMagiasSelecionadas)) {
            $tipo = "MAGIA";
        } elseif (!empty($idsCompanheirosSelecionados)) {
            $tipo = "COMPANHEIRO";
        } else {
            $tipo = "NORMAL";
        }

        $opcoes = carregarOpcoes(
            $idAutor,
            $magiaModel,
            $companheiroModel
        );

        $magias = $opcoes["magias"];
        $companheiros = $opcoes["companheiros"];
        $erro = "";

        require __DIR__ . "/../Views/Vantagem/editar.php";
        break;

    case "atualizar":
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header(
                "Location: /Controllers/VantagemController.php"
                . "?acao=listar"
            );
            exit;
        }

        $idVantagem = filter_input(
            INPUT_POST,
            "id_vantagem",
            FILTER_VALIDATE_INT
        );

        $nome = trim($_POST["nome"] ?? "");
        $descricao = trim($_POST["descricao"] ?? "");
        $tipo = strtoupper(trim($_POST["tipo"] ?? "NORMAL"));

        $idsMagiasSelecionadas = normalizarIds(
            $_POST["ids_magias"] ?? []
        );

        $idsCompanheirosSelecionados = normalizarIds(
            $_POST["ids_companheiros"] ?? []
        );

        $erro = "";
        $idsRelacionados = [];

        if (!$idVantagem || $idVantagem <= 0) {
            $erro = "A vantagem informada é inválida.";
        } elseif (
            !$vantagemModel->buscarPorId($idVantagem, $idAutor)
        ) {
            $erro = "Vantagem não encontrada.";
        } elseif ($nome === "") {
            $erro = "Informe o nome da vantagem.";
        } elseif (strlen($nome) > 100) {
            $erro = "O nome deve possuir no máximo 100 caracteres.";
        } elseif (!in_array($tipo, $tiposPermitidos, true)) {
            $erro = "O tipo de vantagem informado é inválido.";
        } elseif ($tipo === "MAGIA") {
            if (empty($idsMagiasSelecionadas)) {
                $erro = "Selecione pelo menos uma magia.";
            } elseif (
                !validarMagias(
                    $idsMagiasSelecionadas,
                    $idAutor,
                    $magiaModel
                )
            ) {
                $erro = "Uma das magias selecionadas é inválida.";
            } else {
                $idsRelacionados = $idsMagiasSelecionadas;
            }
        } elseif ($tipo === "COMPANHEIRO") {
            if (empty($idsCompanheirosSelecionados)) {
                $erro = "Selecione pelo menos um companheiro animal.";
            } elseif (
                !validarCompanheiros(
                    $idsCompanheirosSelecionados,
                    $idAutor,
                    $companheiroModel
                )
            ) {
                $erro = "Um dos companheiros selecionados é inválido.";
            } else {
                $idsRelacionados = $idsCompanheirosSelecionados;
            }
        }

        if ($erro === "") {
            try {
                $vantagemModel->atualizar(
                    $idVantagem,
                    $nome,
                    $descricao,
                    $tipo,
                    $idsRelacionados,
                    $idAutor
                );

                header(
                    "Location: /Controllers/VantagemController.php"
                    . "?acao=listar&sucesso=edicao"
                );
                exit;
            } catch (PDOException $erroInterno) {
                $erro = "Não foi possível atualizar a vantagem.";
            }
        }

        $vantagem = [
            "id_vantagem" => $idVantagem,
            "nome" => $nome,
            "descricao" => $descricao
        ];

        $opcoes = carregarOpcoes(
            $idAutor,
            $magiaModel,
            $companheiroModel
        );

        $magias = $opcoes["magias"];
        $companheiros = $opcoes["companheiros"];

        require __DIR__ . "/../Views/Vantagem/editar.php";
        break;

    case "excluir":
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header(
                "Location: /Controllers/VantagemController.php"
                . "?acao=listar"
            );
            exit;
        }

        $idVantagem = filter_input(
            INPUT_POST,
            "id_vantagem",
            FILTER_VALIDATE_INT
        );

        if (!$idVantagem || $idVantagem <= 0) {
            header(
                "Location: /Controllers/VantagemController.php"
                . "?acao=listar&erro=id_invalido"
            );
            exit;
        }

        try {
            $excluiu = $vantagemModel->excluir(
                $idVantagem,
                $idAutor
            );

            if (!$excluiu) {
                header(
                    "Location: /Controllers/VantagemController.php"
                    . "?acao=listar&erro=nao_encontrada"
                );
                exit;
            }

            header(
                "Location: /Controllers/VantagemController.php"
                . "?acao=listar&sucesso=exclusao"
            );
            exit;
        } catch (PDOException $erroInterno) {
            if ($erroInterno->getCode() === "23000") {
                header(
                    "Location: /Controllers/VantagemController.php"
                    . "?acao=listar&erro=em_uso"
                );
                exit;
            }

            header(
                "Location: /Controllers/VantagemController.php"
                . "?acao=listar&erro=exclusao"
            );
            exit;
        }

    case "listar":
    default:
        $vantagens = $vantagemModel->listarPorAutor($idAutor);

        require __DIR__ . "/../Views/Vantagem/listagem.php";
        break;
}
