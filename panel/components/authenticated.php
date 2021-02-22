<?php
    if (isset($_SESSION["authenticated"]) === FALSE) {
        header("Location: /panel/");
    }
?>