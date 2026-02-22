<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../php/db.php';

$db = getDB();

// Brisanje projekta
if (isset($_GET['obrisi']) && is_numeric($_GET['obrisi'])) {
    $id = (int)$_GET['obrisi'];

    // Dohvati slike da ih obrišemo s diska
    $slike = $db->prepare('SELECT putanja FROM projekt_slike WHERE projekt_id = ?');
    $slike->execute([$id]);
    foreach ($slike->fetchAll() as $slika) {
        $putanja = __DIR__ . '/../' . $slika['putanja'];
        if (file_exists($putanja)) unlink($putanja);
    }

    $db->prepare('DELETE FROM projekti WHERE id = ?')->execute([$id]);
    header('Location: dashboard.php?poruka=obrisan');
    exit;
}

// Svi projekti
$projekti = $db->query('SELECT p.*, COUNT(s.id) AS broj_slika
    FROM projekti p
    LEFT JOIN projekt_slike s ON s.projekt_id = p.id
    GROUP BY p.id
    ORDER BY p.datum_objave DESC')->fetchAll();

$poruka = $_GET['poruka'] ?? '';
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin – Projekti</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background: #f0f4f8; }
        .topbar { background: linear-gradient(90deg, #0f2f59, #164480); color: white; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; }
        .topbar a { color: rgba(255,255,255,0.8); text-decoration: none; font-size: 0.9rem; }
        .topbar a:hover { color: white; }
        .content { max-width: 1100px; margin: 30px auto; padding: 0 16px; }
        .status-aktivan { color: #198754; font-weight: 600; }
        .status-neaktivan { color: #6c757d; }
        th { font-size: 0.85rem; color: #6c757d; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    </style>
</head>
<body>
<div class="topbar">
    <div><strong>Solar Engineering</strong> <span style="opacity:.5">|</span> Admin panel</div>
    <div class="d-flex gap-3 align-items-center">
        <a href="../projekti.php" target="_blank"><i class="bi bi-box-arrow-up-right"></i> Javna stranica</a>
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Odjava</a>
    </div>
</div>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold">Projekti</h4>
        <a href="novi-projekt.php" class="btn btn-sm" style="background:#164480;color:white;font-weight:600;">
            <i class="bi bi-plus-lg"></i> Novi projekt
        </a>
    </div>

    <?php if ($poruka === 'dodan'): ?>
        <div class="alert alert-success py-2">Projekt uspješno dodan.</div>
    <?php elseif ($poruka === 'uredjeno'): ?>
        <div class="alert alert-success py-2">Projekt uspješno ažuriran.</div>
    <?php elseif ($poruka === 'obrisan'): ?>
        <div class="alert alert-warning py-2">Projekt obrisan.</div>
    <?php endif; ?>

    <?php if (empty($projekti)): ?>
        <div class="card border-0 shadow-sm p-5 text-center text-muted">
            <i class="bi bi-folder2-open fs-1 mb-3"></i>
            <p class="mb-0">Nema projekata. <a href="novi-projekt.php">Dodaj prvi projekt →</a></p>
        </div>
    <?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:40px">#</th>
                        <th>Naslov</th>
                        <th>Lokacija</th>
                        <th>Snaga</th>
                        <th>Slike</th>
                        <th>Status</th>
                        <th>Datum</th>
                        <th style="width:120px"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projekti as $p): ?>
                    <tr>
                        <td class="text-muted"><?= $p['id'] ?></td>
                        <td><strong><?= htmlspecialchars($p['naslov']) ?></strong></td>
                        <td><?= htmlspecialchars($p['lokacija']) ?></td>
                        <td><?= $p['snaga_kw'] ? $p['snaga_kw'] . ' kWp' : '—' ?></td>
                        <td><span class="badge bg-secondary"><?= $p['broj_slika'] ?></span></td>
                        <td>
                            <?php if ($p['aktivan']): ?>
                                <span class="status-aktivan"><i class="bi bi-circle-fill" style="font-size:.55rem"></i> Objavljeno</span>
                            <?php else: ?>
                                <span class="status-neaktivan"><i class="bi bi-circle" style="font-size:.55rem"></i> Skriveno</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted" style="font-size:.85rem"><?= date('d.m.Y', strtotime($p['datum_objave'])) ?></td>
                        <td class="text-end">
                            <a href="uredi-projekt.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-secondary me-1" title="Uredi">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="dashboard.php?obrisi=<?= $p['id'] ?>"
                               class="btn btn-sm btn-outline-danger"
                               title="Obriši"
                               onclick="return confirm('Obrisati projekt i sve slike?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
