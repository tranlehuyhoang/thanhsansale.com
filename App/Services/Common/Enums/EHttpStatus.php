<?php

namespace App\Services\Common\Enums;
class EHttpStatus
{
    public const OK = 200;
    public const CREATED = 201;
    public const ACCEPTED = 202;
    public const NO_CONTENT = 204;
    public const BAD_REQUEST = 400;
    public const UNAUTHORIZED = 401;
    public const NOT_FOUND = 404;
    public const METHOD_NOT_ALLOWED = 405;
    public const INTERNAL_SERVER_ERROR = 500;
}
