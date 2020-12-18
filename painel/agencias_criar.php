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
    $mysqli = new mysqli("127.0.0.1", "u351998101_matheus", "o0/?E&Ec>qQ", "u351998101_maxturismo");
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $banco_id = $_POST["banco_id"];
        $codigo = $_POST["codigo"];
        $stmt = $mysqli->prepare("SELECT COUNT(*) AS contagem FROM agencias WHERE banco_id = ? AND codigo = ?");
        $stmt->bind_param("is", $banco_id, $codigo);
        $stmt->execute();
        $stmt->bind_result($contagem);
        $stmt->fetch();
        if ($contagem === 0) {
            $stmt->close();
            $stmt = $mysqli->prepare("INSERT INTO agencias (banco_id, codigo) VALUES (?, ?)");
            $stmt->bind_param("is", $banco_id, $codigo);
            $stmt->execute();
            header("Location: agencias_listagem.php");
            exit();
        }
        $erro = "Agência já registrada";
    }
    $resultado = $mysqli->query("SELECT id, nome, codigo FROM bancos");
    $bancos = $resultado->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Criar Agência</title>
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
        <h2>Bancos</h2>
        <ul>
            <li><a href="/painel/agencias_listagem.php">Listagem</a></li>
            <li><a href="/painel/agencias_criar.php">Criar</a></li>
        </ul>
        <h3>Criação</h3>
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
                <input type="number" id="codigo" name="codigo" min="1" max="9999" required />
            </p>
            <button type="submit">Criar</button>
        </form>
        <script>
            document.getElementById("codigo").addEventListener("input", function (event) {
                this.target.value = this.value.padStart(4, "0");
            });

            document.querySelector("form").addEventListener("submit", function (event) {
                const codigo = document.getElementById("codigo");
                codigo.value = codigo.value.padStart(4, "0");
                console.log(codigo.value);
                event.preventDefault();
            })
        </script>
    </body>
</html>