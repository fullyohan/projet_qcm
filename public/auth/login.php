<?php 
require_once "../security/auth-guard.php";
session_start();
require_guest();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QCMaster — Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="shortcut icon" href="https://media-public.canva.com/1RQEw/MAG1fo1RQEw/1/tl.png" type="image/x-icon">
</head>
<body>
    <div class="card auth-card shadow-sm p-4 p-sm-5">
        <div class="text-center mb-4">
            <a href="/" class="brand-logo mb-3 d-inline-block">  <img src="https://media-public.canva.com/1RQEw/MAG1fo1RQEw/1/tl.png" alt="Logo" style="width: 30px; height: auto;">   QC<span>Master</span></a>
            <h3 class="fw-bold text-dark mt-2">Content de vous revoir</h3>
            <p class="text-muted small">Accédez à votre espace d'évaluation</p>
        </div>
        
        <form action="/handlers/login-handler.php" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Adresse Email</label>
                <input type="email" name="email" class="form-control" placeholder="nom@mail.com" required>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold text-secondary">Mot de passe</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                <?php if (isset($_SESSION['alert'])) {?>
                    <div class="form-text text-danger mt-1.5 d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-circle-exclamation"></i>  <?= $_SESSION['alert']['message'] ?>
                    </div>
                <?php }; unset($_SESSION['alert']); ?>
            </div>

            <button type="submit" class="btn btn-emerald w-100 fw-bold mb-4">Se connecter</button>
            <div class="text-center">
                <span class="text-muted small">Nouveau sur la plateforme ? </span>
                <a href="register.php" class="small link-emerald">Créer un compte</a>
            </div>
        </form>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>