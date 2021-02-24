<?php
    include_once("./../components/session.php");
    include_once("./../components/not-authenticated.php");
    include_once("./../components/database.php");

    foreach (["email", "password"] as $param) {
        if (isset($_POST[$param]) === FALSE) {
            header("Location: /panel/login/?error=Parâmetro faltando: $param");
            exit();
        }
    }

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT `id`, `name`, `email`, `password`, `role` FROM `User` WHERE `email` = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if (count($users) === 0) {
        header("Location: /panel/login/?error=Email e/ou senha inválido(s)");
        exit();
    }

    $user = $users[0];

    if (password_verify($password, $user["password"]) === FALSE) {
        header("Location: /panel/login/?error=Email e/ou senha inválido(s)");
        exit();
    }

    unset($user["password"]);

    $_SESSION["authenticated"] = TRUE;
    $_SESSION["user"] = $user;

    header("Location: /panel/");
?>