<?php 
namespace App\Models;
use App\Models\Base\BaseModel;
 class Page extends BaseModel {

    public $Title;
    public $Slug;
    public $Content;
    public $Code;
    public $IsMenu;

    public function __construct($page)
    {
        $this->Title = $page['Title'];
        $this->Slug = $page['Slug'];
        $this->Content = $page['Content'];
        $this->Code = $page['Code'];
        $this->IsMenu = $page['IsMenu'];
        parent::__construct($page);
    }
 }