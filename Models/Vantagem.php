<?php

require_once __DIR__ . "/../Config/Connection.php";

class Vantagem
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
        string $tipo,
        array $idsRelacionados,
        int $idAutor
    ): bool {
        try {
            $this->conexao->beginTransaction();

            $sql = "INSERT INTO vantagem
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

            $comando->execute();

            $idVantagem = (int) $this->conexao->lastInsertId();

            $this->salvarRelacionamentos(
                $idVantagem,
                $tipo,
                $idsRelacionados
            );

            $this->conexao->commit();

            return true;
        } catch (Throwable $erro) {
            if ($this->conexao->inTransaction()) {
                $this->conexao->rollBack();
            }

            throw $erro;
        }
    }

    public function listarPorAutor(int $idAutor): array
    {
        $sql = "SELECT
                    v.id_vantagem,
                    v.nome,
                    v.descricao,
                    v.id_autor,

                    CASE
                        WHEN EXISTS (
                            SELECT 1
                            FROM vantagem_companheiro vc
                            WHERE vc.id_vantagem = v.id_vantagem
                        ) THEN 'COMPANHEIRO'

                        WHEN EXISTS (
                            SELECT 1
                            FROM vantagem_magia vm
                            WHERE vm.id_vantagem = v.id_vantagem
                        ) THEN 'MAGIA'

                        ELSE 'NORMAL'
                    END AS tipo

                FROM vantagem v
                WHERE v.id_autor = :id_autor
                ORDER BY v.id_vantagem DESC";

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
        int $idVantagem,
        int $idAutor
    ) {
        $sql = "SELECT
                    id_vantagem,
                    nome,
                    descricao,
                    id_autor
                FROM vantagem
                WHERE id_vantagem = :id_vantagem
                  AND id_autor = :id_autor";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(
            ":id_vantagem",
            $idVantagem,
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

    public function buscarIdsCompanheiros(
        int $idVantagem
    ): array {
        $sql = "SELECT id_companheiro
                FROM vantagem_companheiro
                WHERE id_vantagem = :id_vantagem";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(
            ":id_vantagem",
            $idVantagem,
            PDO::PARAM_INT
        );

        $comando->execute();

        return $comando->fetchAll(
            PDO::FETCH_COLUMN
        );
    }

    public function buscarIdsMagias(
        int $idVantagem
    ): array {
        $sql = "SELECT id_magia
                FROM vantagem_magia
                WHERE id_vantagem = :id_vantagem";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(
            ":id_vantagem",
            $idVantagem,
            PDO::PARAM_INT
        );

        $comando->execute();

        return $comando->fetchAll(
            PDO::FETCH_COLUMN
        );
    }

    public function atualizar(
        int $idVantagem,
        string $nome,
        string $descricao,
        string $tipo,
        array $idsRelacionados,
        int $idAutor
    ): bool {
        try {
            $this->conexao->beginTransaction();

            $sql = "UPDATE vantagem
                    SET nome = :nome,
                        descricao = :descricao
                    WHERE id_vantagem = :id_vantagem
                      AND id_autor = :id_autor";

            $comando = $this->conexao->prepare($sql);

            $comando->bindValue(":nome", $nome);
            $comando->bindValue(":descricao", $descricao);

            $comando->bindValue(
                ":id_vantagem",
                $idVantagem,
                PDO::PARAM_INT
            );

            $comando->bindValue(
                ":id_autor",
                $idAutor,
                PDO::PARAM_INT
            );

            $comando->execute();

            $this->removerRelacionamentos($idVantagem);

            $this->salvarRelacionamentos(
                $idVantagem,
                $tipo,
                $idsRelacionados
            );

            $this->conexao->commit();

            return true;
        } catch (Throwable $erro) {
            if ($this->conexao->inTransaction()) {
                $this->conexao->rollBack();
            }

            throw $erro;
        }
    }

    public function excluir(
        int $idVantagem,
        int $idAutor
    ): bool {
        $sql = "DELETE FROM vantagem
                WHERE id_vantagem = :id_vantagem
                  AND id_autor = :id_autor";

        $comando = $this->conexao->prepare($sql);

        $comando->bindValue(
            ":id_vantagem",
            $idVantagem,
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

    private function removerRelacionamentos(
        int $idVantagem
    ): void {
        $sqlCompanheiros =
            "DELETE FROM vantagem_companheiro
             WHERE id_vantagem = :id_vantagem";

        $comando = $this->conexao->prepare(
            $sqlCompanheiros
        );

        $comando->bindValue(
            ":id_vantagem",
            $idVantagem,
            PDO::PARAM_INT
        );

        $comando->execute();

        $sqlMagias =
            "DELETE FROM vantagem_magia
             WHERE id_vantagem = :id_vantagem";

        $comando = $this->conexao->prepare($sqlMagias);

        $comando->bindValue(
            ":id_vantagem",
            $idVantagem,
            PDO::PARAM_INT
        );

        $comando->execute();
    }

    private function salvarRelacionamentos(
        int $idVantagem,
        string $tipo,
        array $idsRelacionados
    ): void {
        $idsRelacionados = array_unique(
            array_map("intval", $idsRelacionados)
        );

        if ($tipo === "NORMAL") {
            return;
        }

        if ($tipo === "COMPANHEIRO") {
            $sql = "INSERT INTO vantagem_companheiro
                        (id_vantagem, id_companheiro)
                    VALUES
                        (:id_vantagem, :id_relacionado)";
        } elseif ($tipo === "MAGIA") {
            $sql = "INSERT INTO vantagem_magia
                        (id_vantagem, id_magia)
                    VALUES
                        (:id_vantagem, :id_relacionado)";
        } else {
            throw new InvalidArgumentException(
                "Tipo de vantagem inválido."
            );
        }

        $comando = $this->conexao->prepare($sql);

        foreach ($idsRelacionados as $idRelacionado) {
            if ($idRelacionado <= 0) {
                continue;
            }

            $comando->bindValue(
                ":id_vantagem",
                $idVantagem,
                PDO::PARAM_INT
            );

            $comando->bindValue(
                ":id_relacionado",
                $idRelacionado,
                PDO::PARAM_INT
            );

            $comando->execute();
        }
    }
}