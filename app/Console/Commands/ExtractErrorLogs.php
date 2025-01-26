<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExtractErrorLogs extends Command
{
    /**
     * اسم الأمر الخاص بـ Artisan
     */
    protected $signature = 'logs:extract-errors {file}';

    /**
     * وصف الأمر
     */
    protected $description = 'Extract the date-time and type of each error from the Laravel log file';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // الحصول على مسار الملف
        $filePath = $this->argument('file');

        // التحقق من وجود الملف
        if (!file_exists($filePath)) {
            $this->error("The file does not exist: $filePath");
            return;
        }

        // قراءة الأسطر من الملف
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $errors = [];
        foreach ($lines as $line) {
            // التحقق من أن السطر يحتوي على خطأ
            if (preg_match('/^\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] ERROR:/', $line)) {
                // استخراج التاريخ والوقت
                preg_match('/^\[(.*?)\] ERROR:/', $line, $matches);
                $datetime = $matches[1] ?? 'Unknown Date-Time';

                // استخراج نوع الخطأ
                preg_match('/ERROR: ([a-zA-Z\\\]+):/', $line, $typeMatches);
                $type = $typeMatches[1] ?? 'Unknown Type';

                // إنشاء الكائن وإضافته إلى المصفوفة
                $errors[] = (object)[
                    'datetime' => $datetime,
                    'type' => $type,
                ];
            }
        }

        // طباعة النتيجة
        if (empty($errors)) {
            $this->info('No errors found in the log file.');
        } else {
            $this->info("Extracted errors:");
            foreach ($errors as $error) {
                $this->line("Date-Time: {$error->datetime}, Type: {$error->type}");
            }
        }
    }
}
