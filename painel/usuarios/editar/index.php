<?php
    if (!isset($_SESSION)) session_start();
    if (!isset($_SESSION["autenticado"])) {
        header("Location: /painel/login/?voltar_para=/painel/usuarios/criar/");
        exit();
    }
    if ($_SESSION["usuario"]["tipo"] !== "ADMIN") {
        header("Location: /painel/login/?voltar_para=/painel/");
        exit();
    }

    $id = $_GET["id"];
    $mysqli = new mysqli("127.0.0.1", "u351998101_matheus", "o0/?E&Ec>qQ", "u351998101_maxturismo");
    $sql = "SELECT nome, codigo FROM usuarios WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) header("Location: /painel/login/") && exit();
    $stmt->bind_result($nome, $codigo);
    $stmt->fetch();
    $stmt->close();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $mysqli = new mysqli("127.0.0.1", "u351998101_matheus", "o0/?E&Ec>qQ", "u351998101_maxturismo");
        $nome = trim($_POST["nome"]);
        $email = trim($_POST["email"]);
        $senha = $_POST["senha"];

        $sql = "SELECT COUNT(*) AS count FROM usuarios WHERE email = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count !== 0) {
            $erro = "Já existe um usuário com este email";
        } else {
            $senha_hasheada = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $nome, $email, $senha_hasheada);
            $stmt->execute();
            $stmt->close();
            header("Location: /painel/usuarios/");
            exit();
        }
    }
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Editar Usuário</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css" />
    </head>
    <body>
        <div class="has-background-white-ter" style="height: 100vw;">
            <?php include_once("../../_componentes/nav.php") ?>
            <div class="container">
                <div class="columns">
                    <div class="column is-3">
                        <?php include_once("../_componentes/menu.php") ?>
                    </div>
                    <div class="column is-9">
                        <h1 class="is-size-4 mb-3">Usuários</h1>
                        <div class="box">
                        <h1 class="is-size-4 mb-3">Usuários</h1>
                        <div class="box">
                            <h2 class="is-size-5 mb-3">Edição</h2>
                            <form method="POST">
                                <?php if (isset($erro)) { ?>
                                    <p class="has-text-danger"><?= $erro ?></p>
                                <?php } ?>
                                <div class="field">
                                    <label class="label" for="nome">
                                        Nome
                                        <span class="has-text-danger">*</span>
                                    </label>
                                    <input type="text" id="nome" name="nome" maxlength="50" value="<?= $nome ?>" required />
                                </div>
                                <div class="field">
                                    <label class="label" for="email">
                                        Email
                                        <span class="has-text-danger">*</span>
                                    </label>
                                    <input type="email" id="email" name="email" maxlength="50" value="<?= $nome ?>" required />
                                </div>
                                <div class="field">
                                    <label class="senha" for="email">
                                        Senha
                                    </label>
                                    <input type="password" id="senha" name="senha" minlength="6" maxlength="18" />
                                </div>
                                <div class="field">
                                    <label class="senha_confirmacao" for="email">
                                        Confirmação da Senha
                                    </label>
                                    <input type="password" id="senha_confirmacao" name="senha_confirmacao" minlength="6" maxlength="18" />
                                </div>
                                <button type="submit">Cadastrar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.getElementById("senha_confirmacao").addEventListener("input", function (event) {
                const senha = document.getElementById("senha").value;
                if (this.value !== senha) {
                    this.setCustomValidity("Senha e Confirmação de Senha devem ser iguais!");
                } else {
                    this.setCustomValidity("");
                }
            });
        </script>
    </body>
</html>