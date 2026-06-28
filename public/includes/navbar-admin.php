<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top py-2" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <div class="container-fluid px-4">
        
        <a class="navbar-brand navbar-brand-custom" href="/">
            <img src="https://media-public.canva.com/1RQEw/MAG1fo1RQEw/1/tl.png" alt="Logo" style="width: 30px; height: auto;">    
            ADMIN
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-1 align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link px-3 rounded text-secondary fw-medium <?= strpos($_SERVER['PHP_SELF'], 'index') !== false ? 'active-emerald' : ''; ?>" href="/admin/">
                         <i class="fa-solid fa-user me-2"></i>Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 rounded text-secondary fw-medium <?= strpos($_SERVER['PHP_SELF'], 'manage-questions') !== false ? 'active-emerald' : ''; ?>" href="/admin/manage-questions.php">
                        <i class="fa-solid fa-book-bookmark me-2"></i>Questions
                       
                    </a>
                </li>
                
            </ul>
        </div>

    </div>
</nav>

<style>
    .navbar-nav .nav-link:hover {
        background-color: #f1f5f9;
        color: #0f172a !important;
    }
    .navbar-nav .nav-link.active-emerald {
        background-color: #f0fdf4 !important;
        color: #064e3b !important;
        font-weight: 600 !important;
    }
</style>