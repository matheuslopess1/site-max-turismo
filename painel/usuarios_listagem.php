<?php
    include_once("componentes/sessao.php");
    include_once("componentes/apenas_autenticado.php");
    include_once("componentes/apenas_admin.php");
    include_once("componentes/db.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST["nome"];
        $email = $_POST["email"];
        $senha = $_POST["senha"];
        $tipo = $_POST["tipo"];

        $stmt = $mysqli->prepare("SELECT COUNT(*) AS contagem FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($contagem);
        $stmt->fetch();

        if ($contagem === 0) {
            $senha_hasheada = password_hash($senha, PASSWORD_DEFAULT);
            $stmt->close();
            $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ssss", $nome, $email, $senha_hasheada, $tipo);
            $stmt->execute();
        } else {
            $erro = "Esse endereço de email já está em uso.";
        }

        $stmt->close();
    }

    $resultado = $mysqli->query("SELECT id, nome, email, tipo FROM usuarios");
    $usuarios = $resultado->fetch_all(MYSQLI_ASSOC);
    $resultado->free();
    $mysqli->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Listar Usuários</title>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1"
            crossorigin="anonymous"
        />
    </head>
    <body class="bg-light">
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
        <?php include_once("componentes/nav.php"); ?>
        <div class="container">
            <h2 class="mb-3">Usuários</h2>
            <div class="card mb-3">
                <h5 class="card-header">Crie um novo usuário</h5>
                <div class="card-body">
                <form class="row g-3" method="POST">
                    <div>
                        <label class="form-label" for="nome">
                            Nome<span class="text-danger">*</span>
                        </label>
                        <input
                            class="form-control"
                            type="text"
                            id="nome"
                            name="nome"
                            maxlength="50"
                            required
                            autofocus
                        />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="email">
                            Email<span class="text-danger">*</span>
                        </label>
                        <input
                            class="form-control"
                            type="email"
                            id="email"
                            name="email"
                            maxlength="100"
                            required
                        />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="senha">
                            Senha<span class="text-danger">*</span>
                        </label>
                        <input
                            class="form-control"
                            type="password"
                            id="senha"
                            name="senha"
                            minlength="6"
                            maxlength="18"
                            required
                        />
                    </div>
                    <div>
                        <label class="form-label">Tipo*</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    id="tipo_admin"
                                    name="tipo"
                                    value="ADMIN"
                                    required
                                />
                                <label class="form-check-label" for="tipo_admin">ADMIN</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    id="tipo_gerente"
                                    name="tipo"
                                    value="GERENTE"
                                    required
                                />
                                <label class="form-check-label" for="tipo_gerente">GERENTE</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    id="tipo_analista"
                                    name="tipo"
                                    value="ANALISTA"
                                    required
                                />
                                <label class="form-check-label" for="tipo_analista">ANALISTA</label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="d-grid d-md-block">
                            <button class="btn btn-primary" type="submit">Criar</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
            <div class="card">
                <h5 class="card-header">Gerenciar Usuários</h5>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Tipo</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $usuario) { ?>
                                    <tr>
                                        <td><?= $usuario["id"] ?></td>
                                        <td><?= $usuario["nome"] ?></td>
                                        <td><?= $usuario["email"] ?></td>
                                        <td><?= $usuario["tipo"] ?></td>
                                        <td>
                                            <a href="/painel/usuarios_editar.php?id=<?= $usuario["id"] ?>">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script defer src="https://use.fontawesome.com/releases/v5.14.0/js/all.js"></script>
        <?php if (isset($erro)) { ?>
            <script
                src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
                crossorigin="anonymous"
            ></script>
            <script>
                new bootstrap.Toast(document.getElementById("#toast")).show();
            </script>
        <?php } ?>
    </body>
</html>