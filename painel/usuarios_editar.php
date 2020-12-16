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
        $inserir = true;
        var_dump($_POST);
        exit();
        if ($_POST["senha"] === "") {
            $codigo = $_POST["codigo"];
            $stmt = $mysqli->prepare("SELECT COUNT(*) AS contagem FROM bancos WHERE codigo = ?");
            $stmt->bind_param("i", $codigo);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($contagem);
            $stmt->fetch();
            if ($contagem !== 0) {
                $inserir = false;
            }
        }
        if ($inserir) {
            $nome = $_POST["nome"];
            $stmt = $mysqli->prepare("UPDATE bancos SET nome = ?, codigo = ? WHERE id = ?");
            $stmt->bind_param("sii", $nome, $codigo, $id);
            $stmt->execute();
            header("Location: bancos_listagem.php");
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
                <li><a href="/painel/bancos_listagem.php">Bancos</a></li>
                <li><a href="/painel/usuarios_listagem.php">Usuários</a></li>
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
                <input type="password" id="senha" name="senha" minlength="6" maxlength="18" />
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
            <script>
                document.getElementById("tipo_<?= strtolower($tipo) ?>").checked = true;
            </script>
        </form>
    </body>
</html>