<?php
    include_once("componentes/sessao.php");
    include_once("componentes/apenas_autenticado.php");
    include_once("componentes/apenas_admin.php");
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nome = $_POST["nome"];
        $codigo = $_POST["codigo"];
        include_once("componentes/db.php");
        $stmt = $mysqli->prepare("SELECT COUNT(*) AS contagem FROM bancos WHERE codigo = ?");
        $stmt->bind_param("i", $codigo);
        $stmt->execute();
        $stmt->bind_result($contagem);
        $stmt->fetch();
        if ($contagem === 0) {
            $stmt->close();
            $stmt = $mysqli->prepare("INSERT INTO bancos (nome, codigo) VALUES (?, ?)");
            $stmt->bind_param("si", $nome, $codigo);
            $stmt->execute();
            header("Location: bancos_listagem.php");
            exit();
        }
        $stmt->close();
        $mysqli->close();
        $erro = "Código já registrado";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Criar Banco</title>
    </head>
    <body>
        <h1>ML App</h1>
        <?php include_once("componentes/nav.php"); ?>
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
                <label for="codigo">Código*</label>
                <input type="number" id="codigo" name="codigo" min="1" max="999" required />
            </p>
            <p>
                <label for="nome">Nome*</label>
                <input type="text" id="nome" name="nome" maxlength="50" required autofocus />
            </p>
            <button type="submit">Criar</button>
        </form>
    </body>
</html>