<?php
session_start();
require_once '../security/auth-guard.php';
require_auth();

require_once '../../config/db.php'; 


if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Identifiant de tentative manquant.");
}

$attempt_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];


$attempt_sql = "SELECT score, date FROM attempts WHERE id = ? AND user_id = ?";
$stmt = $db->prepare($attempt_sql);
$stmt->bind_param("ii", $attempt_id, $user_id);
$stmt->execute();
$attempt_res = $stmt->get_result();

if ($attempt_res->num_rows === 0) {
    die("Évaluation introuvable ou vous n'avez pas l'autorisation de la consulter.");
}

$attempt_data = $attempt_res->fetch_assoc();
$score_global = $attempt_data['score'];


$questions_sql = "SELECT 
                    q.question, 
                    q.answer1, q.answer2, q.answer3, q.answer4, q.correct_answer,
                    ans.user_answer,
                    ans.is_correct
                  FROM answers ans
                  JOIN questions q ON ans.question_id = q.id
                  JOIN attempts att ON ans.attempt_id = att.id
                  WHERE ans.attempt_id = ? AND att.user_id = ?";
$stmt_q = $db->prepare($questions_sql);
$stmt_q->bind_param("ii", $attempt_id,$user_id);
$stmt_q->execute();
$questions_res = $stmt_q->get_result();


$total_questions = $questions_res->num_rows;
$correct_count = 0;

$answers_history = [];
while ($row = $questions_res->fetch_assoc()) {
    if ($row['is_correct'] == 1) {
        $correct_count++;
    }
    $answers_history[] = $row;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QCMaster — Résultat de l'évaluation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/history.css">
</head>
<body>

    <nav class="navbar-quiz py-3 mb-5">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="/user/dashboard" class="btn btn-sm small fw-semibold">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>
    </nav>

    <div class="container mb-5">
        <div class="result-container">
            <div class="score-banner d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h4 class="fw-bold m-0" style="letter-spacing: -0.02em;">Résultat de l'évaluation</h4>
                    <p class="text-muted small m-0 mt-1">Revue complète des réponses soumises durant la session.</p>
                </div>
                <div class="text-end">
                    <div class="score-digital text-dark"><?php echo number_format($score_global, 1); ?><span class="fs-5 text-muted fw-normal">/20</span></div>
                    <span class="small fw-bold" style="color: var(--emerald-main);">
                        <i class="fa-solid fa-circle-check"></i> <?php echo $correct_count . ' / ' . $total_questions; ?> correctes
                    </span>
                </div>
            </div>

            <h6 class="fw-bold text-muted text-uppercase tracking-wider small mb-3">Détail des questions</h6>
            <div class="d-flex flex-column gap-3">  
                <?php 
                $index = 1;
                foreach ($answers_history as $qa): 
                    $user_ans_text = $qa['answer' . $qa['user_answer']] ?? 'Pas de réponse';
                    $correct_ans_text = $qa['answer' . $qa['correct_answer']];
                ?>
                    <div class="review-card shadow-sm">
                        <div class="mb-3">
                            <span class="text-muted small fw-semibold">Question <?= $index++; ?></span>
                            <h5 class="fw-bold text-dark mt-1 mb-0"><?= htmlspecialchars($qa['question']); ?></h5>
                        </div>  
                        <div class="d-flex flex-column gap-2">
                            <?php if ($qa['is_correct'] == 1): ?>
                                <div>
                                    <span class="text-muted d-block small fw-medium mb-1">Votre réponse (Correcte) :</span>
                                    <div class="answer-badge-correct fw-semibold">
                                        <i class="fa-solid fa-circle-check"></i>  <?= htmlspecialchars($user_ans_text); ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div>
                                    <span class="text-muted d-block small fw-medium mb-1">Votre réponse :</span>
                                    <div class="answer-badge-wrong fw-medium">
                                        <i class="fa-solid fa-circle-xmark"></i>  <?= htmlspecialchars($user_ans_text); ?>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-muted d-block small fw-medium mb-1">Bonne réponse attendue :</span>
                                    <div class="answer-badge-correct fw-semibold">
                                        <i class="fa-solid fa-circle-check"></i>  <?= htmlspecialchars($correct_ans_text); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

        </div>
    </div>

</body>
</html>