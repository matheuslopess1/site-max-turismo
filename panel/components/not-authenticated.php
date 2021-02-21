<?php
    if (isset($_REQUEST["authenticated"]) === TRUE) {
        header("Location: /panel/");
    }
?>