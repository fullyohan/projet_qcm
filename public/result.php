<?php
session_start();
require_once './security/auth-guard.php';
require_auth();

require_once '../config/db.php'; 
$userId = $_SESSION['user_id'];

$attemptId = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

if ($attemptId === 0) {
    header("Location: /user/dashboard.php");
    exit();
}

$sql = "SELECT a.score, 
               a.date AS exam_date,
               (SELECT COUNT(*) FROM answers ans WHERE ans.attempt_id = a.id AND ans.is_correct = 1) AS correct_answers,
               (SELECT COUNT(*) FROM answers ans WHERE ans.attempt_id = a.id) AS total_questions
        FROM attempts a
        WHERE a.id = ? AND a.user_id = ?
        LIMIT 1";

$stmt = $db->prepare($sql);
$stmt->bind_param("ii", $attemptId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: /user/dashboard.php");
    exit();
}

$attemptData = $result->fetch_assoc();
$score = floatval($attemptData['score']);
$correctAnswers = intval($attemptData['correct_answers']);
$totalQuestions = intval($attemptData['total_questions']);
$isSuccess = ($score >= 10);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QCMaster — Résultat final</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/result.css">
    <link rel="shortcut icon" href="https://media-public.canva.com/1RQEw/MAG1fo1RQEw/1/tl.png" type="image/x-icon">
</head>
<body>
    <div class="container my-auto">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card result-card p-4 p-sm-5 text-center shadow-none mb-5">        
                    <?php if ($isSuccess): ?>
                        <div class="mb-3">
                            <i class="bi bi-check-circle-fill text-success fs-1"></i>
                        </div>
                        <h4 class="fw-extrabold text-dark mb-1" style="font-weight: 800; letter-spacing: -0.02em;">Évaluation validée !</h4>
                        <p class="text-muted small mb-4">Félicitations, vous avez acquis les compétences de ce module.</p>
                    <?php else: ?>
                        <div class="mb-3">
                            <i class="bi bi-exclamation-circle-fill text-danger fs-1"></i>
                        </div>
                        <h4 class="fw-extrabold text-dark mb-1" style="font-weight: 800; letter-spacing: -0.02em;">Évaluation non validée</h4>
                        <p class="text-muted small mb-4">Le score minimum requis pour valider ce module est de 10/20.</p>
                    <?php endif; ?>
                    
                    <div class="mb-4">
                        <div class="score-container <?= $isSuccess ? 'success-border' : 'danger-border'; ?>">
                            <div class="score-number">
                                <?= number_format($score, 1); ?><span class="score-max">/20</span>
                            </div>
                            <div class="text-muted fw-bold mt-1" style="font-size: 0.7rem; letter-spacing: 0.05em; text-transform: uppercase;">Note finale</div>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-2 mt-4">
                        <a href="/user/history.php?id=<?= $attemptId; ?>" class="btn btn-emerald d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-eye"></i> Analyser mes erreurs
                        </a>
                        <a href="/user/dashboard.php" class="btn btn-emerald-outline">
                            Retour au tableau de bord
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>