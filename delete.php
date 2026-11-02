<?php
require __DIR__ . '/config.php';

function back(string $q) { header('Location: index.php?' . $q); exit; }

if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
    !hash_equals($_SESSION['token'], $_POST['token'] ?? '')) {
    back('err=' . urlencode('Sesi tidak valid.'));
}
if (!hash_equals(ADMIN_PASSWORD, $_POST['password'] ?? '')) {
    back('err=' . urlencode('Password hapus salah.'));
}

$id = (int)($_POST['id'] ?? 0);
$st = db()->prepare('SELECT filename FROM photos WHERE id = ?');
$st->execute([$id]);
if ($row = $st->fetch()) {
    $path = UPLOAD_DIR . basename($row['filename']);
    if (is_file($path)) unlink($path);
    db()->prepare('DELETE FROM photos WHERE id = ?')->execute([$id]);
    back('del=1');
}
back('err=' . urlencode('Foto tidak ditemukan.'));
