<?php

require_once __DIR__ . "/../Models/Usuario.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../Views/Usuario/cadastro.php");
    exit;
}

$nome = trim($_POST["nome"] ?? "");
$email = trim($_POST["email"] ?? "");
$senha = $_POST["senha"] ?? "";

if ($nome === "" || $email === "" || $senha === "") {
    header("Location: ../Views/Usuario/cadastro.php?erro=campos");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../Views/Usuario/cadastro.php?erro=email_invalido");
    exit;
}

if (strlen($senha) < 6) {
    header("Location: ../Views/Usuario/cadastro.php?erro=senha_curta");
    exit;
}

$usuarioModel = new Usuario();

$usuarioExistente = $usuarioModel->buscarPorEmail($email);

if ($usuarioExistente) {
    header("Location: ../Views/Usuario/cadastro.php?erro=email_cadastrado");
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