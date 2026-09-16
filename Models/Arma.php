<?php

require_once __DIR__ . "/../Config/Connection.php";

class Arma
{
    private PDO $conexao;

    public function __construct()
    {
        $database = new Connection();
        $this->conexao = $database->conectar();
    }

    public function cadastrar(
        string $nome,
        string $tipo,
        int $maos,
        string $dano,
        string $especial,
        int $idAutor
    ): bool {
        $sql = "INSERT INTO arma(nome, tipo, maos, dano, especial, id_autor)
                VALUES (:nome, :tipo, :maos, :dano, :especial, :id_autor)";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(":nome", $nome);
        $comando->bindValue(":tipo", $tipo);
        $comando->bindValue(":maos", $maos, PDO::PARAM_INT);
        $comando->bindValue(":dano", $dano);
        $comando->bindValue(":especial", $especial);
        $comando->bindValue(
            ":id_autor",
            $idAutor,
            PDO::PARAM_INT
        );

        return $comando->execute();
    }

    public function listarPorAutor(int $idAutor): array
    {
        $sql = "SELECT id_arma, nome, tipo, maos, dano, especial, id_autor
                FROM arma
                WHERE id_autor = :id_autor
                ORDER BY id_arma DESC";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(
            ":id_autor",
            $idAutor,
            PDO::PARAM_INT
        );

        $comando->execute();

        return $comando->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $idArma, int $idAutor)
    {
        $sql = "SELECT id_arma, nome, tipo, maos, dano, especial, id_autor
                FROM arma
                WHERE id_arma = :id_arma
                  AND id_autor = :id_autor";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(
            ":id_arma",
            $idArma,
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
        int $idArma,
        string $nome,
        string $tipo,
        int $maos,
        string $dano,
        string $especial,
        int $idAutor
    ): bool {
        $sql = "UPDATE arma
                SET nome = :nome,
                    tipo = :tipo,
                    maos = :maos,
                    dano = :dano,
                    especial = :especial
                WHERE id_arma = :id_arma
                  AND id_autor = :id_autor";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(":nome", $nome);
        $comando->bindValue(":tipo", $tipo);
        $comando->bindValue(":maos", $maos, PDO::PARAM_INT);
        $comando->bindValue(":dano", $dano);
        $comando->bindValue(":especial", $especial);

        $comando->bindValue(
            ":id_arma",
            $idArma,
            PDO::PARAM_INT
        );

        $comando->bindValue(
            ":id_autor",
            $idAutor,
            PDO::PARAM_INT
        );

        return $comando->execute();
    }

    public function excluir(int $idArma, int $idAutor): bool
    {
        $sql = "DELETE FROM arma
                WHERE id_arma = :id_arma
                  AND id_autor = :id_autor";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(
            ":id_arma",
            $idArma,
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