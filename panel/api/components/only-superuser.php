<?php
    if ($_SESSION["user"]["role"] !== "SUPERUSER") {
        echo json_encode(["success" => FALSE, "error" => "Esta ação necessita ser um superusuário"]);
        exit();
    }
?>