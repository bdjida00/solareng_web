<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../php/db.php';

$greske = [];
$uspjeh = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naslov         = trim($_POST['naslov'] ?? '');
    $lokacija       = trim($_POST['lokacija'] ?? '');
    $opis           = trim($_POST['opis'] ?? '');
    $snaga_kw       = $_POST['snaga_kw'] !== '' ? (float)$_POST['snaga_kw'] : null;
    $tip_panela     = trim($_POST['tip_panela'] ?? '');
    $tip_invertera  = trim($_POST['tip_invertera'] ?? '');
    $napomena       = trim($_POST['napomena'] ?? '');
    $datum_ugradnje = $_POST['datum_ugradnje'] ?: null;
    $aktivan        = isset($_POST['aktivan']) ? 1 : 0;

    if (!$naslov) $greske[] = 'Naslov je obavezan.';
    if (!$lokacija) $greske[] = 'Lokacija je obavezna.';

    if (empty($greske)) {
        $db = getDB();
        $stmt = $db->prepare('INSERT INTO projekti
            (naslov, lokacija, opis, snaga_kw, tip_panela, tip_invertera, napomena, datum_ugradnje, aktivan)
            VALUES (?,?,?,?,?,?,?,?,?)');
        $stmt->execute([$naslov, $lokacija, $opis, $snaga_kw, $tip_panela, $tip_invertera, $napomena, $datum_ugradnje, $aktivan]);
        $projekt_id = $db->lastInsertId();

        // Upload slika
        if (!empty($_FILES['slike']['name'][0])) {
            $uploadDir = UPLOAD_DIR . $projekt_id . '/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $redoslijed = 0;
            foreach ($_FILES['slike']['tmp_name'] as $i => $tmpName) {
                if ($_FILES['slike']['error'][$i] !== UPLOAD_ERR_OK) continue;
                if ($_FILES['slike']['size'][$i] > MAX_FILE_SIZE) continue;

                $mime = mime_content_type($tmpName);
                if (!in_array($mime, ALLOWED_TYPES)) continue;

                $ext      = pathinfo($_FILES['slike']['name'][$i], PATHINFO_EXTENSION);
                $filename = uniqid('img_', true) . '.' . strtolower($ext);
                $dest     = $uploadDir . $filename;

                if (move_uploaded_file($tmpName, $dest)) {
                    $relativePath = 'uploads/projekti/' . $projekt_id . '/' . $filename;
                    $db->prepare('INSERT INTO projekt_slike (projekt_id, putanja, redoslijed) VALUES (?,?,?)')
                       ->execute([$projekt_id, $relativePath, $redoslijed++]);
                }
            }
        }

        header('Location: dashboard.php?poruka=dodan');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novi projekt – Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background: #f0f4f8; }
        .topbar { background: linear-gradient(90deg, #0f2f59, #164480); color: white; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; }
        .topbar a { color: rgba(255,255,255,0.8); text-decoration: none; font-size: 0.9rem; }
        .topbar a:hover { color: white; }
        .content { max-width: 860px; margin: 30px auto; padding: 0 16px; }
        .section-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #6c757d; margin-bottom: 12px; }
        .btn-brand { background: #164480; color: white; font-weight: 600; border: none; }
        .btn-brand:hover { background: #1e5ea8; color: white; }
        #preview-zona { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 12px; }
        #preview-zona img { height: 90px; width: 90px; object-fit: cover; border-radius: 8px; border: 2px solid #dee2e6; }
    </style>
</head>
<body>
<div class="topbar">
    <div><strong>Solar Engineering</strong> <span style="opacity:.5">|</span> Novi projekt</div>
    <a href="dashboard.php"><i class="bi bi-arrow-left"></i> Natrag</a>
</div>

<div class="content">
    <?php if (!empty($greske)): ?>
        <div class="alert alert-danger">
            <?php foreach ($greske as $g): ?><div><?= htmlspecialchars($g) ?></div><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <!-- OSNOVNI PODACI -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="section-label">Osnovni podaci</div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Naslov projekta <span class="text-danger">*</span></label>
                    <input type="text" name="naslov" class="form-control" placeholder="npr. Stambena kuća – Split" value="<?= htmlspecialchars($_POST['naslov'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Lokacija <span class="text-danger">*</span></label>
                    <input type="text" name="lokacija" class="form-control" placeholder="npr. Split, Hrvatska" value="<?= htmlspecialchars($_POST['lokacija'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Opis projekta</label>
                    <textarea name="opis" class="form-control" rows="4" placeholder="Kratki opis projekta..."><?= htmlspecialchars($_POST['opis'] ?? '') ?></textarea>
                </div>
                <div class="mb-0">
                    <label class="form-label fw-semibold">Datum ugradnje</label>
                    <input type="date" name="datum_ugradnje" class="form-control" style="max-width:220px" value="<?= htmlspecialchars($_POST['datum_ugradnje'] ?? '') ?>">
                </div>
            </div>
        </div>

        <!-- TEHNIČKE SPECIFIKACIJE -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="section-label">Tehničke specifikacije</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Snaga elektrane (kWp)</label>
                        <input type="number" name="snaga_kw" class="form-control" step="0.01" min="0" placeholder="npr. 9.90" value="<?= htmlspecialchars($_POST['snaga_kw'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tip panela</label>
                        <input type="text" name="tip_panela" class="form-control" placeholder="npr. JA Solar 415W" value="<?= htmlspecialchars($_POST['tip_panela'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tip invertera</label>
                        <input type="text" name="tip_invertera" class="form-control" placeholder="npr. Huawei SUN2000-10KTL" value="<?= htmlspecialchars($_POST['tip_invertera'] ?? '') ?>">
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label fw-semibold">Napomena / dodatne pojedinosti</label>
                    <textarea name="napomena" class="form-control" rows="2" placeholder="Baterijska pohrana, posebnosti ugradnje..."><?= htmlspecialchars($_POST['napomena'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- SLIKE -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="section-label">Slike projekta</div>
                <p class="text-muted" style="font-size:.88rem">Možeš odabrati više slika odjednom. Maksimalna veličina po slici: 5 MB. Formati: JPG, PNG, WebP.</p>
                <input type="file" name="slike[]" id="slike" class="form-control" accept="image/*" multiple>
                <div id="preview-zona"></div>
            </div>
        </div>

        <!-- OBJAVA -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="aktivan" id="aktivan" <?= (!isset($_POST['aktivan']) || $_POST['aktivan']) ? 'checked' : '' ?>>
                    <label class="form-check-label fw-semibold" for="aktivan">Objavi odmah (vidljivo na stranici)</label>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-brand px-4">
                <i class="bi bi-check-lg me-1"></i> Spremi projekt
            </button>
            <a href="dashboard.php" class="btn btn-outline-secondary px-4">Odustani</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('slike').addEventListener('change', function() {
    const zona = document.getElementById('preview-zona');
    zona.innerHTML = '';
    Array.from(this.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            zona.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});
</script>
</body>
</html>
