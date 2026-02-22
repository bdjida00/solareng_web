<?php
// Provjera admin sesije - uključiti na svim zaštićenim stranicama
require_once __DIR__ . '/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}
