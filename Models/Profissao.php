<?php

require_once __DIR__ . "/../Config/Connection.php";

class Profissao
{
    private PDO $conexao;

    public function __construct()
    {
        $connection = new Connection();
        $this->conexao = $connection->conectar();
    }

    public function cadastrar(
        string $nome,
        string $descricao,
        int $pvMaximo,
        string $visibilidade,
        int $idAutor,
        array $vantagens,
        array $armas = [],
        array $vestimentas = [],
        array $itens = []
    ): bool {
        $this->validarVantagens($vantagens);

        try {
            $this->conexao->beginTransaction();

            $sql = "
                INSERT INTO profissao (
                    nome,
                    descricao,
                    pv_maximo,
                    id_autor,
                    visibilidade
                ) VALUES (
                    :nome,
                    :descricao,
                    :pv_maximo,
                    :id_autor,
                    :visibilidade
                )
            ";

            $comando = $this->conexao->prepare($sql);

            $comando->execute([
                ":nome" => $nome,
                ":descricao" => $descricao,
                ":pv_maximo" => $pvMaximo,
                ":id_autor" => $idAutor,
                ":visibilidade" => $visibilidade
            ]);

            $idProfissao = (int) $this->conexao->lastInsertId();

            $this->salvarRelacionamentos(
                $idProfissao,
                $vantagens,
                $armas,
                $vestimentas,
                $itens
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
        $sql = "
            SELECT
                id_profissao,
                nome,
                descricao,
                pv_maximo,
                visibilidade
            FROM profissao
            WHERE id_autor = :id_autor
            ORDER BY nome
        ";

        $comando = $this->conexao->prepare($sql);

        $comando->execute([
            ":id_autor" => $idAutor
        ]);

        return $comando->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(
        int $idProfissao,
        int $idAutor
    ): array|false {
        $sql = "
            SELECT
                id_profissao,
                nome,
                descricao,
                pv_maximo,
                visibilidade,
                id_autor
            FROM profissao
            WHERE id_profissao = :id_profissao
              AND id_autor = :id_autor
        ";

        $comando = $this->conexao->prepare($sql);

        $comando->execute([
            ":id_profissao" => $idProfissao,
            ":id_autor" => $idAutor
        ]);

        return $comando->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarVantagens(
        int $idProfissao,
        int $idAutor
    ): array {
        $sql = "
            SELECT
                pv.slot,
                pv.id_vantagem
            FROM profissao_vantagem AS pv
            INNER JOIN profissao AS p
                ON p.id_profissao = pv.id_profissao
            WHERE pv.id_profissao = :id_profissao
              AND p.id_autor = :id_autor
            ORDER BY pv.slot
        ";

        $comando = $this->conexao->prepare($sql);

        $comando->execute([
            ":id_profissao" => $idProfissao,
            ":id_autor" => $idAutor
        ]);

        return $comando->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function buscarArmas(
        int $idProfissao,
        int $idAutor
    ): array {
        $sql = "
            SELECT
                pa.slot,
                pa.id_arma
            FROM profissao_arma AS pa
            INNER JOIN profissao AS p
                ON p.id_profissao = pa.id_profissao
            WHERE pa.id_profissao = :id_profissao
              AND p.id_autor = :id_autor
            ORDER BY pa.slot
        ";

        $comando = $this->conexao->prepare($sql);

        $comando->execute([
            ":id_profissao" => $idProfissao,
            ":id_autor" => $idAutor
        ]);

        return $comando->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function buscarVestimentas(
        int $idProfissao,
        int $idAutor
    ): array {
        $sql = "
            SELECT
                pv.tipo_slot,
                pv.id_vestimenta
            FROM profissao_vestimenta AS pv
            INNER JOIN profissao AS p
                ON p.id_profissao = pv.id_profissao
            WHERE pv.id_profissao = :id_profissao
              AND p.id_autor = :id_autor
            ORDER BY pv.tipo_slot
        ";

        $comando = $this->conexao->prepare($sql);

        $comando->execute([
            ":id_profissao" => $idProfissao,
            ":id_autor" => $idAutor
        ]);

        return $comando->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function buscarItens(
        int $idProfissao,
        int $idAutor
    ): array {
        $sql = "
            SELECT
                pi.id_item,
                pi.quantidade
            FROM profissao_item AS pi
            INNER JOIN profissao AS p
                ON p.id_profissao = pi.id_profissao
            WHERE pi.id_profissao = :id_profissao
              AND p.id_autor = :id_autor
            ORDER BY pi.id_item
        ";

        $comando = $this->conexao->prepare($sql);

        $comando->execute([
            ":id_profissao" => $idProfissao,
            ":id_autor" => $idAutor
        ]);

        return $comando->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function atualizar(
        int $idProfissao,
        string $nome,
        string $descricao,
        int $pvMaximo,
        string $visibilidade,
        int $idAutor,
        array $vantagens,
        array $armas = [],
        array $vestimentas = [],
        array $itens = []
    ): bool {
        $this->validarVantagens($vantagens);

        if (!$this->buscarPorId($idProfissao, $idAutor)) {
            return false;
        }

        try {
            $this->conexao->beginTransaction();

            $sql = "
                UPDATE profissao
                SET
                    nome = :nome,
                    descricao = :descricao,
                    pv_maximo = :pv_maximo,
                    visibilidade = :visibilidade
                WHERE id_profissao = :id_profissao
                  AND id_autor = :id_autor
            ";

            $comando = $this->conexao->prepare($sql);

            $comando->execute([
                ":nome" => $nome,
                ":descricao" => $descricao,
                ":pv_maximo" => $pvMaximo,
                ":visibilidade" => $visibilidade,
                ":id_profissao" => $idProfissao,
                ":id_autor" => $idAutor
            ]);

            $this->removerRelacionamentos($idProfissao);

            $this->salvarRelacionamentos(
                $idProfissao,
                $vantagens,
                $armas,
                $vestimentas,
                $itens
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
        int $idProfissao,
        int $idAutor
    ): bool {
        $sql = "
            DELETE FROM profissao
            WHERE id_profissao = :id_profissao
              AND id_autor = :id_autor
        ";

        $comando = $this->conexao->prepare($sql);

        $comando->execute([
            ":id_profissao" => $idProfissao,
            ":id_autor" => $idAutor
        ]);

        return $comando->rowCount() > 0;
    }

    private function salvarRelacionamentos(
        int $idProfissao,
        array $vantagens,
        array $armas,
        array $vestimentas,
        array $itens
    ): void {
        $this->salvarVantagens(
            $idProfissao,
            $vantagens
        );

        $this->salvarArmas(
            $idProfissao,
            $armas
        );

        $this->salvarVestimentas(
            $idProfissao,
            $vestimentas
        );

        $this->salvarItens(
            $idProfissao,
            $itens
        );
    }

    private function salvarVantagens(
        int $idProfissao,
        array $vantagens
    ): void {
        $sql = "
            INSERT INTO profissao_vantagem (
                id_profissao,
                slot,
                id_vantagem
            ) VALUES (
                :id_profissao,
                :slot,
                :id_vantagem
            )
        ";

        $comando = $this->conexao->prepare($sql);

        foreach ([1, 2] as $slot) {
            $comando->execute([
                ":id_profissao" => $idProfissao,
                ":slot" => $slot,
                ":id_vantagem" => (int) $vantagens[$slot]
            ]);
        }
    }

    private function salvarArmas(
        int $idProfissao,
        array $armas
    ): void {
        $sql = "
            INSERT INTO profissao_arma (
                id_profissao,
                slot,
                id_arma
            ) VALUES (
                :id_profissao,
                :slot,
                :id_arma
            )
        ";

        $comando = $this->conexao->prepare($sql);

        foreach ($armas as $slot => $idArma) {
            $slot = (int) $slot;
            $idArma = (int) $idArma;

            if ($idArma <= 0) {
                continue;
            }

            if ($slot < 1 || $slot > 3) {
                throw new InvalidArgumentException(
                    "O slot da arma é inválido."
                );
            }

            $comando->execute([
                ":id_profissao" => $idProfissao,
                ":slot" => $slot,
                ":id_arma" => $idArma
            ]);
        }
    }

    private function salvarVestimentas(
        int $idProfissao,
        array $vestimentas
    ): void {
        $tiposPermitidos = [
            "ARMADURA",
            "ELMO",
            "BRACELETES",
            "BOTAS",
            "ESCUDO"
        ];

        $sql = "
            INSERT INTO profissao_vestimenta (
                id_profissao,
                tipo_slot,
                id_vestimenta
            ) VALUES (
                :id_profissao,
                :tipo_slot,
                :id_vestimenta
            )
        ";

        $comando = $this->conexao->prepare($sql);

        foreach ($vestimentas as $tipoSlot => $idVestimenta) {
            $tipoSlot = strtoupper((string) $tipoSlot);
            $idVestimenta = (int) $idVestimenta;

            if ($idVestimenta <= 0) {
                continue;
            }

            if (!in_array($tipoSlot, $tiposPermitidos, true)) {
                throw new InvalidArgumentException(
                    "O tipo do slot da vestimenta é inválido."
                );
            }

            $comando->execute([
                ":id_profissao" => $idProfissao,
                ":tipo_slot" => $tipoSlot,
                ":id_vestimenta" => $idVestimenta
            ]);
        }
    }

    private function salvarItens(
        int $idProfissao,
        array $itens
    ): void {
        $sql = "
            INSERT INTO profissao_item (
                id_profissao,
                id_item,
                quantidade
            ) VALUES (
                :id_profissao,
                :id_item,
                :quantidade
            )
        ";

        $comando = $this->conexao->prepare($sql);

        foreach ($itens as $idItem => $quantidade) {
            $idItem = (int) $idItem;
            $quantidade = (int) $quantidade;

            if ($idItem <= 0 || $quantidade <= 0) {
                continue;
            }

            $comando->execute([
                ":id_profissao" => $idProfissao,
                ":id_item" => $idItem,
                ":quantidade" => $quantidade
            ]);
        }
    }

    private function removerRelacionamentos(
        int $idProfissao
    ): void {
        $tabelas = [
            "profissao_vantagem",
            "profissao_arma",
            "profissao_vestimenta",
            "profissao_item"
        ];

        foreach ($tabelas as $tabela) {
            $sql = "
                DELETE FROM {$tabela}
                WHERE id_profissao = :id_profissao
            ";

            $comando = $this->conexao->prepare($sql);

            $comando->execute([
                ":id_profissao" => $idProfissao
            ]);
        }
    }

    private function validarVantagens(
        array $vantagens
    ): void {
        $vantagemSlot1 = (int) ($vantagens[1] ?? 0);
        $vantagemSlot2 = (int) ($vantagens[2] ?? 0);

        if ($vantagemSlot1 <= 0 || $vantagemSlot2 <= 0) {
            throw new InvalidArgumentException(
                "A profissão deve possuir duas vantagens."
            );
        }

        if ($vantagemSlot1 === $vantagemSlot2) {
            throw new InvalidArgumentException(
                "As duas vantagens devem ser diferentes."
            );
        }
    }
}