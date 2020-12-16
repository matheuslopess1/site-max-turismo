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
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nome = $_POST["nome"];
        $codigo = $_POST["codigo"];
        $mysqli = new mysqli("127.0.0.1", "u351998101_matheus", "o0/?E&Ec>qQ", "u351998101_maxturismo");
        $stmt = $mysqli->prepare("SELECT COUNT(*) AS contagem FROM bancos WHERE codigo = ?");
        $stmt->bind_param("i", $codigo);
        $stmt->execute();
        $stmt->bind_result($contagem);
        $stmt->fetch();
        if ($contagem === 0) {
            $stmt->close();
            $stmt = $mysqli->prepare("INSERT INTO bancos (nome, codigo) VALUES (?, ?)");
            $stmt->bind_param("si", $nome, $codigo);
            $stmt->execute();
            header("Location: bancos_listagem.php");
            exit();
        }
        $stmt->close();
        $mysqli->close();
        $erro = "Código já registrado";
    }
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
        <h3>Criação</h3>
        <form method="POST">
            <?php if (isset($erro)) { ?>
                <p style="color: red;"><?= $erro ?></p>
            <?php } ?>
            <p>
                <label id="nome">Nome</label>
                <input type="text" id="nome" name="nome" maxlength="50" required autofocus />
            </p>
            <p>
                <label id="codigo">Código</label>
                <input type="number" id="codigo" name="codigo" min="1" max="999" required />
            </p>
            <button type="submit">Criar</button>
        </form>
    </body>
</html>