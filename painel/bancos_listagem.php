<?php
    include_once("componentes/sessao.php");
    include_once("componentes/apenas_autenticado.php");
    include_once("componentes/apenas_admin.php");
    include_once("componentes/db.php");
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
        } else {
            $erro = "Código já registrado";
            $iniciar_modal_aberto = true;
        }
        $stmt->close();
        $mysqli->close();
    }
    $sql = "SELECT b.id, b.nome, b.codigo, (SELECT COUNT(*) FROM agencias WHERE banco_id = b.id) agencias FROM bancos b";
    $resultado = $mysqli->query($sql);
    $bancos = $resultado->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Listar Bancos</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous" />
    </head>
    <body class="bg-secondary">
        <div class="container bg-white min-vh-100">
            <h1 class="text-center py-5">ML App</h1>
            <?php include_once("componentes/nav.php"); ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Bancos</h2>
                <div class="nav">
                    <button class="nav-link btn btn-success" onclick="toggleModalCriacao()">
                        Criar
                    </button>
                </div>
            </div>
            <h3>Listagem</h3>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Nº de Agências</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bancos as $banco) { ?>
                            <tr>
                                <td><?= $banco["id"] ?></td>
                                <td><?= $banco["codigo"] ?></td>
                                <td><?= $banco["nome"] ?></td>
                                <td><?= $banco["agencias"] ?></td>
                                <td>
                                    <a class="btn btn-info" href="/painel/bancos_detalhe.php?id=<?= $banco["id"] ?>">
                                        Ver mais
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal fade" id="modal-criacao" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="h5 modal-title">Criação</h3>
                        <button class="btn-close" onclick="toggleModalCriacao()"></button>
                    </div>
                    <div class="modal-body">
                        <form class="row g-3" method="POST">
                            <?php if (isset($erro)) { ?>
                                <div class="text-danger"><?= $erro ?></div>
                            <?php } ?>
                            <div class="col-md-2">
                                <label class="form-label" for="codigo">Código*</label>
                                <input class="form-control" type="number" id="codigo" name="codigo" min="1" max="999" required />
                            </div>
                            <div class="col-md-8">
                                <label class="form-label" for="nome">Nome*</label>
                                <input class="form-control" type="text" id="nome" name="nome" maxlength="50" required autofocus />
                            </div>
                            <input type="submit" id="submit-form-criacao" class="d-none" />
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" onclick="toggleModalCriacao()">Close</button>
                        <label class="btn btn-primary" for="submit-form-criacao" tabindex="0"></label>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function toggleModalCriacao () {
                document.getElementById("modal-criacao").classList.toggle("show");
            }

            <?php if (isset($iniciar_modal_aberto)) { ?>
                toggleModalCriacao();
            <?php } ?>
        </script>
    </body>
</html>