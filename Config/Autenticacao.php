<?php

require_once __DIR__ . "/../Models/Usuario.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function exigirLogin(): void
{
    if (!isset($_SESSION["id_usuario"])) {
        header("Location: /Views/Usuario/login.php");
        exit;
    }

    try {
        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->buscarPorId(
            (int) $_SESSION["id_usuario"]
        );

        if (!$usuario || !$usuario["ativo"]) {
            session_unset();
            session_destroy();

            header(
                "Location: /Views/Usuario/login.php"
                . "?erro=usuario_inativo"
            );
            exit;
        }
    } catch (PDOException $erro) {
        error_log($erro->getMessage());

        header(
            "Location: /Views/Usuario/login.php?erro=consulta"
        );
        exit;
    }
}