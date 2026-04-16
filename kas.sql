-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versi server:                 8.4.3 - MySQL Community Server - GPL
-- OS Server:                    Win64
-- HeidiSQL Versi:               12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Membuang struktur basisdata untuk kas_rt
CREATE DATABASE IF NOT EXISTS `kas_rt` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `kas_rt`;

-- membuang struktur untuk table kas_rt.activity_logs
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `tipe` varchar(100) NOT NULL,
  `deskripsi` varchar(500) NOT NULL,
  `warna` varchar(20) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel kas_rt.activity_logs: ~34 rows (lebih kurang)
INSERT INTO `activity_logs` (`id`, `user_id`, `tipe`, `deskripsi`, `warna`, `icon`, `created_at`, `updated_at`) VALUES
	(1, NULL, 'pembayaran_disetujui', 'Admin menyetujui pembayaran Ahmad Fadli', 'green', 'check', '2026-03-27 23:30:22', '2026-03-28 00:00:22'),
	(2, 1, 'upload_bukti', 'Dewi Lestari mengunggah bukti pembayaran', 'blue', 'upload', '2026-03-27 22:00:22', '2026-03-28 00:00:22'),
	(3, NULL, 'tambah_warga', 'Admin menambahkan warga baru (Andi Pratama)', 'purple', 'user-plus', '2026-03-27 21:00:22', '2026-03-28 00:00:22'),
	(4, NULL, 'generate_iuran', 'Admin membuat tagihan iuran Maret 2026 untuk 8 warga', 'yellow', 'file', '2026-03-27 19:00:22', '2026-03-28 00:00:22'),
	(5, NULL, 'pembayaran_ditolak', 'Admin menolak pembayaran Rudi Hermawan', 'red', 'x', '2026-03-27 18:00:22', '2026-03-28 00:00:22'),
	(6, 1, 'upload_bukti', 'Budi Santoso mengunggah bukti pembayaran', 'blue', 'upload', '2026-03-27 16:00:22', '2026-03-28 00:00:22'),
	(7, NULL, 'tambah_pengeluaran', 'Admin menambahkan pengeluaran: Perbaikan jalan RT (Rp 3.500.000)', 'orange', 'money', '2026-03-27 14:00:22', '2026-03-28 00:00:22'),
	(8, 11, 'generate_iuran', 'Admin membuat tagihan iuran March 2026 untuk 1 warga', 'yellow', 'file', '2026-03-29 19:44:09', '2026-03-29 19:44:09'),
	(9, 11, 'upload_bukti', 'Muhammad Arif Mulyanto mengunggah bukti pembayaran', 'blue', 'upload', '2026-03-29 19:44:31', '2026-03-29 19:44:31'),
	(10, 11, 'pembayaran_disetujui', 'Admin menyetujui pembayaran Muhammad Arif Mulyanto', 'green', 'check', '2026-03-29 19:45:00', '2026-03-29 19:45:00'),
	(11, 1, 'generate_iuran', 'Admin membuat tagihan iuran April 2026 untuk 10 warga', 'yellow', 'file', '2026-03-29 20:17:12', '2026-03-29 20:17:12'),
	(12, 11, 'upload_bukti', 'Muhammad Arif Mulyanto mengunggah bukti pembayaran', 'blue', 'upload', '2026-03-29 20:17:57', '2026-03-29 20:17:57'),
	(13, 1, 'generate_iuran', 'Admin membuat tagihan iuran March 2026 untuk 0 warga', 'yellow', 'file', '2026-03-30 04:54:47', '2026-03-30 04:54:47'),
	(14, 1, 'generate_iuran', 'Admin membuat tagihan iuran March 2026 untuk 0 warga', 'yellow', 'file', '2026-03-31 04:46:39', '2026-03-31 04:46:39'),
	(15, 1, 'login_admin', 'Admin berhasil masuk ke sistem', 'blue', 'log-in', '2026-04-04 05:54:09', '2026-04-04 05:54:09'),
	(16, 1, 'pembayaran_disetujui', 'Admin menyetujui pembayaran Muhammad Arif Mulyanto', 'green', 'check', '2026-04-04 05:54:42', '2026-04-04 05:54:42'),
	(17, 1, 'pembayaran_disetujui', 'Admin menyetujui pembayaran Muhammad Arif Mulyanto', 'green', 'check', '2026-04-04 05:54:49', '2026-04-04 05:54:49'),
	(18, 1, 'login_admin', 'Admin berhasil masuk ke sistem', 'blue', 'log-in', '2026-04-04 23:53:14', '2026-04-04 23:53:14'),
	(19, 1, 'generate_iuran', 'Admin membuat tagihan iuran April 2026 untuk 0 warga', 'yellow', 'file', '2026-04-04 23:55:30', '2026-04-04 23:55:30'),
	(20, 1, 'login_admin', 'Admin berhasil masuk ke sistem', 'blue', 'log-in', '2026-04-05 00:07:31', '2026-04-05 00:07:31'),
	(21, 1, 'login_admin', 'Admin berhasil masuk ke sistem', 'blue', 'log-in', '2026-04-05 00:56:03', '2026-04-05 00:56:03'),
	(22, 1, 'login_admin', 'Admin berhasil masuk ke sistem', 'blue', 'log-in', '2026-04-05 01:53:46', '2026-04-05 01:53:46'),
	(23, 2, 'system_verify', 'Sistem memverifikasi pembayaran otomatis Warga RT via OVO', 'emerald', 'cpu', '2026-04-05 02:10:41', '2026-04-05 02:10:41'),
	(24, 2, 'system_verify', 'Sistem memverifikasi pembayaran otomatis Warga RT via OVO', 'emerald', 'cpu', '2026-04-05 02:14:00', '2026-04-05 02:14:00'),
	(25, 1, 'login_admin', 'Admin berhasil masuk ke sistem', 'blue', 'log-in', '2026-04-05 03:53:45', '2026-04-05 03:53:45'),
	(26, 1, 'login_admin', 'Admin berhasil masuk ke sistem', 'blue', 'log-in', '2026-04-05 22:04:03', '2026-04-05 22:04:03'),
	(27, 1, 'login_admin', 'Admin berhasil masuk ke sistem', 'blue', 'log-in', '2026-04-05 22:47:00', '2026-04-05 22:47:00'),
	(28, 1, 'login_admin', 'Admin berhasil masuk ke sistem', 'blue', 'log-in', '2026-04-05 22:49:10', '2026-04-05 22:49:10'),
	(29, 1, 'login_admin', 'Admin berhasil masuk ke sistem', 'blue', 'log-in', '2026-04-05 22:52:13', '2026-04-05 22:52:13'),
	(30, 1, 'login_admin', 'Admin berhasil masuk ke sistem', 'blue', 'log-in', '2026-04-06 00:42:11', '2026-04-06 00:42:11'),
	(31, 1, 'login_admin', 'Admin berhasil masuk ke sistem', 'blue', 'log-in', '2026-04-06 00:43:26', '2026-04-06 00:43:26'),
	(32, 1, 'login_admin', 'Admin berhasil masuk ke sistem', 'blue', 'log-in', '2026-04-06 00:43:28', '2026-04-06 00:43:28'),
	(33, 1, 'login_admin', 'Admin berhasil masuk ke sistem', 'blue', 'log-in', '2026-04-06 00:55:49', '2026-04-06 00:55:49'),
	(34, 1, 'login_admin', 'Admin berhasil masuk ke sistem', 'blue', 'log-in', '2026-04-06 00:59:19', '2026-04-06 00:59:19');

-- membuang struktur untuk table kas_rt.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel kas_rt.cache: ~4 rows (lebih kurang)
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
	('laravel-cache-admin@email.com|127.0.0.1', 'i:1;', 1775454466),
	('laravel-cache-admin@email.com|127.0.0.1:timer', 'i:1775454466;', 1775454466),
	('laravel-cache-piahlutpiah786@gmail.com|127.0.0.1', 'i:1;', 1775460598),
	('laravel-cache-piahlutpiah786@gmail.com|127.0.0.1:timer', 'i:1775460598;', 1775460598);

-- membuang struktur untuk table kas_rt.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel kas_rt.cache_locks: ~0 rows (lebih kurang)

-- membuang struktur untuk table kas_rt.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel kas_rt.failed_jobs: ~0 rows (lebih kurang)

-- membuang struktur untuk table kas_rt.iuran
CREATE TABLE IF NOT EXISTS `iuran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `bulan` varchar(7) NOT NULL,
  `nominal` bigint unsigned DEFAULT '150000',
  `status` enum('lunas','menunggu','belum_bayar') DEFAULT 'belum_bayar',
  `dibayar_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `iuran_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel kas_rt.iuran: ~21 rows (lebih kurang)
INSERT INTO `iuran` (`id`, `user_id`, `bulan`, `nominal`, `status`, `dibayar_at`, `created_at`, `updated_at`) VALUES
	(1, 1, '2026-03', 150000, 'lunas', '2026-03-27 17:42:27', '2026-03-27 17:42:27', '2026-03-27 17:42:27'),
	(3, 3, '2026-03', 150000, 'lunas', '2026-03-27 17:42:27', '2026-03-27 17:42:27', '2026-03-27 17:42:27'),
	(4, 4, '2026-03', 150000, 'lunas', '2026-03-27 17:42:27', '2026-03-27 17:42:27', '2026-03-27 17:42:27'),
	(5, 5, '2026-03', 150000, 'menunggu', NULL, '2026-03-27 17:42:27', '2026-03-27 17:42:27'),
	(6, 6, '2026-03', 150000, 'menunggu', NULL, '2026-03-27 17:42:27', '2026-03-27 17:42:27'),
	(7, 7, '2026-03', 150000, 'belum_bayar', NULL, '2026-03-27 17:42:27', '2026-03-27 17:42:27'),
	(8, 8, '2026-03', 150000, 'belum_bayar', NULL, '2026-03-27 17:42:27', '2026-03-27 17:42:27'),
	(9, 9, '2026-03', 150000, 'lunas', '2026-03-27 17:42:27', '2026-03-27 17:42:27', '2026-03-27 17:42:27'),
	(10, 10, '2026-03', 150000, 'lunas', '2026-03-27 17:42:27', '2026-03-27 17:42:27', '2026-03-27 17:42:27'),
	(11, 2, '2026-03', 100000, 'lunas', '2026-04-05 02:14:00', '2026-03-27 18:59:11', '2026-04-05 02:14:00'),
	(12, 11, '2026-03', 150000, 'lunas', '2026-03-29 19:45:00', '2026-03-29 19:44:09', '2026-03-29 19:45:00'),
	(13, 2, '2026-04', 150000, 'lunas', '2026-04-05 02:10:41', '2026-03-29 20:17:12', '2026-04-05 02:10:41'),
	(14, 3, '2026-04', 150000, 'belum_bayar', NULL, '2026-03-29 20:17:12', '2026-03-29 20:17:12'),
	(15, 4, '2026-04', 150000, 'belum_bayar', NULL, '2026-03-29 20:17:12', '2026-03-29 20:17:12'),
	(16, 5, '2026-04', 150000, 'belum_bayar', NULL, '2026-03-29 20:17:12', '2026-03-29 20:17:12'),
	(17, 6, '2026-04', 150000, 'belum_bayar', NULL, '2026-03-29 20:17:12', '2026-03-29 20:17:12'),
	(18, 7, '2026-04', 150000, 'belum_bayar', NULL, '2026-03-29 20:17:12', '2026-03-29 20:17:12'),
	(19, 8, '2026-04', 150000, 'belum_bayar', NULL, '2026-03-29 20:17:12', '2026-03-29 20:17:12'),
	(20, 9, '2026-04', 150000, 'belum_bayar', NULL, '2026-03-29 20:17:12', '2026-03-29 20:17:12'),
	(21, 10, '2026-04', 150000, 'belum_bayar', NULL, '2026-03-29 20:17:12', '2026-03-29 20:17:12'),
	(22, 11, '2026-04', 150000, 'lunas', '2026-04-04 05:54:49', '2026-03-29 20:17:12', '2026-04-04 05:54:49');

-- membuang struktur untuk table kas_rt.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel kas_rt.jobs: ~0 rows (lebih kurang)

-- membuang struktur untuk table kas_rt.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel kas_rt.job_batches: ~0 rows (lebih kurang)

-- membuang struktur untuk table kas_rt.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel kas_rt.migrations: ~19 rows (lebih kurang)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_03_27_090310_add_role_to_users_table', 2),
	(10, '2026_03_27_091139_create_iuran_table', 3),
	(11, '2026_03_27_091151_create_pembayaran_table', 3),
	(12, '2026_03_27_091201_create_pengeluaran_table', 3),
	(13, '2026_03_27_091210_create_activity_logs_table', 3),
	(27, '2026_03_27_091219_create_notifikasi_table', 4),
	(28, '2026_03_27_161132_add_google_fields_to_users_table', 4),
	(29, '2026_03_27_171623_add_warga_fields_to_users_table', 4),
	(30, '2026_03_28_005123_create_pembayaran_table', 5),
	(31, '2026_03_30_103903_add_banner_to_users_table', 6),
	(32, '2026_03_30_105524_add_banner_to_users_table', 7),
	(33, '2026_04_01_000001_add_phone_verified_at_to_users_table', 8),
	(34, '2026_04_01_000002_create_pesan_table', 8),
	(35, '2026_04_04_000003_add_file_fields_to_pesan_table', 9),
	(36, '2026_04_05_000001_make_isi_pesan_nullable_in_pesan_table', 9),
	(37, '2026_04_06_145042_add_last_seen_at_to_users_table', 10);

-- membuang struktur untuk table kas_rt.notifikasi
CREATE TABLE IF NOT EXISTS `notifikasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `dibaca_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifikasi_user_id_foreign` (`user_id`),
  CONSTRAINT `notifikasi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel kas_rt.notifikasi: ~39 rows (lebih kurang)
INSERT INTO `notifikasi` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `dibaca_at`, `created_at`, `updated_at`) VALUES
	(1, 2, 'Tagihan Iuran March 2026', 'Tagihan iuran bulan March 2026 sebesar Rp 100.000 telah dibuat. Harap segera melakukan pembayaran.', 'peringatan', '2026-03-27 18:59:20', '2026-03-27 18:59:11', '2026-03-27 18:59:20'),
	(2, 11, 'Tagihan Iuran March 2026', 'Tagihan iuran bulan March 2026 sebesar Rp 150.000 telah dibuat. Harap segera melakukan pembayaran.', 'peringatan', '2026-03-29 19:44:35', '2026-03-29 19:44:09', '2026-03-29 19:44:35'),
	(3, 11, 'Pembayaran Sedang Diverifikasi', 'Pembayaran iuran Anda via DANA sedang diverifikasi oleh admin.', 'info', '2026-03-29 19:44:35', '2026-03-29 19:44:31', '2026-03-29 19:44:35'),
	(4, 11, 'Pembayaran Dikonfirmasi', 'Pembayaran iuran Anda sebesar Rp 150.000 via E-Wallet (DANA) telah dikonfirmasi. Kwitansi dikirim ke email Anda.', 'sukses', '2026-03-29 19:45:26', '2026-03-29 19:45:00', '2026-03-29 19:45:26'),
	(5, 2, 'Tagihan Iuran April 2026', 'Tagihan iuran bulan April 2026 sebesar Rp 150.000 telah dibuat. Harap segera melakukan pembayaran.', 'peringatan', '2026-04-05 02:43:32', '2026-03-29 20:17:12', '2026-04-05 02:43:32'),
	(6, 3, 'Tagihan Iuran April 2026', 'Tagihan iuran bulan April 2026 sebesar Rp 150.000 telah dibuat. Harap segera melakukan pembayaran.', 'peringatan', NULL, '2026-03-29 20:17:12', '2026-03-29 20:17:12'),
	(7, 4, 'Tagihan Iuran April 2026', 'Tagihan iuran bulan April 2026 sebesar Rp 150.000 telah dibuat. Harap segera melakukan pembayaran.', 'peringatan', NULL, '2026-03-29 20:17:12', '2026-03-29 20:17:12'),
	(8, 5, 'Tagihan Iuran April 2026', 'Tagihan iuran bulan April 2026 sebesar Rp 150.000 telah dibuat. Harap segera melakukan pembayaran.', 'peringatan', NULL, '2026-03-29 20:17:12', '2026-03-29 20:17:12'),
	(9, 6, 'Tagihan Iuran April 2026', 'Tagihan iuran bulan April 2026 sebesar Rp 150.000 telah dibuat. Harap segera melakukan pembayaran.', 'peringatan', NULL, '2026-03-29 20:17:12', '2026-03-29 20:17:12'),
	(10, 7, 'Tagihan Iuran April 2026', 'Tagihan iuran bulan April 2026 sebesar Rp 150.000 telah dibuat. Harap segera melakukan pembayaran.', 'peringatan', NULL, '2026-03-29 20:17:12', '2026-03-29 20:17:12'),
	(11, 8, 'Tagihan Iuran April 2026', 'Tagihan iuran bulan April 2026 sebesar Rp 150.000 telah dibuat. Harap segera melakukan pembayaran.', 'peringatan', NULL, '2026-03-29 20:17:12', '2026-03-29 20:17:12'),
	(12, 9, 'Tagihan Iuran April 2026', 'Tagihan iuran bulan April 2026 sebesar Rp 150.000 telah dibuat. Harap segera melakukan pembayaran.', 'peringatan', NULL, '2026-03-29 20:17:12', '2026-03-29 20:17:12'),
	(13, 10, 'Tagihan Iuran April 2026', 'Tagihan iuran bulan April 2026 sebesar Rp 150.000 telah dibuat. Harap segera melakukan pembayaran.', 'peringatan', NULL, '2026-03-29 20:17:12', '2026-03-29 20:17:12'),
	(14, 11, 'Tagihan Iuran April 2026', 'Tagihan iuran bulan April 2026 sebesar Rp 150.000 telah dibuat. Harap segera melakukan pembayaran.', 'peringatan', '2026-03-29 20:17:34', '2026-03-29 20:17:12', '2026-03-29 20:17:34'),
	(15, 11, 'Pembayaran Sedang Diverifikasi', 'Pembayaran iuran Anda via DANA sedang diverifikasi oleh admin.', 'info', '2026-03-29 20:18:13', '2026-03-29 20:17:57', '2026-03-29 20:18:13'),
	(16, 2, 'Pengingat Pembayaran Iuran', 'Iuran bulan March 2026 belum dibayar. Segera lakukan pembayaran sebelum tanggal 28.', 'peringatan', '2026-04-05 02:43:32', '2026-03-30 04:54:59', '2026-04-05 02:43:32'),
	(17, 3, 'Pengingat Pembayaran Iuran', 'Iuran bulan March 2026 belum dibayar. Segera lakukan pembayaran sebelum tanggal 28.', 'peringatan', NULL, '2026-03-30 04:54:59', '2026-03-30 04:54:59'),
	(18, 4, 'Pengingat Pembayaran Iuran', 'Iuran bulan March 2026 belum dibayar. Segera lakukan pembayaran sebelum tanggal 28.', 'peringatan', NULL, '2026-03-30 04:54:59', '2026-03-30 04:54:59'),
	(19, 5, 'Pengingat Pembayaran Iuran', 'Iuran bulan March 2026 belum dibayar. Segera lakukan pembayaran sebelum tanggal 28.', 'peringatan', NULL, '2026-03-30 04:54:59', '2026-03-30 04:54:59'),
	(20, 6, 'Pengingat Pembayaran Iuran', 'Iuran bulan March 2026 belum dibayar. Segera lakukan pembayaran sebelum tanggal 28.', 'peringatan', NULL, '2026-03-30 04:54:59', '2026-03-30 04:54:59'),
	(21, 7, 'Pengingat Pembayaran Iuran', 'Iuran bulan March 2026 belum dibayar. Segera lakukan pembayaran sebelum tanggal 28.', 'peringatan', NULL, '2026-03-30 04:54:59', '2026-03-30 04:54:59'),
	(22, 8, 'Pengingat Pembayaran Iuran', 'Iuran bulan March 2026 belum dibayar. Segera lakukan pembayaran sebelum tanggal 28.', 'peringatan', NULL, '2026-03-30 04:54:59', '2026-03-30 04:54:59'),
	(23, 9, 'Pengingat Pembayaran Iuran', 'Iuran bulan March 2026 belum dibayar. Segera lakukan pembayaran sebelum tanggal 28.', 'peringatan', NULL, '2026-03-30 04:54:59', '2026-03-30 04:54:59'),
	(24, 10, 'Pengingat Pembayaran Iuran', 'Iuran bulan March 2026 belum dibayar. Segera lakukan pembayaran sebelum tanggal 28.', 'peringatan', NULL, '2026-03-30 04:54:59', '2026-03-30 04:54:59'),
	(25, 11, 'Pengingat Pembayaran Iuran', 'Iuran bulan March 2026 belum dibayar. Segera lakukan pembayaran sebelum tanggal 28.', 'peringatan', '2026-03-31 05:44:00', '2026-03-30 04:54:59', '2026-03-31 05:44:00'),
	(26, 11, 'Pembayaran Dikonfirmasi', 'Pembayaran iuran Anda sebesar Rp 150.000 via E-Wallet (DANA) telah dikonfirmasi. Kwitansi dikirim ke email Anda.', 'sukses', '2026-04-05 01:47:45', '2026-04-04 05:54:42', '2026-04-05 01:47:45'),
	(27, 11, 'Pembayaran Dikonfirmasi', 'Pembayaran iuran Anda sebesar Rp 150.000 via E-Wallet (DANA) telah dikonfirmasi. Kwitansi dikirim ke email Anda.', 'sukses', '2026-04-05 01:47:45', '2026-04-04 05:54:49', '2026-04-05 01:47:45'),
	(28, 2, 'Pengingat Pembayaran Iuran', 'Iuran bulan April 2026 belum dibayar. Segera lakukan pembayaran sebelum tanggal 28.', 'peringatan', '2026-04-05 02:43:32', '2026-04-04 23:53:49', '2026-04-05 02:43:32'),
	(29, 3, 'Pengingat Pembayaran Iuran', 'Iuran bulan April 2026 belum dibayar. Segera lakukan pembayaran sebelum tanggal 28.', 'peringatan', NULL, '2026-04-04 23:53:49', '2026-04-04 23:53:49'),
	(30, 4, 'Pengingat Pembayaran Iuran', 'Iuran bulan April 2026 belum dibayar. Segera lakukan pembayaran sebelum tanggal 28.', 'peringatan', NULL, '2026-04-04 23:53:49', '2026-04-04 23:53:49'),
	(31, 5, 'Pengingat Pembayaran Iuran', 'Iuran bulan April 2026 belum dibayar. Segera lakukan pembayaran sebelum tanggal 28.', 'peringatan', NULL, '2026-04-04 23:53:49', '2026-04-04 23:53:49'),
	(32, 6, 'Pengingat Pembayaran Iuran', 'Iuran bulan April 2026 belum dibayar. Segera lakukan pembayaran sebelum tanggal 28.', 'peringatan', NULL, '2026-04-04 23:53:49', '2026-04-04 23:53:49'),
	(33, 7, 'Pengingat Pembayaran Iuran', 'Iuran bulan April 2026 belum dibayar. Segera lakukan pembayaran sebelum tanggal 28.', 'peringatan', NULL, '2026-04-04 23:53:49', '2026-04-04 23:53:49'),
	(34, 8, 'Pengingat Pembayaran Iuran', 'Iuran bulan April 2026 belum dibayar. Segera lakukan pembayaran sebelum tanggal 28.', 'peringatan', NULL, '2026-04-04 23:53:49', '2026-04-04 23:53:49'),
	(35, 9, 'Pengingat Pembayaran Iuran', 'Iuran bulan April 2026 belum dibayar. Segera lakukan pembayaran sebelum tanggal 28.', 'peringatan', NULL, '2026-04-04 23:53:49', '2026-04-04 23:53:49'),
	(36, 10, 'Pengingat Pembayaran Iuran', 'Iuran bulan April 2026 belum dibayar. Segera lakukan pembayaran sebelum tanggal 28.', 'peringatan', NULL, '2026-04-04 23:53:49', '2026-04-04 23:53:49'),
	(37, 11, 'Pengingat Pembayaran Iuran', 'Iuran bulan April 2026 belum dibayar. Segera lakukan pembayaran sebelum tanggal 28.', 'peringatan', '2026-04-05 01:47:45', '2026-04-04 23:53:49', '2026-04-05 01:47:45'),
	(38, 2, 'Pembayaran Diverifikasi ✓', 'Pembayaran iuran Anda sebesar Rp 150.000 via OVO telah diverifikasi secara otomatis oleh sistem. Kwitansi tersedia di profil Anda.', 'sukses', '2026-04-05 02:43:32', '2026-04-05 02:10:41', '2026-04-05 02:43:32'),
	(39, 2, 'Pembayaran Diverifikasi ✓', 'Pembayaran iuran Anda sebesar Rp 100.000 via OVO telah diverifikasi secara otomatis oleh sistem. Kwitansi tersedia di profil Anda.', 'sukses', '2026-04-05 02:43:32', '2026-04-05 02:14:00', '2026-04-05 02:43:32');

-- membuang struktur untuk table kas_rt.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel kas_rt.password_reset_tokens: ~0 rows (lebih kurang)

-- membuang struktur untuk table kas_rt.pembayaran
CREATE TABLE IF NOT EXISTS `pembayaran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `iuran_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `metode` enum('ewallet','qris','transfer_bank') COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah` bigint unsigned NOT NULL,
  `status` enum('menunggu','disetujui','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `bukti_bayar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dibayar_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pembayaran_iuran_id_foreign` (`iuran_id`),
  KEY `pembayaran_user_id_foreign` (`user_id`),
  CONSTRAINT `pembayaran_iuran_id_foreign` FOREIGN KEY (`iuran_id`) REFERENCES `iuran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pembayaran_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel kas_rt.pembayaran: ~4 rows (lebih kurang)
INSERT INTO `pembayaran` (`id`, `iuran_id`, `user_id`, `metode`, `provider`, `jumlah`, `status`, `bukti_bayar`, `catatan`, `dibayar_at`, `created_at`, `updated_at`) VALUES
	(1, 12, 11, 'ewallet', 'dana', 150000, 'disetujui', NULL, NULL, '2026-03-29 19:44:31', '2026-03-29 19:44:31', '2026-03-29 19:45:00'),
	(2, 22, 11, 'ewallet', 'dana', 150000, 'disetujui', NULL, NULL, '2026-03-29 20:17:57', '2026-03-29 20:17:57', '2026-04-04 05:54:42'),
	(3, 13, 2, 'ewallet', 'ovo', 150000, 'disetujui', NULL, NULL, '2026-04-05 02:10:41', '2026-04-05 02:10:41', '2026-04-05 02:10:41'),
	(4, 11, 2, 'ewallet', 'ovo', 100000, 'disetujui', NULL, NULL, '2026-04-05 02:13:59', '2026-04-05 02:13:59', '2026-04-05 02:13:59');

-- membuang struktur untuk table kas_rt.pengeluaran
CREATE TABLE IF NOT EXISTS `pengeluaran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `keterangan` varchar(255) NOT NULL,
  `nominal` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel kas_rt.pengeluaran: ~3 rows (lebih kurang)
INSERT INTO `pengeluaran` (`id`, `keterangan`, `nominal`, `tanggal`, `created_at`, `updated_at`) VALUES
	(1, 'Perbaikan jalan RT', 3500000, '2026-03-15', '2026-03-27 22:47:17', '2026-03-27 22:47:17'),
	(2, 'Pembelian lampu jalan', 2200000, '2026-03-10', '2026-03-27 22:47:17', '2026-03-27 22:47:17'),
	(3, 'Kebersihan lingkungan', 1500000, '2026-03-05', '2026-03-27 22:47:17', '2026-03-27 22:47:17');

-- membuang struktur untuk table kas_rt.pesan
CREATE TABLE IF NOT EXISTS `pesan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pengirim_id` bigint unsigned NOT NULL,
  `penerima_id` bigint unsigned NOT NULL,
  `isi_pesan` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dibaca_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pesan_penerima_id_foreign` (`penerima_id`),
  KEY `pesan_pengirim_id_penerima_id_index` (`pengirim_id`,`penerima_id`),
  CONSTRAINT `pesan_penerima_id_foreign` FOREIGN KEY (`penerima_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pesan_pengirim_id_foreign` FOREIGN KEY (`pengirim_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel kas_rt.pesan: ~6 rows (lebih kurang)
INSERT INTO `pesan` (`id`, `pengirim_id`, `penerima_id`, `isi_pesan`, `file_path`, `file_type`, `dibaca_at`, `created_at`, `updated_at`) VALUES
	(1, 11, 1, 'haloo test', NULL, NULL, '2026-04-05 00:07:37', '2026-04-05 00:07:19', '2026-04-05 00:07:37'),
	(2, 1, 11, 'siap, pesan diterima.', NULL, NULL, '2026-04-05 01:47:46', '2026-04-05 00:57:15', '2026-04-05 01:47:46'),
	(3, 2, 1, 'Halo Admin, ini tes dari warga.', NULL, NULL, '2026-04-05 03:54:00', '2026-04-05 01:28:04', '2026-04-05 03:54:00'),
	(4, 2, 1, 'ppppp', NULL, NULL, '2026-04-06 00:46:47', '2026-04-06 00:38:59', '2026-04-06 00:46:47'),
	(5, 1, 2, 'ada yang bisa di bantu?', NULL, NULL, '2026-04-06 01:33:13', '2026-04-06 00:47:01', '2026-04-06 01:33:13'),
	(6, 1, 2, 'Halo mas', NULL, NULL, '2026-04-06 01:33:13', '2026-04-06 00:57:30', '2026-04-06 01:33:13');

-- membuang struktur untuk table kas_rt.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel kas_rt.sessions: ~9 rows (lebih kurang)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('1epLUHANxIGQ1rBEKFaOyqj65Hk2APrjcFMI0NlE', 1, '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicUFGamJ1SnB2a1VoVzBSa0gzcFVEOWZpRmJLdXNWTlVpNktwaGxhVSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODY6Imh0dHA6Ly92ZG95YS0yMDAxLTQ0OGEtMjAwMi04M2VmLTZjOGEtM2E0My05ZmRlLTZkNDEucnVuLnBpbmdneS1mcmVlLmxpbmsvYWRtaW4vcHJvZmlsIjtzOjU6InJvdXRlIjtzOjEyOiJhZG1pbi5wcm9maWwiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1775462342),
	('3pex2dwbiyECdleZDmQONEy3tDhXOv1UvB6xL0XZ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWHpOcnBsclRxUWtrNFFSNlVwSXJ6dzIxbWhZRGNzSThmQXlyOHN4WiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Nzk6Imh0dHA6Ly92ZG95YS0yMDAxLTQ0OGEtMjAwMi04M2VmLTZjOGEtM2E0My05ZmRlLTZkNDEucnVuLnBpbmdneS1mcmVlLmxpbmsvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1775461158),
	('aLOm4W9VOIwFHGZsxxveiHHxFSYBxqx2du7GvnUZ', NULL, '127.0.0.1', 'WhatsApp/2.2611.102 W', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSlJ0Z1VxZk5YUVZhRWZ0cEZja1JJamJrUGxiR3ZZRXZzdkVYaTA0RiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Nzk6Imh0dHA6Ly92ZG95YS0yMDAxLTQ0OGEtMjAwMi04M2VmLTZjOGEtM2E0My05ZmRlLTZkNDEucnVuLnBpbmdneS1mcmVlLmxpbmsvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1775460370),
	('K0HnAL6GogHPQIlob8p46tGz9G7chip2Hi87qClD', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNEtvNXlaSjZ3SGxGZWtjRVlzVktZMFJjazZRekVkTU9iSjM0aHJEayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODk6Imh0dHA6Ly92ZG95YS0yMDAxLTQ0OGEtMjAwMi04M2VmLTZjOGEtM2E0My05ZmRlLTZkNDEucnVuLnBpbmdneS1mcmVlLmxpbmsvYWRtaW4vZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjE1OiJhZG1pbi5kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1775463937),
	('k2Jhei5mNzRT1UkIRsZtEdYUpQ0kOZrrMtluLeAB', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNkdsVTZaYzRRc3p3U21wVVA5bzRmWUlnODFXQm94QnEwdXNzZ0hJVCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODQ6Imh0dHA6Ly92ZG95YS0yMDAxLTQ0OGEtMjAwMi04M2VmLTZjOGEtM2E0My05ZmRlLTZkNDEucnVuLnBpbmdneS1mcmVlLmxpbmsvYWRtaW4vY2hhdCI7czo1OiJyb3V0ZSI7czoxMDoiYWRtaW4uY2hhdCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1775461695),
	('Nhe83nPdBly0WeOwaMyYnqNqvjagggbeo21HlkOW', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUUgyQXNzRXVrWmNUY1d2REFpeXc0dXkwZ25ZdVNzQXBqY09jTEsxVyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM0OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvd2FyZ2EvcHJvZmlsIjtzOjU6InJvdXRlIjtzOjEyOiJ3YXJnYS5wcm9maWwiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1775460094),
	('oNEwvHoR1RA8klLpNGhRIlJAtySrKQ6EfS45ugtM', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSHFqR1lmaHJnQ3VBdHpoQXhXM00wSm5STWFEaVMzbXgxOVhkMlVOVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODQ6Imh0dHA6Ly9iaGdtby0yMDAxLTQ0OGEtMjAwMi04M2VmLTZjOGEtM2E0My05ZmRlLTZkNDEucnVuLnBpbmdneS1mcmVlLmxpbmsvd2FyZ2EvY2hhdCI7czo1OiJyb3V0ZSI7czoxMDoid2FyZ2EuY2hhdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1775464622),
	('Ts2u6l9v76tS6ZS69c87ekcFjbVFM1RhWhT6wZxD', 1, '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZHBBUzhwOFZBWFpSMW5nSE1YOGJ4eTc3ZkxxMjhwcFQ0UkNZbEdBdyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODk6Imh0dHA6Ly92ZG95YS0yMDAxLTQ0OGEtMjAwMi04M2VmLTZjOGEtM2E0My05ZmRlLTZkNDEucnVuLnBpbmdneS1mcmVlLmxpbmsvYWRtaW4vZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjE1OiJhZG1pbi5kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1775462902),
	('XwvQEkKKxYeIQVT7pwbh1aY5RquGClNZXVhivUrL', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRkhHZEhRQllYT1lORUhrNW5nb2FoTm90UHpZMjl6SlNiRmxid1RMbiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1775464621);

-- membuang struktur untuk table kas_rt.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'warga',
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel kas_rt.users: ~11 rows (lebih kurang)
INSERT INTO `users` (`id`, `name`, `email`, `role`, `google_id`, `avatar`, `banner`, `alamat`, `no_hp`, `phone_verified_at`, `status`, `last_seen_at`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin RT Test', 'admin@rt.com', 'admin', NULL, 'avatars/bQDdKNzrmSHD98vUKiYxBTDivJQ05mZli2ayf7jY.jpg', 'banners/7e3eRzgX3jvXWFFozn9yp43KU987iWjFE8AkaNBr.jpg', NULL, NULL, NULL, 'aktif', '2026-04-06 01:37:01', '2026-03-27 09:14:48', '$2y$12$Su9iqYhDheHypcmOSzF.7.sbXsITcbwL.idJ7OoIpso8SZFrzvBOy', 'iZyXOFNtB3tMKwdSkewe3yPnMjmVVqZS50ZHDPH0xG3sIgbjR8LreoHvRRoB', '2026-03-27 09:14:48', '2026-04-06 00:58:32'),
	(2, 'Warga RT', 'warga@rt.com', 'warga', NULL, NULL, NULL, NULL, NULL, NULL, 'aktif', '2026-04-06 01:37:02', '2026-03-27 09:14:48', '$2y$12$1KuHAAnLOTrAckJbKMPJROvAWDohP5Iwy8x2HJM7stIZzx6XIbFg.', 'iYc53jPCut1Acrf4JngQjZpH2IlGRMtHn4vvSwi57UtT9QHTZjRXef34zDrZ', '2026-03-27 09:14:48', '2026-03-27 09:14:48'),
	(3, 'Budi Santoso', 'budi@rt.com', 'warga', NULL, NULL, NULL, NULL, NULL, NULL, 'aktif', NULL, NULL, '$2y$12$zXc8Jx4uv7kMEo3ErpQPF.lrph.QYQXnOydHtLqzeSQMvzyF9pgZq', NULL, '2026-03-27 10:22:24', '2026-03-27 10:22:24'),
	(4, 'Siti Nurhaliza', 'siti@rt.com', 'warga', NULL, NULL, NULL, NULL, NULL, NULL, 'aktif', NULL, NULL, '$2y$12$sA1uu4tY77o.Q9Jwz5Lo0.UYONwF2LMfXPVIZCJqCPs3HHw4rddna', NULL, '2026-03-27 10:22:24', '2026-03-27 10:22:24'),
	(5, 'Ahmad Fadli', 'ahmad@rt.com', 'warga', NULL, NULL, NULL, NULL, NULL, NULL, 'aktif', NULL, NULL, '$2y$12$2VEwv5GZyupDLJrvAc0rcuHCXV4eomi8NR/E7Veq28CsA0n.EaUXy', NULL, '2026-03-27 10:22:25', '2026-03-27 10:22:25'),
	(6, 'Dewi Lestari', 'dewi@rt.com', 'warga', NULL, NULL, NULL, NULL, NULL, NULL, 'aktif', NULL, NULL, '$2y$12$jpwhG.x1lltCDuH7Y7uqCOl7XL/dSFMfP2gb5fOIFp27jJU0jWx9q', NULL, '2026-03-27 10:22:25', '2026-03-27 10:22:25'),
	(7, 'Rudi Hermawan', 'rudi@rt.com', 'warga', NULL, NULL, NULL, NULL, NULL, NULL, 'aktif', NULL, NULL, '$2y$12$qv4GhfCGBSOpG/BhsN31YumhxV6ogqPALpSFgq4mCPa3aipyzpbLe', NULL, '2026-03-27 10:22:25', '2026-03-27 10:22:25'),
	(8, 'Rina Marlina', 'rina@rt.com', 'warga', NULL, NULL, NULL, NULL, NULL, NULL, 'aktif', NULL, NULL, '$2y$12$JwzCySweNqagD3RX4gBwaeSagnVpz/3f8aMXWbkGEEVjoK1e0cOIa', NULL, '2026-03-27 10:22:26', '2026-03-27 10:22:26'),
	(9, 'Hendra Wijaya', 'hendra@rt.com', 'warga', NULL, NULL, NULL, NULL, NULL, NULL, 'aktif', NULL, NULL, '$2y$12$f.FJt9.Bfus.BoFGJ4/Fa.2onhuG/wix18NIB0lsqZ39D/sNxsXfm', NULL, '2026-03-27 10:22:26', '2026-03-27 10:22:26'),
	(10, 'Joko Susilo', 'joko@rt.com', 'warga', NULL, NULL, NULL, NULL, NULL, NULL, 'aktif', NULL, NULL, '$2y$12$dgsKGMbUvniC1XIESopTCuuKihwIt26gdfuguHyOeqhZuHOSmowzW', NULL, '2026-03-27 10:22:27', '2026-03-27 10:22:27'),
	(11, 'Muhammad Arif Mulyanto', 'mr.xarifmacan@gmail.com', 'warga', 'ULPfJpj0PwNP6eKuwX0CnjEMfoT2', 'avatars/zIiKKEz5BJ8GGaX8GB6XWUdDN5r3T8YcXjUO4vqk.jpg', NULL, NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, NULL, '2026-03-29 19:40:54', '2026-04-06 00:39:39');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
