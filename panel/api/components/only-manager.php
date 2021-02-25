<?php
    if ($_SESSION["user"]["role"] !== "MANAGER") {
        echo json_encode(["success" => FALSE, "error" => "Esta ação necessita ser um superusuário"]);
        exit();
    }
?>