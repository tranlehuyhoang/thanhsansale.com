<?php 
namespace App\Controllers\Base;

use App\Core\Controller;
use App\Services\Common\Session;

class BaseController extends Controller
{
   
    public function __construct()
    {
        if (!Session::IsAuth()) {
            header('Location: /auth/login');
            exit();
        }
        parent::__construct();
    }
}