<?php
    require_once("utils/session_start.php");
    require_once("utils/authenticated_page.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Dashboard</title>
        <?php include_once("utils/bulma_link.php"); ?>
    </head>
    <body>
        <div class="has-background-white-ter" style="height: 100vw;">
            <?php include_once("components/nav.php"); ?>
            <div class="container">
                <h1 class="is-size-4 mb-3">Dashboard</h1>
                <div class="box">
                    <table class="table">
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