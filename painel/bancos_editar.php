<?php
    include_once("componentes/sessao.php");
    include_once("componentes/apenas_autenticado.php");
    include_once("componentes/apenas_admin.php");
    include_once("componentes/db.php");
    $id = $_GET["id"];
    $stmt = $mysqli->prepare("SELECT nome, codigo FROM bancos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($nome, $codigo);
    $stmt->fetch();
    $stmt->close();
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $inserir = true;
        if ($codigo != $_POST["codigo"]) {
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
        <title>ML App - Editar Banco</title>
    </head>
    <body>
        <h1>ML App</h1>
        <?php include_once("componentes/nav.php"); ?>
        <h2>Bancos</h2>
        <ul>
            <li><a href="/painel/bancos_listagem.php">Listagem</a></li>
            <li><a href="/painel/bancos_criar.php">Criar</a></li>
        </ul>
        <h3>Edição</h3>
        <ul>
            <li><a href="/painel/bancos_detalhe.php?id=<?= $id ?>">Detalhe</a></li>
        </ul>
        <form method="POST">
            <?php if (isset($erro)) { ?>
                <p style="color: red;"><?= $erro ?></p>
            <?php } ?>
            <p>
                <label for="nome">Nome*</label>
                <input type="text" id="nome" name="nome" maxlength="50" value="<?= $nome ?>" required autofocus />
            </p>
            <p>
                <label for="codigo">Código*</label>
                <input type="number" id="codigo" name="codigo" min="1" max="999" value="<?= $codigo ?>" required />
            </p>
            <button type="submit">Editar</button>
        </form>
    </body>
</html>