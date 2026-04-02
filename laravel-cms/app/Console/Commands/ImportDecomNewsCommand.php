<?php

namespace App\Console\Commands;

use App\Jobs\ImportDecomNewsJob;
use Illuminate\Console\Command;

class ImportDecomNewsCommand extends Command
{
    protected $signature = 'decom:import-news {--pages=10} {--sync}';
    protected $description = 'Importa noticias e editais do portal DECOM';

    public function handle(): int
    {
        $pages = max(1, (int)$this->option('pages'));
        if ($this->option('sync')) {
            (new ImportDecomNewsJob($pages))->handle();
            $this->info("Importacao sincronizada concluida. pages={$pages}");
            return self::SUCCESS;
        }

        ImportDecomNewsJob::dispatch($pages);
        $this->info("Importacao enviada para fila. pages={$pages}");
        return self::SUCCESS;
    }
}
