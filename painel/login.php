<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_SESSION["autenticado"])) {
        header("Location: index.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = trim($_POST["email"]);
        $senha = $_POST["senha"];
        $mysqli = new mysqli("127.0.0.1", "u351998101_matheus", "o0/?E&Ec>qQ", "u351998101_maxturismo");
        $stmt = $mysqli->prepare("SELECT nome, senha FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($nome, $senha_hasheada);
        if ($stmt->fetch()) {
            if (password_verify($senha, $senha_hasheada)) {
                $_SESSION["autenticado"] = true;
                $_SESSION["usuario"] = ["nome" => $nome, "email" => $email];
                header("Location: index.php");
                exit();
            }
        }
        $stmt->close();
        $mysqli->close();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <h1>ML App</h2>
        <h2>Login</h2>
        <form method="POST">
            <p>
                <label>Email</label>
                <input name="email" required />
            </p>
            <p>
                <label>Senha</label>
                <input name="senha" required />
            </p>
            <button type="submit">Entrar</button>
        </form>
    </body>
</html>