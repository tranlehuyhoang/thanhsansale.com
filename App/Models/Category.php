<?php

namespace App\Models;

use App\Models\Base\BaseModel;

class Category extends BaseModel
{
    // Properties
    public $Name;
    public $Image;
    public $Slug;
    public $Config;
    public $Discount;
    public $Content;
    // constructor
    public function __construct($data)
    {

        $this->Name = $data['Name'];
        $this->Image = $data['Image'];
        $this->Slug = $data['Slug'] ?? '';
        $this->Config = $data['Config'];
        $this->Discount = $data['Discount'];
        $this->Content = $data['Content'];

        parent::__construct($data);
    }
}
