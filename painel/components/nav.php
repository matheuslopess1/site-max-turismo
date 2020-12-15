<?php
    require_once(ROOT_DIR . "utils/session_start.php");
    require_once(ROOT_DIR . "utils/authenticated_page.php");
?>
<ul>
    <li>
        <a href="index.php">Página Inicial</a>
    </li>
    <?php if ($_SESSION["usuario"]["tipo"] === "ADMIN") { ?>
        <li>
            <a href="usuarios.php">Usuários</a>
        </li>
        <li>
            <a href="bancos.php">Bancos</a>
        </li>
    <?php } ?>
    <li>
        <a href="logout.php">Sair</a>
    </li>
</ul>