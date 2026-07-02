<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseBackup extends Command
{
    protected $signature = 'app:database-backup {--keep=10 : Number of recent backups to keep}';

    protected $description = 'Create a database backup using mysqldump';

    public function handle(): int
    {
        $disk = Storage::build([
            'driver' => 'local',
            'root' => storage_path('app/backups'),
        ]);

        $db = config('database.connections.mysql');
        $filename = 'backup-' . now()->format('Y-m-d-H-i-s') . '-' . Str::random(6) . '.sql';
        $path = storage_path('app/backups/' . $filename);

        $mysqldump = $this->findMysqldump();
        if (!$mysqldump) {
            $this->error('mysqldump not found. Please install MySQL client tools or set DB_BACKUP_MYSQLDUMP_PATH in .env');

            return Command::FAILURE;
        }

        $command = sprintf(
            '"%s" --host=%s --port=%s --user=%s %s %s > "%s" 2>&1',
            $mysqldump,
            escapeshellarg($db['host']),
            escapeshellarg($db['port']),
            escapeshellarg($db['username']),
            $db['password'] ? '-p' . escapeshellarg($db['password']) : '',
            escapeshellarg($db['database']),
            $path
        );

        $output = null;
        $resultCode = null;
        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            $this->error('Backup failed: ' . implode("\n", $output));

            return Command::FAILURE;
        }

        $disk->put($filename . '.meta', json_encode([
            'created_at' => now()->toIso8601String(),
            'database' => $db['database'],
            'size' => file_exists($path) ? filesize($path) : 0,
        ]));

        $this->info("Backup created: {$filename}");

        $this->pruneOldBackups((int)$this->option('keep'), $disk);

        return Command::SUCCESS;
    }

    private function findMysqldump(): ?string
    {
        $custom = env('DB_BACKUP_MYSQLDUMP_PATH');
        if ($custom && file_exists($custom)) {
            return $custom;
        }

        $candidates = [
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
        ];

        foreach ($candidates as $candidate) {
            if (file_exists($candidate)) {
                return $candidate;
            }
        }

        $which = trim(shell_exec('which mysqldump 2>/dev/null') ?? '');
        if ($which && file_exists($which)) {
            return $which;
        }

        return null;
    }

    private function pruneOldBackups(int $keep, $disk): void
    {
        $files = collect($disk->files())
            ->filter(fn($f) => str_ends_with($f, '.sql'))
            ->sort()
            ->values();

        $toDelete = $files->slice(0, max(0, $files->count() - $keep));

        foreach ($toDelete as $file) {
            $disk->delete($file);
            $disk->delete(str_replace('.sql', '.sql.meta', $file));
            $this->line("Pruned old backup: {$file}");
        }
    }
}
