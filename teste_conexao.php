<?php

require_once __DIR__ . "/Config/Connection.php";

$database = new Connection();
$conexao = $database->conectar();

echo "Conexão realizada com sucesso!";