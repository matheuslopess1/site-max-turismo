<?php
    if (isset($_SESSION["autenticado"])) {
        header("Location: index.php");
        exit();
    }
?>