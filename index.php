<?php

require_once __DIR__ . "/Config/Autenticacao.php";

if (isset($_SESSION["id_usuario"])) {
    header("Location: Views/Usuario/painel.php");
    exit;
}

header("Location: Views/Usuario/login.php");
exit;