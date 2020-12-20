<?php
    include_once("componentes/sessao.php");
    include_once("componentes/apenas_autenticado.php");
    include_once("componentes/apenas_admin.php");
    include_once("componentes/db.php");
    $id = $_GET["id"];
    $stmt = $mysqli->prepare("SELECT codigo, banco_id FROM agencias WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $agencia = $result->fetch_assoc();
    $result->free();
    $stmt->close();
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $banco_id = $_POST["banco_id"];
        $codigo = $_POST["codigo"];
        var_dump($_POST);
        // if ($agencia["banco_id"] != $banco_id)
        // $stmt = $mysqli->prepare("SELECT COUNT(*) AS contagem FROM agencias WHERE banco_id = ? AND codigo = ? AND id <> ?");
        // $stmt->bind_param("is", $banco_id, $codigo);
        // $stmt->execute();
        // $stmt->bind_result($contagem);
        // $stmt->fetch();
        // if ($contagem === 0) {
        //     $stmt->close();
        //     $stmt = $mysqli->prepare("INSERT INTO agencias (banco_id, codigo) VALUES (?, ?)");
        //     $stmt->bind_param("is", $banco_id, $codigo);
        //     $stmt->execute();
        //     header("Location: agencias_listagem.php");
        //     exit();
        // }
        // $erro = "Agência já registrada";
    }
    $resultado = $mysqli->query("SELECT id, nome, codigo FROM bancos");
    $bancos = $resultado->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Editar Agência</title>
    </head>
    <body>
        <?php var_dump($agencia); ?>
        <h1>ML App</h1>
        <?php include_once("componentes/nav.php"); ?>
        <h2>Bancos</h2>
        <ul>
            <li><a href="/painel/agencias_listagem.php">Listagem</a></li>
            <li><a href="/painel/agencias_criar.php">Criar</a></li>
        </ul>
        <h3>Edição</h3>
        <ul>
            <li><a href="/painel/agencias_detalhe.php?id=<?= $id ?>">Detalhe</a></li>
        </ul>
        <form method="POST">
            <?php if (isset($erro)) { ?>
                <p style="color: red;"><?= $erro ?></p>
            <?php } ?>
            <p>
                <label for="banco_id">Banco*</label>
                <select id="banco_id" name="banco_id" required>
                    <?php foreach ($bancos as $banco) {  ?>
                        <option value="<?= $banco["id"] ?>">
                            <?= $banco["id"] ?> - <?= $banco["nome"] ?>
                        </option>
                    <?php } ?>
                </select>
            </p>
            <p>
                <label for="codigo">Código*</label>
                <input type="number" id="codigo" name="codigo" min="1" max="9999" value="<?= $agencia["codigo"] ?>" required />
            </p>
            <button type="submit">Editar</button>
        </form>
        <script>
            document.querySelector("form").addEventListener("submit", function (event) {
                const codigo = document.getElementById("codigo");
                codigo.value = codigo.value.padStart(4, "0");
            })
            document.querySelector("option[value=\"<?= $agencia["banco_id"] ?>\"]").selected = true;
        </script>
    </body>
</html>