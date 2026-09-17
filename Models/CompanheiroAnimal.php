<?php

require_once __DIR__ . "/../Config/Connection.php";

class CompanheiroAnimal
{
    private PDO $conexao;

    public function __construct()
    {
        $database = new Connection();
        $this->conexao = $database->conectar();
    }

    public function cadastrar(
        string $tipo,
        int $pvMaximo,
        string $dano,
        string $descricao,
        int $idAutor
    ): bool {
        $sql = "INSERT INTO companheiro_animal
                    (
                        tipo,
                        pv_maximo,
                        dano,
                        descricao,
                        id_autor
                    )
                VALUES
                    (
                        :tipo,
                        :pv_maximo,
                        :dano,
                        :descricao,
                        :id_autor
                    )";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(":tipo", $tipo);

        $comando->bindValue(
            ":pv_maximo",
            $pvMaximo,
            PDO::PARAM_INT
        );

        $comando->bindValue(":dano", $dano);
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
                    id_companheiro,
                    tipo,
                    pv_maximo,
                    dano,
                    descricao,
                    id_autor
                FROM companheiro_animal
                WHERE id_autor = :id_autor
                ORDER BY id_companheiro DESC";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(
            ":id_autor",
            $idAutor,
            PDO::PARAM_INT
        );

        $comando->execute();

        return $comando->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(
        int $idCompanheiro,
        int $idAutor
    ) {
        $sql = "SELECT
                    id_companheiro,
                    tipo,
                    pv_maximo,
                    dano,
                    descricao,
                    id_autor
                FROM companheiro_animal
                WHERE id_companheiro = :id_companheiro
                  AND id_autor = :id_autor";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(
            ":id_companheiro",
            $idCompanheiro,
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
        int $idCompanheiro,
        string $tipo,
        int $pvMaximo,
        string $dano,
        string $descricao,
        int $idAutor
    ): bool {
        $sql = "UPDATE companheiro_animal
                SET tipo = :tipo,
                    pv_maximo = :pv_maximo,
                    dano = :dano,
                    descricao = :descricao
                WHERE id_companheiro = :id_companheiro
                  AND id_autor = :id_autor";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(":tipo", $tipo);

        $comando->bindValue(
            ":pv_maximo",
            $pvMaximo,
            PDO::PARAM_INT
        );

        $comando->bindValue(":dano", $dano);
        $comando->bindValue(":descricao", $descricao);

        $comando->bindValue(
            ":id_companheiro",
            $idCompanheiro,
            PDO::PARAM_INT
        );

        $comando->bindValue(
            ":id_autor",
            $idAutor,
            PDO::PARAM_INT
        );

        return $comando->execute();
    }

    public function excluir(
        int $idCompanheiro,
        int $idAutor
    ): bool {
        $sql = "DELETE FROM companheiro_animal
                WHERE id_companheiro = :id_companheiro
                  AND id_autor = :id_autor";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(
            ":id_companheiro",
            $idCompanheiro,
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