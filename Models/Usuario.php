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
        string $nome,
        string $email,
        string $senha
    ): bool {
        $sql = "INSERT INTO usuario (nome, email, senha)
                VALUES (:nome, :email, :senha)";

        $comando = $this->conexao->prepare($sql);

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $comando->bindValue(":nome", $nome);
        $comando->bindValue(":email", $email);
        $comando->bindValue(":senha", $senhaHash);

        return $comando->execute();
    }

    public function buscarPorEmail(string $email)
    {
        $sql = "SELECT
                    id_usuario,
                    nome,
                    email,
                    senha,
                    ativo
                FROM usuario
                WHERE email = :email";

        $comando = $this->conexao->prepare($sql);
        $comando->bindValue(":email", $email);
        $comando->execute();

        return $comando->fetch(PDO::FETCH_ASSOC);
    }
}