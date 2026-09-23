<?php

require_once __DIR__ . "/../Models/Usuario.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../Views/Usuario/cadastro.php");
    exit;
}

$nome = $_POST["nome"] ?? "";
$email = $_POST["email"] ?? "";
$senha = $_POST["senha"] ?? "";

// Verifica se os dados recebidos são textos.
if (
    !is_string($nome) ||
    !is_string($email) ||
    !is_string($senha)
) {
    header("Location: ../Views/Usuario/cadastro.php?erro=campos");
    exit;
}

$nome = trim($nome);
$email = trim($email);

// Não removemos espaços da senha.
if ($nome === "" || $email === "" || $senha === "") {
    header("Location: ../Views/Usuario/cadastro.php?erro=campos");
    exit;
}

if (strlen($nome) > 100) {
    header("Location: ../Views/Usuario/cadastro.php?erro=nome_invalido");
    exit;
}

if (
    strlen($email) > 255 ||
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {
    header("Location: ../Views/Usuario/cadastro.php?erro=email_invalido");
    exit;
}

if (strlen($senha) < 6) {
    header("Location: ../Views/Usuario/cadastro.php?erro=senha_curta");
    exit;
}

try {
    $usuarioModel = new Usuario();

    $usuarioExistente = $usuarioModel->buscarPorEmail($email);

    if ($usuarioExistente) {
        header(
            "Location: ../Views/Usuario/cadastro.php?erro=email_cadastrado"
        );
        exit;
    }

    $resultado = $usuarioModel->cadastrar(
        $nome,
        $email,
        $senha
    );

    if ($resultado) {
        header("Location: ../Views/Usuario/cadastro.php?sucesso=1");
        exit;
    }

    header("Location: ../Views/Usuario/cadastro.php?erro=cadastro");
    exit;

} catch (PDOException $erro) {
    // 1062 é o código de valor duplicado no MariaDB.
    $codigoBanco = (int) ($erro->errorInfo[1] ?? 0);

    if ($erro->getCode() === "23000" && $codigoBanco === 1062) {
        header(
            "Location: ../Views/Usuario/cadastro.php?erro=email_cadastrado"
        );
        exit;
    }

    // Registra o erro no servidor.
    error_log($erro->getMessage());

    header("Location: ../Views/Usuario/cadastro.php?erro=cadastro");
    exit;
}