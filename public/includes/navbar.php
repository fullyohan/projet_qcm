<style>
.navbar-custom {
    background-color: var(--glass-white);
    border-bottom: 1px solid #e5e7eb;
    padding: 0.85rem 0;
}
.navbar-brand-custom {
    font-weight: 800;
    letter-spacing: -0.5px;
    color: var(--emerald-dark) !important;
    display: flex;
    align-items: center;
}
.navbar-brand-custom span {
    color: var(--emerald-light);
}
</style>


<nav class="navbar navbar-expand-lg navbar-custom shadow-sm">
    <div class="container">
        <a class="navbar-brand navbar-brand-custom" href="/">
            <img src="https://media-public.canva.com/1RQEw/MAG1fo1RQEw/1/tl.png" alt="Logo" style="width: 30px; height: auto;">    
            QC<span>Master</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" 
                data-bs-target="#<?=isset($_SESSION['user_id']) ? 'navbarUserContent' : 'navbarGuestContent'; ?>">
            <span class="navbar-toggler-icon"></span>
        </button>

        <?php if (!isset($_SESSION['user_id'])) { ?>
            <div class="collapse navbar-collapse" id="navbarGuestContent">
                <div class="d-flex flex-column flex-lg-row gap-2 ms-auto mt-3 mt-lg-0">
                    <a href="/auth/login.php" class="btn btn-outline-emerald btn-sm px-3">Connexion</a>
                    <a href="/auth/register.php" class="btn btn-emerald btn-sm shadow-sm px-3">S'inscrire</a>
                </div>
            </div>
        <?php } else { ?>
            <div class="collapse navbar-collapse" id="navbarUserContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-3">
                    
                    <li class="nav-item">
                        <a class="nav-link small fw-medium text-secondary" href="/user/dashboard">
                            <i class="fa-solid fa-chalkboard"></i> Tableau de bord
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle small fw-semibold text-dark d-flex align-items-center gap-2 bg-light px-3 py-1.5 rounded-pill border" 
                           href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 24px; height: 24px; font-size: 0.75rem;">
                                <?=strtoupper(substr($_SESSION['first_name'] ?? 'U', 0, 1)); ?>
                            </div>
                            <?= htmlspecialchars($_SESSION['first_name'] ?? 'Utilisateur'); ?>
                        </a>
                        
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border mt-2" style="border-radius: 0.5rem;">
                            <li>
                                <div class="dropdown-header small text-muted">
                                    Connecté avec<br>
                                    <strong class="text-dark"><?= htmlspecialchars($_SESSION['email']); ?></strong>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-menu-item dropdown-item small fw-medium text-muted" href="/user">
                                    <i class="fa-solid fa-user"></i> Mon compte
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item small fw-medium text-danger" href="/handlers/logout-handler.php">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Déconnexion
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        <?php } ?>
    </div>
</nav>
