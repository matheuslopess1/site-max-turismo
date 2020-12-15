<?php
    require_once("utils/session_start.php");
    require_once("utils/unauthenticated_page.php");

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        require_once("utils/mysqli_connection.php");
        $email = trim($_POST["email"]);
        $senha = $_POST["senha"];
        $stmt = $mysqli->prepare("SELECT nome, senha, tipo FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($nome, $senha_hasheada, $tipo);
        if ($stmt->fetch()) {
            if (password_verify($senha, $senha_hasheada)) {
                $_SESSION["autenticado"] = true;
                $_SESSION["usuario"] = ["nome" => $nome, "email" => $email, "tipo" => $tipo];
                header("Location: index.php");
                exit();
            }
        }
        $erro = "E-mail e/ou senha inválido(s)";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Login</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css" />
    </head>
    <body>
        <div class="columns is-flex is-align-items-center" style="height: 100vh;">
            <div class="column"></div>
            <div class="column">
                <div class="box">
                    <h1 class="title is-4 has-text-centered">ML App</h2>
                    <form method="POST">
                        <?php if (isset($erro)) { ?>
                            <p style="color: red;"><?= $erro ?></p>
                        <?php } ?>
                        <div class="field">
                            <label for="email" class="label">Email</label>
                            <div class="control has-icons-left">
                                <input class="input" type="email" id="email" name="email" required autofocus />
                                <span class="icon is-small is-left">
                                    <i class="fas fa-envelope"></i>
                                </span>
                            </div>
                        </div>
                        <div class="field">
                            <label for="senha" class="label">Senha</label>
                            <div class="control has-icons-left">
                                <input class="input" type="password" id="senha" name="senha" required />
                                <span class="icon is-small is-left">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                <button class="button is-link" type="submit">Entrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="column"></div>
        </div>
        <script defer src="https://use.fontawesome.com/releases/v5.14.0/js/all.js"></script>
    </body>
</html>