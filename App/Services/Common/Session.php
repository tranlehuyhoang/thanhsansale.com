<?php

namespace App\Services\Common;

use App\Services\Common\Enums\ERole;

class Session
{
    public static function start()
    {
        ini_set('session.gc_maxlifetime', 5184000);
        ini_set('session.cookie_lifetime', 5184000);
        if (session_status() == PHP_SESSION_NONE) {
            ob_start();
            session_start();
        }
    }
    // Authozize
    public static function Authorize($role = ERole::Admin)
    {
        $user = self::get('user');
        if ($user == null) {
            return false;
        }
        $roleName = self::get('role');
        if ($roleName != $role) {
            return false;
        }
        return true;
    }

    // authen
    public static function IsAuth()
    {
        $user = self::get('user');
        if ($user == null) {
            return false;
        }
        return true;
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function remove($key)
    {
        if (self::has($key)) {
            unset($_SESSION[$key]);
        }
    }
    public static function destroy()
    {
         self::remove('user');
    }
}
