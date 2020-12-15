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
            <nav class="navbar is-dark mb-5">
                <div class="navbar-brand">
                    <a class="navbar-item is-size-4" href="/painel/">ML App</a>
                    <a class="navbar-burger" onclick="document.querySelector('.navbar-menu').classList.toggle('is-active');">
                        <span></span>
                        <span></span>
                        <span></span>
                    </a>
                </div>
                <div class="navbar-menu">
                    <div class="navbar-start">
                        <a class="navbar-item" href="/painel/">Página Inicial</a>
                        <?php if ($_SESSION["usuario"]["tipo"] === "ADMIN") { ?>
                            <a class="navbar-item" href="/painel/usuarios/">Usuários</a>
                            <a class="navbar-item" href="/painel/bancos/">Bancos</a>
                        <?php } ?>
                    </div>
                    <div class="navbar-end">
                        <span class="navbar-item"><?=$_SESSION["usuario"]["nome"] ?></a>
                        <a class="navbar-item has-text-danger" href="/painel/logout/">Sair</a>
                    </div>
                </div>
            </nav>
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