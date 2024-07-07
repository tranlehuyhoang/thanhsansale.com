<?php
namespace App\Services\Identities\UserTokenServices;
use App\Services\Interfaces\IBaseInterface;

interface IUserTokenService extends IBaseInterface
{
    public function GetByToken($token);
    public function GetByUserId($userId);
}