<?php
    if (!isset($_SESSION)) session_start();
    if (!isset($_SESSION["autenticado"])) {
        header("Location: /painel/login/?voltar_para=/painel/bancos/editar/");
        exit();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Dashboard</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css" />
    </head>
    <body>
        <div class="has-background-white-ter" style="height: 100vw;">
            <?php include_once("_componentes/nav.php"); ?>
            <div class="container">
                <h1 class="is-size-4 mb-3">Dashboard</h1>
                <div class="box">
                    <h2 class="is-size-5 mb-3">Dados de Sessão</h2>
                    <table class="table is-fullwidth">
                        <thead>
                            <tr>
                                <td>Nome</td>
                                <td>Email</td>
                                <td>Tipo</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?=$_SESSION["usuario"]["nome"] ?></td>
                                <td><?=$_SESSION["usuario"]["email"] ?></td>
                                <td><?=$_SESSION["usuario"]["tipo"] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>