<?php
    if (!isset($_SESSION)) session_start();
    if (!isset($_SESSION["autenticado"])) {
        header("Location: /painel/login/?voltar_para=/painel/bancos/");
        exit();
    }
    if ($_SESSION["usuario"]["tipo"] !== "ADMIN") {
        header("Location: /painel/login/?voltar_para=/painel/");
        exit();
    }

    $mysqli = new mysqli("127.0.0.1", "u351998101_matheus", "o0/?E&Ec>qQ", "u351998101_maxturismo");
    $result = $mysqli->query("SELECT id, nome, codigo FROM bancos");
    $bancos = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Bancos</title>
    </head>
    <body>
        <h1>ML App</h1>
        <ul>
            <li>
                <a href="/painel/">Página Inicial</a>
            </li>
            <li>
                <a href="/painel/usuarios/">Usuários</a>
            </li>
            <li>
                <a href="/painel/bancos/">Bancos</a>
            </li>
        </ul>
        <h2>Bancos</h2>
        <ul>
            <li>
                <a href="/painel/bancos/">Listar</a>
            </li>
            <li>
                <a href="/painel/bancos/criar/">Criar</a>
            </li>
        </ul>
        <h3>Listagem</h3>
        <table>
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Nome</td>
                    <td>Código</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bancos as $banco) { ?>
                    <tr>
                        <td><?= $banco["id"] ?></td>
                        <td><?= $banco["nome"] ?></td>
                        <td><?= $banco["codigo"] ?></td>
                        <td><a href="/painel/bancos/editar/?id=<?= $banco["id"] ?>">Editar</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </body>
</html>