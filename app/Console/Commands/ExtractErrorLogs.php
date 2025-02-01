<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class ExtractErrorLogs extends Command
{

    protected $signature = 'logs:process-errors {file} {--start-date=} {--end-date=}';


    protected $description = 'Process Laravel log file errors with filtering and grouping';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $filePath = $this->argument('file');
        $startDate = $this->option('start-date');
        $endDate = $this->option('end-date');


        if (!file_exists($filePath)) {
            $this->error("The file does not exist: $filePath");
            return;
        }


        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);


        $logs = $this->extractLogs($lines);


        if ($startDate || $endDate) {
            $logs = $this->filterLogsByDate($logs, $startDate, $endDate);
        }


        $this->displayLogs($logs);
        $this->displayErrorCounts($logs);
    }


    private function extractLogs(array $lines): array
    {
        $logs = [];
        foreach ($lines as $line) {

            if (preg_match('/^\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] ERROR:/', $line)) {

                preg_match('/^\[(.*?)\] ERROR:/', $line, $matches);
                $datetime = $matches[1] ?? 'Unknown Date-Time';


                preg_match('/ERROR: ([a-zA-Z\\\]+):/', $line, $typeMatches);
                $type = $typeMatches[1] ?? 'Unknown Type';


                $logs[] = [
                    'datetime' => $datetime,
                    'type' => $type,
                ];
            }
        }

        return $logs;
    }


    private function filterLogsByDate(array $logs, ?string $startDate, ?string $endDate): array
    {
        $filteredLogs = [];
        $start = $startDate ? Carbon::parse($startDate) : null;
        $end = $endDate ? Carbon::parse($endDate) : null;

        foreach ($logs as $log) {
            $logDate = Carbon::parse($log['datetime']);
            if (($start && $logDate->lt($start)) || ($end && $logDate->gt($end))) {
                continue;
            }
            $filteredLogs[] = $log;
        }

        return $filteredLogs;
    }


    private function displayLogs(array $logs): void
    {
        $this->info("Logs sorted by date (newest to oldest):");
        usort($logs, function ($a, $b) {
            return strcmp($b['datetime'], $a['datetime']);
        });

        foreach ($logs as $log) {
            $this->line("Date-Time: {$log['datetime']}, Type: {$log['type']}");
        }
    }


    private function displayErrorCounts(array $logs): void
    {
        $this->info("\nError counts by type:");
        $errorCounts = [];
        foreach ($logs as $log) {
            $type = $log['type'];
            if (isset($errorCounts[$type])) {
                $errorCounts[$type]++;
            } else {
                $errorCounts[$type] = 1;
            }
        }

        foreach ($errorCounts as $type => $count) {
            $this->line("Type: {$type}, Count: {$count}");
        }
    }
}
