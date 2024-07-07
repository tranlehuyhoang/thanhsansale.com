<?php

namespace App\Models;

use App\Models\Base\BaseModel;
use App\Services\Common\Helper;

class User extends BaseModel
{
    // Properties
    public $Username;
    public $Email;
    public $Password;
    public $FullName;
    public $Phone;
    public $Avatar;
    public $Role;
    public $NameBank;
    public $NumberBank;
    public $Money;

    // other properties
    public $Price;
    public $BankCode;
    // constructor
    public function __construct($user)
    {
        $this->Username = $user['Username'];
        $this->Email = $user['Email'];
        $this->Password = $user['Password'];
        $this->FullName = $user['FullName'] ?? '';
        $this->Phone = $user['Phone'] ?? '';
        $this->Avatar = $user['Avatar'] ?? '';
        $this->Role = $user['Role'] ?? '';

        $this->NameBank = $user['NameBank'] ?? '';
        $this->NumberBank = $user['NumberBank'] ?? '';
        $this->Money = Helper::formatCurrencyVND($user['Money']);
        // other properties
        $this->Price = $user['Money'];
        parent::__construct($user);
    }
}
