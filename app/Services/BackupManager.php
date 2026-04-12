<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class BackupManager
{
    protected string $disk = 'local';
    protected string $dir  = 'backups';

    /**
     * Buat backup database menggunakan PDO native + ZipArchive.
     * Cross-platform: bekerja di Windows & Linux tanpa mysqldump/gzip.
     * Return path file relatif terhadap storage/app.
     */
    public function create(): string
    {
        $timestamp  = now()->format('Y-m-d_H-i-s');
        $sqlFile    = storage_path("app/{$this->dir}/tmp_backup_{$timestamp}.sql");
        $zipName    = "backup_{$timestamp}.sql.zip";
        $zipPath    = storage_path("app/{$this->dir}/{$zipName}");

        // Pastikan direktori ada
        $dir = storage_path("app/{$this->dir}");
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Dump SQL menggunakan PDO
        $this->dumpSql($sqlFile);

        // Compress ke ZIP
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            @unlink($sqlFile);
            throw new \RuntimeException('Gagal membuat file ZIP backup.');
        }

        $zip->addFile($sqlFile, "backup_{$timestamp}.sql");
        $zip->close();

        // Hapus file SQL temporary
        @unlink($sqlFile);

        if (! file_exists($zipPath) || filesize($zipPath) === 0) {
            throw new \RuntimeException('Backup gagal: file ZIP tidak terbuat atau kosong.');
        }

        Log::info("BackupManager: backup berhasil → {$this->dir}/{$zipName}");

        return "{$this->dir}/{$zipName}";
    }

    /**
     * Dump seluruh database ke file SQL menggunakan koneksi PDO native.
     */
    protected function dumpSql(string $outputFile): void
    {
        $pdo      = DB::getPdo();
        $database = config('database.connections.mysql.database');
        $handle   = fopen($outputFile, 'w');

        if (! $handle) {
            throw new \RuntimeException("Gagal membuka file untuk ditulis: {$outputFile}");
        }

        // Header SQL
        fwrite($handle, "-- Smart Kas RT Database Backup\n");
        fwrite($handle, "-- Generated: " . now()->toDateTimeString() . "\n");
        fwrite($handle, "-- Database: {$database}\n\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n");
        fwrite($handle, "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\n");
        fwrite($handle, "SET NAMES utf8mb4;\n\n");

        // Ambil semua tabel
        $tables = $pdo->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            // DROP + CREATE TABLE
            $createRow = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(\PDO::FETCH_ASSOC);
            $createSql = $createRow['Create Table'] ?? $createRow[array_key_last($createRow)];

            fwrite($handle, "-- -----------------------------------------------\n");
            fwrite($handle, "-- Tabel: `{$table}`\n");
            fwrite($handle, "-- -----------------------------------------------\n");
            fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n");
            fwrite($handle, $createSql . ";\n\n");

            // Data per tabel dalam batch 500 rows
            $offset = 0;
            $limit  = 500;

            while (true) {
                $rows = $pdo->query(
                    "SELECT * FROM `{$table}` LIMIT {$limit} OFFSET {$offset}"
                )->fetchAll(\PDO::FETCH_ASSOC);

                if (empty($rows)) break;

                $columns = array_map(fn($col) => "`{$col}`", array_keys($rows[0]));
                $colStr  = implode(', ', $columns);

                fwrite($handle, "INSERT INTO `{$table}` ({$colStr}) VALUES\n");

                $rowValues = [];
                foreach ($rows as $row) {
                    $vals = array_map(function ($val) use ($pdo) {
                        if ($val === null) return 'NULL';
                        return $pdo->quote((string) $val);
                    }, array_values($row));
                    $rowValues[] = '(' . implode(', ', $vals) . ')';
                }

                fwrite($handle, implode(",\n", $rowValues) . ";\n\n");

                $offset += $limit;
                if (count($rows) < $limit) break;
            }
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($handle);
    }

    /**
     * Daftar file backup (hanya .sql.zip).
     * Return array of ['name', 'size', 'created_at'].
     */
    public function list(): array
    {
        if (! Storage::disk($this->disk)->exists($this->dir)) {
            return [];
        }

        $files = Storage::disk($this->disk)->files($this->dir);
        $files = array_values(array_filter($files, fn($f) => str_ends_with($f, '.sql.zip')));

        $result = [];
        foreach ($files as $file) {
            $basename = basename($file);
            $bytes    = Storage::disk($this->disk)->size($file);
            $time     = Storage::disk($this->disk)->lastModified($file);

            $result[] = [
                'name'       => $basename,
                'size'       => $this->formatSize($bytes),
                'created_at' => date('d M Y H:i:s', $time),
            ];
        }

        usort($result, fn($a, $b) => strcmp($b['name'], $a['name']));

        return $result;
    }

    /**
     * Hapus file terlama jika jumlah file > $keep.
     */
    public function prune(int $keep = 30): void
    {
        if (! Storage::disk($this->disk)->exists($this->dir)) {
            return;
        }

        $files = Storage::disk($this->disk)->files($this->dir);
        $files = array_values(array_filter($files, fn($f) => str_ends_with($f, '.sql.zip')));

        if (count($files) <= $keep) {
            return;
        }

        usort($files, fn($a, $b) =>
            Storage::disk($this->disk)->lastModified($a) -
            Storage::disk($this->disk)->lastModified($b)
        );

        $toDelete = array_slice($files, 0, count($files) - $keep);

        foreach ($toDelete as $file) {
            try {
                Storage::disk($this->disk)->delete($file);
                Log::info("BackupManager: hapus file lama → {$file}");
            } catch (\Throwable $e) {
                Log::error("BackupManager: gagal hapus {$file}: {$e->getMessage()}");
            }
        }
    }

    // ---------------------------------------------------------------

    protected function formatSize(int $bytes): string
    {
        if ($bytes >= 1_048_576) {
            return round($bytes / 1_048_576, 2) . ' MB';
        }
        return round($bytes / 1_024, 2) . ' KB';
    }
}
