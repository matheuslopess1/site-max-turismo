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
            exit();
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
        header("Location: /panel/transfers/?error=Parâmetro amount deve ser composto apenas por números com casas decimais identificados por um . (ponto)");
        exit();
    }

    if ($_POST["amount"] <= 0.00) {
        header("Location: /panel/transfers/?error=Parâmetro amount deve ser composto por números positivos");
        exit();
    }

    $outgoingAccount = $_POST["outgoing_account"];
    $incomingAccount = $_POST["incoming_account"];
    $amount = $_POST["amount"];

    $registerMatheus = "114.234.744-30";
    $registerNSA = "34.803.364/0001-21";
    $registerEirelli = "29.995.023/0001-65";

    $glue = ", ";
    $addQuotes = function($item) { return "'$item'"; };
    $outgoingRegisters = [$registerMatheus, $registerNSA, $registerEirelli];
    $outgoingRegisters = implode($glue, array_map($addQuotes, $outgoingRegisters));

    $sql = "SELECT (SELECT COUNT(*) FROM Account WHERE register IN ($outgoingRegisters) AND id = $outgoingAccount) AS outgoingAccountCounter, (SELECT COUNT(*) FROM Account WHERE register NOT IN ($outgoingRegisters) AND Id = $incomingAccount) AS incomingAccountCounter";
    $result = mysqli_query($link, $sql);
    [$outgoingAccountCounter, $incomingAccountCounter] = mysqli_fetch_all($result, MYSQLI_ASSOC)[0];

    if ($outgoingAccountCounter === 0) {
        header("Location: /panel/transfers/?error=Parâmetro outgoingAccount contém um valor inválido");
        exit();
    }

    if ($incomingAccountCounter === 0) {
        header("Location: /panel/transfers/?error=Parâmetro incomingAccountCounter contém um valor inválido");
        exit();
    }

    $userId = $_SESSION["user"]["id"];

    $sql = "INSERT INTO `Transfer` (outgoingAccount, incomingAccount, amount, createdBy) VALUES (?, ?, ?, $userId)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "iid", $outgoingAccount, $incomingAccount, $amount);
    mysqli_stmt_execute($stmt);

    header("Location: /panel/transfers/");
?>