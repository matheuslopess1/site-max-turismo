<?php
    ini_set("display_errors", "1");
    ini_set("display_startup_errors", "1");
    error_reporting(E_ALL);
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
    $id = $_GET["id"];
    $mysqli = new mysqli("127.0.0.1", "u351998101_matheus", "o0/?E&Ec>qQ", "u351998101_maxturismo");
    $stmt = $mysqli->prepare("SELECT nome, codigo FROM bancos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($nome, $codigo);
    $stmt->fetch();
    $stmt->close();
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $inserir = true;
        if ($codigo !== $_POST["codigo"]) {
            $codigo = $_POST["codigo"];
            $stmt = $mysqli->prepare("SELECT COUNT(*) AS contagem FROM bancos WHERE codigo = ?");
            $stmt->bind_param("i", $codigo);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($contagem);
            $stmt->fetch();
            if ($contagem !== 0) {
                $inserir = false;
            }
        }
        if ($inserir) {
            $nome = $_POST["nome"];
            $stmt = $mysqli->prepare("UPDATE bancos SET nome = ?, codigo = ? WHERE id = ?");
            $stmt->bind_param("sii", $nome, $codigo, $id);
            $stmt->execute();
            header("Location: bancos_listagem.php");
            exit();
        }
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
        <h3>Edição</h3>
        <form method="POST">
            <?php if (isset($erro)) { ?>
                <p style="color: red;"><?= $erro ?></p>
            <?php } ?>
            <p>
                <label id="nome">Nome</label>
                <input type="text" id="nome" name="nome" maxlength="50" value="<?= $nome ?>" required autofocus />
            </p>
            <p>
                <label id="codigo">Código</label>
                <input type="number" id="codigo" name="codigo" min="1" max="999" value="<?= $codigo ?>" required />
            </p>
            <button type="submit">Criar</button>
        </form>
    </body>
</html>