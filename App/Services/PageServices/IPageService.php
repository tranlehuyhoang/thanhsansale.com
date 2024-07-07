<?php
namespace App\Services\PageServices;

use App\Services\Interfaces\IBaseInterface;

interface IPageService extends IBaseInterface
{
    public function GetByCode($code);
    public function GetAllIsMenu($isMenu = 0);
}