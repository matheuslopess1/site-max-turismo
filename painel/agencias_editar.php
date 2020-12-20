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
        $stmt = $mysqli->prepare("SELECT COUNT(*) AS contagem FROM agencias WHERE banco_id = ? AND codigo = ? AND id <> ?");
        $stmt->bind_param("isi", $banco_id, $codigo, $id);
        $stmt->bind_result($contagem);
        $stmt->fetch();
        if ($contagem === 0) {
            $stmt->close();
            $stmt = $mysqli->prepare("UPDATE agencias SET banco_id = ?, codigo = ? WHERE id = ?");
            $stmt->bind_param("isi", $banco_id, $codigo, $id);
            $stmt->execute();
            header("Location: agencias_detalhe.phps");
            exit();
        }
        $erro = "Código já registrado no banco informado";
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
        <h1>ML App</h1>
        <?php include_once("componentes/nav.php"); ?>
        <h2>Agências</h2>
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