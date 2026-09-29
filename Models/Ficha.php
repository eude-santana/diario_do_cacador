<?php

require_once __DIR__ . "/../Config/Connection.php";

class Ficha
{
    private PDO $conexao;

    public function __construct()
    {
        $database = new Connection();
        $this->conexao = $database->conectar();
    }

    /*
     * Lista apenas profissões completas pertencentes ao usuário.
     */
    public function listarProfissoesDisponiveis(
        int $idUsuario
    ): array {
        $sql = "
            SELECT
                p.id_profissao,
                p.nome,
                p.descricao,
                p.pv_maximo
            FROM profissao p
            INNER JOIN profissao_vantagem pv
                ON pv.id_profissao = p.id_profissao
            WHERE p.id_autor = :id_usuario
            GROUP BY
                p.id_profissao,
                p.nome,
                p.descricao,
                p.pv_maximo
            HAVING COUNT(pv.id_vantagem) = 2
            ORDER BY p.nome
        ";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            ':id_usuario' => $idUsuario
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     * Retorna os dados que serão exibidos quando uma profissão
     * for escolhida.
     *
     * Esses dados servem para visualização. O formulário não
     * poderá alterar PV, vantagens, equipamentos ou itens.
     */
    public function buscarDadosIniciais(
        int $idProfissao,
        int $idUsuario
    ): ?array {
        $sql = "
            SELECT
                p.id_profissao,
                p.nome,
                p.descricao,
                p.pv_maximo
            FROM profissao p
            WHERE p.id_profissao = :id_profissao
              AND p.id_autor = :id_usuario
              AND (
                    SELECT COUNT(*)
                    FROM profissao_vantagem pv
                    WHERE pv.id_profissao = p.id_profissao
                  ) = 2
        ";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            ':id_profissao' => $idProfissao,
            ':id_usuario' => $idUsuario
        ]);

        $profissao = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$profissao) {
            return null;
        }

        $profissao['vantagens'] = $this->buscarVantagens(
            $idProfissao,
            $idUsuario
        );

        $profissao['armas'] = $this->buscarArmas(
            $idProfissao,
            $idUsuario
        );

        $profissao['vestimentas'] = $this->buscarVestimentas(
            $idProfissao,
            $idUsuario
        );

        $profissao['itens'] = $this->buscarItens(
            $idProfissao,
            $idUsuario
        );

        /*
         * Magias são opções de escolha.
         * Apenas uma delas será registrada como magia inicial.
         */
        $profissao['magias'] = $this->buscarMagias(
            $idProfissao,
            $idUsuario
        );

        $profissao['companheiros'] = $this->buscarCompanheiros(
            $idProfissao,
            $idUsuario
        );

        return $profissao;
    }

    /*
     * Cadastra a ficha e copia os dados iniciais da profissão.
     *
     * $idMagia:
     * magia inicial escolhida pelo jogador.
     *
     * $idCompanheiro:
     * companheiro escolhido, caso a profissão permita.
     */
    public function cadastrar(
        string $nome,
        int $idProfissao,
        int $idUsuario,
        ?int $idMagia = null,
        ?int $idCompanheiro = null,
        ?string $nomeCompanheiro = null
    ): int {

        $nome = trim($nome);
        $nomeCompanheiro = trim($nomeCompanheiro ?? '');

        if ($nome === '') {
            throw new InvalidArgumentException(
                'Informe o nome do personagem.'
            );
        }

        if (strlen($nome) > 100) {
            throw new InvalidArgumentException(
                'O nome do personagem deve possuir no máximo 100 caracteres.'
            );
        }

        if ($idProfissao <= 0 || $idUsuario <= 0) {
            throw new InvalidArgumentException(
                'Dados da ficha inválidos.'
            );
        }

        try {
            $this->conexao->beginTransaction();

            $profissao = $this->buscarProfissaoParaCadastro(
                $idProfissao,
                $idUsuario
            );

            if (!$profissao) {
                throw new InvalidArgumentException(
                    'A profissão não existe, não pertence ao usuário ou não possui duas vantagens.'
                );
            }

            /*
             * Busca e valida a magia inicial.
             */
            $magias = $this->buscarMagias(
                $idProfissao,
                $idUsuario
            );

            $magiaEscolhida = $this->validarMagia(
                $magias,
                $idMagia
            );

            /*
             * Busca e valida o companheiro inicial.
             */
            $companheiros = $this->buscarCompanheiros(
                $idProfissao,
                $idUsuario
            );

            $companheiroEscolhido = $this->validarCompanheiro(
                $companheiros,
                $idCompanheiro,
                $nomeCompanheiro
            );

            /*
             * O jogador não envia PV nem fadiga.
             *
             * PV atual começa com o PV máximo da profissão.
             * Fadiga começa em zero.
             */
            $sqlFicha = "
                INSERT INTO ficha (
                    nome,
                    pv_atual,
                    fadiga,
                    id_profissao,
                    id_usuario
                ) VALUES (
                    :nome,
                    :pv_atual,
                    0,
                    :id_profissao,
                    :id_usuario
                )
            ";

            $stmtFicha = $this->conexao->prepare($sqlFicha);
            $stmtFicha->execute([
                ':nome' => $nome,
                ':pv_atual' => $profissao['pv_maximo'],
                ':id_profissao' => $idProfissao,
                ':id_usuario' => $idUsuario
            ]);

            $idFicha = (int) $this->conexao->lastInsertId();

            $this->copiarArmas(
                $idFicha,
                $idProfissao,
                $idUsuario
            );

            $this->copiarVestimentas(
                $idFicha,
                $idProfissao,
                $idUsuario
            );

            $this->copiarItens(
                $idFicha,
                $idProfissao,
                $idUsuario
            );

            /*
             * Registra somente a magia escolhida.
             */
            if ($magiaEscolhida !== null) {
                $this->cadastrarMagiaInicial(
                    $idFicha,
                    $magiaEscolhida
                );
            }

            if ($companheiroEscolhido !== null) {
                $this->cadastrarCompanheiroNaFicha(
                    $idFicha,
                    $companheiroEscolhido,
                    $nomeCompanheiro
                );
            }

            $this->conexao->commit();

            return $idFicha;
        } catch (Throwable $erro) {
            if ($this->conexao->inTransaction()) {
                $this->conexao->rollBack();
            }

            throw $erro;
        }
    }

    public function listarPorUsuario(int $idUsuario): array
    {
        $sql = "
            SELECT
                f.id_ficha,
                f.nome,
                f.pv_atual,
                f.fadiga,
                p.nome AS nome_profissao,
                p.pv_maximo
            FROM ficha f
            INNER JOIN profissao p
                ON p.id_profissao = f.id_profissao
            WHERE f.id_usuario = :id_usuario
            ORDER BY f.nome
        ";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            ':id_usuario' => $idUsuario
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(
        int $idFicha,
        int $idUsuario
    ): array|false {
        $sql = "
            SELECT
                f.id_ficha,
                f.nome,
                f.pv_atual,
                f.fadiga,
                f.id_profissao,
                p.nome AS nome_profissao,
                p.descricao AS descricao_profissao,
                p.pv_maximo
            FROM ficha f
            INNER JOIN profissao p
                ON p.id_profissao = f.id_profissao
            WHERE f.id_ficha = :id_ficha
              AND f.id_usuario = :id_usuario
        ";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            ':id_ficha' => $idFicha,
            ':id_usuario' => $idUsuario
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarDetalhes(
        int $idFicha,
        int $idUsuario
    ): ?array {
        $ficha = $this->buscarPorId(
            $idFicha,
            $idUsuario
        );

        if (!$ficha) {
            return null;
        }

        return [
            'ficha' => $ficha,

            'vantagens' => $this->buscarVantagens(
                (int) $ficha['id_profissao'],
                $idUsuario
            ),

            'armas' => $this->buscarArmasDaFicha(
                $idFicha
            ),

            'vestimentas' => $this->buscarVestimentasDaFicha(
                $idFicha
            ),

            'itens' => $this->buscarItensDaFicha(
                $idFicha
            ),

            'magias' => $this->buscarMagiasDaFicha(
                $idFicha
            ),

            'companheiro' => $this->buscarCompanheiroDaFicha(
                $idFicha
            )
        ];
    }

    public function atualizarDadosBasicos(
        int $idFicha,
        int $idUsuario,
        string $nomePersonagem,
        int $pvAtual,
        int $fadiga
    ): bool {
        $nomePersonagem = trim($nomePersonagem);

        if ($nomePersonagem === '') {
            throw new InvalidArgumentException(
                'Informe o nome do personagem.'
            );
        }

        if (strlen($nomePersonagem) > 100) {
            throw new InvalidArgumentException(
                'O nome do personagem deve possuir no máximo 100 caracteres.'
            );
        }

        if ($pvAtual < 0) {
            throw new InvalidArgumentException(
                'O PV atual não pode ser negativo.'
            );
        }

        if ($fadiga < 0 || $fadiga > 6) {
            throw new InvalidArgumentException(
                'A fadiga deve estar entre 0 e 6.'
            );
        }

        $ficha = $this->buscarPorId(
            $idFicha,
            $idUsuario
        );

        if (!$ficha) {
            throw new InvalidArgumentException(
                'Ficha não encontrada ou pertencente a outro usuário.'
            );
        }

        if ($pvAtual > (int) $ficha['pv_maximo']) {
            throw new InvalidArgumentException(
                'O PV atual não pode ultrapassar o PV máximo.'
            );
        }

        $sql = "
        UPDATE ficha
        SET
            nome = :nome,
            pv_atual = :pv_atual,
            fadiga = :fadiga
        WHERE id_ficha = :id_ficha
          AND id_usuario = :id_usuario
    ";

        $stmt = $this->conexao->prepare($sql);

        return $stmt->execute([
            ':nome' => $nomePersonagem,
            ':pv_atual' => $pvAtual,
            ':fadiga' => $fadiga,
            ':id_ficha' => $idFicha,
            ':id_usuario' => $idUsuario
        ]);
    }

    public function excluir(
        int $idFicha,
        int $idUsuario
    ): bool {
        $sql = "
            DELETE FROM ficha
            WHERE id_ficha = :id_ficha
              AND id_usuario = :id_usuario
        ";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            ':id_ficha' => $idFicha,
            ':id_usuario' => $idUsuario
        ]);

        return $stmt->rowCount() > 0;
    }

    private function buscarProfissaoParaCadastro(
        int $idProfissao,
        int $idUsuario
    ): array|false {
        $sql = "
            SELECT
                p.id_profissao,
                p.pv_maximo
            FROM profissao p
            INNER JOIN profissao_vantagem pv
                ON pv.id_profissao = p.id_profissao
            WHERE p.id_profissao = :id_profissao
              AND p.id_autor = :id_usuario
            GROUP BY
                p.id_profissao,
                p.pv_maximo
            HAVING COUNT(pv.id_vantagem) = 2
        ";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            ':id_profissao' => $idProfissao,
            ':id_usuario' => $idUsuario
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function buscarVantagens(
        int $idProfissao,
        int $idUsuario
    ): array {
        $sql = "
            SELECT
                pv.slot,
                v.id_vantagem,
                v.nome,
                v.descricao
            FROM profissao_vantagem pv
            INNER JOIN vantagem v
                ON v.id_vantagem = pv.id_vantagem
            WHERE pv.id_profissao = :id_profissao
              AND v.id_autor = :id_usuario
            ORDER BY pv.slot
        ";

        return $this->executarLista(
            $sql,
            $idProfissao,
            $idUsuario
        );
    }

    private function buscarArmas(
        int $idProfissao,
        int $idUsuario
    ): array {
        $sql = "
            SELECT
                pa.slot,
                a.id_arma,
                a.nome,
                a.tipo,
                a.maos,
                a.dano,
                a.especial
            FROM profissao_arma pa
            INNER JOIN arma a
                ON a.id_arma = pa.id_arma
            WHERE pa.id_profissao = :id_profissao
              AND a.id_autor = :id_usuario
            ORDER BY pa.slot
        ";

        return $this->executarLista(
            $sql,
            $idProfissao,
            $idUsuario
        );
    }

    private function buscarVestimentas(
        int $idProfissao,
        int $idUsuario
    ): array {
        $sql = "
            SELECT
                pv.tipo_slot,
                v.id_vestimenta,
                v.nome,
                v.tipo,
                v.pontos_protecao_maximo,
                v.dano,
                v.elemento,
                v.especial
            FROM profissao_vestimenta pv
            INNER JOIN vestimenta v
                ON v.id_vestimenta = pv.id_vestimenta
            WHERE pv.id_profissao = :id_profissao
              AND v.id_autor = :id_usuario
            ORDER BY pv.tipo_slot
        ";

        return $this->executarLista(
            $sql,
            $idProfissao,
            $idUsuario
        );
    }

    private function buscarItens(
        int $idProfissao,
        int $idUsuario
    ): array {
        $sql = "
            SELECT
                pi.quantidade,
                i.id_item,
                i.nome,
                i.tipo,
                i.descricao
            FROM profissao_item pi
            INNER JOIN item i
                ON i.id_item = pi.id_item
            WHERE pi.id_profissao = :id_profissao
              AND i.id_autor = :id_usuario
              AND pi.quantidade > 0
            ORDER BY i.nome
        ";

        return $this->executarLista(
            $sql,
            $idProfissao,
            $idUsuario
        );
    }

    /*
     * Retorna as magias disponíveis nas vantagens da profissão.
     *
     * Essas magias são opções. Elas não são automaticamente
     * adicionadas à ficha.
     */
    private function buscarMagias(
        int $idProfissao,
        int $idUsuario
    ): array {
        $sql = "
            SELECT DISTINCT
                m.id_magia,
                m.nome,
                m.elemento,
                m.descricao
            FROM profissao_vantagem pv
            INNER JOIN vantagem_magia vm
                ON vm.id_vantagem = pv.id_vantagem
            INNER JOIN magia m
                ON m.id_magia = vm.id_magia
            WHERE pv.id_profissao = :id_profissao
              AND m.id_autor = :id_usuario
            ORDER BY m.nome
        ";

        return $this->executarLista(
            $sql,
            $idProfissao,
            $idUsuario
        );
    }

    private function buscarCompanheiros(
        int $idProfissao,
        int $idUsuario
    ): array {
        $sql = "
            SELECT DISTINCT
                c.id_companheiro,
                c.tipo,
                c.pv_maximo,
                c.dano,
                c.descricao
            FROM profissao_vantagem pv
            INNER JOIN vantagem_companheiro vc
                ON vc.id_vantagem = pv.id_vantagem
            INNER JOIN companheiro_animal c
                ON c.id_companheiro = vc.id_companheiro
            WHERE pv.id_profissao = :id_profissao
              AND c.id_autor = :id_usuario
            ORDER BY c.tipo
        ";

        return $this->executarLista(
            $sql,
            $idProfissao,
            $idUsuario
        );
    }

    private function executarLista(
        string $sql,
        int $idProfissao,
        int $idUsuario
    ): array {
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            ':id_profissao' => $idProfissao,
            ':id_usuario' => $idUsuario
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     * Valida a magia escolhida.
     */
    private function validarMagia(
        array $magias,
        ?int $idMagia
    ): ?array {
        if (count($magias) === 0) {
            if ($idMagia !== null) {
                throw new InvalidArgumentException(
                    'A profissão escolhida não concede magia inicial.'
                );
            }

            return null;
        }

        /*
         * Se só existir uma magia disponível, ela pode ser
         * selecionada automaticamente.
         */
        if (count($magias) === 1 && $idMagia === null) {
            $idMagia = (int) $magias[0]['id_magia'];
        }

        if ($idMagia === null) {
            throw new InvalidArgumentException(
                'Escolha uma magia inicial.'
            );
        }

        foreach ($magias as $magia) {
            if ((int) $magia['id_magia'] === $idMagia) {
                return $magia;
            }
        }

        throw new InvalidArgumentException(
            'A magia escolhida não pertence às opções da profissão.'
        );
    }

    private function validarCompanheiro(
        array $companheiros,
        ?int $idCompanheiro,
        string $nomeCompanheiro
    ): ?array {
        if (count($companheiros) === 0) {
            if ($idCompanheiro !== null || $nomeCompanheiro !== '') {
                throw new InvalidArgumentException(
                    'A profissão escolhida não concede companheiro.'
                );
            }

            return null;
        }

        if (count($companheiros) === 1 && $idCompanheiro === null) {
            $idCompanheiro = (int) $companheiros[0]['id_companheiro'];
        }

        if ($idCompanheiro === null) {
            throw new InvalidArgumentException(
                'Escolha um companheiro.'
            );
        }

        if ($nomeCompanheiro === '') {
            throw new InvalidArgumentException(
                'Informe o nome do companheiro.'
            );
        }

        if (strlen($nomeCompanheiro) > 100) {
            throw new InvalidArgumentException(
                'O nome do companheiro deve possuir no máximo 100 caracteres.'
            );
        }

        foreach ($companheiros as $companheiro) {
            if (
                (int) $companheiro['id_companheiro']
                === $idCompanheiro
            ) {
                return $companheiro;
            }
        }

        throw new InvalidArgumentException(
            'O companheiro escolhido não pertence às opções da profissão.'
        );
    }

    private function copiarArmas(
        int $idFicha,
        int $idProfissao,
        int $idUsuario
    ): void {
        $sql = "
            INSERT INTO ficha_arma (
                id_ficha,
                slot,
                id_arma
            )
            SELECT
                :id_ficha,
                pa.slot,
                pa.id_arma
            FROM profissao_arma pa
            INNER JOIN arma a
                ON a.id_arma = pa.id_arma
            WHERE pa.id_profissao = :id_profissao
              AND a.id_autor = :id_usuario
        ";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            ':id_ficha' => $idFicha,
            ':id_profissao' => $idProfissao,
            ':id_usuario' => $idUsuario
        ]);
    }

    private function copiarVestimentas(
        int $idFicha,
        int $idProfissao,
        int $idUsuario
    ): void {
        $sql = "
            INSERT INTO ficha_vestimenta (
                id_ficha,
                tipo_slot,
                id_vestimenta,
                pontos_protecao_atual
            )
            SELECT
                :id_ficha,
                pv.tipo_slot,
                pv.id_vestimenta,
                v.pontos_protecao_maximo
            FROM profissao_vestimenta pv
            INNER JOIN vestimenta v
                ON v.id_vestimenta = pv.id_vestimenta
            WHERE pv.id_profissao = :id_profissao
              AND v.id_autor = :id_usuario
        ";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            ':id_ficha' => $idFicha,
            ':id_profissao' => $idProfissao,
            ':id_usuario' => $idUsuario
        ]);
    }

    private function copiarItens(
        int $idFicha,
        int $idProfissao,
        int $idUsuario
    ): void {
        $sql = "
            INSERT INTO ficha_item (
                id_ficha,
                id_item,
                quantidade
            )
            SELECT
                :id_ficha,
                pi.id_item,
                pi.quantidade
            FROM profissao_item pi
            INNER JOIN item i
                ON i.id_item = pi.id_item
            WHERE pi.id_profissao = :id_profissao
              AND i.id_autor = :id_usuario
              AND pi.quantidade > 0
        ";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            ':id_ficha' => $idFicha,
            ':id_profissao' => $idProfissao,
            ':id_usuario' => $idUsuario
        ]);
    }

    /*
     * Registra somente a magia escolhida pelo jogador.
     */
    private function cadastrarMagiaInicial(
        int $idFicha,
        array $magia
    ): void {
        $sql = "
            INSERT INTO ficha_magia (
                id_ficha,
                id_magia
            ) VALUES (
                :id_ficha,
                :id_magia
            )
        ";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            ':id_ficha' => $idFicha,
            ':id_magia' => $magia['id_magia']
        ]);
    }

    private function cadastrarCompanheiroNaFicha(
        int $idFicha,
        array $companheiro,
        string $nomeCompanheiro
    ): void {
        $sql = "
            INSERT INTO ficha_companheiro (
                id_ficha,
                id_companheiro,
                nome,
                pv_atual
            ) VALUES (
                :id_ficha,
                :id_companheiro,
                :nome,
                :pv_atual
            )
        ";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            ':id_ficha' => $idFicha,
            ':id_companheiro' => $companheiro['id_companheiro'],
            ':nome' => $nomeCompanheiro,
            ':pv_atual' => $companheiro['pv_maximo']
        ]);
    }

    private function buscarArmasDaFicha(
        int $idFicha
    ): array {
        $sql = "
        SELECT
            fa.slot,
            a.id_arma,
            a.nome,
            a.tipo,
            a.maos,
            a.dano,
            a.especial
        FROM ficha_arma fa
        INNER JOIN arma a
            ON a.id_arma = fa.id_arma
        WHERE fa.id_ficha = :id_ficha
        ORDER BY fa.slot
    ";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            ':id_ficha' => $idFicha
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function buscarVestimentasDaFicha(
        int $idFicha
    ): array {
        $sql = "
        SELECT
            fv.tipo_slot,
            fv.pontos_protecao_atual,
            v.id_vestimenta,
            v.nome,
            v.tipo,
            v.pontos_protecao_maximo,
            v.dano,
            v.elemento,
            v.especial
        FROM ficha_vestimenta fv
        INNER JOIN vestimenta v
            ON v.id_vestimenta = fv.id_vestimenta
        WHERE fv.id_ficha = :id_ficha
        ORDER BY fv.tipo_slot
    ";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            ':id_ficha' => $idFicha
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function buscarItensDaFicha(
        int $idFicha
    ): array {
        $sql = "
        SELECT
            fi.quantidade,
            i.id_item,
            i.nome,
            i.tipo,
            i.descricao
        FROM ficha_item fi
        INNER JOIN item i
            ON i.id_item = fi.id_item
        WHERE fi.id_ficha = :id_ficha
        ORDER BY i.nome
    ";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            ':id_ficha' => $idFicha
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function buscarMagiasDaFicha(
        int $idFicha
    ): array {
        $sql = "
        SELECT
            m.id_magia,
            m.nome,
            m.elemento,
            m.descricao
        FROM ficha_magia fm
        INNER JOIN magia m
            ON m.id_magia = fm.id_magia
        WHERE fm.id_ficha = :id_ficha
        ORDER BY m.nome
    ";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            ':id_ficha' => $idFicha
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function buscarCompanheiroDaFicha(
        int $idFicha
    ): ?array {
        $sql = "
        SELECT
            fc.id_companheiro,
            fc.nome,
            fc.pv_atual,
            c.tipo,
            c.pv_maximo,
            c.dano,
            c.descricao
        FROM ficha_companheiro fc
        INNER JOIN companheiro_animal c
            ON c.id_companheiro = fc.id_companheiro
        WHERE fc.id_ficha = :id_ficha
    ";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            ':id_ficha' => $idFicha
        ]);

        $companheiro = $stmt->fetch(PDO::FETCH_ASSOC);

        return $companheiro ?: null;
    }
}