<?php
    require_once("utils/session_start.php");
    require_once("utils/authenticated_page.php");
    require_once("utils/mysqli_connection.php");

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (!isset($_POST["nome"])) {
            $erro = "Campo Nome é obrigatório";
        } else if (!isset($_POST["codigo"])) {
            $erro = "Campo Código é obrigatório";
        } else {
            $nome = trim($_POST["nome"]);
            $codigo = $_POST["codigo"];
            if ($nome === "") {
                $erro = "Campo Nome não pode estar vazio";
            } else if ($codigo === "") {
                $erro = "Campo Código não pode estar vazio";
            } else if (strlen($nome) > 50) {
                $erro = "Campo Nome não pode ser maior que 50 caracteres";
            } else if (!is_numeric($codigo)) {
                $erro = "Campo Código deve conter apenas números";
            } else {
                $codigo = intval($codigo);
                if ($codigo < 1 || intval($codigo) > 999) {
                    $erro = "Campo Código deve estar entre 1 e 999";
                } else {
                    $sql = "SELECT COUNT(*) AS count FROM bancos WHERE codigo = ?";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("i", $codigo);
                    $stmt->execute();
                    $stmt->bind_result($count);
                    $stmt->fetch();
                    $stmt->close();
                    if ($count !== 0) {
                        $erro = "Já existe um banco com este código";
                    } else {
                        $sql = "INSERT INTO bancos (nome, codigo) VALUES (?, ?)";
                        $stmt = $mysqli->prepare($sql);
                        $stmt->bind_param("si", $nome, $codigo);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
            }
        }
    }

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
        <h1>ML App</h2>
        <h2>Bancos</h2>
        <?php include_once("components/nav.php"); ?>
        <form method="POST">
            <?php if (isset($erro)) { ?>
                <p style="color: red;"><?= $erro ?></p>
            <?php } ?>
            <p>
                <label for="nome" style="display: block;">Nome<span style="color: red;">*</span></label>
                <input type="text" id="nome" name="nome" maxlength="100" required />
            </p>
            <p>
                <label for="codigo" style="display: block;">Código<span style="color: red;">*</span></label>
                <input type="number" id="codigo" name="codigo" min="1" max="999" required />
            </p>
            <p>
                <button type="submit">Cadastrar</button>
            </p>
        </form>
        <table>
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Nome</td>
                    <td>Código</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bancos as $banco) { ?>
                    <tr>
                        <td><?= $banco["id"] ?></td>
                        <td><?= $banco["nome"] ?></td>
                        <td><?= $banco["codigo"] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </body>
</html>