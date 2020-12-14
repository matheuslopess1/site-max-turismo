<?php
    require_once("utils/display_errors.php");
    require_once("utils/session_start.php");
    require_once("utils/authenticated_page.php");

    $_SESSION = [];

    header("Location: login.php");
?>