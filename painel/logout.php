<?php
    require_once(ROOT_DIR . "utils/display_errors.php");
    require_once(ROOT_DIR . "utils/session_start.php");
    require_once(ROOT_DIR . "utils/authenticated_page.php");

    $_SESSION = [];

    header("Location: login.php");
?>