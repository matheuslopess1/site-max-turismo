<?php
    include_once("componentes/sessao.php");
    include_once("componentes/apenas_autenticado.php");
    include_once("componentes/apenas_admin.php");
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nome = $_POST["nome"];
        $email = $_POST["email"];
        $senha = $_POST["senha"];
        $tipo = $_POST["tipo"];
        include_once("componentes/db.php");
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
            header("Location: usuarios_listagem.php");
            exit();
        }
        $stmt->close();
        $mysqli->close();
        $erro = "Email já registrado";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Criar Usuário</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous" />
    </head>
    <body class="bg-secondary">
        <div class="bg-white vh-100">
            <div class="container">
                <h1 class="text-center py-5">ML App</h1>
                <?php include_once("componentes/nav.php"); ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="mb-0">Usuários</h2>
                    <div class="nav">
                        <a class="nav-link" href="/painel/usuarios_listagem.php">Listagem</a>
                        <a class="nav-link" href="/painel/usuarios_criar.php">Criar</a>
                    </div>
                </div>
                <h3>Criação</h3>
                <form method="POST">
                    <?php if (isset($erro)) { ?>
                        <div class="text-danger mb-3"><?= $erro ?></div>
                    <?php } ?>
                    <div class="mb-3">
                        <label class="form-label" for="nome">Nome*</label>
                        <input class="form-control" type="text" id="nome" name="nome" maxlength="50" required autofocus />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">Email*</label>
                        <input class="form-control" type="email" id="email" name="email" maxlength="100" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="senha">Senha*</label>
                        <input class="form-control" type="password" id="senha" name="senha" minlength="6" maxlength="18" required />
                    </div>
                    <div class="mb-3">
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
                    <button class="btn" type="submit">Criar</button>
                </form>
            </div>
        </div>
    </body>
</html>