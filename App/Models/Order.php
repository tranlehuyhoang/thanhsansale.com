<?php 
namespace App\Models;
use App\Models\Base\BaseModel;
class Order extends BaseModel
{
    // Properties
    public $UserId;
    public $ProductName;
    public $Code;
    public $Price;
    public $Discount;
    public $Status;
    public $StatusCode;
    public $Type;
    public $Note;
    public $Refund;
    // for view
    public $Username;
    public $Index;
    // constructor
    public function __construct($order)
    {
        $this->UserId = $order['UserId'] ?? '';
        $this->ProductName = $order['ProductName'];
        $this->Code = $order['Code'];
        $this->Price = $order['Price'];
        $this->Discount = $order['Discount'];
        $this->Status = $order['Status'];
        $this->Type = $order['Type'];
        $this->Note = $order['Note'];
        //for view
        $this->Username = $order['Username'] ?? '';
        $this->Index = $order['Index'] ?? '';
        $this->Refund = $order['Refund'] ?? '';
        $this->StatusCode = $order['StatusCode'] ?? '';

        
        parent::__construct($order);
    }
}