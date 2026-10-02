<?php

$profissoesPublicas = $profissoesPublicas ?? [];

$mensagensErro = [
    "id_invalido" => "A profissão informada é inválida.",
    "nao_encontrada" => "A profissão pública não foi encontrada."
];

$codigoErro = $_GET["erro"] ?? "";
$mensagemErro = $mensagensErro[$codigoErro] ?? "";

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profissões públicas</title>
</head>

<body>
    <main>
        <h1>Profissões públicas</h1>

        <p>
            Estas profissões podem ser consultadas, mas somente seus
            autores podem editá-las ou utilizá-las em suas fichas.
        </p>

        <?php if ($mensagemErro !== ""): ?>
            <p>
                <?= htmlspecialchars(
                    $mensagemErro,
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>
            </p>
        <?php endif; ?>

        <nav>
            <a href="/Controllers/ProfissaoController.php?acao=listar">
                Minhas profissões
            </a>

            <a href="/Views/Usuario/painel.php">
                Voltar ao painel
            </a>
        </nav>

        <?php if (empty($profissoesPublicas)): ?>
            <p>
                Nenhuma profissão pública disponível.
            </p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Autor</th>
                        <th>Descrição</th>
                        <th>PV máximo</th>
                        <th>Ação</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach (
                        $profissoesPublicas as $profissao
                    ): ?>
                        <?php
                        $idProfissao =
                            (int) $profissao["id_profissao"];

                        $descricao =
                            $profissao["descricao"] ?? "";
                        ?>

                        <tr>
                            <td>
                                <?= htmlspecialchars(
                                    $profissao["nome"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $profissao["nome_autor"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>
                            </td>

                            <td>
                                <?php if ($descricao !== ""): ?>
                                    <?= nl2br(
                                        htmlspecialchars(
                                            $descricao,
                                            ENT_QUOTES,
                                            "UTF-8"
                                        )
                                    ) ?>
                                <?php else: ?>
                                    Sem descrição
                                <?php endif; ?>
                            </td>

                            <td>
                                <?= (int) $profissao["pv_maximo"] ?>
                            </td>

                            <td>
                                <a href="/Controllers/ProfissaoController.php?acao=visualizar&id=<?= $idProfissao ?>">
                                    Visualizar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>

</html>