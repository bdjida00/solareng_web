<?php
// =============================================
// Admin konfiguracija
// Za promjenu lozinke, generiraj novi hash:
//   php -r "echo password_hash('nova_lozinka', PASSWORD_DEFAULT);"
// =============================================

define('ADMIN_USER', 'admin');

// Defaultna lozinka: solareng2024
// OBAVEZNO promijeni prije deploy-a!
define('ADMIN_PASS_HASH', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

define('UPLOAD_DIR', __DIR__ . '/../uploads/projekti/');
define('UPLOAD_URL', '../uploads/projekti/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
