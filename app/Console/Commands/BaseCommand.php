<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

abstract class BaseCommand extends Command
{
    protected $signature = 'laravel:base';

    protected $startTime;

    protected $logid;

    protected function startLog($logging = true)
    {
        $this->logid = uniqid();
        $this->startTime = now();

        if ($logging) {
            log_notice("[{$this->logid}][{$this->signature}] Running: " . date('Y-m-d H:i:s'), 'cron');
        }
    }

    protected function endLog($logging = true)
    {
        $time = $this->startTime->diffInSeconds(now());
        $this->info('Done. Processed: ' . $time . 's');

        if ($logging) {
            log_notice("[{$this->logid}][{$this->signature}] Done: {$time}s", 'cron');
        }
    }
}
