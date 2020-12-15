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
            <div class="columns">
                <div class="column is-3">
                    <div class="panel has-background-white">
                        <div class="panel-block">
                            <a class="button is-link" href="/painel/usuarios/">Listar</a>
                        </div>
                        <div class="panel-block">
                            <a class="button is-link" href="/painel/usuarios/criar/">Criar</a>
                        </div>
                    </div>
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