<?php

require_once __DIR__ . "/../../Config/Autenticacao.php";

exigirLogin();

if (!isset($vantagens)) {
    header("Location: /Controllers/VantagemController.php?acao=listar");
    exit;
}

$mensagensSucesso = [
    "cadastro" => "Vantagem cadastrada com sucesso.",
    "edicao" => "Vantagem atualizada com sucesso.",
    "exclusao" => "Vantagem excluída com sucesso."
];

$mensagensErro = [
    "id_invalido" => "A vantagem informada é inválida.",
    "nao_encontrada" => "Vantagem não encontrada.",
    "em_uso" => "A vantagem está sendo utilizada por uma profissão.",
    "exclusao" => "Não foi possível excluir a vantagem."
];

$tipos = [
    "NORMAL" => "Normal",
    "MAGIA" => "Concede magia",
    "COMPANHEIRO" => "Concede companheiro animal"
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

    <title>Vantagens</title>
</head>

<body>
    <main>
        <h1>Vantagens</h1>

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
            <a href="VantagemController.php?acao=novo">
                Cadastrar nova vantagem
            </a>

            <a href="../Views/Usuario/painel.php">
                Voltar ao painel
            </a>
        </nav>

        <?php if (empty($vantagens)): ?>
            <p>
                Nenhuma vantagem cadastrada.
            </p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Tipo</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($vantagens as $vantagem): ?>
                        <?php
                        $idVantagem =
                            (int) $vantagem["id_vantagem"];

                        $tipoVantagem =
                            $vantagem["tipo"] ?? "NORMAL";

                        $nomeTipo =
                            $tipos[$tipoVantagem]
                            ?? "Tipo desconhecido";
                        ?>

                        <tr>
                            <td>
                                <?= htmlspecialchars(
                                    $vantagem["nome"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>
                            </td>

                            <td>
                                <?php
                                $descricao =
                                    $vantagem["descricao"] ?? "";
                                ?>

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
                                <?= htmlspecialchars(
                                    $nomeTipo,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>
                            </td>

                            <td>
                                <a href="VantagemController.php?acao=editar&id=<?= $idVantagem ?>">
                                    Editar
                                </a>

                                <form action="VantagemController.php" method="POST" style="display: inline;"
                                    onsubmit="return confirmarExclusao();">
                                    <input type="hidden" name="acao" value="excluir">

                                    <input type="hidden" name="id_vantagem" value="<?= $idVantagem ?>">

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
                "Deseja realmente excluir esta vantagem?"
            );
        }
    </script>
</body>

</html>