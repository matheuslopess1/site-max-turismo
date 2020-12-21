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
            header("Location: bancos_editar.php?id=$id");
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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous" />
    </head>
    <body class="bg-secondary">
        <div class="container bg-white min-vh-100">
            <h1 class="text-center py-5">ML App</h1>
            <?php include_once("componentes/nav.php"); ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Bancos</h2>
                <div class="nav">
                    <a class="nav-link" href="/painel/bancos_listagem.php">Listagem</a>
                    <a class="nav-link" href="/painel/bancos_criar.php">Criar</a>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h3 class="mb-0">Edição</h2>
                <div class="nav">
                    <a class="nav-link" href="/painel/bancos_detalhe.php?id=<?= $id ?>">Detalhe</a>
                </div>
            </div>
            <form class="row g-3" method="POST">
                <?php if (isset($erro)) { ?>
                    <div class="text-danger"><?= $erro ?></div>
                <?php } ?>
                <div class="col-md-2">
                    <label class="form-label" for="codigo">Código*</label>
                    <input class="form-control" type="number" id="codigo" name="codigo" min="1" max="999" value="<?= $codigo ?>" required />
                </div>
                <div class="col-md-8">
                    <label class="form-label" for="nome">Nome*</label>
                    <input class="form-control" type="text" id="nome" name="nome" maxlength="50" value="<?= $nome ?>" required autofocus />
                </div>
                <div class="col-md-2 pt-md-4">
                    <div class="d-grid mt-md-2">
                        <button class="btn btn-primary" type="submit">Criar</button>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>