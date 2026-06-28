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
    <title>QCMaster — Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/register.css">

</head>
<body>
    <div class="card auth-card shadow-sm p-4 p-sm-5">
        <div class="text-center mb-4">
            <a href="/" class="brand-logo mb-3 d-inline-block">  <img src="https://media-public.canva.com/1RQEw/MAG1fo1RQEw/1/tl.png" alt="Logo" style="width: 30px; height: auto;">   QC<span>Master</span></a>
            <h3 class="fw-bold text-dark mt-2">Créer un compte</h3>
            <p class="text-muted small">Rejoignez-nous pour mesurer votre niveau en ligne</p>
        </div>
        
        <form action="/handlers/register-handler.php" method="POST">
            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label small fw-semibold text-secondary">Nom</label>
                    <input type="text" name="first-name" class="form-control" placeholder="Votre nom" required>
                </div>
                <div class="col-sm-6">
                    <label class="form-label small fw-semibold text-secondary">Prénom</label>
                    <input type="text" name="last-name" class="form-control" placeholder="Votre prenom" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Adresse Email</label>
                <input type="email" name="email" class="form-control" placeholder="xyz@mail.com" required>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold text-secondary">Mot de passe</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required minlength="8">
                <?php if (isset($_SESSION['alert'])) {?>
                    <div class="form-text text-danger mt-1.5 d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-circle-exclamation"></i>  <?= $_SESSION['alert']['message'] ?>
                    </div>
                <?php }; unset($_SESSION['alert']); ?>
            </div>

            <button type="submit" class="btn btn-emerald w-100 fw-bold mb-4">S'inscrire</button>
            
            <div class="text-center">
                <span class="text-muted small">Déjà inscrit ? </span>
                <a href="login.php" class="small link-emerald">Se connecter</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>