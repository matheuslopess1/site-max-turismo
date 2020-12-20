<?php
    include_once("componentes/sessao.php");
    include_once("componentes/apenas_autenticado.php");
    include_once("componentes/apenas_admin.php");
    include_once("componentes/db.php");
    $resultado = $mysqli->query("SELECT id, nome, email, tipo FROM usuarios");
    $usuarios = $resultado->fetch_all(MYSQLI_ASSOC);
    $resultado->free();
    $mysqli->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Listar Usuários</title>
    </head>
    <body class="bg-secondary">
        <div class="container bg-white vh-100">
            <h1 class="text-center py-5">ML App</h1>
            <?php include_once("componentes/nav.php"); ?>
            <h2>Usuários</h2>
            <ul>
                <li><a href="/painel/usuarios_listagem.php">Listagem</a></li>
                <li><a href="/painel/usuarios_criar.php">Criar</a></li>
            </ul>
            <h3>Listagem</h3>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario) { ?>
                        <tr>
                            <td><?= $usuario["id"] ?></td>
                            <td><?= $usuario["nome"] ?></td>
                            <td><?= $usuario["email"] ?></td>
                            <td><?= $usuario["tipo"] ?></td>
                            <td>
                                <a href="/painel/usuarios_editar.php?id=<?= $usuario["id"] ?>">
                                    Editar
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </body>
</html>