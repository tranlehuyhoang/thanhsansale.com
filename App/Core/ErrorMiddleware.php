<?php

namespace App\Core;

class ErrorMiddleware
{
    protected $logger;
    protected $error = null; // Lưu trữ thông tin lỗi

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function handle()
    {
        set_error_handler([$this, 'errorHandler']);
        set_exception_handler([$this, 'exceptionHandler']);
    }

    public function errorHandler($severity, $message, $file, $line)
    {
        if (error_reporting() & $severity) {
            $this->setError("Error", $message, $file, $line);
            $this->logger->log("$message in $file on line $line", "Error");
        }
    }

    public function exceptionHandler($exception)
    {
        $this->setError("Exception", $exception->getMessage(), $exception->getFile(), $exception->getLine());
        $this->logger->log($exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine(), "Exception");
    }

    protected function setError($type, $message, $file, $line)
    {
        $this->error = [
            'type' => $type,
            'message' => $message,
            'file' => $file,
            'line' => $line
        ];
    }

    public function getError()
    {
        return $this->error;
    }
}

