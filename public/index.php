<?php session_start();?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QCMaster</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="https://media-public.canva.com/1RQEw/MAG1fo1RQEw/1/tl.png" type="image/x-icon">
</head>

<body>

   <?php include 'includes/navbar.php' ?>

    <section class="hero-section text-center">
        <div class="container">
            <h1 class="hero-title mb-4">Évaluez vos compétences</h1>
            <p class="hero-lead mb-5">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus, ipsa.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="/user/dashboard" class="btn btn-emerald btn-lg shadow-sm">Commencer</a>
            </div>
        </div>
    </section>

    <section class="bg-white py-5 border-bottom">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3">
                    <h3 class="display-6 fw-bold text-dark mb-1">150+</h3>
                    <p class="text-muted small mb-0">Questions en Base</p>
                </div>
                <div class="col-6 col-md-3">
                    <h3 class="display-6 fw-bold text-success mb-1">0%</h3>
                    <p class="text-muted small mb-0">Triche Tolérée</p>
                </div>
                <div class="col-6 col-md-3">
                    <h3 class="display-6 fw-bold text-dark mb-1">10 min</h3>
                    <p class="text-muted small mb-0">Temps Moyen / Test</p>
                </div>
                <div class="col-6 col-md-3">
                    <h3 class="display-6 fw-bold text-dark mb-1">Instant</h3>
                    <p class="text-muted small mb-0">Correction Automatisée</p>
                </div>
            </div>
        </div>
    </section>

    <section id="categories" class="container my-5 py-4" style="overflow: hidden;">
        <h2 class="text-center section-title">Notions abordées</h2>
        <div class="infinite-slider-container">
            <div class="infinite-slider-track">
                <div class="infinite-slider-list row flex-nowrap g-5" aria-hidden="true">
                    <div class="col-auto col-custom">
                        <a href="quiz.html" class="d-flex flex-column align-items-center gap-2 text-decoration-none text-center">
                            <span class="step-number"><i class="fa-brands fa-php"></i></span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">PHP</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-auto col-custom">
                        <a href="quiz.html" class="d-flex flex-column align-items-center gap-2 text-decoration-none text-center">
                            <span class="step-number"><i class="fa-brands fa-html5"></i></span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">HTML5 / CSS3</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-auto col-custom">
                        <a href="quiz.html" class="d-flex flex-column align-items-center gap-2 text-decoration-none text-center">
                            <span class="step-number"><i class="fa-brands fa-js"></i></span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">JavaScript</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-auto col-custom">
                        <a href="quiz.html" class="d-flex flex-column align-items-center gap-2 text-decoration-none text-center">
                            <span class="step-number"><i class="fa-solid fa-database"></i></span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">Modélisation SQL</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-auto col-custom">
                        <a href="quiz.html" class="d-flex flex-column align-items-center gap-2 text-decoration-none text-center">
                            <span class="step-number"><i class="fa-solid fa-shield-halved"></i></span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">Sécurité</h5>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="infinite-slider-list row flex-nowrap g-5" aria-hidden="true">
                    <div class="col-auto col-custom">
                        <a href="quiz.html" class="d-flex flex-column align-items-center gap-2 text-decoration-none text-center">
                            <span class="step-number"><i class="fa-brands fa-php"></i></span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">PHP</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-auto col-custom">
                        <a href="quiz.html" class="d-flex flex-column align-items-center gap-2 text-decoration-none text-center">
                            <span class="step-number"><i class="fa-brands fa-html5"></i></span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">HTML5 / CSS3</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-auto col-custom">
                        <a href="quiz.html" class="d-flex flex-column align-items-center gap-2 text-decoration-none text-center">
                            <span class="step-number"><i class="fa-brands fa-js"></i></span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">JavaScript</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-auto col-custom">
                        <a href="quiz.html" class="d-flex flex-column align-items-center gap-2 text-decoration-none text-center">
                            <span class="step-number"><i class="fa-solid fa-database"></i></span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">Modélisation SQL</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-auto col-custom">
                        <a href="quiz.html" class="d-flex flex-column align-items-center gap-2 text-decoration-none text-center">
                            <span class="step-number"><i class="fa-solid fa-shield-halved"></i></span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">Sécurité</h5>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container my-5 pt-4">
        <h2 class="text-center section-title">Pourquoi utiliser notre environnement ?</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="card h-100 feature-card p-4 shadow-none">
                    <div class="card-body p-0">
                        <h5 class="card-title-custom">Tirage Aléatoire</h5>
                        <p class="card-text text-muted small">Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatum dolor repudiandae qui earum soluta est sed cupiditate odit esse distinctio.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 feature-card p-4 shadow-none">
                    <div class="card-body p-0">
                        <h5 class="card-title-custom">Surveillance Anti-Triche</h5>
                        <p class="card-text text-muted small">Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatum dolor repudiandae qui earum soluta est sed cupiditate odit esse distinctio.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 feature-card p-4 shadow-none">
                    <div class="card-body p-0">
                        <h5 class="card-title-custom">Analyse des Résultats</h5>
                        <p class="card-text text-muted small">Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatum dolor repudiandae qui earum soluta est sed cupiditate odit esse distinctio.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-5 border-top border-bottom">
        <div class="container my-4">
            <h2 class="text-center section-title">Comment se déroule votre examen ?</h2>
            <div class="row g-4 mt-2">
                <div class="col-md-4">
                    <div class="d-flex gap-3 align-items-start">
                        <span class="step-number">01</span>
                        <div>
                            <h5 class="fw-bold text-dark mb-2">Authentification requise</h5>
                            <p class="text-muted small">Créez votre compte étudiant ou connectez-vous pour lier vos notes finales à votre numéro de matricule universitaire.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-3 align-items-start">
                        <span class="step-number">02</span>
                        <div>
                            <h5 class="fw-bold text-dark mb-2">Respect du Chronomètre</h5>
                            <p class="text-muted small">Une fois lancé, vous disposez de 10 minutes strictes.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-3 align-items-start">
                        <span class="step-number">03</span>
                        <div>
                            <h5 class="fw-bold text-dark mb-2">Rapport de Compétence</h5>
                            <p class="text-muted small">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi voluptatibus, nam placeat facere dignissimos cupiditate et ipsam tempora laboriosam? Commodi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include 'includes/footer.php'?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


