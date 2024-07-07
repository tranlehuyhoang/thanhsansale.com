<?php

namespace App\Controllers\Base;

use App\Core\Controller;
use App\Services\Common\Enums\ERole;
use App\Services\Common\Session;

class AdminController extends Controller
{
   
    public function __construct()
    {
        if (!Session::Authorize(ERole::Admin)) {
            header('Location: /auth/login');
            exit();
        }
        parent::__construct();
    }
}
