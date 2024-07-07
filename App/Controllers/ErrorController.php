<?php

namespace App\Controllers;

use App\Core\Controller;

class ErrorController extends Controller
{
    public function PageNotFound()
    {
        $this->render('Error.404','_AuthenLayout', ['title' => 'Page Not Found']);
    }
}
