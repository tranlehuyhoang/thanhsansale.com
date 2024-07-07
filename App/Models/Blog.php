<?php

namespace App\Models;

use App\Models\Base\BaseModel;

class Blog extends BaseModel
{
    // Properties
    public $Title;
    public $Slug;
    public $Content;
    public $Image;
    // constructor
    public function __construct($data)
    {

        $this->Title = $data['Title'];
        $this->Slug = $data['Slug'] ?? '';
        $this->Content = $data['Content'];
        $this->Image = $data['Image'] ?? '';

        parent::__construct($data);
    }
}
