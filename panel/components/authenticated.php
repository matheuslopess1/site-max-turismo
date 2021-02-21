<?php
    if (isset($_REQUEST["authenticated"]) === FALSE) {
        header("Location: /panel/");
    }
?>