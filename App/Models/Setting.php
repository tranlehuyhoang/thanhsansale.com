<?php

namespace App\Models;

use App\Models\Base\BaseModel;

class Setting extends BaseModel
{
    // Properties
    public $Logo;
    public $SiteName;
    public $Copyright;
    public $Description;
    public $Keyword;
    public $Address;
    public $Type;
    public $Favicon;
    public $DatePayment;
    public $ShowTop;
    public $DescriptionTop;
    // constructor
    public function __construct($data)
    {
        $this->Logo = $data['Logo'];
        $this->SiteName = $data ['SiteName'];
        $this->Copyright = $data ['Copyright'];
        $this->Description = $data ['Description'];
        $this->Keyword = $data ['Keyword'];
        $this->Address = $data ['Address'];
        $this->Type = $data ['Type'];
        $this->Favicon = $data ['Favicon'];
        $this->DatePayment = $data ['DatePayment'] ?? '';
        $this->ShowTop = $data ['ShowTop'] ?? 0;
        $this->DescriptionTop = $data ['DescriptionTop'] ?? '';
 
        parent::__construct($data);
    }
}
