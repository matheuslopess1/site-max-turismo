<nav class="navbar is-dark mb-5">
    <div class="navbar-brand">
        <a class="navbar-item is-size-4" href="/painel/">ML App</a>
        <a class="navbar-burger" onclick="document.querySelector('.navbar-menu').classList.toggle('is-active');">
            <span></span>
            <span></span>
            <span></span>
        </a>
    </div>
    <div class="navbar-menu">
        <div class="navbar-start">
            <a class="navbar-item" href="/painel/">Página Inicial</a>
            <?php if ($_SESSION["usuario"]["tipo"] === "ADMIN") { ?>
                <a class="navbar-item" href="/painel/usuarios/">Usuários</a>
                <a class="navbar-item" href="/painel/bancos/">Bancos</a>
            <?php } ?>
        </div>
        <div class="navbar-end">
            <span class="navbar-item"><?=$_SESSION["usuario"]["nome"] ?></a>
            <a class="navbar-item has-text-danger" href="/painel/logout/">Sair</a>
        </div>
    </div>
</nav>