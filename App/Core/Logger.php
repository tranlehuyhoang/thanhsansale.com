<?php
namespace App\Core;
class Logger
{
    protected $logPath;

    public function __construct($logPath = null)
    {
        if ($logPath === null) {
            $logPath = __DIR__ . '/../../Logs'; // Đường dẫn mặc định cho thư mục log
        }

        $this->logPath = $logPath;
    }


    public function log($message, $level = 'error')
    {
        $date = date('Ymd');
        $logDir = $this->logPath . '/' . $date;

        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $filename = $logDir . '/' . $level . '.log';

        // Check if message is an array and convert it to a string if it is
        if (is_array($message)) {
            $message = json_encode($message);
        }

        $logMessage = "[" . date('Y-m-d H:i:s') . "] [$level]: $message" . PHP_EOL;

        file_put_contents($filename, $logMessage, FILE_APPEND);
    }
}
