<div class="nav mb-3">
    <a class="nav-link" href="/painel/index.php">Dashboard</a>
    <?php if ($_SESSION["usuario"]["tipo"] === "ADMIN") { ?>
        <a class="nav-link" href="/painel/usuarios_listagem.php">Usuários</a>
    <?php } ?>
    <span class="nav-link ms-md-auto"><?= $_SESSION["usuario"]["nome"] ?></span>
    <a class="nav-link text-secondary" href="/painel/logout.php">sair</a>
</div>