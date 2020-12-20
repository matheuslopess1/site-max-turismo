<ul>
    <li><a href="/painel/index.php">Dashboard</a></li>
    <?php if ($_SESSION["usuario"]["tipo"] === "ADMIN") { ?>
        <li><a href="/painel/usuarios_listagem.php">Usuários</a></li>
        <li><a href="/painel/bancos_listagem.php">Bancos</a></li>
        <li><a href="/painel/agencias_listagem.php">Agências</a></li>
    <?php } ?>
</ul>
<ul>
    <li><?= $_SESSION["usuario"]["nome"] ?></li>
    <li><a href="/painel/logout.php">Sair</a></li>
</ul>