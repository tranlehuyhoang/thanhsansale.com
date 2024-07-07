<?php

namespace App\Services\Common;

class Request
{
    public static function method($expectedMethod)
    {
        $method = self::server('REQUEST_METHOD');
        return strtoupper($method) === strtoupper($expectedMethod);
    }
    public static function get($key, $default = null)
    {
        return isset($_GET[$key]) ? Helper::antiXSS($_GET[$key]) : $default;
    }

    public static function post($key, $default = null)
    {
        return isset($_POST[$key]) ? Helper::antiXSS($_POST[$key]) : $default;
    }

    public static function cookie($key, $default = null)
    {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : $default;
    }

    public static function files($key)
    {
        return isset($_FILES[$key]) ? $_FILES[$key] : null;
    }

    public static function server($key, $default = null)
    {
        return isset($_SERVER[$key]) ? $_SERVER[$key] : $default;
    }

    // host
    public static function host()
    {
        return self::server('HTTP_HOST');
    }

    // document root
    public static function root()
    {
        return self::server('DOCUMENT_ROOT');
    }

    public static function uri()
    {
        return self::server('REQUEST_URI');
    }


    public static function queryString()
    {
        // users/page/1?Role=1&Username=abc&Email=abc&FullName=abc
        // check if has ?
        // get: Role=1&Username=abc&Email=abc&FullName=abc
        // add to array
        $uriParts = explode('?', self::uri());
        $params = [];

        if (count($uriParts) > 1) {
            $queryParts = explode('&', $uriParts[1]);

            foreach ($queryParts as $part) {
                list($key, $value) = explode('=', $part);
                $params[$key] = $value;
            }
        }

        return $params;
    }
}
