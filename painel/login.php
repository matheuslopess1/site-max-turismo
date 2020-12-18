<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    if (isset($_SESSION["autenticado"])) {
        header("Location: index.php");
        exit();
    }
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = $_POST["email"];
        $senha = $_POST["senha"];
        $mysqli = new mysqli("127.0.0.1", "u351998101_matheus", "o0/?E&Ec>qQ", "u351998101_maxturismo");
        $stmt = $mysqli->prepare("SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows !== 0) {
            $linha = $resultado->fetch_assoc();
            if (password_verify($senha, $linha["senha"])) {
                $_SESSION["autenticado"] = true;
                $_SESSION["usuario"] = ["id" => $linha["id"], "nome" => $linha["nome"], "email" => $linha["email"], "tipo" => $linha["tipo"]];
                header("Location: index.php");
                exit();
            }
            $resultado->free();
        }
        $stmt->close();
        $mysqli->close();
        $erro = "Email e/ou senha inválido(s)";
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
        <h2>Login</h2>
        <form method="POST">
            <?php if (isset($erro)) { ?>
                <p style="color: red;"><?= $erro ?></p>
            <?php } ?>
            <p>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required />
            </p>
            <p>
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required />
            </p>
            <button type="submit">Entrar</button>
        </form>
    </body>
</html>