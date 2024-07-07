<?php

namespace App\Services\Common;

class Response
{
    public static function success($data = [], $message = "Success", $statusCode = 200,$pageIndex = 1)
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
            'pageIndex'=> $pageIndex,
        ]);
    }

    public static function badRequest($data = [], $message = "Bad Request", $statusCode = 400)
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data
        ]);
    }
    
    public static function notFound($data = [], $message = "Not Found", $statusCode = 404)
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data
        ]);
    }
    public static function serverError($data = [], $message = "Server Error", $statusCode = 500)
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data
        ]);
    }
    public static function unauthorized($data = [], $message = "Unauthorized", $statusCode = 401)
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data
        ]);
    }
    public static function forbidden($data = [], $message = "Forbidden", $statusCode = 403)
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data
        ]);
    }
    public static function methodNotAllowed(){
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'statusCode' => 405,
            'message' => 'Method Not Allowed',
            'data' => []
        ]);
    }
}
