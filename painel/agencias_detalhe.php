<?php
    include_once("componentes/sessao.php");
    include_once("componentes/apenas_autenticado.php");
    include_once("componentes/apenas_admin.php");
    include_once("componentes/db.php");
    $id = $_GET["id"];
    $stmt = $mysqli->prepare("SELECT a.banco_id, a.codigo, b.nome AS banco_nome FROM agencias a JOIN bancos b ON a.banco_id = b.id WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $agencia = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Detalhe de uma Agência</title>
    </head>
    <body>
        <h1>ML App</h1>
        <?php include_once("componentes/nav.php"); ?>
        <h2>Agências</h2>
        <ul>
            <li><a href="/painel/agencias_listagem.php">Listagem</a></li>
            <li><a href="/painel/agencias_criar.php">Criar</a></li>
        </ul>
        <h3>Detalhe</h3>
        <table>
            <tbody>
                <tr>
                    <th style="text-align: center;">#</th>
                    <td><?= $id ?></td>
                </tr>
                <tr>
                    <th style="text-align: center;">Código</th>
                    <td><?= $agencia["codigo"] ?></td>
                </tr>
                <tr>
                    <th style="text-align: center;">Banco</th>
                    <td>
                        <a href="/painel/bancos_detalhe.php?id=<?= $agencia["banco_id"] ?>">
                            <?= $agencia["banco_nome"] ?> (<?= $agencia["banco_id"] ?>)
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
        <h4>Ações</h4>
        <ul>
            <li>
                <a href="/painel/agencias_editar.php?id=<?= $id ?>">Editar</a>
            </li>
        </ul>
    </body>
</html>