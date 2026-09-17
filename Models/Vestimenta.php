<?php

require_once __DIR__ . "/../Config/Connection.php";

class Vestimenta
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
        int $pontosProtecaoMaximo,
        string $dano,
        string $elemento,
        string $especial,
        int $idAutor
    ): bool {
        $sql = "INSERT INTO vestimenta
                    (
                        nome,
                        tipo,
                        pontos_protecao_maximo,
                        dano,
                        elemento,
                        especial,
                        id_autor
                    )
                VALUES
                    (
                        :nome,
                        :tipo,
                        :pontos_protecao_maximo,
                        :dano,
                        :elemento,
                        :especial,
                        :id_autor
                    )";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(":nome", $nome);
        $comando->bindValue(":tipo", $tipo);

        $comando->bindValue(
            ":pontos_protecao_maximo",
            $pontosProtecaoMaximo,
            PDO::PARAM_INT
        );

        $comando->bindValue(":dano", $dano);
        $comando->bindValue(":elemento", $elemento);
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
        $sql = "SELECT
                    id_vestimenta,
                    nome,
                    tipo,
                    pontos_protecao_maximo,
                    dano,
                    elemento,
                    especial,
                    id_autor
                FROM vestimenta
                WHERE id_autor = :id_autor
                ORDER BY id_vestimenta DESC";

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
        int $idVestimenta,
        int $idAutor
    ) {
        $sql = "SELECT
                    id_vestimenta,
                    nome,
                    tipo,
                    pontos_protecao_maximo,
                    dano,
                    elemento,
                    especial,
                    id_autor
                FROM vestimenta
                WHERE id_vestimenta = :id_vestimenta
                  AND id_autor = :id_autor";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(
            ":id_vestimenta",
            $idVestimenta,
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
        int $idVestimenta,
        string $nome,
        string $tipo,
        int $pontosProtecaoMaximo,
        string $dano,
        string $elemento,
        string $especial,
        int $idAutor
    ): bool {
        $sql = "UPDATE vestimenta
                SET nome = :nome,
                    tipo = :tipo,
                    pontos_protecao_maximo =
                        :pontos_protecao_maximo,
                    dano = :dano,
                    elemento = :elemento,
                    especial = :especial
                WHERE id_vestimenta = :id_vestimenta
                  AND id_autor = :id_autor";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(":nome", $nome);
        $comando->bindValue(":tipo", $tipo);

        $comando->bindValue(
            ":pontos_protecao_maximo",
            $pontosProtecaoMaximo,
            PDO::PARAM_INT
        );

        $comando->bindValue(":dano", $dano);
        $comando->bindValue(":elemento", $elemento);
        $comando->bindValue(":especial", $especial);

        $comando->bindValue(
            ":id_vestimenta",
            $idVestimenta,
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
        int $idVestimenta,
        int $idAutor
    ): bool {
        $sql = "DELETE FROM vestimenta
                WHERE id_vestimenta = :id_vestimenta
                  AND id_autor = :id_autor";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(
            ":id_vestimenta",
            $idVestimenta,
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