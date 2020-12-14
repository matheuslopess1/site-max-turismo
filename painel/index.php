<?php
    require_once("./utils/session_start.php");
    require_once("./utils/authenticated_page.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Dashboard</title>
    </head>
    <body>
        <h1>ML App</h2>
        <h2>Dashboard</h2>
        <?php include_once("./components/nav.php"); ?>
        <table>
            <thead>
                <tr>
                    <td>Nome</td>
                    <td>Email</td>
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
    </body>
</html>