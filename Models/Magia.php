<?php

require_once __DIR__ . "/../Config/Connection.php";

class Magia
{
    private PDO $conexao;

    public function __construct()
    {
        $database = new Connection();
        $this->conexao = $database->conectar();
    }

    public function cadastrar(
        string $nome,
        string $elemento,
        string $descricao,
        int $idAutor
    ): bool {
        $sql = "INSERT INTO magia
                    (
                        nome,
                        elemento,
                        descricao,
                        id_autor
                    )
                VALUES
                    (
                        :nome,
                        :elemento,
                        :descricao,
                        :id_autor
                    )";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(":nome", $nome);
        $comando->bindValue(":elemento", $elemento);
        $comando->bindValue(":descricao", $descricao);

        $comando->bindValue(
            ":id_autor",
            $idAutor,
            PDO::PARAM_INT
        );

        return $comando->execute();
    }

    public function listarPorAutor(int $idAutor): array
    {
        $sql = "SELECT
                    id_magia,
                    nome,
                    elemento,
                    descricao,
                    id_autor
                FROM magia
                WHERE id_autor = :id_autor
                ORDER BY id_magia DESC";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(
            ":id_autor",
            $idAutor,
            PDO::PARAM_INT
        );

        $comando->execute();

        return $comando->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $idMagia, int $idAutor)
    {
        $sql = "SELECT
                    id_magia,
                    nome,
                    elemento,
                    descricao,
                    id_autor
                FROM magia
                WHERE id_magia = :id_magia
                  AND id_autor = :id_autor";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(
            ":id_magia",
            $idMagia,
            PDO::PARAM_INT
        );

        $comando->bindValue(
            ":id_autor",
            $idAutor,
            PDO::PARAM_INT
        );

        $comando->execute();

        return $comando->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar(
        int $idMagia,
        string $nome,
        string $elemento,
        string $descricao,
        int $idAutor
    ): bool {
        $sql = "UPDATE magia
                SET nome = :nome,
                    elemento = :elemento,
                    descricao = :descricao
                WHERE id_magia = :id_magia
                  AND id_autor = :id_autor";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(":nome", $nome);
        $comando->bindValue(":elemento", $elemento);
        $comando->bindValue(":descricao", $descricao);

        $comando->bindValue(
            ":id_magia",
            $idMagia,
            PDO::PARAM_INT
        );

        $comando->bindValue(
            ":id_autor",
            $idAutor,
            PDO::PARAM_INT
        );

        return $comando->execute();
    }

    public function excluir(int $idMagia, int $idAutor): bool
    {
        $sql = "DELETE FROM magia
                WHERE id_magia = :id_magia
                  AND id_autor = :id_autor";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(
            ":id_magia",
            $idMagia,
            PDO::PARAM_INT
        );

        $comando->bindValue(
            ":id_autor",
            $idAutor,
            PDO::PARAM_INT
        );

        $comando->execute();

        return $comando->rowCount() > 0;
    }
}