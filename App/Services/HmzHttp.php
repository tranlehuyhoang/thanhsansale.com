<?php
namespace App\Services;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class HmzHttp
{
    private $cookie;

    public function __construct($cookie)
    {
        $this->cookie = $cookie;
    }

    private function buildParams($params)
    {
        $paramString = '';
        foreach ($params as $key => $value) {
            $paramString .= '&' . $key . '=' . $value;
        }
        return rtrim($paramString, '&');
    }

    public function get($url, $params = [])
    {
        $url .= '?' . $this->buildParams($params);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIE, $this->cookie);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $res = [
                'success' => false,
                'message' => 'Lỗi khi thực hiện call API: ' . curl_error($ch)
            ];
            curl_close($ch);
            return $res;

        } else {
            $data = json_decode($response, true);
            curl_close($ch);
            return $data;
        }
    }

    // post

    public function post($url, $params = [])
    {
        $ch = curl_init();
        $params = json_encode($params);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIE, $this->cookie);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params); // JSON encode the parameters
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); // Set Content-Type to application/json

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Lỗi khi thực hiện call API: ' . curl_error($ch);
        } else {
            $data = json_decode($response, true);
            curl_close($ch);
            return $data;
        }
    }


    public function get_final_url($url)
    {
        $client = new Client([
            'allow_redirects' => [
                'max' => 10, // Số lần chuyển hướng tối đa
                'strict' => true,
                'referer' => true,
                'protocols' => ['http', 'https'],
                'track_redirects' => true
            ],
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
            ]
        ]);

        try {
            $response = $client->request('GET', $url);
            $redirect_history = $response->getHeader('X-Guzzle-Redirect-History');

            if (!empty($redirect_history)) {
                return end($redirect_history); // Trả về URL cuối cùng trong lịch sử chuyển hướng
            } else {
                return $url; // Nếu không có chuyển hướng, trả về URL gốc
            }
        } catch (RequestException $e) {
            return false;
        }
    }
}