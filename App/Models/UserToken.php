<?php 
namespace App\Models;
use App\Models\Base\BaseModel;

class UserToken extends BaseModel
{
    // Properties
    public $Token;
    public $UserId;
    public $ExpiredTime;

    // constructor
    public function __construct($userToken)
    {
        $this->Token = $userToken['Token'];
        $this->UserId = $userToken['UserId'];
        $this->ExpiredTime = $userToken['ExpiredTime'];
        parent::__construct($userToken);
    }
}