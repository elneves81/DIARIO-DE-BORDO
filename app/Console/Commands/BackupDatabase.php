<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database {--compress} {--email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realiza backup do banco de dados';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Iniciando backup do banco de dados...');

        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port', 3306);

        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $fileName = "backup_{$database}_{$timestamp}.sql";
        $backupPath = storage_path("app/backups/{$fileName}");

        // Criar diretório se não existir
        if (!file_exists(dirname($backupPath))) {
            mkdir(dirname($backupPath), 0755, true);
        }

        // Comando mysqldump
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --port=%s --single-transaction --routines --triggers %s > %s',
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($database),
            escapeshellarg($backupPath)
        );

        $returnVar = null;
        $output = null;
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            $this->error('Erro ao executar backup: ' . implode("\n", $output));
            return 1;
        }

        $this->info("Backup criado: {$backupPath}");

        // Comprimir se solicitado
        if ($this->option('compress')) {
            $this->compressBackup($backupPath);
        }

        // Limpar backups antigos (manter apenas os últimos 7 dias)
        $this->cleanOldBackups();

        // Enviar por email se solicitado
        if ($this->option('email')) {
            $this->emailBackup($backupPath);
        }

        $this->info('Backup concluído com sucesso!');
        return 0;
    }

    private function compressBackup(string $backupPath): void
    {
        $this->info('Comprimindo backup...');
        
        $compressedPath = $backupPath . '.gz';
        
        $command = sprintf('gzip %s', escapeshellarg($backupPath));
        exec($command);
        
        if (file_exists($compressedPath)) {
            $this->info("Backup comprimido: {$compressedPath}");
        }
    }

    private function cleanOldBackups(): void
    {
        $this->info('Limpando backups antigos...');
        
        $backupDir = storage_path('app/backups');
        $files = glob($backupDir . '/backup_*.sql*');
        
        $cutoffDate = Carbon::now()->subDays(7);
        
        foreach ($files as $file) {
            $fileTime = Carbon::createFromTimestamp(filemtime($file));
            
            if ($fileTime->lt($cutoffDate)) {
                unlink($file);
                $this->info("Removido backup antigo: " . basename($file));
            }
        }
    }

    private function emailBackup(string $backupPath): void
    {
        // Implementar envio por email se necessário
        $this->info('Funcionalidade de email não implementada ainda.');
    }
}
