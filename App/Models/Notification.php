<?php

namespace App\Models;

use App\Models\Base\BaseModel;

class Notification extends BaseModel
{
    // Properties
    public $Title;
    public $Content;
    public $Type;
    public $UserId;
    public $IsRead;
    // constructor
    public function __construct($data)
    {
        $this->Title = $data['Title'];
        $this->Content = $data['Content'];
        $this->Type = $data['Type'];
        $this->UserId = $data['UserId'];
        $this->IsRead = $data['IsRead'];
        parent::__construct($data);
    }
}