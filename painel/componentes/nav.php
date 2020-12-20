<ul class="nav">
    <li class="nav-item">
        <a class="nav-link" href="/painel/index.php">Dashboard</a>
    </li>
    <?php if ($_SESSION["usuario"]["tipo"] === "ADMIN") { ?>
        <li class="nav-item">
            <a class="nav-link" href="/painel/usuarios_listagem.php">Usuários</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/painel/bancos_listagem.php">Bancos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/painel/agencias_listagem.php">Agências</a>
        </li>
    <?php } ?>
</ul>
<ul class="nav">
    <li class="nav-item">
        <?= $_SESSION["usuario"]["nome"] ?>
    </li>
    <li class="nav-item">
        <a class="nav-link text-secondary" href="/painel/logout.php">Sair</a>
    </li>
</ul>