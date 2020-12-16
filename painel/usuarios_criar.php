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
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nome = $_POST["nome"];
        $email = $_POST["email"];
        $senha = $_POST["senha"];
        $tipo = $_POST["tipo"];
        $mysqli = new mysqli("127.0.0.1", "u351998101_matheus", "o0/?E&Ec>qQ", "u351998101_maxturismo");
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
            header("Location: bancos_listagem.php");
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
        <title>Dashboard</title>
    </head>
    <body>
        <h1>ML App</h1>
        <ul>
            <li><a href="/painel/index.php">Dashboard</a></li>
            <?php if ($_SESSION["usuario"]["tipo"] === "ADMIN") { ?>
                <li><a href="/painel/bancos_listagem.php">Bancos</a></li>
                <li><a href="/painel/usuarios_listagem.php">Usuários</a></li>
            <?php } ?>
        </ul>
        <ul>
            <li><?= $_SESSION["usuario"]["nome"] ?></li>
            <li><a href="/painel/logout.php">Sair</a></li>
        </ul>
        <h2>Bancos</h2>
        <ul>
            <li><a href="/painel/bancos_listagem.php">Listagem</a></li>
            <li><a href="/painel/bancos_criar.php">Criar</a></li>
        </ul>
        <h3>Criação</h3>
        <form method="POST">
            <?php if (isset($erro)) { ?>
                <p style="color: red;"><?= $erro ?></p>
            <?php } ?>
            <p>
                <label id="nome">Nome</label>
                <input type="text" id="nome" name="nome" maxlength="50" required autofocus />
            </p>
            <p>
                <label id="email">Email</label>
                <input type="email" id="email" name="email" maxlength="100" required />
            </p>
            <p>
                <label id="senha">Senha</label>
                <input type="password" id="senha" name="senha" minlength="6" maxlength="18" required />
            </p>
            <p>
                <label id="tipo_admin">
                    <input type="radio" id="tipo_admin" name="tipo" value="ADMIN" required /> ADMIN
                </label>
                <label id="tipo_gerente">
                    <input type="radio" id="tipo_gerente" name="tipo" value="GERENTE" required /> GERENTE
                </label>
                <label id="tipo_analista">
                    <input type="radio" id="tipo_analista" name="tipo" value="ANALISTA" required /> ANALISTA
                </label>
            </p>
            <button type="submit">Criar</button>
        </form>
    </body>
</html>