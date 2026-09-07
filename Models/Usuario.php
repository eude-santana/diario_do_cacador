<?php

require_once __DIR__ . "/../Config/Connection.php";

class Usuario
{
    private PDO $conexao;

    public function __construct()
    {
        $database = new Connection();
        $this->conexao = $database->conectar();
    }

    public function cadastrar(
        string $nomeUsuario,
        string $email,
        string $senha
    ): bool {
        $sql = "INSERT INTO usuario (nome_usuario, email, senha)
                VALUES (:nome_usuario, :email, :senha)";

        $comando = $this->conexao->prepare($sql);

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $comando->bindValue(":nome_usuario", $nomeUsuario);
        $comando->bindValue(":email", $email);
        $comando->bindValue(":senha", $senhaHash);

        return $comando->execute();
    }
}