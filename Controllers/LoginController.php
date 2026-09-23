<?php

session_start();

require_once __DIR__ . "/../Models/Usuario.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../Views/Usuario/login.php");
    exit;
}

$email = $_POST["email"] ?? "";
$senha = $_POST["senha"] ?? "";

// Verifica se os dados recebidos são textos.
if (!is_string($email) || !is_string($senha)) {
    header("Location: ../Views/Usuario/login.php?erro=campos");
    exit;
}

$email = trim($email);

// A senha permanece exatamente como foi digitada.
if ($email === "" || $senha === "") {
    header("Location: ../Views/Usuario/login.php?erro=campos");
    exit;
}

if (
    strlen($email) > 255 ||
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {
    header("Location: ../Views/Usuario/login.php?erro=email_invalido");
    exit;
}

try {
    $usuarioModel = new Usuario();
    $usuario = $usuarioModel->buscarPorEmail($email);

    if (!$usuario || !password_verify($senha, $usuario["senha"])) {
        header("Location: ../Views/Usuario/login.php?erro=login_invalido");
        exit;
    }

    if (!$usuario["ativo"]) {
        header("Location: ../Views/Usuario/login.php?erro=usuario_inativo");
        exit;
    }

    session_regenerate_id(true);

    $_SESSION["id_usuario"] = $usuario["id_usuario"];
    $_SESSION["nome_usuario"] = $usuario["nome"];
    $_SESSION["email_usuario"] = $usuario["email"];

    header("Location: ../Views/Usuario/painel.php");
    exit;

} catch (PDOException $erro) {
    error_log($erro->getMessage());

    header("Location: ../Views/Usuario/login.php?erro=consulta");
    exit;
}