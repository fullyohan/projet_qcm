<?php

session_start();
require_once '../security/auth-guard.php';
require_auth();
require_once '../../config/db.php';
require_admin($db);
$current_user_id = (int)$_SESSION['user_id'];
$statUsers = $db->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'] ?? 0;
$statAdmins = $db->query("SELECT COUNT(*) as total FROM users WHERE status = 'admin'")->fetch_assoc()['total'] ?? 0;
$usersResult = $db->query("SELECT id, first_name,last_name, email, status FROM users ORDER BY id DESC");

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QCMaster — Espace Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
            background-color: var(--bg-page); color: var(--text-main);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; letter-spacing: -0.01em;
        }
       
        .nav-link-admin { display: flex; align-items: center; gap: 12px; padding: 0.6rem 0.75rem; color: var(--text-muted); text-decoration: none; border-radius: 0.375rem; font-size: 0.9rem; font-weight: 500; transition: all 0.15s ease; }
        .nav-link-admin:hover, .nav-link-admin.active { background-color: #f1f5f9; color: var(--text-main); }
        .admin-card { background: #ffffff; border: 1px solid var(--border-color); border-radius: 0.75rem; }
        .stat-card { padding: 1.25rem 1.5rem; }
        .stat-card .label { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 600; }
        .table th { font-weight: 600; color: var(--text-muted); font-size: 0.775rem; text-transform: uppercase; padding: 1rem 0.75rem; border-bottom: 1px solid var(--border-color); }
        .table td { padding: 1rem 0.75rem; font-size: 0.925rem; border-bottom: 1px solid #f1f5f9; }
        .badge-role-admin { background-color: #fee2e2; color: #991b1b; font-weight: 600; font-size: 0.8rem; padding: 0.35rem 0.6rem; border-radius: 0.375rem; border: 1px solid rgba(153, 27, 27, 0.1); }
        .badge-role-student { background-color: #e0f2fe; color: #0369a1; font-weight: 600; font-size: 0.8rem; padding: 0.35rem 0.6rem; border-radius: 0.375rem; border: 1px solid rgba(3, 105, 161, 0.1); }
        .btn-action-outline { border: 1px solid var(--border-color); background: #ffffff; color: var(--text-muted); padding: 0.35rem 0.7rem; font-size: 0.85rem; border-radius: 0.375rem; transition: all 0.15s; }
        .btn-action-outline:hover { background: #f8fafc; color: var(--text-main); border-color: #cbd5e1; }
        .btn-emerald { background-color: var(--emerald-main); color: white; }
        .btn-emerald:hover { background-color: var(--emerald-dark); color: white; }
        @media (max-width: 991.98px) { .sidebar { display: none; } .main-content { margin-left: 0; padding: 1.5rem; } }
    </style>
</head>
<body>
    <?php include '../includes/navbar-admin.php'?> 
   
    <div class="p-5">
        <?php include '../includes/alert.php' ?>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card admin-card stat-card">
                    <div class="label">Total Comptes</div>
                    <div class="mt-2"><span class="fs-2 fw-bold text-dark lh-1"><?= $statUsers; ?></span></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card admin-card stat-card">
                    <div class="label">Administrateurs</div>
                    <div class="mt-2"><span class="fs-2 fw-bold text-dark lh-1"><?= $statAdmins; ?></span></div>
                </div>
            </div>
        </div>

        <div class="card admin-card overflow-hidden">
            <div class="px-4 py-3 border-bottom bg-white"><span class="fw-bold small text-dark text-uppercase">Liste des Comptes</span></div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 15%;">Prenom</th>
                            <th style="width: 15%;">Nom</th>
                            <th style="width: 30%;">Adresse Email</th>
                            <th style="width: 15%;">Status</th>
                            <th style="width: 20%;" class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($usersResult->num_rows === 0): ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">Aucun utilisateur.</td></tr>
                        <?php else: 
                            while ($user = $usersResult->fetch_assoc()): 
                                $isAdmin = ($user['status'] === 'admin');
                        ?>
                            <tr>
                                <td class="fw-semibold text-dark"><?= htmlspecialchars($user['first_name']); ?></td>
                                <td class="fw-semibold text-dark"><?= htmlspecialchars($user['last_name']); ?></td>
                                <td class="text-secondary"><?= htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <span class="badge <?= $isAdmin ? 'text-bg-danger' : 'text-bg-success'; ?>">
                                        <?= $user['status'] ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-2">
                                        <form action="/handlers/user-management-handler.php" method="POST" class="m-0">
                                            <input type="hidden" name="action" value="<?=$isAdmin ? 'demote' : 'promote' ?>">
                                            <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                            <button type="submit" class="btn btn-action-outline" <?= ($user['id'] == $current_user_id) ? 'disabled' : ''; ?>>
                                                <i class="fa-solid <?= $isAdmin ? 'fa-arrow-down' : 'fa-arrow-up'; ?> me-1"></i>
                                            </button>
                                        </form>

                                       <div class="d-inline-flex gap-2">
                                            <?php if ($user['id'] != $current_user_id): ?>
                                                <form action="/handlers/user-management-handler.php" method="POST" class="m-0">
                                                    <input type="hidden" name="action" value="<?= $user['status'] === 'suspended' ? 'unsuspend' : 'suspend'; ?>">
                                                    <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                                    
                                                    <button type="submit" 
                                                            class="btn btn-action-outline <?= $user['status'] === 'suspended' ? 'border-success-subtle text-success' : 'border-warning-subtle text-warning'; ?> p-1 px-2" 
                                                            title="<?= $user['status'] === 'suspended' ? 'Réactiver le compte' : 'Suspendre le compte'; ?>">
                                                        <i class="fa-solid <?= $user['status'] === 'suspended' ? 'fa-user-check' : 'fa-ban'; ?>"></i>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <button class="btn btn-action-outline p-1 px-2" disabled><i class="fa-solid fa-lock text-muted"></i></button>
                                            <?php endif; ?>


                                            <?php if ($user['id'] != $current_user_id): ?>
                                                <form action="/handlers/user-management-handler.php" method="POST" class="m-0" 
                                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cet utilisateur ?');">
                                                    <input type="hidden" name="action" value="delete_user">
                                                    <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                                    <button type="submit" class="btn btn-action-outline border-danger-subtle text-danger p-1 px-2" title="Supprimer définitivement">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <button class="btn btn-action-outline p-1 px-2" disabled><i class="fa-solid fa-lock text-muted"></i></button>
                                            <?php endif; ?>
                                        </div>


                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>