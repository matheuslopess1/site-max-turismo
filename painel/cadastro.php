<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $mysqli = new mysqli("127.0.0.1", "u351998101_matheus", "o0/?E&Ec>qQ", "u351998101_maxturismo");
        $stmt = $mysqli->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $nome = trim($_POST["nome"]);
        $email = trim($_POST["email"]);
        $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
        $stmt->bind_param("sss", $nome, $email, $senha);
        $stmt->execute();
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
        <h2>Cadastro</h2>
        <form method="POST">
            <p>
                <label>Nome</label>
                <input name="nome" required />
            </p>
            <p>
                <label>Email</label>
                <input name="email" required />
            </p>
            <p>
                <label>Senha</label>
                <input name="senha" required />
            </p>
            <button type="submit">Cadastrar</button>
        </form>
    </body>
</html>