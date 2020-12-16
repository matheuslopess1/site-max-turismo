<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    if (!isset($_SESSION["autenticado"])) {
        header("Location: login.php");
        exit();
    }
    if ($_SESSION["usuario"]["tipo"] !== "ADMIN") {
        header("Location: index.php");
        exit();
    }
    $mysqli = new mysqli("127.0.0.1", "u351998101_matheus", "o0/?E&Ec>qQ", "u351998101_maxturismo");
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
        <title>Dashboard</title>
    </head>
    <body>
        <h1>ML App</h1>
        <ul>
            <li><a href="/painel/index.php">Dashboard</a></li>
            <?php if ($_SESSION["usuario"]["tipo"] === "ADMIN") { ?>
                <li><a href="/painel/bancos_listagem.php">Bancos</a></li>
                <li><a href="/painel/usuarios_listagem.php">Usuários</a></li>
            <?php } ?>
        </ul>
        <ul>
            <li><?= $_SESSION["usuario"]["nome"] ?></li>
            <li><a href="/painel/logout.php">Sair</a></li>
        </ul>
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
    </body>
</html>