<?php
    include_once("componentes/sessao.php");
    include_once("componentes/apenas_autenticado.php");
    $_SESSION = [];
    header("Location: login.php");
?>