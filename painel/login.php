<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    if (isset($_SESSION["autenticado"])) {
        header("Location: index.php");
        exit();
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
            <p>
                <label id="email">Email</label>
                <input type="email" id="email" name="email" required />
            </p>
            <p>
                <label id="senha">Senha</label>
                <input type="password" id="senha" name="senha" required />
            </p>
            <button type="submit">Entrar</button>
        </form>
    </body>
</html>