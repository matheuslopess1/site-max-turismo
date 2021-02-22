<?php
    if (isset($_SESSION["authenticated"]) === TRUE) {
        header("Location: /panel/");
    }
?>