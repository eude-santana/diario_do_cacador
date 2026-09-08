<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function exigirLogin(): void
{
    if (!isset($_SESSION["id_usuario"])) {
        header("Location: /Views/Usuario/login.php");
        exit;
    }
}