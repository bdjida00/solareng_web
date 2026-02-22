<?php
require_once __DIR__ . '/php/db.php';
$db = getDB();
$zadnji_projekti = $db->query('
    SELECT p.*,
           (SELECT putanja FROM projekt_slike WHERE projekt_id = p.id ORDER BY redoslijed LIMIT 1) AS naslovna_slika
    FROM projekti p
    WHERE p.aktivan = 1
    ORDER BY p.datum_objave DESC
    LIMIT 4
')->fetchAll();
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="google-site-verification" content="2PABFpwUenBb_WjBSR3PW-NeYx0T1Dln6eoG9-oPGXA" />
    <title>Solar Engineering d.o.o</title>
    <meta name="description" content="Solar Engineering d.o.o je tvrtka specijalizirana za projektiranje, montažu i održavanje solarnih fotonaponskih sustava.">
    <link rel="shortcut icon" type="image/png" href="pictures/newlogo.png">
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;0,900;1,700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/global.css">
    <style>
        .project-card { border: none; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.08); transition: transform .25s ease, box-shadow .25s ease; height: 100%; text-decoration: none; color: inherit; display: flex; flex-direction: column; }
        .project-card:hover { transform: translateY(-6px); box-shadow: 0 8px 28px rgba(0,0,0,0.15); color: inherit; }
        .project-card .thumb { height: 200px; object-fit: cover; width: 100%; }
        .project-card .thumb-placeholder { height: 200px; background: #e9ecef; display: flex; align-items: center; justify-content: center; color: #adb5bd; font-size: 3rem; }
        .project-card .card-body-inner { padding: 16px; flex-grow: 1; display: flex; flex-direction: column; }
        .project-card .naslov { font-size: 1rem; font-weight: 700; color: #164480; margin-bottom: 4px; }
        .project-card .lokacija { font-size: 0.82rem; color: #6c757d; margin-bottom: 10px; }
        .project-card .specs { margin-top: auto; display: flex; flex-wrap: wrap; gap: 5px; }
        .spec-badge { background: #eef2f8; color: #164480; font-size: 0.75rem; font-weight: 600; border-radius: 20px; padding: 2px 9px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .section-header h2 { margin-bottom: 0; }
    </style>

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-C3HY7Z62M1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-C3HY7Z62M1');
    </script>

    <!-- Meta Pixel Code -->
    <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '793325625940658');
      fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=793325625940658&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
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
    <nav class="navbar navbar-expand-lg sticky-top border-bottom" style="background-color: rgba(255, 255, 255, 0.95); box-shadow: 0 2px 4px rgba(0,0,0,0.3);">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <img class="desktop-logo" src="pictures/newlogo.png" alt="Desktop Logo">
                <img class="mobile-logo" src="pictures/newlogo.png" alt="Mobile Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item order-4">
                        <a class="nav-link" href="projekti.php" style="text-align:center;">Projekti</a>
                    </li>
                    <li class="nav-item order-3">
                        <a class="nav-link" href="upitnik.html" style="text-align:center;">Besplatni upitnik</a>
                    </li>
                    <li class="nav-item order-2">
                        <a class="nav-link" href="#kontakt" style="text-align:center;">Kontakt</a>
                    </li>
                    <li class="nav-item dropdown order-1">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Naše usluge</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
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

    <!--HERO_IMAGE-->
    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleFade" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleFade" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleFade" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="pictures/bg3_edit_dimm.jpg" class="w-100 img-fluid" id="carousel-img">
                <div class="carousel-caption">
                    <h2>Solarna energija za održive domove</h2>
                    <p>Otkrijte prednosti solarnih elektrana za kućanstva i smanjite troškove energije dok doprinosite zaštiti okoliša.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="pictures/bg1.jpg" class="w-100 img-fluid" id="carousel-img">
                <div class="carousel-caption">
                    <h2>Energetska neovisnost za vašu kompaniju</h2>
                    <p>Naša rješenja solarnih elektrana pružaju sigurnost i dugoročne uštede za vašu kompaniju, osiguravajući energetsku nezavisnost i smanjenje emisija CO2.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="pictures/carouselimg.jpeg" class="d-block w-100" id="carousel-img">
                <div class="carousel-caption">
                    <h2>Stručno projektiranje i ugradnja solarnih elektrana</h2>
                    <p>Sa našim iskusnim timom inženjera, osiguravamo vrhunsku kvalitetu i profesionalnost prilikom projektiranja i ugradnje solarnih elektrana za vaše potrebe.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!--3_CARDS-->
    <div class="container marketing mt-5 mb-5">
        <div class="row">
            <div class="col-lg-4">
                <div class="content-wrapper">
                    <img src="pictures/energy-saving.png" alt="Image" class="rounded-img" id="three_cards_img">
                    <h2 class="fw-normal">Zelena energija</h2>
                    <p>Ugradnjom solarnih elektrana, osiguravate održivu budućnost i aktivno doprinosite tranziciji ka čistoj, zelenoj energiji, smanjujući svoju ekološku stopu i nezavisnost od fosilnih goriva.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="content-wrapper">
                    <img src="pictures/zarulja.png" alt="Image" class="rounded-img" id="three_cards_img">
                    <h2 class="fw-normal">Ušteda</h2>
                    <p>Ugradnjom solarnih elektrana, ostvarujete značajne uštede na dugoročnom planu, jer tako proizvodite vlastitu električnu energiju iz obnovljivih izvora. Povrat investicije je moguće značajno smanjiti sufinanciranjem iz fondova.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="content-wrapper">
                    <img src="pictures/solar_icon.png" alt="Image" class="rounded-img" id="three_cards_img">
                    <h2 class="fw-normal">Kvaliteta</h2>
                    <p>Naša ponuda opreme ističe se vrhunskom kvalitetom, pouzdanošću i visokim stupnjem efikasnosti, pružajući vam dugotrajan izvor obnovljive energije i sigurnost u ulaganju u održivu budućnost.</p>
                </div>
            </div>
        </div>
    </div>

    <!--TEXT-->
    <div class="purple-container">
        <div class="container text-center">
            <div id="text">
                <h1><i>Pametno ulaganje za bolju budućnost</i></h1>
                <h4>Prosječni povrat investicija je 6-9 godina, a uz stopu pdv-a od 0% te moguće EU sufinanciranje,
                    to vrijeme se značajno smanjuje. Za više informacija obratite nam se putem <a href="#kontakt" id="text-link">kontakt forme</a> ili <a href="upitnik.html" id="text-link">besplatnog upitnika</a>.</h4>
            </div>
        </div>
    </div>

    <!--CARDS – NAŠE USLUGE-->
    <div class="container mt-5" id="nase_usluge_link">
        <h2><b>NAŠE USLUGE</b></h2>
        <div class="row">
            <div class="col-md-3 mt-4">
                <div class="card shadow">
                    <a href="projektiranje.html"><img src="pictures/projektiranje.jpg" class="card-img-top" alt="Projektiranje"></a>
                    <div class="card-body">
                        <h5 class="card-title">Projektiranje</h5>
                        <p class="card-text">Izrada projektne dokumentacije, optimiziranje elektrane za vaše potrebe, kompletna komunikacija sa HEP-om, rješavanje papirologije.</p>
                        <a class="btn" href="projektiranje.html" role="button" style="background-color: rgb(22, 68, 128); color:white">Saznajte više</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mt-4">
                <div class="card shadow">
                    <a href="on-grid.html"><img src="pictures/on_grid.png" class="card-img-top" alt="On-grid"></a>
                    <div class="card-body">
                        <h5 class="card-title">Mrežni fotonaponski sustavi</h5>
                        <p class="card-text">Solarne elektrane povezane sa električnom mrežom, bez baterijske pohrane. Najpopularniji sustavi u Hrvatskoj.</p>
                        <a class="btn" href="on-grid.html" role="button" style="background-color: rgb(22, 68, 128); color:white">Saznajte više</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mt-4">
                <div class="card shadow">
                    <a href="hybrid.html"><img src="pictures/hybrid.png" class="card-img-top" alt="Hibridni"></a>
                    <div class="card-body">
                        <h5 class="card-title">Hibridni fotonaponski sustavi</h5>
                        <p class="card-text">Solarne elektrane povezane sa električnom mrežom, ali sadrže i baterijsku pohranu. Optimalan pristup izvedbe solarne elektrane.</p>
                        <a class="btn" href="hybrid.html" role="button" style="background-color: rgb(22, 68, 128); color:white">Saznajte više</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mt-4">
                <div class="card shadow">
                    <a href="elektroinstalacije.html"><img src="pictures/elektroinstalacije_thumb1.jpg" class="card-img-top" alt="Elektroinstalacije"></a>
                    <div class="card-body">
                        <h5 class="card-title">Elektroinstalacijske usluge</h5>
                        <p class="card-text">Nudimo postavljanje i servis električnih instalacija, brzu dijagnostiku i sanaciju kvarova, izradu elektro projekata...</p>
                        <a class="btn" href="elektroinstalacije.html" role="button" style="background-color: rgb(22, 68, 128); color:white">Saznajte više</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--ZADNJI PROJEKTI-->
    <?php if (!empty($zadnji_projekti)): ?>
    <div style="background:#f0f4f8; padding:3.5rem 0; margin-top:3rem;">
    <div class="container">
        <div class="section-header">
            <h2><b>NAŠI PROJEKTI</b></h2>
            <a href="projekti.php" class="btn" style="background:#164480;color:white;font-weight:600;border-radius:8px;">Svi projekti &rarr;</a>
        </div>
        <div class="row g-4">
            <?php foreach ($zadnji_projekti as $p): ?>
            <div class="col-md-6 col-lg-3">
                <a href="projekt.php?id=<?= $p['id'] ?>" class="project-card">
                    <?php if ($p['naslovna_slika']): ?>
                        <img src="<?= htmlspecialchars($p['naslovna_slika']) ?>" class="thumb" alt="<?= htmlspecialchars($p['naslov']) ?>">
                    <?php else: ?>
                        <div class="thumb-placeholder"><i class="bi bi-image"></i></div>
                    <?php endif; ?>
                    <div class="card-body-inner">
                        <div class="naslov"><?= htmlspecialchars($p['naslov']) ?></div>
                        <div class="lokacija"><i class="bi bi-geo-alt-fill me-1"></i><?= htmlspecialchars($p['lokacija']) ?></div>
                        <div class="specs">
                            <?php if ($p['snaga_kw']): ?>
                                <span class="spec-badge"><i class="bi bi-lightning-charge-fill me-1"></i><?= $p['snaga_kw'] ?> kWp</span>
                            <?php endif; ?>
                            <?php if ($p['tip_panela']): ?>
                                <span class="spec-badge"><i class="bi bi-sun me-1"></i><?= htmlspecialchars($p['tip_panela']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    </div>
    <?php endif; ?>

    <!--TEXT – OPREMA-->
    <div class="black-container mt-5">
        <div class="container text-center">
            <div id="text">
                <h1><i><a href="oprema.html" id="text-link">Saznajte više</a> o opremi koju nudimo</i></h1>
                <h4>Vrhunska oprema svjetskih top brandova omogoćuje iskorištavanje sunčeve energije sa visokim stupnjem učinkovitosti.</h4>
            </div>
        </div>
    </div>

    <!--FOOTER_IMAGE_UPITNIK-->
    <div class="container-fluid d-flex justify-content-center align-items-center" id="footer_image">
        <div class="card" id="formular">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-9">
                        <h5 class="card-title">Besplatni izračun i ponuda solarne elektrane</h5>
                        <p class="card-text">Ispunite upitnik, a naš tim će evaluirati projekt i poslati ponudu.</p>
                    </div>
                    <div class="col">
                        <a href="upitnik.html"><button class="btn" id="tipka_formular">Upitnik</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--KONTAKT_FORMA-->
    <div class="container mb-5" id="kontakt-forma">
        <div class="row">
            <div class="col-md-6 mt-5">
                <h1 class="text-center mb-4">Kontaktirajte nas</h1>
                <h5 class="text-center mb-5">Postavite nam pitanje i naš tim će vam se obratiti u najkraćem mogućem roku.</h5>
                <form id="email-form">
                    <div class="form-group">
                        <label for="name">Ime i prezime:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Poruka:</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" id="submit-btn" class="btn">Pošalji</button>
                </form>
                <div id="status"></div>
            </div>
            <div class="col-md-6 mt-5">
                <h1 class="text-center">Lokacija</h1>
                <div class="map-container mt-5">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d797.3060658726602!2d16.408844611287098!3d43.93430706760289!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47601b76c3057f69%3A0xc86d59e75c60e2!2sSolar%20Engineering%20d.o.o.!5e1!3m2!1shr!2shr!4v1686772711508!5m2!1shr!2shr" width="600" height="600" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-secondary text-white" id="kontakt">
        <div class="container p-4">
            <div class="row">
                <div class="col-lg-4 col-md-8 mb-4 mb-md-0">
                    <h5 class="text-uppercase">O nama</h5>
                    <p>Solar Engineering d.o.o je tvrtka specijalizirana za projektiranje, montažu i održavanje solarnih fotonaponskih sustava. Prateći tržište i trendove, nudimo najmodernija rješenja.</p>
                </div>
                <div class="col-lg-5 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Kontakt</h5>
                    <ul class="list-unstyled mb-0">
                        <li><b>Solar Engineering d.o.o</b></li>
                        <li><i class="bi bi-house-fill me-1"></i> Sjedište: Treća ulica 2, 21236 Vrlika</li>
                        <li><i class="bi bi-house-fill me-1"></i> Ured: Cesta dr. Franje Tuđmana 736, 21217 Kaštel Novi</li>
                        <li><i class="bi bi-telephone-fill me-1"></i> Mob 1: +385 95 820 8237</li>
                        <li><i class="bi bi-telephone-fill me-1"></i> Mob 2: +385 95 904 8319</li>
                        <li><i class="bi bi-envelope-at-fill me-1"></i> E-mail: info@solareng.eu</li>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/send-email.js"></script>
    <script src="js/upitnik.js"></script>
</body>
</html>
