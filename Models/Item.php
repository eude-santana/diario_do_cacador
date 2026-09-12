<?php

require_once __DIR__ . "/../Config/Connection.php";

class Item
{
    private PDO $conexao;

    public function __construct()
    {
        $database = new Connection();
        $this->conexao = $database->conectar();
    }

    public function cadastrar(
        string $nome,
        string $descricao,
        int $idAutor
    ): bool {
        $sql = "INSERT INTO item
                    (nome, descricao, id_autor)
                VALUES
                    (:nome, :descricao, :id_autor)";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(":nome", $nome);
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
                    id_item,
                    nome,
                    descricao,
                    id_autor
                FROM item
                WHERE id_autor = :id_autor
                ORDER BY id_item DESC";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(
            ":id_autor",
            $idAutor,
            PDO::PARAM_INT
        );

        $comando->execute();

        return $comando->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $idItem, int $idAutor)
    {
        $sql = "SELECT
                    id_item,
                    nome,
                    descricao,
                    id_autor
                FROM item
                WHERE id_item = :id_item
                AND id_autor = :id_autor";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(
            ":id_item",
            $idItem,
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
        int $idItem,
        string $nome,
        string $descricao,
        int $idAutor
    ): bool {
        $sql = "UPDATE item
                SET nome = :nome,
                    descricao = :descricao
                WHERE id_item = :id_item
                AND id_autor = :id_autor";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(":nome", $nome);
        $comando->bindValue(":descricao", $descricao);
        $comando->bindValue(
            ":id_item",
            $idItem,
            PDO::PARAM_INT
        );
        $comando->bindValue(
            ":id_autor",
            $idAutor,
            PDO::PARAM_INT
        );

        return $comando->execute();
    }

    public function excluir(int $idItem, int $idAutor): bool
    {
        $sql = "DELETE FROM item
                WHERE id_item = :id_item
                AND id_autor = :id_autor";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(
            ":id_item",
            $idItem,
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