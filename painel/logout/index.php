<?php
    if (!isset($_SESSION)) session_start();
    if (!isset($_SESSION["autenticado"])) header("Location: /painel/login/") && exit();

    $_SESSION = [];

    header("Location: /painel/login/");
?>