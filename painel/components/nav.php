<?php
    require_once("../utils/session_start.php");
    require_once("../utils/authenticated_page.php");
?>
<ul>
    <?php if ($_SESSION["usuario"]["tipo"] === "ADMIN") { ?>
        <li>
            <a href="usuarios.php">Usuários</a>
        </li>
    <?php } ?>
    <li>
        <a href="logout.php">Sair</a>
    </li>
</ul>