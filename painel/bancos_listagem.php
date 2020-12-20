<?php
    include_once("componentes/sessao.php");
    include_once("componentes/apenas_autenticado.php");
    include_once("componentes/apenas_admin.php");
    include_once("componentes/db.php");
    $sql = "SELECT b.id, b.nome, b.codigo, (SELECT COUNT(*) FROM agencias WHERE banco_id = b.id) agencias FROM bancos b";
    $resultado = $mysqli->query($sql);
    $bancos = $resultado->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Listar Bancos</title>
    </head>
    <body>
        <h1>ML App</h1>
        <?php include_once("componentes/nav.php"); ?>
        <h2>Bancos</h2>
        <ul>
            <li><a href="/painel/bancos_listagem.php">Listagem</a></li>
            <li><a href="/painel/bancos_criar.php">Criar</a></li>
        </ul>
        <h3>Listagem</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Nº de Agências</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bancos as $banco) { ?>
                    <tr>
                        <td><?= $banco["id"] ?></td>
                        <td><?= $banco["codigo"] ?></td>
                        <td><?= $banco["nome"] ?></td>
                        <td><?= $banco["agencias"] ?></td>
                        <td>
                            <a href="/painel/bancos_detalhe.php?id=<?= $banco["id"] ?>">Detalhe</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </body>
</html>