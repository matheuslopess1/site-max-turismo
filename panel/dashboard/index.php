<?php
    include_once("./../components/session.php");
    include_once("./../components/authenticated.php");

    $view = file_get_contents("./page.html");
    echo $view;
?>