<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    if (!isset($_SESSION["autenticado"])) {
        header("Location: login.php");
        exit();
    }
    $mysqli = new mysqli("127.0.0.1", "u351998101_matheus", "o0/?E&Ec>qQ", "u351998101_maxturismo");
    $resultado = $mysqli->query("SELECT id, nome, codigo FROM bancos");
    $bancos = $resultado->fetch_all(MYSQLI_ASSOC);
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
        <h2>Bancos</h2>
        <ul>
            <li><a href="/painel/bancos_listagem.php">Listagem</a></li>
            <li><a href="/painel/bancos_criar.php">Criar</a></li>
        </ul>
        <h3>Listagem</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Código</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bancos as $banco) { ?>
                    <tr>
                        <td><?= $banco["id"] ?></td>
                        <td><?= $banco["nome"] ?></td>
                        <td><?= $banco["codigo"] ?></td>
                        <td>
                            <a href="/painel/bancos_editar.php?id=<?= $banco["id"] ?>">Editar</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </body>
</html>