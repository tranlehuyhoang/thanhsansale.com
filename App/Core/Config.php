<?php

namespace App\Core;

class Config
{
    private $data = [];
    public const JWT_SECRET_KEY = "3c9b320d02d1e65bc4159606e374b57c94c366cc";

    public const GoogleRecaptcha_KEY = "6LfWLgkqAAAAAK4lPlK6XXMDxM8GZWAwUwhyQDbM";
    public const GoogleRecaptcha_SECRET = "6LfWLgkqAAAAAGvfjdAWdzXeLmcuX8Jzrgi7oX-Q";

    public function __construct()
    {
        // Load your configuration data here
        $this->loadConfig();
    }

    private function loadConfig()
    {
 

        $this->data = [
            'app_name' => 'Shopping Affiliate',
            'db_host' => 'localhost',
            'db_name' => 'thanhsansale_db',
            'db_user' => 'thanhsansale_admin',
            'db_password' => 'ZGEVx2G2L2;i',
            'timezone' => 'Asia/Ho_Chi_Minh',
        ];
    }
    public static function PageConfig()
    {
        return [
            'PageSize' => 10,
            'PageIndex' => 1,
            'PageOption' => [10, 20, 50, 100],
        ];
    }

    public static function MailConfig()
    {
        // return [
        //     'Host' => '	smtp.zshield.cloud',
        //     'Port' => 587,
        //     'Username' => 'no-reply@muahanghoantien.com',
        //     'Password' => 'Abc12345@',
        //     'SMTPSecure' => 'tls',
        //     'FromName' => 'Mua Hàng Hoàn Tiền',
        // ];
        return [
            'Host' => 'smtp.zshield.cloud',
            'Port' => 587,
            'Username' => 'no-reply@thanhsansale.com',
            'Password' => 'Abc123345@',
            'SMTPSecure' => 'tls',
            'FromName' => 'Thánh Săn Sale',
        ];
    }

    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }
}
