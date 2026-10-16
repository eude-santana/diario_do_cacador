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

    public function buscarPorId(int $idUsuario)
    {
        $sql = "SELECT
                id_usuario,
                nome,
                email,
                senha,
                ativo
            FROM usuario
            WHERE id_usuario = :id_usuario";

        $comando = $this->conexao->prepare($sql);
        $comando->bindValue(
            ":id_usuario",
            $idUsuario,
            PDO::PARAM_INT
        );
        $comando->execute();

        return $comando->fetch(PDO::FETCH_ASSOC);
    }

    public function desativar(int $idUsuario): bool
    {
        /*
         * O e-mail interno libera o endereço original para um novo
         * cadastro, mas mantém o usuário relacionado aos seus dados.
         */
        $sql = "UPDATE usuario
            SET ativo = FALSE,
                email = CONCAT(
                    'conta_desativada_',
                    id_usuario
                )
            WHERE id_usuario = :id_usuario
              AND ativo = TRUE";

        $comando = $this->conexao->prepare($sql);
        $comando->bindValue(
            ":id_usuario",
            $idUsuario,
            PDO::PARAM_INT
        );

        $resultado = $comando->execute();

        return $resultado && $comando->rowCount() === 1;
    }
}