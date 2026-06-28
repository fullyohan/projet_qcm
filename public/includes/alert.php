<?php if (isset($_SESSION['alert']) && is_array($_SESSION['alert'])): 
    $alert_type = ($_SESSION['alert']['type'] === 'error') ? 'danger' : 'success';
    $alert_message = $_SESSION['alert']['message'] ?? '';
?>
    <div class="alert alert-<?= $alert_type; ?> fade show shadow-sm border-0 d-flex align-items-center mb-4" role="alert">
        <div class="me-2">
            <i class="fa-solid <?= $alert_type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation' ?>fa-lg"></i>
        </div>
        <div class="small fw-medium">
            <?= htmlspecialchars($alert_message); ?>
        </div>
    </div>

<?php unset($_SESSION['alert']); endif;?>