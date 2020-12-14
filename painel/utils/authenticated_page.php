<?php
    if (!isset($_SESSION["autenticado"])) {
        header("Location: login.php");
        exit();
    }
?>