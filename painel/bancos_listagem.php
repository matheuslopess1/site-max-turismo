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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous" />
    </head>
    <body class="bg-secondary">
        <div class="container bg-white min-vh-100">
            <h1 class="text-center py-5">ML App</h1>
            <?php include_once("componentes/nav.php"); ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Bancos</h2>
                <div class="nav">
                    <a class="nav-link" href="/painel/bancos_listagem.php">Listagem</a>
                    <a class="nav-link" href="/painel/bancos_criar.php">Criar</a>
                </div>
            </div>
            <h3>Listagem</h3>
            <div class="table-responsive">
                <table class="table">
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
                                    <a href="/painel/bancos_detalhe.php?id=<?= $banco["id"] ?>">
                                        Ver mais
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>