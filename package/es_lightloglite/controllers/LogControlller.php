<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class LogController extends Controller
{
    /**
     * عرض السجلات (Logs) مرتبة من الأحدث إلى الأقدم
     */
    public function showLogs(Request $request)
    {
        $logs = $this->processLogs($request, 'logs');

        // Count logs by type for pie chart
        $logCountsByType = [];
        foreach ($logs as $log) {
            $type = $log['type'];
            if (isset($logCountsByType[$type])) {
                $logCountsByType[$type]++;
            } else {
                $logCountsByType[$type] = 1;
            }
        }

        // Group logs by date for line chart
        $logCountsByDate = [];
        foreach ($logs as $log) {
            $date = Carbon::parse($log['datetime'])->format('Y-m-d');
            if (isset($logCountsByDate[$date])) {
                $logCountsByDate[$date]++;
            } else {
                $logCountsByDate[$date] = 1;
            }
        }

        return view('Logs.logs', compact('logs', 'logCountsByType', 'logCountsByDate'));
    }

    /**
     * عرض الإحصائيات (Counts) مرتبة من الأكبر إلى الأصغر
     */
    public function showCounts(Request $request)
    {
        $counts = $this->processLogs($request, 'counts');
        return view('Logs.log-counts', compact('counts'));
    }

    /**
     * معالجة السجلات
     */
    private function processLogs(Request $request, $type)
    {
        $filePath = storage_path('logs/laravel.log'); // موقع ملف اللوق

        if (!file_exists($filePath)) {
            return [];
        }

        // قراءة الملف كسطور
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $logs = $this->extractLogs($lines);

        // تصفية السجلات بناءً على نطاق التاريخ
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        if ($startDate || $endDate) {
            $logs = $this->filterLogsByDate($logs, $startDate, $endDate);
        }

        if ($type === 'logs') {
            // ترتيب السجلات من الأحدث إلى الأقدم
            usort($logs, function ($a, $b) {
                return strcmp($b['datetime'], $a['datetime']);
            });
            return $logs;
        } elseif ($type === 'counts') {
            // حساب عدد مرات ظهور كل نوع خطأ
            $counts = [];
            foreach ($logs as $log) {
                $type = $log['type'];
                if (isset($counts[$type])) {
                    $counts[$type]++;
                } else {
                    $counts[$type] = 1;
                }
            }
            // ترتيب الإحصائيات من الأكبر إلى الأصغر
            arsort($counts);
            return $counts;
        }

        return [];
    }

    /**
     * استخراج السجلات من الملف
     */
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

    /**
     * تصفية السجلات بناءً على نطاق التاريخ
     */
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
}
