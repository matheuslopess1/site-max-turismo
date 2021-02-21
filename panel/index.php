<?php
    include_once("./components/session.php");

    if (isset($_SESSION["authenticated"]) === FALSE) {
        header("Location: /panel/login/");
    }
?>