<?php
    include_once("componentes/sessao.php");
    include_once("componentes/apenas_convidado.php");
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = $_POST["email"];
        $senha = $_POST["senha"];

        include_once("componentes/db.php");

        $sql = "SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $query = $stmt->get_result();

        if ($query->num_rows != 0) {
            $usuario = $query->fetch_assoc();

            if (password_verify($senha, $usuario["senha"])) {
                $_SESSION = [
                    "autenticado" => true,
                    "usuario" => [
                        "id" => $usuario["id"],
                        "nome" => $usuario["nome"],
                        "email" => $usuario["email"],
                        "tipo" => $usuario["tipo"]
                    ]
                ];

                $query->free();
                $stmt->close();
                $mysqli->close();

                header("Location: index.php");
                exit();
            }
        }

        $query->free();
        $stmt->close();
        $mysqli->close();

        $erro = "O e-mail ou a senha digitados não estão corretos.";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Login</title>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1"
            crossorigin="anonymous"
        />
    </head>
    <body class="bg-dark">
        <?php if (isset($erro)) { ?>
            <div class="toast-container position-absolute top-0 end-0 p-3">
                <div class="toast" id="toast">
                    <div class="toast-header">
                        <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                        <strong class="me-auto">Erro</strong>
                        <button class="btn-close" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body">
                        <?= $erro ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="container min-vh-100">
            <div class="row min-vh-100 align-items-center">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="card bg-white p-5">
                        <div class="card-body">
                            <h1 class="text-center mb-5">ML App</h1>
                            <form method="POST">
                                <div class="mb-3">
                                    <input
                                        class="form-control form-control-lg"
                                        type="email"
                                        name="email"
                                        placeholder="Email"
                                        required
                                        autofocus
                                    />
                                </div>
                                <div class="mb-3">
                                    <input
                                        class="form-control form-control-lg"
                                        type="password"
                                        name="senha"
                                        placeholder="Senha"
                                        required
                                    />
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-primary btn-lg" type="submit">
                                        Entrar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
            crossorigin="anonymous"
        ></script>
        <?php if (isset($erro)) { ?>
            <script defer src="https://use.fontawesome.com/releases/v5.14.0/js/all.js"></script>
            <script>
                new bootstrap.Toast(document.getElementById("toast")).show();
            </script>
        <?php } ?>
    </body>
</html>