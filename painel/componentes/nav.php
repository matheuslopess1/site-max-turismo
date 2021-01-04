<div class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="/painel">ML App</a>
        <button
            class="navbar-toggler"
            onclick="document.querySelector('#navbar').classList.toggle('show')"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar">
            <div class="navbar-nav me-auto">
                <a class="nav-item nav-link" href="/painel/index.php">Dashboard</a>
                <?php if ($_SESSION["usuario"]["tipo"] === "ADMIN") { ?>
                    <a class="nav-item nav-link" href="/painel/usuarios.php">Usuários</a>
                <?php } ?>
            </div>
            <hr class="bg-white my-2 d-lg-none" />
            <span class="navbar-text">
                <?= $_SESSION["usuario"]["nome"] ?>
            </span>
            <div class="navbar-nav">
                <a class="nav-item nav-link text-secondary" href="/painel/logout.php">sair</a>
            </div>
        </div>
    </div>
</div>