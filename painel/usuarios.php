<?php
    require_once("utils/session_start.php");
    require_once("utils/authenticated_page.php");
    require_once("utils/mysqli_connection.php");

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (!isset($_POST["nome"])) {
            $erro = "Campo Nome é obrigatório";
        } else if (!isset($_POST["email"])) {
            $erro = "Campo Email é obrigatório";
        } else if (!isset($_POST["senha"])) {
            $erro = "Campo Senha é obrigatório";
        } else if (!isset($_POST["senha_confirmacao"])) {
            $erro = "Campo Confirmação de Senha é obrigatório";
        } else if (!isset($_POST["tipo"])) {
            $erro = "Campo Tipo é obrigatório";
        } else {
            $nome = trim($_POST["nome"]);
            $email = trim($_POST["email"]);
            $senha = $_POST["senha"];
            $senha_confirmacao = $_POST["senha_confirmacao"];
            $tipo = $_POST["tipo"];

            if ($nome === "") {
                $erro = "Campo Nome não pode estar vazio";
            } else if ($email === "") {
                $erro = "Campo Email não pode estar vazio";
            } else if ($senha === "") {
                $erro = "Campo Senha não pode estar vazio";
            } else if ($senha_confirmacao === "") {
                $erro = "Campo Confirmação de Senha não pode estar vazio";
            } else if ($senha !== $senha_confirmacao) {
                $erro = "Campos Senha e Confirmação de Senha não são iguais";
            } else if (strlen($nome) > 50) {
                $erro = "Campo Nome não pode ser maior que 50 caracteres";
            } else if (strlen($email) > 100) {
                $erro = "Campo Email não pode ser maior que 100 caracteres";
            } else if (strlen($senha) < 6 || strlen($senha) > 18) {
                $erro = "Campo Senha deve estar entre 6 a 18 caracteres";
            } else if ($tipo !== "ADMIN" && $tipo !== "NORMAL") {
                $erro = "Campo Tipo deve ser ADMIN ou NORMAL";
            } else {
                $stmt = $mysqli->prepare("SELECT COUNT(*) AS count FROM usuarios WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->bind_result($count);
                $stmt->fetch();
                $stmt->close();
                if ($count !== 0) {
                    $erro = "Já existe um usuário com este e-mail";
                } else {
                    $stmt = $mysqli->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
                    $senha_hasheada = password_hash($senha, PASSWORD_DEFAULT);
                    $stmt->bind_param("ssss", $nome, $email, $senha_hasheada, $tipo);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }
    }

    $result = $mysqli->query("SELECT id, nome, email, tipo FROM usuarios");
    $usuarios = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ML App - Usuários</title>
    </head>
    <body>
        <div class="has-background-white-ter" style="height: 100vw;">
            <?php include_once("components/nav.php"); ?>
            <div class="container">
                <h1 class="is-size-4 mb-3">Usuários</h1>
                <div class="box">
                    <h2 class="is-size-5 mb-3">Adicionar Usuário</h2>
                    <form method="POST">
                        <?php if (isset($erro)) { ?>
                            <p style="color: red;"><?= $erro ?></div>
                        <?php } ?>
                        <div class="field">
                            <label class="label" for="nome" style="display: block;">Nome<span style="color: red;">*</span></label>
                            <div class="control">
                                <input class="input" type="text" id="nome" name="nome" maxlength="100" required />
                            </div>
                        </div>
                        <div class="field">
                            <label class="label" for="email" style="display: block;">Email<span style="color: red;">*</span></label>
                            <div class="control">
                                <input class="input" type="email" id="email" name="email" maxlength="100" required />
                            </div>
                        </div>
                        <div class="field">
                            <label class="label" for="senha" style="display: block;">Senha<span style="color: red;">*</span></label>
                            <div class="control">
                                <input class="input" type="password" id="senha" name="senha" minlength="6" maxlength="18" required />
                            </div>
                        </div>
                        <div class="field">
                            <label class="label" for="senha_confirmacao" style="display: block;">Confirmação da Senha<span style="color: red;">*</span></label>
                            <div class="control">
                                <input class="input" type="password" id="senha_confirmacao" name="senha_confirmacao" minlength="6" maxlength="18" required />
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                <label class="radio" for="tipo_admin">
                                    <input type="radio" id="tipo_admin" name="tipo" value="ADMIN" required />
                                    ADMIN
                                </label>
                                <label class="radio" for="tipo_normal">
                                    <input type="radio" id="tipo_normal" name="tipo" value="NORMAL" required />
                                    NORMAL
                                </label>
                            </div>
                        </div>
                        <div class="field">
                            <button type="submit">Cadastrar</button>
                        </div>
                    </form>

                    <h2 class="is-size-5 mb-3">Listagem de Usuários</h2>
                    <table class="table is-fullwidth">
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>Nome</td>
                                <td>Email</td>
                                <td>Tipo</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario) { ?>
                                <tr>
                                    <td><?= $usuario["id"] ?></td>
                                    <td><?= $usuario["nome"] ?></td>
                                    <td><?= $usuario["email"] ?></td>
                                    <td><?= $usuario["tipo"] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
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