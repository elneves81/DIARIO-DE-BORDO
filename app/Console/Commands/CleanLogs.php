<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clean {--days=30 : Dias de logs para manter}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpa logs antigos do sistema';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);
        
        $this->info("Limpando logs mais antigos que {$days} dias ({$cutoffDate->format('Y-m-d')})...");

        $logPath = storage_path('logs');
        $deletedCount = 0;
        $deletedSize = 0;

        // Buscar todos os arquivos de log
        $logFiles = glob($logPath . '/*.log');
        
        foreach ($logFiles as $file) {
            $fileTime = Carbon::createFromTimestamp(filemtime($file));
            
            if ($fileTime->lt($cutoffDate)) {
                $size = filesize($file);
                
                if (unlink($file)) {
                    $deletedCount++;
                    $deletedSize += $size;
                    $this->line("Removido: " . basename($file) . " (" . $this->formatBytes($size) . ")");
                }
            }
        }

        // Limpar logs do Laravel (arquivos com data)
        $laravelLogs = glob($logPath . '/laravel-*.log');
        
        foreach ($laravelLogs as $file) {
            // Extrair data do nome do arquivo (formato: laravel-YYYY-MM-DD.log)
            if (preg_match('/laravel-(\d{4}-\d{2}-\d{2})\.log$/', basename($file), $matches)) {
                $fileDate = Carbon::createFromFormat('Y-m-d', $matches[1]);
                
                if ($fileDate->lt($cutoffDate)) {
                    $size = filesize($file);
                    
                    if (unlink($file)) {
                        $deletedCount++;
                        $deletedSize += $size;
                        $this->line("Removido: " . basename($file) . " (" . $this->formatBytes($size) . ")");
                    }
                }
            }
        }

        if ($deletedCount > 0) {
            $this->info("Limpeza concluída: {$deletedCount} arquivos removidos, {$this->formatBytes($deletedSize)} liberados.");
        } else {
            $this->info("Nenhum arquivo de log antigo encontrado para remoção.");
        }

        return 0;
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
