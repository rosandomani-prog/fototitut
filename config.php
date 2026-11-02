<?php
// ====== SESUAIKAN BAGIAN INI DENGAN HOSTING KAMU ======
const DB_HOST = 'localhost';
const DB_NAME = 'galeri_titut';
const DB_USER = 'root';
const DB_PASS = '';

// Password untuk MENGHAPUS foto (wajib diganti!)
const ADMIN_PASSWORD = 'ganti-password-ini';

// Password untuk MENGUNGGAH foto. Kosongkan ('') kalau siapa saja boleh unggah.
const UPLOAD_PASSWORD = '';

const MAX_FILE_MB = 8;                 // batas ukuran per foto
const UPLOAD_DIR  = __DIR__ . '/uploads/';
// =======================================================

session_start();
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(16));
}

function db(): PDO {
    static $pdo = null;
    if (!$pdo) {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER, DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
    }
    return $pdo;
}

function h($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
