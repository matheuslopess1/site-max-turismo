<?php
    include_once("componentes/sessao.php");
    include_once("componentes/apenas_autenticado.php");
    include_once("componentes/apenas_admin.php");
    include_once("componentes/db.php");
    $id = $_GET["id"];
    $stmt = $mysqli->prepare("SELECT nome, codigo FROM bancos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $banco = $result->fetch_assoc();
    $result->free();
    $stmt->close();
    $stmt = $mysqli->prepare("SELECT id, banco_id, codigo FROM agencias WHERE banco_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $agencias = $resultado->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Detalhe de um Banco</title>
    </head>
    <body>
        <h1>ML App</h1>
        <?php include_once("componentes/nav.php"); ?>
        <h2>Bancos</h2>
        <ul>
            <li><a href="/painel/bancos_listagem.php">Listagem</a></li>
            <li><a href="/painel/bancos_criar.php">Criar</a></li>
        </ul>
        <h3>Detalhe</h3>
        <table>
            <tbody>
                <tr>
                    <th>#</th>
                    <td><?= $id ?></td>
                </tr>
                <tr>
                    <th>Nome</th>
                    <td><?= $banco["nome"] ?></td>
                </tr>
                <tr>
                    <th>Código</th>
                    <td><?= $banco["codigo"] ?></td>
                </tr>
            </tbody>
        </table>
        <h4>Agências</h4>
        <?php if ($agencias) { ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Código</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agencias as $agencia) { ?>
                        <tr>
                            <td><?= $agencia["id"] ?></td>
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
        <?php } else { ?>
            <p>Não há agências cadastradas</p>
        <?php } ?>
    </body>
</html>