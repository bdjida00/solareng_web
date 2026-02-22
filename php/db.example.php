<?php
// =============================================
// TEMPLATE - kopiraj u db.php i popuni podacima
// cp php/db.example.php php/db.php
// =============================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'NAZIV_BAZE');     // npr. solareng_solardb
define('DB_USER', 'KORISNIK_BAZE'); // npr. solareng_admin
define('DB_PASS', 'LOZINKA_BAZE');

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            die('Greška konekcije na bazu. Provjerite php/db.php konfiguraciju.');
        }
    }
    return $pdo;
}
