<?php

namespace App\Models;

use App\Models\Base\BaseModel;

class Bank extends BaseModel
{
    public $Code;
    public $Name;
    public $NameTCB;
    public $NameVPBank;
    public $Logo;

    public function __construct($data)
    {
        $this->Code = $data['Code'];
        $this->Name = $data['Name'];
        $this->NameTCB = $data['NameTCB'];
        $this->NameVPBank = $data['NameVPBank'];
        $this->Logo = $data['Logo'];

        parent::__construct($data);
    }
}

