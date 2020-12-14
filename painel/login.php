<?php
    require_once("./utils/session_start.php");
    require_once("./utils/unauthenticated_page.php");

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        require_once("./utils/mysqli_connection.php");
        $email = trim($_POST["email"]);
        $senha = $_POST["senha"];
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
        <title>ML App - Login</title>
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