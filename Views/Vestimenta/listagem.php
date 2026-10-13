<?php

require_once __DIR__ . "/../../Config/Autenticacao.php";

exigirLogin();

if (!isset($vestimentas)) {
    header("Location: /Controllers/VestimentaController.php?acao=listar");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&amp;family=Source+Sans+3:wght@400;600;700&amp;display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="/Assets/css/style.css">

    <title>
        Minhas vestimentas — Diário do Caçador
    </title>
</head>

<body>
    <main class="pagina pagina-listagem" aria-labelledby="titulo-pagina">
        <header class="cabecalho-com-acoes">
            <div class="cabecalho-pagina">
                <p class="cabecalho-pagina__marca">
                    Catálogo de campanha
                </p>

                <h1 class="cabecalho-pagina__titulo" id="titulo-pagina">
                    Minhas vestimentas
                </h1>

                <p class="cabecalho-pagina__descricao">
                    Organize armaduras, escudos e equipamentos de proteção
                    disponíveis para suas jornadas.
                </p>
            </div>

            <nav class="acoes-pagina" aria-label="Ações da página">
                <a class="botao botao--primario" href="/Views/Vestimenta/cadastro.php">
                    Cadastrar vestimenta
                </a>

                <a class="botao botao--secundario" href="/Views/Usuario/painel.php">
                    Voltar ao painel
                </a>
            </nav>
        </header>

        <?php if (($_GET["sucesso"] ?? "") === "atualizada"): ?>
            <div class="mensagem mensagem--sucesso" role="status">
                <p>Vestimenta atualizada com sucesso!</p>
            </div>

        <?php elseif (($_GET["sucesso"] ?? "") === "excluida"): ?>
            <div class="mensagem mensagem--sucesso" role="status">
                <p>Vestimenta excluída com sucesso!</p>
            </div>
        <?php endif; ?>

        <?php
        $erro = $_GET["erro"] ?? "";
        ?>

        <?php if ($erro === "vestimenta_nao_encontrada"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    Vestimenta não encontrada ou não pertence ao usuário.
                </p>
            </div>

        <?php elseif ($erro === "vestimenta_vinculada"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>
                    Esta vestimenta não pode ser excluída porque está
                    sendo utilizada por uma profissão ou ficha.
                </p>
            </div>

        <?php elseif ($erro === "dados"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Os dados informados são inválidos.</p>
            </div>

        <?php elseif ($erro === "exclusao"): ?>
            <div class="mensagem mensagem--erro" role="alert">
                <p>Não foi possível excluir a vestimenta.</p>
            </div>
        <?php endif; ?>

        <?php if (empty($vestimentas)): ?>
            <div class="estado-vazio">
                <p>Nenhuma vestimenta cadastrada.</p>

                <p>
                    Cadastre a primeira vestimenta para iniciar
                    este registro de equipamentos.
                </p>
            </div>
        <?php else: ?>
            <div class="tabela-responsiva">
                <table class="tabela-registro tabela-registro--muitas-colunas">
                    <caption>Registro de vestimentas</caption>

                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">PP máximo</th>
                            <th scope="col">Dano</th>
                            <th scope="col">Elemento</th>
                            <th scope="col">Especial</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($vestimentas as $vestimenta): ?>
                            <tr>
                                <td class="tabela-registro__nome" data-label="Nome">
                                    <?php
                                    echo htmlspecialchars(
                                        $vestimenta["nome"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );
                                    ?>
                                </td>

                                <td class="tabela-registro__tipo" data-label="Tipo">
                                    <?php
                                    $nomesTipos = [
                                        "ARMADURA" => "Armadura",
                                        "ELMO" => "Elmo",
                                        "BRACELETES" => "Braceletes",
                                        "BOTAS" => "Botas",
                                        "ESCUDO" => "Escudo"
                                    ];

                                    echo $nomesTipos[
                                        $vestimenta["tipo"]
                                    ];
                                    ?>
                                </td>

                                <td data-label="PP máximo">
                                    <?php
                                    echo (int) $vestimenta[
                                        "pontos_protecao_maximo"
                                    ];
                                    ?>
                                </td>

                                <td data-label="Dano">
                                    <?php if (($vestimenta["dano"] ?? "") !== ""): ?>
                                        <?php
                                        echo htmlspecialchars(
                                            $vestimenta["dano"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );
                                        ?>
                                    <?php else: ?>
                                        Não se aplica
                                    <?php endif; ?>
                                </td>

                                <td data-label="Elemento">
                                    <?php if (($vestimenta["elemento"] ?? "") !== ""): ?>
                                        <?php
                                        echo htmlspecialchars(
                                            $vestimenta["elemento"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );
                                        ?>
                                    <?php else: ?>
                                        Não se aplica
                                    <?php endif; ?>
                                </td>

                                <td class="tabela-registro__descricao" data-label="Especial">
                                    <?php
                                    echo nl2br(
                                        htmlspecialchars(
                                            $vestimenta["especial"] ?? "",
                                            ENT_QUOTES,
                                            "UTF-8"
                                        )
                                    );
                                    ?>
                                </td>

                                <td class="tabela-registro__acoes" data-label="Ações">
                                    <div class="acoes-registro">
                                        <a class="botao botao--secundario botao--pequeno" href="/Controllers/VestimentaController.php?acao=editar&id=<?php
                                        echo $vestimenta["id_vestimenta"];
                                        ?>">
                                            Editar
                                        </a>

                                        <form class="acoes-registro__formulario" action="/Controllers/VestimentaController.php"
                                            method="POST"
                                            onsubmit="return confirm('Deseja realmente excluir esta vestimenta?');">
                                            <input type="hidden" name="acao" value="excluir">

                                            <input type="hidden" name="id_vestimenta" value="<?php
                                            echo $vestimenta[
                                                "id_vestimenta"
                                            ];
                                            ?>">

                                            <button class="botao botao--perigo botao--pequeno" type="submit">
                                                Excluir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>