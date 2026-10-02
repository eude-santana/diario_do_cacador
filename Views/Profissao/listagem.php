<?php

$profissoes = $profissoes ?? [];

$mensagensSucesso = [
    "cadastro" => "Profissão cadastrada com sucesso.",
    "edicao" => "Profissão atualizada com sucesso.",
    "exclusao" => "Profissão excluída com sucesso."
];

$mensagensErro = [
    "id_invalido" => "A profissão informada é inválida.",
    "nao_encontrada" => "Profissão não encontrada.",
    "em_uso" => "A profissão está sendo utilizada por uma ficha.",
    "exclusao" => "Não foi possível excluir a profissão."
];

$codigoSucesso = $_GET["sucesso"] ?? "";
$codigoErro = $_GET["erro"] ?? "";

$mensagemSucesso =
    $mensagensSucesso[$codigoSucesso] ?? "";

$mensagemErro =
    $mensagensErro[$codigoErro] ?? "";

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profissões</title>
</head>

<body>
    <main>
        <h1>Profissões</h1>

        <?php if ($mensagemSucesso !== ""): ?>
            <p>
                <?= htmlspecialchars(
                    $mensagemSucesso,
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>
            </p>
        <?php endif; ?>

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
            <a href="/Controllers/ProfissaoController.php?acao=novo">
                Cadastrar nova profissão
            </a>

            <a href="/Controllers/ProfissaoController.php?acao=publicas">
                Ver profissões públicas
            </a>

            <a href="/Views/Usuario/painel.php">
                Voltar ao painel
            </a>
        </nav>

        <?php if (empty($profissoes)): ?>
            <p>
                Nenhuma profissão cadastrada.
            </p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>PV máximo</th>
                        <th>Visibilidade</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($profissoes as $profissao): ?>
                        <?php
                        $idProfissao =
                            (int) $profissao["id_profissao"];

                        $descricao =
                            $profissao["descricao"] ?? "";

                        $visibilidade =
                            $profissao["visibilidade"] ?? "PRIVADO";

                        $nomeVisibilidade =
                            $visibilidade === "PUBLICO"
                            ? "Público"
                            : "Privado";
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
                                <?= htmlspecialchars(
                                    $nomeVisibilidade,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>
                            </td>

                            <td>
                                <a href="/Controllers/ProfissaoController.php?acao=editar&id=<?= $idProfissao ?>">
                                    Editar
                                </a>

                                <form action="/Controllers/ProfissaoController.php" method="POST" style="display: inline;"
                                    onsubmit="return confirmarExclusao();">
                                    <input type="hidden" name="acao" value="excluir">

                                    <input type="hidden" name="id_profissao" value="<?= $idProfissao ?>">

                                    <button type="submit">
                                        Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

    <script>
        function confirmarExclusao() {
            return confirm(
                "Deseja realmente excluir esta profissão?"
            );
        }
    </script>
</body>

</html>