<?php

require_once __DIR__ . "/Models/Usuario.php";

$usuarioModel = new Usuario();

$usuarioEncontrado = $usuarioModel->buscarPorEmail(
    "cacador.teste@exemplo.com"
);

if ($usuarioEncontrado) {
    echo "Usuário encontrado!<br>";
    echo "ID: " . $usuarioEncontrado["id_usuario"] . "<br>";
    echo "Nome: " . $usuarioEncontrado["nome"] . "<br>";
    echo "E-mail: " . $usuarioEncontrado["email"] . "<br>";
    echo "Ativo: " . $usuarioEncontrado["ativo"];
} else {
    echo "Usuário não encontrado.";
}