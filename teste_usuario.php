<?php

require_once __DIR__ . "/Models/Usuario.php";

$usuario = new Usuario();

$resultado = $usuario->cadastrar(
    "Caçador Teste",
    "cacador.teste@exemplo.com",
    "123456"
);

if ($resultado) {
    echo "Usuário cadastrado com sucesso!";
} else {
    echo "Não foi possível cadastrar o usuário.";
}