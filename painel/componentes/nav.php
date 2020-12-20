<div class="d-flex justify-content-between flex-column flex-md-row mb-3">
    <div class="nav">
        <a class="nav-link" href="/painel/index.php">Dashboard</a>
        <?php if ($_SESSION["usuario"]["tipo"] === "ADMIN") { ?>
            <a class="nav-link" href="/painel/usuarios_listagem.php">Usuários</a>
            <a class="nav-link" href="/painel/bancos_listagem.php">Bancos</a>
            <a class="nav-link" href="/painel/agencias_listagem.php">Agências</a>
        <?php } ?>
    </div>
    <div class="nav">
        <span class="nav-link"><?= $_SESSION["usuario"]["nome"] ?></span>
        <a class="nav-link text-secondary" href="/painel/logout.php">sair</a>
    </div>
</div>