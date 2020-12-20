<?php
    include_once("componentes/sessao.php");
    include_once("componentes/apenas_autenticado.php");
    include_once("componentes/apenas_admin.php");
    include_once("componentes/db.php");
    $sql = "SELECT a.id, a.banco_id, a.codigo, b.nome FROM agencias a JOIN bancos b ON a.banco_id = b.id";
    $resultado = $mysqli->query($sql);
    $agencias = $resultado->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Listar Agências</title>
    </head>
    <body>
        <h1>ML App</h1>
        <ul>
            <li><a href="/painel/index.php">Dashboard</a></li>
            <?php if ($_SESSION["usuario"]["tipo"] === "ADMIN") { ?>
                <li><a href="/painel/usuarios_listagem.php">Usuários</a></li>
                <li><a href="/painel/bancos_listagem.php">Bancos</a></li>
                <li><a href="/painel/agencias_listagem.php">Agências</a></li>
            <?php } ?>
        </ul>
        <ul>
            <li><?= $_SESSION["usuario"]["nome"] ?></li>
            <li><a href="/painel/logout.php">Sair</a></li>
        </ul>
        <h2>Agências</h2>
        <ul>
            <li><a href="/painel/agencias_listagem.php">Listagem</a></li>
            <li><a href="/painel/agencias_criar.php">Criar</a></li>
        </ul>
        <h3>Listagem</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Banco</th>
                    <th>Código</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($agencias as $agencia) { ?>
                    <tr>
                        <td><?= $agencia["id"] ?></td>
                        <td><?= $agencia["nome"] ?> (<?= $agencia["banco_id"] ?>)</td>
                        <td><?= $agencia["codigo"] ?></td>
                        <td>
                            <a href="/painel/agencias_editar.php?id=<?= $agencia["id"] ?>">
                                Editar
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </body>
</html>