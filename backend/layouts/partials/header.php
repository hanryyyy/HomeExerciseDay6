<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div>
            <a class="navbar-brand" href="/demoshop/frontend/index.php">DemoShop</a>
            <span class="navbar-text text-light">Dashboard</span>
        </div>

        <div class="d-flex align-items-center">
            <?php if (isset($_SESSION['username'])): ?>
                <span class="text-light me-3">Xin chào, <?= htmlspecialchars($_SESSION['username']) ?></span>
            <?php endif; ?>
            <a href="/demoshop/frontend/pages/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>