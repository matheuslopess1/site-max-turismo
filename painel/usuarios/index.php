<?php
    if (!isset($_SESSION)) session_start();
    if (!isset($_SESSION["autenticado"])) {
        header("Location: /painel/login/?voltar_para=/painel/usuarios/");
        exit();
    }

    $mysqli = new mysqli("127.0.0.1", "u351998101_matheus", "o0/?E&Ec>qQ", "u351998101_maxturismo");
    $result = $mysqli->query("SELECT id, nome, email FROM usuarios");
    $usuarios = $result->fetch_all(MYSQLI_ASSOC);
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Usuários</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css" />
    </head>
    <body>
        <div class="has-background-white-ter" style="height: 100vw;">
            <?php include_once("../_componentes/nav.php"); ?>
            <div class="columns">
                <div class="column is-3">
                    <?php include_once("../_componentes/menu.php") ?>
                </div>
                <div class="column is-9">
                    <div class="container">
                        <h1 class="is-size-4 mb-3">Usuários</h1>
                        <div class="box">
                            <h2 class="is-size-5 mb-3">Listagem</h2>
                            <table class="table is-fullwidth">
                                <thead>
                                    <tr>
                                        <td>ID</td>
                                        <td>Nome</td>
                                        <td>Email</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($usuarios as $usuario) { ?>
                                        <tr>
                                            <td><?= $usuario["id"] ?></td>
                                            <td><?= $usuario["nome"] ?></td>
                                            <td><?= $usuario["email"] ?></td>
                                            <td>
                                                <a
                                                    class="button is-link"
                                                    href="/painel/usuarios/editar/?id=<?= $usuario["id"] ?>"
                                                >
                                                    Editar
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>