<?php
    include_once("../components/session.php");
    include_once("../components/authenticated.php");
    include_once("../components/database.php");

    $sql = "SELECT id, outgoingAccount, incomingAccount, amount, createdBy, createdAt, authorizedBy, authorizedAt, madeBy, madeAt FROM `Transfer`";
    $result = mysqli_query($link, $sql);
    $transfers = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $registerMatheus = "114.234.744-30";
    $registerNSA = "34.803.364/0001-21";
    $registerEirelli = "29.995.023/0001-65";

    $glue = ", ";
    $addQuotes = function($item) { return "'$item'"; };
    $outgoingRegisters = [$registerMatheus, $registerNSA, $registerEirelli];
    $outgoingRegisters = implode($glue, array_map($addQuotes, $outgoingRegisters));

    $sql = "SELECT id, `name`, IF(`type` = 'checking', 'CC', 'CP') AS `type`, bank, agency, account FROM Account WHERE register IN ($outgoingRegisters)";
    $result = mysqli_query($link, $sql);
    $outgoingAccounts = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $sql = "SELECT id, `name`, IF(`type` = 'checking', 'CC', 'CP') AS `type`, bank, agency, account FROM Account WHERE register NOT IN ($outgoingRegisters)";
    $result = mysqli_query($link, $sql);
    $incomingAccounts = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $view = file_get_contents('./page.html');
    $view = str_replace('[TRANSFERS_LIST]', json_encode($transfers), $view);
    $view = str_replace('[OUTGOING_ACCOUNTS_LIST]', json_encode($outgoingAccounts), $view);
    $view = str_replace('[INCOMING_ACCOUNTS_LIST]', json_encode($incomingAccounts), $view);

    echo $view;
?>