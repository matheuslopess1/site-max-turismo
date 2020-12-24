<?php
    include_once("componentes/sessao.php");
    include_once("componentes/apenas_convidado.php");
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
        $erro = "O e-mail ou a senha digitados não estão corretos.";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous" />
    </head>
    <body class="bg-dark">
        <div class="container min-vh-100">
            <div class="row min-vh-100 align-items-center">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="card bg-white p-5">
                        <div class="card-body">
                            <h1 class="text-center mb-5">ML App</h1>
                            <form method="POST">
                                <?php if (isset($erro)) { ?>
                                    <div class="alert alert-danger text-center" role="alert">
                                        <small><?= $erro ?></small>
                                    </div>
                                <?php } ?>
                                <div class="mb-3">
                                    <input class="form-control form-control-lg" type="email" id="email" name="email" placeholder="Email" required />
                                </div>
                                <div class="mb-3">
                                    <input class="form-control form-control-lg" type="password" id="senha" name="senha" placeholder="Senha" required />
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-primary btn-lg" type="submit">Entrar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </body>
</html>