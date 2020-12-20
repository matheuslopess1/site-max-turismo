<?php
    include_once("componentes/sessao.php");
    include_once("componentes/apenas_autenticado.php");
    include_once("componentes/apenas_admin.php");
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = $_POST["email"];
        $senha = $_POST["senha"];
        include_once("componentes/db.php");
        $stmt = $mysqli->prepare("SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows !== 0) {
            $linha = $result->fetch_assoc();
            if (password_verify($senha, $linha["senha"])) {
                $_SESSION["autenticado"] = true;
                $_SESSION["usuario"] = ["id" => $linha["id"], "nome" => $linha["nome"], "email" => $linha["email"], "tipo" => $linha["tipo"]];
                header("Location: index.php");
                exit();
            }
        }
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