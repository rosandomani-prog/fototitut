-- Galeri Titut - struktur database MySQL
-- Untuk shared hosting: buat database lewat panel hosting, pilih databasenya
-- di phpMyAdmin, lalu impor file ini lewat tab Import.

CREATE TABLE IF NOT EXISTS `photos` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `filename`   VARCHAR(80)  NOT NULL,
  `caption`    VARCHAR(160) NOT NULL DEFAULT '',
  `uploader`   VARCHAR(60)  NOT NULL DEFAULT '',
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
