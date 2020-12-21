<?php
    include_once("componentes/sessao.php");
    include_once("componentes/apenas_autenticado.php");
    include_once("componentes/apenas_admin.php");
    include_once("componentes/db.php");
    $id = $_GET["id"];
    $stmt = $mysqli->prepare("SELECT nome, email, tipo FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($nome, $email, $tipo);
    $stmt->fetch();
    $stmt->close();
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if ($email !== $_POST["email"]) {
            $email = $_POST["email"];
            $stmt = $mysqli->prepare("SELECT COUNT(*) AS contagem FROM usuarios WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($contagem);
            $stmt->fetch();
            if ($contagem !== 0) {
                $email_invalido = true;
            }
        }
        if (!isset($email_invalido)) {
            $nome = $_POST["nome"];
            $tipo = $_POST["tipo"];
            if ($_POST["senha"] !== "") {
                $sql = "UPDATE usuarios SET nome = ?, email = ?, senha = ?, tipo = ? WHERE id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("ssssi", $nome, $email, $senha, $tipo, $id);
            } else {
                $sql = "UPDATE usuarios SET nome = ?, email = ?, tipo = ? WHERE id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sssi", $nome, $email, $tipo, $id);
            }
            $stmt->execute();
            header("Location: usuarios_listagem.php");
            exit();
        }
        $erro = "Código já registrado";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Editar Usuário</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous" />
    </head>
    <body class="bg-secondary">
        <div class="container bg-white min-vh-100">
            <h1 class="text-center py-5">ML App</h1>
            <?php include_once("componentes/nav.php"); ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Usuários</h2>
                <div class="nav">
                    <a class="nav-link" href="/painel/usuarios_listagem.php">Listagem</a>
                    <a class="nav-link" href="/painel/usuarios_criar.php">Criar</a>
                </div>
            </div>
            <h3>Edição</h3>
            <form method="POST" class="row g-3">
                <?php if (isset($erro)) { ?>
                    <div class="text-danger mb-3"><?= $erro ?></div>
                <?php } ?>
                <div>
                    <label for="nome">Nome*</label>
                    <input type="text" id="nome" name="nome" maxlength="50" value="<?= $nome ?>" required autofocus />
                </div>
                <div class="col-md-6">
                    <label for="email">Email*</label>
                    <input type="email" id="email" name="email" maxlength="100" value="<?= $email ?>" required />
                </div>
                <div class="col-md-6">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" minlength="6" maxlength="18" />
                </div>
                <div>
                    <label class="form-label">Tipo*</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="tipo_admin" name="tipo" value="ADMIN" required />
                            <label class="form-check-label" for="tipo_admin">ADMIN</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="tipo_gerente" name="tipo" value="GERENTE" required />
                            <label class="form-check-label" for="tipo_gerente">GERENTE</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="tipo_analista" name="tipo" value="ANALISTA" required />
                            <label class="form-check-label" for="tipo_analista">ANALISTA</label>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-grid d-md-block">
                        <button class="btn btn-primary" type="submit">Editar</button>
                    </div>
                </div>
            </form>
        </div>
        <script>
            document.getElementById("tipo_<?= strtolower($tipo) ?>").checked = true;
        </script>
    </body>
</html>