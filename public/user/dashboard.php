<?php
session_start();
require_once '../security/auth-guard.php';
require_auth();

require_once '../../config/db.php'; 
$userId = $_SESSION['user_id'];

$limit = 5; 
$page = isset($_GET['p']) && is_numeric($_GET['p']) ? intval($_GET['p']) : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$countSql = "SELECT COUNT(*) AS total FROM attempts WHERE user_id = ?";
$countStmt = $db->prepare($countSql);
$countStmt->bind_param("i", $userId);
$countStmt->execute();
$totalRows = $countStmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

$avgSql = "SELECT AVG(score) AS global_avg FROM attempts WHERE user_id = ?";
$avgStmt = $db->prepare($avgSql);
$avgStmt->bind_param("i", $userId);
$avgStmt->execute();
$avgRow = $avgStmt->get_result()->fetch_assoc();

$avg = $avgRow['global_avg'] !== null ? floatval($avgRow['global_avg']) : null;

$historySql = "SELECT a.id AS attempt_id, 
                      a.score,
                      a.date AS exam_date,
                      (SELECT q.module FROM answers ans 
                       JOIN questions q ON ans.question_id = q.id 
                       WHERE ans.attempt_id = a.id LIMIT 1) AS module_name
               FROM attempts a
               WHERE a.user_id = ?
               ORDER BY a.date DESC
               LIMIT ? OFFSET ?";

$historyStmt = $db->prepare($historySql);
$historyStmt->bind_param("iii", $userId, $limit, $offset);
$historyStmt->execute();
$historyResult = $historyStmt->get_result();

$modulesSql = "SELECT module
               FROM questions 
               GROUP BY module 
               ORDER BY module ASC";

$modulesResult = $db->query($modulesSql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Espace - QCMaster</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>
    <?php include '../includes/navbar.php'; ?>
    <header class="dashboard-header mb-5">
        <div class="container">
            <div class="row align-items-center g-3">
                <div class="col-md-8 text-center text-md-start">
                    <h1 class="fw-800 text-dark mb-1" style="font-weight: 800; letter-spacing: -1px;">Ravi de vous revoir, <?= $_SESSION['first_name']?></h1>
                </div>
            </div>
        </div>
    </header>

    <main class="container mb-5">
        <div class="row g-4">
           <div class="col-lg-4">
                <div class="card content-card p-4 shadow-none">
                    <?php include '../includes/alert.php'?>
                    <h4 class="fw-bold text-dark mb-3">Nouveau test</h4>
                    <p class="text-muted small mb-3">Prêt à tester vos connaissances ?</p>
                    
                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded mb-3">
                        <span class="text-secondary small fw-medium">
                            <i class="fa-solid fa-chart-simple me-2 text-muted"></i>Votre moyenne
                        </span>
                        <span class="fw-bold text-dark">
                            <?php echo isset($avg) ? number_format($avg, 2) . ' / 20' : '-- / 20'; ?>
                        </span>
                    </div>

                    <button type="button" class="btn btn-emerald w-100 py-2" data-bs-toggle="modal" data-bs-target="#ChoiceModal">
                        <i class="fa-solid fa-play me-2 small"></i>Lancer
                    </button>
                </div>
            </div>

            <div class="col-lg-8" id="history">
                <div class="card content-card p-4 shadow-none h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold text-dark mb-0">Historique récent</h4>
                            <span class="text-muted small fw-medium">Page <?= $page; ?> sur <?= max(1, $totalPages); ?></span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="border-collapse: separate; border-spacing: 0 10px;">
                                <thead>
                                    <tr class="text-muted small">
                                        <th class="border-0">Module abordé</th>
                                        <th class="border-0">Date de passage</th>
                                        <th class="border-0 text-center">Note obtenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($historyResult->num_rows > 0): ?>
                                        <?php while ($row = $historyResult->fetch_assoc()): 
                                            $formattedDate = (new DateTime($row['exam_date']))->format('d/m/Y');
                                            $score = $row['score'];
                                        ?>
                                            <tr onclick="window.location.href='history.php?id=<?= $row['attempt_id']; ?>';" 
                                                style="cursor: pointer;" 
                                                class="align-middle">
                                                
                                                <td class="py-3">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <span class="fs-5"><i class="fa-solid fa-graduation-cap"></i></span>
                                                        <div>
                                                            <h6 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($row['module_name']); ?></h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                
                                                <td class="text-muted small"><?= $formattedDate; ?></td>
                                                
                                                <td class="text-center fw-bold text-dark"><?= number_format($score, 1); ?> / 20</td>
                                                
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted small">
                                                <i class="fa-solid fa-inbox d-block fs-3 mb-2"></i>
                                                Vous n'avez pas encore effectué de tentative de QCM.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?php if ($totalPages > 1): ?>
                        <nav aria-label="Navigation historique" class="mt-4">
                            <ul class="pagination pagination-sm justify-content-center mb-0">
                                
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?p=<?php echo $page - 1; ?>#history" aria-label="Précédent">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>

                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?p=<?php echo $i; ?>#history"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?p=<?php echo $page + 1; ?>#history" aria-label="Suivant">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                                
                            </ul>
                        </nav>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </main>

    <div class="modal fade" id="ChoiceModal" tabindex="-1" aria-labelledby="ChoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content border-0 shadow" style="border-radius: 1.25rem;">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title fw-bold text-dark" id="ChoiceModalLabel" style="letter-spacing: -0.02em;">Choisir un module</h5>
                        <p class="text-muted small mb-0">Sélectionnez le bloc de compétences à évaluer.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body py-4">
                    <form action="/quiz.php" method="POST" id="formChoiceModal">
                        <div class="d-flex flex-column gap-2">
                            <?php 
                            $isFirst = true; 
                            while ($moduleRow = $modulesResult->fetch_assoc()): ?>
                                <label class="module-select-card p-3 d-flex align-items-center gap-3 border rounded-3 position-relative" style="cursor: pointer;">
                                    <input 
                                        type="radio" 
                                        name="module" 
                                        value="<?= htmlspecialchars($moduleRow['module']); ?>" 
                                        class="form-check-input stretched-link m-0 border-secondary" 
                                           <?= $isFirst ? 'required checked' : ''; ?>
                                    >
                                    
                                    <span class="fs-4 text-secondary">
                                        <i class="fa-solid fa-graduation-cap"></i>
                                    </span>
                                    
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($moduleRow['module'])?></h6>
                                    </div>
                                </label>
                            <?php 
                                $isFirst = false; 
                            endwhile; 
                            ?>
                        </div>
                    </form>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light border fw-medium px-4" style="border-radius: 0.5rem;" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" form="formChoiceModal" class="btn btn-emerald px-4">Démarrer</button>
                </div>

            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
