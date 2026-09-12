<?php

require_once __DIR__ . "/Config/Autenticacao.php";
require_once __DIR__ . "/Models/Item.php";

exigirLogin();

$itemModel = new Item();

$resultado = $itemModel->cadastrar(
    "Corda",
    "Uma corda resistente com 10 metros.",
    $_SESSION["id_usuario"]
);

if ($resultado) {
    echo "<p>Item cadastrado com sucesso!</p>";
} else {
    echo "<p>Não foi possível cadastrar o item.</p>";
}

$itens = $itemModel->listarPorAutor(
    $_SESSION["id_usuario"]
);

echo "<h2>Meus itens</h2>";

foreach ($itens as $item) {
    echo "<p>";
    echo "ID: " . htmlspecialchars($item["id_item"]) . "<br>";
    echo "Nome: " . htmlspecialchars($item["nome"]) . "<br>";
    echo "Descrição: " . htmlspecialchars($item["descricao"] ?? "");
    echo "</p>";
}