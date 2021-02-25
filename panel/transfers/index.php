<?php
    include_once("../components/session.php");
    include_once("../components/authenticated.php");
    include_once("../components/database.php");

    $sql = "SELECT t.id, aa.shortName AS outgoingAccount, ab.shortName AS incomingAccount, t.amount, ua.shortName AS createdBy, ub.shortName AS authorizedBy, uc.shortName AS madeBy FROM `Transfer` t JOIN Account aa ON t.outgoingAccount = aa.id JOIN Account ab ON t.incomingAccount = ab.id JOIN User ua ON t.createdBy = ua.id LEFT JOIN User ub ON t.authorizedBy = ub.id LEFT JOIN User uc ON t.madeBy = uc.id";
    $result = mysqli_query($link, $sql);
    $transfers = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $registerMatheus = "114.234.744-30";
    $registerNSA = "34.803.364/0001-21";
    $registerEirelli = "29.995.023/0001-65";

    $glue = ", ";
    $addQuotes = function($item) { return "'$item'"; };
    $outgoingRegisters = [$registerMatheus, $registerNSA, $registerEirelli];
    $outgoingRegisters = implode($glue, array_map($addQuotes, $outgoingRegisters));

    $sql = "SELECT id, shortName, IF(`type` = 'checking', 'CC', 'CP') AS `type`, bank, agency, account FROM Account WHERE register IN ($outgoingRegisters)";
    $result = mysqli_query($link, $sql);
    $outgoingAccounts = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $sql = "SELECT id, shortName, IF(`type` = 'checking', 'CC', 'CP') AS `type`, bank, agency, account FROM Account WHERE register NOT IN ($outgoingRegisters)";
    $result = mysqli_query($link, $sql);
    $incomingAccounts = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $view = file_get_contents('./page.html');
    $view = str_replace('[TRANSFERS_LIST]', json_encode($transfers), $view);
    $view = str_replace('[OUTGOING_ACCOUNTS_LIST]', json_encode($outgoingAccounts), $view);
    $view = str_replace('[INCOMING_ACCOUNTS_LIST]', json_encode($incomingAccounts), $view);

    echo $view;
?>