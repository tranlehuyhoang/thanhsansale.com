<?php

namespace App\Services\Common;

use App\Core\Config;

class JWTToken
{
    private static $secretKey = Config::JWT_SECRET_KEY;

    public static function generateToken($data, $expiration)
    {
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT',
        ];

        $payload = [
            'data' => $data,
            'exp' => $expiration,
        ];

        $headerBase64 = base64_encode(json_encode($header));
        $payloadBase64 = base64_encode(json_encode($payload));

        $signature = hash_hmac('sha256', "$headerBase64.$payloadBase64", self::$secretKey, true);
        $signatureBase64 = base64_encode($signature);

        return "$headerBase64.$payloadBase64.$signatureBase64";
    }

    public static function verifyToken($token)
    {
        list($headerBase64, $payloadBase64, $signatureProvided) = explode('.', $token);

        $signature = hash_hmac('sha256', "$headerBase64.$payloadBase64", self::$secretKey, true);
        $signatureBase64 = base64_encode($signature);

        return hash_equals($signatureBase64, $signatureProvided);
    }

    public static function decodeToken($token)
    {
        list(, $payloadBase64,) = explode('.', $token);
        $payload = base64_decode($payloadBase64);
        return json_decode($payload, true);
    }
}
