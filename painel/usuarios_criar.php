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
        <h3>Criação</h3>
        <form method="POST">
            <?php if (isset($erro)) { ?>
                <p style="color: red;"><?= $erro ?></p>
            <?php } ?>
            <p>
                <label for="nome">Nome*</label>
                <input type="text" id="nome" name="nome" maxlength="50" required autofocus />
            </p>
            <p>
                <label for="email">Email*</label>
                <input type="email" id="email" name="email" maxlength="100" required />
            </p>
            <p>
                <label for="senha">Senha*</label>
                <input type="password" id="senha" name="senha" minlength="6" maxlength="18" required />
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
            <button type="submit">Criar</button>
        </form>
    </body>
</html>