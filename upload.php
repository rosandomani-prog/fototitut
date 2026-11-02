<?php
require __DIR__ . '/config.php';

function back(string $q) { header('Location: index.php?' . $q); exit; }

if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
    !hash_equals($_SESSION['token'], $_POST['token'] ?? '')) {
    back('err=' . urlencode('Sesi tidak valid, coba muat ulang halaman.'));
}
if (UPLOAD_PASSWORD !== '' && !hash_equals(UPLOAD_PASSWORD, $_POST['pass'] ?? '')) {
    back('err=' . urlencode('Password unggah salah.'));
}

$caption  = mb_substr(trim($_POST['caption']  ?? ''), 0, 160);
$uploader = mb_substr(trim($_POST['uploader'] ?? ''), 0, 60);

$allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
$files = $_FILES['photos'] ?? null;
if (!$files || empty($files['name'][0])) {
    back('err=' . urlencode('Pilih minimal satu foto dulu.'));
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$saved = 0; $skipped = 0;
$stmt = db()->prepare('INSERT INTO photos (filename, caption, uploader) VALUES (?, ?, ?)');

foreach ($files['name'] as $i => $name) {
    if ($files['error'][$i] !== UPLOAD_ERR_OK) { $skipped++; continue; }
    if ($files['size'][$i] > MAX_FILE_MB * 1024 * 1024) { $skipped++; continue; }
    $mime = $finfo->file($files['tmp_name'][$i]);
    if (!isset($allowed[$mime]) || !@getimagesize($files['tmp_name'][$i])) { $skipped++; continue; }

    $fn = bin2hex(random_bytes(10)) . '.' . $allowed[$mime];
    if (move_uploaded_file($files['tmp_name'][$i], UPLOAD_DIR . $fn)) {
        $stmt->execute([$fn, $caption, $uploader]);
        $saved++;
    } else { $skipped++; }
}

$q = 'ok=' . $saved;
if ($skipped) $q .= '&skip=' . $skipped;
back($q);
