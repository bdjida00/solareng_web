<?php
require_once __DIR__ . '/config.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// Već ulogiran → na dashboard
if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: dashboard.php');
    exit;
}

$greska = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $korisnik = trim($_POST['korisnik'] ?? '');
    $lozinka  = $_POST['lozinka'] ?? '';

    if ($korisnik === ADMIN_USER && password_verify($lozinka, ADMIN_PASS_HASH)) {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $greska = 'Pogrešno korisničko ime ili lozinka.';
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin prijava – Solar Engineering</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <style>
        body { background: #f0f4f8; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .login-card { width: 100%; max-width: 400px; border: none; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.12); }
        .login-header { background: linear-gradient(135deg, #0f2f59, #164480); color: white; border-radius: 12px 12px 0 0; padding: 28px; text-align: center; }
        .login-header h4 { margin: 0; font-weight: 700; }
        .login-header small { opacity: 0.7; font-size: 0.85rem; }
        .btn-brand { background-color: #164480; color: white; font-weight: 600; width: 100%; padding: 10px; border: none; border-radius: 8px; }
        .btn-brand:hover { background-color: #1e5ea8; color: white; }
    </style>
</head>
<body>
<div class="card login-card">
    <div class="login-header">
        <h4>Solar Engineering</h4>
        <small>Admin panel</small>
    </div>
    <div class="card-body p-4">
        <?php if ($greska): ?>
            <div class="alert alert-danger py-2"><?= htmlspecialchars($greska) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold">Korisničko ime</label>
                <input type="text" name="korisnik" class="form-control" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Lozinka</label>
                <input type="password" name="lozinka" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-brand">Prijava</button>
        </form>
    </div>
</div>
</body>
</html>
