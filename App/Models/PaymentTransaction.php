<?php 
namespace App\Models;
use App\Models\Base\BaseModel;

class PaymentTransaction extends BaseModel
{
    public $UserId;
    public $Code;
    public $Type;
    public $Price;
    public $Status;
    public $Note;
    // for view
    public $Username;
    public $StatusString;
    public $Money;
    // constructor
    public function __construct($data)
    {
        $this->UserId = $data['UserId'];
        $this->Code = $data['Code'];
        $this->Type = $data['Type'];
        $this->Price = $data['Price'];
        $this->Status = $data['Status'];
        $this->Note = $data['Note'];
        // for view
        $this->Username = $data['Username'] ?? '';
        $this->StatusString = $data['Status'] ?? '';
        $this->Money = $data['Price'] ?? '';

        parent::__construct($data);
    }
}