<?php 
namespace App\Services\NotificationServices;
use App\Services\Interfaces\IBaseInterface;

interface INotificationService extends IBaseInterface
{
    public function GetByType($type = 0);
}