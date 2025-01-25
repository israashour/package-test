<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExtractErrorLogs extends Command
{
    
    protected $signature = 'logs:extract-errors {file}';

   
    protected $description = 'Extract the first line of each error from the Laravel log file';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
       
        $filePath = $this->argument('file');

       
        if (!file_exists($filePath)) {
            $this->error("The file does not exist: $filePath");
            return;
        }

        
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $errors = [];
        foreach ($lines as $line) {
           
            if (preg_match('/^\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] ERROR:/', $line)) {
                $errors[] = $line;
            }
        }

       
        if (empty($errors)) {
            $this->info('No errors found in the log file.');
        } else {
            $this->info("Extracted errors:");
            foreach ($errors as $error) {
                $this->line($error);
            }
        }
    }
}
