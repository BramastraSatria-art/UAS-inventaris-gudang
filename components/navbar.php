<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Inventaris Gudang</span>

        <div class="d-flex align-items-center">
            <span class="text-white me-3">
                <?= htmlspecialchars(Session::getNama()) ?> (<?= htmlspecialchars(Session::getRole()) ?>)
            </span>
            <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>