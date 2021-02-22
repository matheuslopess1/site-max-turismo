<?php
    include_once("./../components/session.php");
    include_once("./../components/authenticated.php");

    $_SESSION = [];

    header("Location: /panel/");
?>