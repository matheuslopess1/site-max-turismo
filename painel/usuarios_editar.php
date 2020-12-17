<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    if (!isset($_SESSION["autenticado"])) {
        header("Location: login.php");
        exit();
    }
    if ($_SESSION["usuario"]["tipo"] !== "ADMIN") {
        header("Location: index.php");
        exit();
    }
    $id = $_GET["id"];
    $mysqli = new mysqli("127.0.0.1", "u351998101_matheus", "o0/?E&Ec>qQ", "u351998101_maxturismo");
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
        <title>Dashboard</title>
    </head>
    <body>
    <h1>ML App</h1>
        <ul>
            <li><a href="/painel/index.php">Dashboard</a></li>
            <?php if ($_SESSION["usuario"]["tipo"] === "ADMIN") { ?>
                <li><a href="/painel/usuarios_listagem.php">Usuários</a></li>
                <li><a href="/painel/bancos_listagem.php">Bancos</a></li>
                <li><a href="/painel/agencias_listagem.php">Agências</a></li>
            <?php } ?>
        </ul>
        <ul>
            <li><?= $_SESSION["usuario"]["nome"] ?></li>
            <li><a href="/painel/logout.php">Sair</a></li>
        </ul>
        <h2>Usuários</h2>
        <ul>
            <li><a href="/painel/usuarios_listagem.php">Listagem</a></li>
            <li><a href="/painel/usuarios_criar.php">Criar</a></li>
        </ul>
        <h3>Edição</h3>
        <form method="POST">
            <?php if (isset($erro)) { ?>
                <p style="color: red;"><?= $erro ?></p>
            <?php } ?>
            <p>
                <label for="nome">Nome*</label>
                <input type="text" id="nome" name="nome" maxlength="50" value="<?= $nome ?>" required autofocus />
            </p>
            <p>
                <label for="email">Email*</label>
                <input type="email" id="email" name="email" maxlength="100" value="<?= $email ?>" required />
            </p>
            <p>
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" minlength="6" maxlength="18" />
            </p>
            <p>
                <label for="tipo_admin">
                    <input type="radio" id="tipo_admin" name="tipo" value="ADMIN" required /> ADMIN
                </label>
                <label for="tipo_gerente">
                    <input type="radio" id="tipo_gerente" name="tipo" value="GERENTE" required /> GERENTE
                </label>
                <label for="tipo_analista">
                    <input type="radio" id="tipo_analista" name="tipo" value="ANALISTA" required /> ANALISTA
                </label>
            </p>
            <button type="submit">Editar</button>
        </form>
        <script>
            document.getElementById("tipo_<?= strtolower($tipo) ?>").checked = true;
        </script>
    </body>
</html>