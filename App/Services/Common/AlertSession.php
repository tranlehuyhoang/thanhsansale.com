<?php

namespace App\Services\Common;
class AlertSession
{
    const SESSION_KEY = 'alerts';

    public static function Error($message)
    {
        self::Alert('danger', $message);
    }

    public static function Success($message)
    {
        self::Alert('success', $message);
    }

    public static function Info($message)
    {
        self::Alert('info', $message);
    }

    public static function Warning($message)
    {
        self::Alert('warning', $message);
    }

    public static function getAlerts()
    {
        if (!isset($_SESSION[self::SESSION_KEY])) {
            return [];
        }

        $alerts = $_SESSION[self::SESSION_KEY];
        // Clear alerts after reading
        self::ClearAlerts();
        return $alerts;
    }

    protected static function Alert($type, $message)
    {
        if (!isset($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = [];
        }

        $_SESSION[self::SESSION_KEY][] = [
            'type' => $type,
            'message' => $message,
        ];
    }
    public static function ClearAlerts()
    {
        unset($_SESSION[self::SESSION_KEY]);
    }
}

// Use Example:
// AlertSession::addError('Something went wrong.');
// AlertSession::addSuccess('Operation completed successfully.');
// AlertSession::addInfo('You have a new message.');
// AlertSession::addWarning('You are about to reach your limit.');
// // In your view:
// $alerts = AlertSession::getAlerts();
// foreach ($alerts as $alert) {
//     echo '<div class="alert alert-' . $alert['type'] . '">' . $alert['message'] . '</div>';
// }
// Path: App\Services\Common\AlertSession.php