<?php
    if (isset($_SESSION["authenticated"]) === FALSE) {
        echo json_encode(["success" => FALSE, "error" => "Esta ação necessita estar autenticado"]);
        exit();
    }
?>