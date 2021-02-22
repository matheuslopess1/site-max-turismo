<?php
    include_once("./../components/session.php");
    include_once("./../components/authenticated.php");
    include_once("./../components/database.php");

    $sql = "SELECT COUNT(*) AS `amountToAuthorize` FROM `Transfer` WHERE `authorizedBy` IS NULL";
    $result = mysqli_query($stmt);
    $amountToAuthorize = mysqli_fetch_all($result)[0]["amountToAuthorize"];


    $sql = "SELECT COUNT(*) FROM `Transfer` WHERE `authorizedBy` IS NOT NULL AND `madeBy` IS NULL";
    $result = mysqli_query($stmt);
    $amountToMade = mysqli_fetch_all($result, MYSQLI_ASSOC)[0]["amountToMade"];

    $view = file_get_contents("./page.html");

    $view = str_replace("[TRANSFERS_AMOUNT_TO_AUTHORIZE]", $amountToAuthorize, $view);
    $view = str_replace("[TRANSFERS_AMOUNT_TO_MADE]", $amountToMade, $view);

    echo $view;
?>