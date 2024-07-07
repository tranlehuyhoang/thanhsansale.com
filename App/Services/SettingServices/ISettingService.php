<?php

namespace App\Services\SettingServices;

use App\Services\Interfaces\IBaseInterface;

interface ISettingService extends IBaseInterface
{
    public function GetTopActive();
    public function GetSetting($type);
}
