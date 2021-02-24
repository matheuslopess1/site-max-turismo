<?php
    include_once("../components/session.php");
    include_once("../components/authenticated.php");
    include_once("../components/database.php");

    foreach(array_keys($_POST) as $key) {
        $_POST[$key] = trim($_POST[$key]);
    }

    foreach (["outgoing_account", "incoming_account", "amount"] as $param) {
        if (isset($_POST[$param]) === FALSE) {
            header("Location: /panel/transfers/?error=Parâmetro faltando: $param");
            exit();
        }

        if ($_POST[$param] === "") {
            header("Location: /panel/transfers/?error=Parâmetro vazio: $param");
        }
    }

    foreach (["outgoing_account", "incoming_account"] as $param) {
        if (ctype_digit($_POST[$param]) === FALSE) {
            header("Location: /panel/transfers/?error=Parâmetro deve ser um número inteiro: $param");
        }

        if ($_POST[$param] <= 0) {
            header("Location: /panel/transfers/?error=Parâmetro deve ser um número positivo: $param");
        }
    }

    if (is_numeric($_POST["amount"]) === FALSE) {
        header("Location: /panel/transfers/?error=Parâmetro deve ser composto apenas por números com casas decimais identificados por um . (ponto): amount");
        exit();
    }

    if ($_POST["amount"] <= 0.00) {
        header("Location: /panel/transfers/?error=Parâmetro deve ser composto por números positivos");
        exit();
    }

    $outgoingAccount = $_POST["outgoing_account"];
    $incomingAccount = $_POST["incoming_account"];
    $amount = $_POST["amount"];

    $sql = "INSERT INTO `Transfer` (outgoingAccount, incomingAccount, amount) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "iid", $outgoingAccount, $incomingAccount, $amount);
    mysqli_stmt_execute($stmt);

    header("Location: /panel/transfers/");
?>