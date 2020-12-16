<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    if (!isset($_SESSION["autenticado"])) {
        header("Location: login.php");
        exit();
    }
    $_SESSION = [];
    header("Location: login.php");
?>