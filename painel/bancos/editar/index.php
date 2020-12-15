<?php
    if (!isset($_SESSION)) session_start();
    if (!isset($_SESSION["autenticado"])) {
        header("Location: /painel/login/?voltar_para=/painel/bancos/editar/");
        exit();
    }
    if ($_SESSION["usuario"]["tipo"] !== "ADMIN") {
        header("Location: /painel/login/?voltar_para=/painel/");
        exit();
    }

    $id = $_GET["id"];
    $mysqli = new mysqli("127.0.0.1", "u351998101_matheus", "o0/?E&Ec>qQ", "u351998101_maxturismo");
    $sql = "SELECT nome, codigo FROM bancos WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) header("Location: /painel/login/") && exit();
    $stmt->bind_result($nome, $codigo);
    $stmt->fetch();
    $stmt->close();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nome = trim($_POST["nome"]);
        $codigo = $_POST["codigo"];

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
            header("Location: /painel/bancos/");
            exit();
        }
    }
?>
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
        <h3>Edição</h3>
        <form method="POST">
            <?php if (isset($erro)) { ?>
                <p style="color: red;"><?= $erro ?></p>
            <?php } ?>
            <p>
                <label for="nome" style="display: block;">Nome<span style="color: red;">*</span></label>
                <input type="text" id="nome" name="nome" maxlength="100" value="<?= $nome ?>" required />
            </p>
            <p>
                <label for="codigo" style="display: block;">Código<span style="color: red;">*</span></label>
                <input type="number" id="codigo" name="codigo" min="1" max="999" value="<?= $codigo ?>" required />
            </p>
            <p>
                <button type="submit">Editar</button>
            </p>
        </form>
    </body>
</html>