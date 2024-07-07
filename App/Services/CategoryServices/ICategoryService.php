<?php 
namespace App\Services\CategoryServices;
use App\Services\Interfaces\IBaseInterface;

interface ICategoryService extends IBaseInterface
{
    public function GetByName($name);
}