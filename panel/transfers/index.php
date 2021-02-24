<?php
    include_once("../components/session.php");
    include_once("../components/authenticated.php");
    include_once("../components/database.php");

    $sql = "SELECT id, outgoingAccount, incomingAccount, amount, createdBy, createdAt, authorizedBy, authorizedAt, madeBy, madeAt FROM `Transfer`";
    $result = mysqli_query($link, $sql);
    $transfers = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $view = file_get_contents('./page.html');
    $view = str_replace('[TRANSFERS_LIST]', json_encode($transfers), $view);

    echo $view;
?>