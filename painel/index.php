<?php
    include_once("componentes/sessao.php");
    include_once("componentes/apenas_autenticado.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous" />
    </head>
    <body class="bg-light">
        <?php include_once("componentes/nav.php"); ?>
        <div class="container ">
            <h2>Dashboard</h2>
            <div class="bg-white py-3 px-2">
                Bem vindo. Utilize o menu acima para navegar.
            </div>
        </div>
    </body>
</html>