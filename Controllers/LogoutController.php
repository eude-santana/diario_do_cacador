<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../Views/Usuario/painel.php");
    exit;
}

session_unset();
session_destroy();

header("Location: ../Views/Usuario/login.php");
exit;