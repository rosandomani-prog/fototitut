<?php
require __DIR__ . '/config.php';

$photos = db()->query('SELECT * FROM photos ORDER BY created_at DESC, id DESC')->fetchAll();

// Tiap foto punya "gaya tempel" yang acak tapi tetap (berdasarkan id)
function scrap(int $id): array {
    mt_srand($id * 7919 + 13);
    $variants = ['polaroid', 'polaroid', 'taped', 'torn', 'film', 'polaroid'];
    $papers   = ['#e9e7e1', '#dfe3ea', '#e6dfd2', '#d9dde6'];
    $tapes    = ['rgba(224,165,58,.62)', 'rgba(201,143,154,.6)', 'rgba(111,139,176,.55)', 'rgba(233,231,225,.55)'];
    return [
        'v'     => $variants[mt_rand(0, 5)],
        'r'     => mt_rand(-70, 70) / 10,
        'w'     => mt_rand(205, 285),
        'mt'    => mt_rand(-10, 40),
        'ml'    => mt_rand(-14, 10),
        'tr'    => mt_rand(-80, 80) / 10,
        'paper' => $papers[mt_rand(0, 3)],
        'tape'  => $tapes[mt_rand(0, 3)],
    ];
}
$n = count($photos);
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Galeri Titut</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;700&family=Special+Elite&family=Work+Sans:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header class="top">
  <h1>Galeri Titut</h1>
  <p class="tag">potongan-potongan kita, ditempel di satu dinding.</p>
  <p class="count"><?= $n ?> foto tertempel</p>
</header>

<?php if (isset($_GET['ok'])): ?>
  <div class="note ok"><?= (int)$_GET['ok'] ?> foto berhasil ditempel<?= !empty($_GET['skip']) ? ', ' . (int)$_GET['skip'] . ' dilewati (format/ukuran tidak sesuai)' : '' ?>.</div>
<?php elseif (isset($_GET['del'])): ?>
  <div class="note ok">Foto sudah dilepas dari dinding.</div>
<?php elseif (isset($_GET['err'])): ?>
  <div class="note err"><?= h($_GET['err']) ?></div>
<?php endif; ?>

<main class="board">
<?php if (!$photos): ?>
  <div class="empty">
    <p>Dindingnya masih kosong.</p>
    <button class="btn" data-open="dlgUpload">Tempel foto pertama</button>
  </div>
<?php endif; ?>

<?php foreach ($photos as $p): $s = scrap((int)$p['id']); ?>
  <figure class="scrap v-<?= $s['v'] ?>" tabindex="0" role="button"
    style="--r:<?= $s['r'] ?>deg;--w:<?= $s['w'] ?>px;--mt:<?= $s['mt'] ?>px;--ml:<?= $s['ml'] ?>px;--tr:<?= $s['tr'] ?>deg;--paper:<?= $s['paper'] ?>;--tape:<?= $s['tape'] ?>"
    data-id="<?= (int)$p['id'] ?>"
    data-src="uploads/<?= h($p['filename']) ?>"
    data-cap="<?= h($p['caption']) ?>"
    data-by="<?= h($p['uploader']) ?>"
    data-date="<?= h(date('d/m/Y', strtotime($p['created_at']))) ?>">
    <div class="card">
      <img src="uploads/<?= h($p['filename']) ?>" alt="<?= h($p['caption'] ?: 'Foto Galeri Titut') ?>" loading="lazy">
      <?php if ($p['caption'] !== '' && $s['v'] !== 'film'): ?>
        <figcaption><?= h($p['caption']) ?></figcaption>
      <?php elseif ($s['v'] === 'polaroid'): ?>
        <figcaption>&nbsp;</figcaption>
      <?php endif; ?>
    </div>
  </figure>
<?php endforeach; ?>
</main>

<button class="fab" data-open="dlgUpload" aria-label="Tempel foto baru">+ tempel foto</button>

<!-- Form unggah -->
<dialog id="dlgUpload">
  <form class="paper" action="upload.php" method="post" enctype="multipart/form-data">
    <h2>Tempel foto baru</h2>
    <input type="hidden" name="token" value="<?= h($_SESSION['token']) ?>">

    <label class="drop">
      <input type="file" name="photos[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple required>
      <span id="fileInfo">Pilih satu atau banyak foto</span>
    </label>

    <label>Keterangan
      <input type="text" name="caption" maxlength="160" placeholder="mis. Sore itu di pantai">
    </label>
    <label>Nama kamu
      <input type="text" name="uploader" maxlength="60" placeholder="boleh dikosongkan">
    </label>
    <?php if (UPLOAD_PASSWORD !== ''): ?>
    <label>Password unggah
      <input type="password" name="pass" required>
    </label>
    <?php endif; ?>

    <div class="row">
      <button type="button" class="ghost" data-close>Batal</button>
      <button class="btn">Tempel</button>
    </div>
    <small>JPG, PNG, WEBP, GIF. Maksimal <?= MAX_FILE_MB ?> MB per foto.</small>
  </form>
</dialog>

<!-- Lihat foto -->
<dialog id="dlgView">
  <div class="view">
    <button class="x" data-close aria-label="Tutup">&times;</button>
    <img id="vImg" alt="">
    <div class="meta">
      <p id="vCap" class="vcap"></p>
      <p id="vBy" class="vby"></p>
      <form action="delete.php" method="post" onsubmit="return askPass(this)">
        <input type="hidden" name="token" value="<?= h($_SESSION['token']) ?>">
        <input type="hidden" name="id" id="vId">
        <input type="hidden" name="password" id="vPass">
        <button class="ghost del">Lepas foto</button>
      </form>
    </div>
  </div>
</dialog>

<script src="assets/script.js"></script>
</body>
</html>
