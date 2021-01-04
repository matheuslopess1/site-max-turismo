<?php
    include_once("componentes/sessao.php");
    include_once("componentes/apenas_autenticado.php");
    include_once("componentes/apenas_admin.php");
    include_once("componentes/db.php");

    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $stmt = $mysqli->prepare("SELECT nome, email, tipo FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $query = $stmt->get_result();
        $usuario = $query->fetch_assoc();
        $stmt->fetch();
        $stmt->close();
        $mysqli->close();
        echo json_encode($usuario);
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST["nome"];
        $email = $_POST["email"];
        $senha = $_POST["senha"];
        $tipo = $_POST["tipo"];

        if (!isset($_POST["id"])) {
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
        } else {
            $id = $_POST["id"];

            $sql = "SELECT COUNT(*) AS contagem FROM usuarios WHERE email = ? AND id <> ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $email, $id);
            $stmt->execute();
            $stmt->bind_result($contagem);
            $stmt->fetch();

            if ($contagem === 0) {
                $stmt->close();

                if (empty($senha)) {
                    $sql = "UPDATE usuarios SET nome = ?, email = ?, tipo = ? WHERE id = ?";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("sssi", $nome, $email, $tipo, $id);
                } else {
                    $senha_hasheada = password_hash($senha, PASSWORD_DEFAULT);

                    $sql = "UPDATE usuarios SET nome = ?, email = ?, senha = ?, tipo = ? WHERE id = ?";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("ssssi", $nome, $email, $senha_hasheada, $tipo, $id);
                }

                $stmt->execute();
            } else {
                $erro = "Esse endereço de email já está em uso.";
            }
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
        <div class="modal fade" id="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Editar Usuário</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" method="POST" id="formulario-editar">
                        <input type="hidden" name="id" />
                        <div>
                            <label class="form-label" for="nome-editar">
                                Nome<span class="text-danger">*</span>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                id="nome-editar"
                                name="nome"
                                maxlength="50"
                                required
                                autofocus
                            />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="email-editar">
                                Email<span class="text-danger">*</span>
                            </label>
                            <input
                                class="form-control"
                                type="email"
                                id="email-editar"
                                name="email"
                                maxlength="100"
                                required
                            />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="senha-editar">
                                Senha
                            </label>
                            <input
                                class="form-control"
                                type="password"
                                id="senha-editar"
                                name="senha"
                                minlength="6"
                                maxlength="18"
                            />
                        </div>
                        <div>
                            <label class="form-label">
                                Tipo<span class="text-danger">*</span>
                            </label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        id="tipo-admin-editar"
                                        name="tipo"
                                        value="ADMIN"
                                        required
                                    />
                                    <label class="form-check-label" for="tipo-admin-editar">
                                        ADMIN
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        id="tipo-gerente-editar"
                                        name="tipo"
                                        value="GERENTE"
                                        required
                                    />
                                    <label class="form-check-label" for="tipo-gerente-editar">
                                        GERENTE
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        id="tipo-analista-editar"
                                        name="tipo"
                                        value="ANALISTA"
                                        required
                                    />
                                    <label class="form-check-label" for="tipo-analista-editar">
                                        ANALISTA
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <input
                        class="btn btn-primary"
                        type="submit"
                        value="Salvar Mudanças"
                        form="formulario-editar"
                    />
                </div>
                </div>
            </div>
        </div>
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
                            <label class="form-label">
                                Tipo<span class="text-danger">*</span>
                            </label>
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
                                            <button
                                                class="btn btn-link"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal"
                                                data-bs-id="<?= $usuario["id"] ?>"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </button>
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
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
            crossorigin="anonymous"
        ></script>
        <script>
            <?php if (isset($erro)) { ?>
                new bootstrap.Toast(document.getElementById("#toast")).show();
            <?php } ?>

            const modal = document.getElementById("modal");
            modal.addEventListener("show.bs.modal", async function (event) {
                const id = event.relatedTarget.getAttribute("data-bs-id");
                const response = await fetch(window.location.href + "?id=" + id);
                const usuario = await response.json();
                modal.querySelector("input[name=id]").value = id;
                modal.querySelector("input[name=nome]").value = usuario.nome;
                modal.querySelector("input[name=email]").value = usuario.email;
                modal.querySelector(`input[value=${usuario.tipo}`).checked = true;
            });
        </script>
    </body>
</html>