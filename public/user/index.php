<?php
session_start();
require_once '../security/auth-guard.php';
require_auth();
$user_name  = $_SESSION['first_name'];
$user_last_name = $_SESSION['last_name'];
$user_email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - QCMaster</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/user.css">
    <link rel="shortcut icon" href="https://media-public.canva.com/1RQEw/MAG1fo1RQEw/1/tl.png" type="image/x-icon">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <main class="container my-5" style="max-width: 800px;">
        <div class="d-flex align-items-center gap-3 mb-5">
            <div class="text-secondary display-5">
                <i class="fa-solid fa-circle-user"></i>
            </div>
            <div>
                <h1 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">
                    <?= htmlspecialchars($user_name . ' ' . $user_last_name); ?>
                </h1>
            </div>
        </div>

        <form action="/handlers/update-account-info.php" method="POST">
            <?php include '../includes/alert.php' ?>
            <div class="d-flex flex-column gap-4">
                <div class="card p-4 shadow-none border rounded-3">
                    <h5 class="fw-bold mb-4 text-dark" style="font-size: 1.1rem;">Informations de compte</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-medium mb-1">Nom</label>
                            <input type="text" class="form-control" name="last-name" value="<?= htmlspecialchars($user_last_name); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-medium mb-1">Prénom</label>
                            <input type="text" class="form-control" name="first-name" value="<?= htmlspecialchars($user_name); ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-secondary small fw-medium mb-1">Email</label>
                            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user_email); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="card p-4 shadow-none border rounded-3">
                    <h5 class="fw-bold mb-4 text-dark" style="font-size: 1.1rem;">Sécurité du compte</h5>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="password" class="form-label text-secondary small fw-medium mb-1">Nouveau mot de passe (laisser vide si inchangé)</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" minlength="8">
                        </div>
                        
                        <div class="col-12 text-end mt-4">
                            <button type="submit" class="btn btn-success px-4">
                                Mettre à jour le profil
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </main>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>