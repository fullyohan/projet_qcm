<?php
session_start();
require_once '../security/auth-guard.php';
require_auth();
require_once '../../config/db.php';
require_admin($db);

require_once '../../config/db.php';

$statTotal = $db->query("SELECT COUNT(*) as total FROM questions")->fetch_assoc()['total'] ?? 0;

$editMode = false;
$editQuestion = [
    'id' => '', 'module' => '', 'question' => '', 
    'answer1' => '', 'answer2' => '', 'answer3' => '', 'answer4' => '', 'correct_answer' => 1
];

if (isset($_GET['edit'])) {
    $id_edit = (int)$_GET['edit'];
    $stmt = $db->prepare("SELECT * FROM questions WHERE id = ?");
    $stmt->bind_param("i", $id_edit);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $row = $res->fetch_assoc()) {
        $editMode = true;
        $editQuestion = $row;
    }
}

$questionsResult = $db->query("SELECT id, module, question, answer1, answer2, answer3, answer4, correct_answer FROM questions ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QCMaster — Gestion de questions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="https://media-public.canva.com/1RQEw/MAG1fo1RQEw/1/tl.png" type="image/x-icon">
    <style>
        :root {
            --emerald-dark: #064e3b;
            --emerald-main: #059669;
            --emerald-light: #10b981;
            --emerald-soft: #f0fdf4;
            --bg-page: #f8fafc;
            --border-color: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --sidebar-width: 240px;
        }
        body { 
            background-color: var(--bg-page); 
            color: var(--text-main);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            letter-spacing: -0.01em;
        }
        .main-content { padding: 2.5rem; }
        .admin-card { background: #ffffff; border: 1px solid var(--border-color); border-radius: 0.75rem; }
        .form-label-custom { font-size: 0.825rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem; }
        .form-control {
            border: 1px solid var(--border-color); border-radius: 0.5rem; padding: 0.625rem 0.85rem;
            font-size: 0.925rem; background-color: #ffffff; color: var(--text-main); transition: all 0.15s ease;
        }
        .form-control:focus { border-color: #94a3b8; box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.1); }
        .option-field-group {
            background: #ffffff; border: 1px solid var(--border-color); border-radius: 0.5rem;
            display: flex; align-items: center; padding: 0.25rem 0.75rem;
        }
        .option-field-group .form-control { border: none !important; box-shadow: none !important; padding-left: 0.5rem; }
        .btn-premium { background-color: var(--text-main); color: #ffffff; font-weight: 500; font-size: 0.9rem; padding: 0.625rem 1.25rem; border-radius: 0.5rem; border: 1px solid var(--text-main); }
        .btn-premium:hover { background-color: #1e293b; color: #ffffff; }
        .table th { font-weight: 600; color: var(--text-muted); font-size: 0.775rem; text-transform: uppercase; padding: 1rem 0.75rem; border-bottom: 1px solid var(--border-color); }
        .table td { padding: 1rem 0.75rem; font-size: 0.925rem; border-bottom: 1px solid #f1f5f9; }
        .badge-premium-success { background-color: var(--emerald-soft); color: var(--emerald-dark); font-weight: 500; border: 1px solid rgba(4, 120, 87, 0.1); font-size: 0.8rem; padding: 0.35rem 0.6`rem; border-radius: 0.375rem; }
        .badge-module { background-color: #f1f5f9; color: #334155; font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-weight: 600; }
    </style>
</head>
<body>
     <?php include '../includes/navbar-admin.php' ?>
    <div class="p-4 main-content">
        <div class="d-flex justify-content-between align-items-baseline mb-4 pb-3">
            <div>
                <h3 class="fw-bold tracking-tight m-0" style="letter-spacing: -0.02em;">
                    <?= $editMode ? "Modifier la question #" . $editQuestion['id'] : "Ajouter une question" ?>
                </h3>
            </div>
            <?php if($editMode): ?>
                <a href="/admin" class="btn text-danger"><i class="fa-regular fa-x"></i></a>
            <?php endif; ?>
        </div>

        <div class="card admin-card p-4 mb-4">
            <?php include '../includes/alert.php'?>
            <form action="/handlers/questions-handler.php" method="POST">
                <input type="hidden" name="action" value="<?= $editMode ? 'edit' : 'add' ?>">
                <?php if($editMode): ?>
                    <input type="hidden" name="id" value="<?= $editQuestion['id'] ?>">
                <?php endif; ?>
                
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label-custom">Nom / Bloc du Module</label>
                        <input type="text" name="module" class="form-control" placeholder="Ex: Réseau, Développement..." value="<?= htmlspecialchars($editQuestion['module']) ?>" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label-custom">Énoncé principal</label>
                        <input type="text" name="question" class="form-control" placeholder="Entrez la question..." value="<?= htmlspecialchars($editQuestion['question']) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-medium mb-1">Option A (answer1)</label>
                        <div class="option-field-group">
                            <input class="form-check-input" type="radio" name="correct_vector" value="A" <?= $editQuestion['correct_answer'] == 1 ? 'checked' : '' ?> required>
                            <input type="text" name="option_a" class="form-control" placeholder="Texte de l'option 1" value="<?= htmlspecialchars($editQuestion['answer1']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-medium mb-1">Option B (answer2)</label>
                        <div class="option-field-group">
                            <input class="form-check-input" type="radio" name="correct_vector" value="B" <?= $editQuestion['correct_answer'] == 2 ? 'checked' : '' ?>>
                            <input type="text" name="option_b" class="form-control" placeholder="Texte de l'option 2" value="<?= htmlspecialchars($editQuestion['answer2']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-medium mb-1">Option C (answer3)</label>
                        <div class="option-field-group">
                            <input class="form-check-input" type="radio" name="correct_vector" value="C" <?= $editQuestion['correct_answer'] == 3 ? 'checked' : '' ?>>
                            <input type="text" name="option_c" class="form-control" placeholder="Texte de l'option 3" value="<?= htmlspecialchars($editQuestion['answer3']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-medium mb-1">Option D (answer4)</label>
                        <div class="option-field-group">
                            <input class="form-check-input" type="radio" name="correct_vector" value="D" <?= $editQuestion['correct_answer'] == 4 ? 'checked' : '' ?>>
                            <input type="text" name="option_d" class="form-control" placeholder="Texte de l'option 4" value="<?= htmlspecialchars($editQuestion['answer4']) ?>" required>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-premium">
                        <i class="fa-solid <?= $editMode ? 'fa-pen-to-square' : 'fa-plus' ?> me-2 small"></i>
                        <?= $editMode ? 'Mettre à jour' : 'Enregistrer' ?>
                    </button>
                </div>
            </form>
        </div>

        <?php if (!$editMode) : ?>
            <div class="card admin-card overflow-hidden">
                <div class="px-4 py-3 border-bottom bg-white"><span class="fw-bold small text-dark text-uppercase">Banque de question (<?= $statTotal; ?>)</span></div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 8%;" class="ps-4">ID</th>
                                <th style="width: 12%;">Module</th>
                                <th style="width: 40%;">Énoncé</th>
                                <th style="width: 24%;">Solution Validée</th>
                                <th style="width: 16%;" class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($questionsResult->num_rows === 0): ?>
                                <tr><td colspan="5" class="text-center text-muted py-4">Aucune question.</td></tr>
                            <?php else: 
                                while ($row = $questionsResult->fetch_assoc()): 
                                    $idx = (int)$row['correct_answer'];
                                    $correct_text = $row["answer" . $idx] ?? 'Non défini';
                            ?>
                                <tr>
                                    <td class="ps-4 text-secondary fw-mono small">#<?= $row['id']; ?></td>
                                    <td><span class="badge-module"><?= htmlspecialchars($row['module']); ?></span></td>
                                    <td class="fw-medium text-dark"><?= htmlspecialchars($row['question']); ?></td>
                                    <td><span class="badge-premium-success"><?= htmlspecialchars($correct_text); ?></span></td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="?edit=<?= $row['id']; ?>" class="btn btn-sm btn-outline-secondary py-1 px-2">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </a>
                                            
                                            <form action="/handlers/questions-handler.php" method="POST" style="display:inline;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                <button type="submit" class="btn btn-sm text-danger p-1">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>