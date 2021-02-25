<?php
    include_once("../components/session.php");
    include_once("../components/authenticated.php");
    include_once("../components/only-manager.php");
    include_once("../components/database.php");

    if (isset($_POST["id"]) === FALSE) {
        echo json_encode(["success" => FALSE, "error" => "Parâmetro faltando: id"]);
        exit();
    }

    $_POST["id"] = trim($_POST["id"]);

    if ($_POST["id"] === "") {
        echo json_encode(["success" => FALSE, "error" => "Parâmetro vazio: id"]);
        exit();
    }

    if (ctype_digit($_POST["id"]) === FALSE) {
        echo json_encode(["success" => FALSE, "error" => "Parâmetro deve ser um número inteiro: id"]);
        exit();
    }

    if ($_POST["id"] <= 0) {
        echo json_encode(["success" => FALSE, "error" => "Parâmetro deve ser um número positivo: id"]);
        exit();
    }

    $id = $_POST["id"];

    $sql = "SELECT authorizedBy FROM `Transfer` WHERE id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $transfers = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if (count($transfers) === 0) {
        echo json_encode(["success" => FALSE, "error" => "Transferência não encontrada"]);
        exit();
    }

    $transfer = $transfers[0];

    if ($transfer["authorizedBy"] !== NULL) {
        echo json_encode(["success" => FALSE, "error" => "Transferência já marcada como autorizada"]);
        exit();
    }

    $sql = "UPDATE `Transfer` SET authorizedBy = %d, authorizedAt = NOW() WHERE id = ?";
    $sql = sprintf($sql, $_SESSION["user"]["id"]);
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    echo json_encode(["success" => TRUE]);
?>