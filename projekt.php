<?php
require_once __DIR__ . '/php/db.php';

$db = getDB();
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $db->prepare('SELECT * FROM projekti WHERE id = ? AND aktivan = 1');
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) {
    header('Location: projekti.php');
    exit;
}

$slike = $db->prepare('SELECT * FROM projekt_slike WHERE projekt_id = ? ORDER BY redoslijed');
$slike->execute([$id]);
$slike = $slike->fetchAll();
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($p['naslov']) ?> – Solar Engineering</title>
    <meta name="description" content="<?= htmlspecialchars(mb_substr($p['opis'] ?? '', 0, 155)) ?>">
    <link rel="shortcut icon" type="image/png" href="pictures/newlogo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;0,900;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/global.css">
    <style>
        .breadcrumb-bar { background: #f0f4f8; padding: 12px 0; border-bottom: 1px solid #e2e8f0; }
        .breadcrumb-bar a { color: #164480; text-decoration: none; font-size: .88rem; }
        .breadcrumb-bar a:hover { text-decoration: underline; }
        .breadcrumb-bar span { color: #6c757d; font-size: .88rem; }

        .projekt-hero { background: linear-gradient(135deg, #0f2f59, #164480); color: white; padding: 48px 20px; }
        .projekt-hero h1 { font-weight: 800; font-size: clamp(1.5rem, 3.5vw, 2.2rem); margin-bottom: 8px; }
        .projekt-hero .lokacija { opacity: .8; font-size: .95rem; }

        .spec-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; }
        .spec-item { background: #f0f4f8; border-radius: 10px; padding: 16px 20px; }
        .spec-item .label { font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #6c757d; margin-bottom: 4px; }
        .spec-item .value { font-size: 1.05rem; font-weight: 700; color: #164480; }

        /* Gallery grid */
        .gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; }
        .gallery-item { border-radius: 8px; overflow: hidden; cursor: pointer; aspect-ratio: 4/3; }
        .gallery-item img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s ease; }
        .gallery-item:hover img { transform: scale(1.05); }

        /* Lightbox */
        #lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.92); z-index: 9999; align-items: center; justify-content: center; }
        #lightbox.aktivan { display: flex; }
        #lightbox img { max-width: 92vw; max-height: 90vh; border-radius: 6px; object-fit: contain; }
        #lightbox .zatvori { position: fixed; top: 20px; right: 28px; color: white; font-size: 2rem; cursor: pointer; line-height: 1; opacity: .8; background: none; border: none; }
        #lightbox .zatvori:hover { opacity: 1; }
        #lightbox .prev, #lightbox .next { position: fixed; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,.15); border: none; color: white; font-size: 1.8rem; padding: 10px 16px; border-radius: 6px; cursor: pointer; transition: background .2s; }
        #lightbox .prev { left: 16px; }
        #lightbox .next { right: 16px; }
        #lightbox .prev:hover, #lightbox .next:hover { background: rgba(255,255,255,.3); }
    </style>
</head>
<body>

    <!--TOP_BAR-->
    <div class="top-bar">
        <div class="contact-info">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-at" viewBox="0 0 16 16"><path d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2H2Zm3.708 6.208L1 11.105V5.383l4.708 2.825ZM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2-7-4.2Z"/><path d="M14.247 14.269c1.01 0 1.587-.857 1.587-2.025v-.21C15.834 10.43 14.64 9 12.52 9h-.035C10.42 9 9 10.36 9 12.432v.214C9 14.82 10.438 16 12.358 16h.044c.594 0 1.018-.074 1.237-.175v-.73c-.245.11-.673.18-1.18.18h-.044c-1.334 0-2.571-.788-2.571-2.655v-.157c0-1.657 1.058-2.724 2.64-2.724h.04c1.535 0 2.484 1.05 2.484 2.326v.118c0 .975-.324 1.39-.639 1.39-.232 0-.41-.148-.41-.42v-2.19h-.906v.569h-.03c-.084-.298-.368-.63-.954-.63-.778 0-1.259.555-1.259 1.4v.528c0 .892.49 1.434 1.26 1.434.471 0 .896-.227 1.014-.643h.043c.118.42.617.648 1.12.648Zm-2.453-1.588v-.227c0-.546.227-.791.573-.791.297 0 .572.192.572.708v.367c0 .573-.253.744-.564.744-.354 0-.581-.215-.581-.8Z"/></svg>
            <p> info@solareng.eu</p>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16"><path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/></svg>
            <p> +385 95 820 8237</p>
        </div>
    </div>

    <!--NAVBAR-->
    <nav class="navbar navbar-expand-lg sticky-top border-bottom">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <img class="desktop-logo" src="pictures/newlogo.png" alt="Logo">
                <img class="mobile-logo" src="pictures/newlogo.png" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item order-4">
                        <a class="nav-link active" href="projekti.php" style="text-align:center;">Projekti</a>
                    </li>
                    <li class="nav-item order-3">
                        <a class="nav-link" href="upitnik.html" style="text-align:center;">Besplatni upitnik</a>
                    </li>
                    <li class="nav-item order-2">
                        <a class="nav-link" href="index.html#kontakt" style="text-align:center;">Kontakt</a>
                    </li>
                    <li class="nav-item dropdown order-1">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Naše usluge</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="projektiranje.html">Projektiranje</a>
                            <a class="dropdown-item" href="on-grid.html">Mrežni fotonaponski sustavi</a>
                            <a class="dropdown-item" href="hybrid.html">Hibridni fotonaponski sustavi</a>
                            <a class="dropdown-item" href="elektroinstalacije.html">Elektroinstalacijske usluge</a>
                            <a class="dropdown-item" href="oprema.html">Oprema koju nudimo</a>
                        </div>
                    </li>
                    <li class="nav-item order-0">
                        <a class="nav-link" href="/" style="text-align:center;">Početna</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- BREADCRUMB -->
    <div class="breadcrumb-bar">
        <div class="container">
            <a href="projekti.php"><i class="bi bi-arrow-left me-1"></i>Svi projekti</a>
            <span class="mx-2">/</span>
            <span><?= htmlspecialchars($p['naslov']) ?></span>
        </div>
    </div>

    <!-- HERO -->
    <div class="projekt-hero">
        <div class="container">
            <h1><?= htmlspecialchars($p['naslov']) ?></h1>
            <div class="lokacija"><i class="bi bi-geo-alt-fill me-1"></i><?= htmlspecialchars($p['lokacija']) ?></div>
            <?php if ($p['datum_ugradnje']): ?>
                <div class="mt-2" style="opacity:.7;font-size:.85rem">
                    <i class="bi bi-calendar3 me-1"></i>Ugradnja: <?= date('d.m.Y', strtotime($p['datum_ugradnje'])) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- SADRŽAJ -->
    <div class="container my-5">
        <div class="row g-5">

            <!-- LIJEVO: opis + slike -->
            <div class="col-lg-8">
                <?php if ($p['opis']): ?>
                <div class="mb-5">
                    <h4 class="fw-bold mb-3" style="color:#164480">O projektu</h4>
                    <p style="line-height:1.8;color:#444;"><?= nl2br(htmlspecialchars($p['opis'])) ?></p>
                </div>
                <?php endif; ?>

                <?php if (!empty($slike)): ?>
                <div>
                    <h4 class="fw-bold mb-3" style="color:#164480">Galerija</h4>
                    <div class="gallery">
                        <?php foreach ($slike as $i => $s): ?>
                        <div class="gallery-item" onclick="openLightbox(<?= $i ?>)">
                            <img src="<?= htmlspecialchars($s['putanja']) ?>" alt="Slika <?= $i+1 ?>" loading="lazy">
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- DESNO: specifikacije -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 mb-4">
                    <h5 class="fw-bold mb-4" style="color:#164480;border-bottom:2px solid #f5c518;padding-bottom:12px;">Tehničke specifikacije</h5>
                    <div class="spec-grid">
                        <?php if ($p['snaga_kw']): ?>
                        <div class="spec-item">
                            <div class="label"><i class="bi bi-lightning-charge-fill me-1"></i>Snaga</div>
                            <div class="value"><?= $p['snaga_kw'] ?> kWp</div>
                        </div>
                        <?php endif; ?>
                        <?php if ($p['tip_panela']): ?>
                        <div class="spec-item">
                            <div class="label"><i class="bi bi-sun me-1"></i>Panel</div>
                            <div class="value"><?= htmlspecialchars($p['tip_panela']) ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if ($p['tip_invertera']): ?>
                        <div class="spec-item">
                            <div class="label"><i class="bi bi-cpu me-1"></i>Inverter</div>
                            <div class="value"><?= htmlspecialchars($p['tip_invertera']) ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if ($p['lokacija']): ?>
                        <div class="spec-item">
                            <div class="label"><i class="bi bi-geo-alt me-1"></i>Lokacija</div>
                            <div class="value"><?= htmlspecialchars($p['lokacija']) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($p['napomena']): ?>
                    <div class="mt-4 pt-3" style="border-top:1px solid #e9ecef;">
                        <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6c757d;margin-bottom:6px;">Napomena</div>
                        <p style="font-size:.9rem;color:#444;margin:0;"><?= nl2br(htmlspecialchars($p['napomena'])) ?></p>
                    </div>
                    <?php endif; ?>
                </div>

                <a href="upitnik.html" class="btn w-100" style="background:#164480;color:white;font-weight:700;padding:12px;border-radius:8px;">
                    <i class="bi bi-clipboard-check me-2"></i>Zatraži besplatnu ponudu
                </a>
            </div>

        </div>
    </div>

    <!-- LIGHTBOX -->
    <div id="lightbox">
        <button class="zatvori" onclick="closeLightbox()">×</button>
        <button class="prev" onclick="changeLightbox(-1)"><i class="bi bi-chevron-left"></i></button>
        <img id="lightbox-img" src="" alt="">
        <button class="next" onclick="changeLightbox(1)"><i class="bi bi-chevron-right"></i></button>
    </div>

    <!-- FOOTER -->
    <footer class="bg-secondary text-white mt-5" id="kontakt">
        <div class="container p-4">
            <div class="row">
                <div class="col-lg-4 col-md-8 mb-4 mb-md-0">
                    <h5 class="text-uppercase">O nama</h5>
                    <p>Solar Engineering d.o.o je tvrtka specijalizirana za projektiranje, montažu i održavanje solarnih fotonaponskih sustava.</p>
                </div>
                <div class="col-lg-5 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Kontakt</h5>
                    <ul class="list-unstyled mb-0">
                        <li><b>Solar Engineering d.o.o</b></li>
                        <li><i class="bi bi-house-fill me-1"></i> Sjedište: Treća ulica 2, 21236 Vrlika</li>
                        <li><i class="bi bi-house-fill me-1"></i> Ured: Cesta dr. Franje Tuđmana 736, 21217 Kaštel Novi</li>
                        <li><i class="bi bi-telephone-fill me-1"></i> +385 95 820 8237</li>
                        <li><i class="bi bi-telephone-fill me-1"></i> +385 95 904 8319</li>
                        <li><i class="bi bi-envelope-at-fill me-1"></i> info@solareng.eu</li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <ul class="list-unstyled">
                        <li><b>OIB:</b> 41205362462</li>
                        <li><b>MBS:</b> 060458016</li>
                        <li>Trgovački sud u Splitu</li>
                        <li>Temeljni kapital: 13.272,28 €</li>
                        <li><b>IBAN:</b> HR6723400091111216182</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="text-center p-3" style="background-color:rgb(22,68,128);color:rgb(174,174,174);">
            Solar Engineering d.o.o © 2023 - by studio <a href="https://www.instagram.com/_d.web/">d.web</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    const slike = <?= json_encode(array_column($slike, 'putanja')) ?>;
    let trenutni = 0;

    function openLightbox(i) {
        trenutni = i;
        document.getElementById('lightbox-img').src = slike[i];
        document.getElementById('lightbox').classList.add('aktivan');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('aktivan');
        document.body.style.overflow = '';
    }

    function changeLightbox(dir) {
        trenutni = (trenutni + dir + slike.length) % slike.length;
        document.getElementById('lightbox-img').src = slike[trenutni];
    }

    document.getElementById('lightbox').addEventListener('click', function(e) {
        if (e.target === this) closeLightbox();
    });

    document.addEventListener('keydown', function(e) {
        if (!document.getElementById('lightbox').classList.contains('aktivan')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') changeLightbox(-1);
        if (e.key === 'ArrowRight') changeLightbox(1);
    });
    </script>
</body>
</html>
