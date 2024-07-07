<?php

namespace App\Services\BankServices;

use App\Services\Interfaces\IBaseInterface;

interface IBankService extends IBaseInterface
{
    public function GetByCode($code);
}
