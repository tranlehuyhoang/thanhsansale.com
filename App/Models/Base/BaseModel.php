<?php 
namespace App\Models\Base;

use App\Services\Common\Helper;

class BaseModel
{
    /**
     * Properties Id CreatedAt UpdatedAt CreatedBy UpdatedBy IsActive
     * 
     */
    public $Id;
    public $CreatedAt;
    public $UpdatedAt;
    public $CreatedBy;
    public $UpdatedBy;
    public $IsActive;
    /**
     * BaseModel constructor.
     * @param $data
     * @throws \Exception
     */
    public function __construct($data)
    {
        $this->Id = $data['Id'];
        $this->CreatedAt = $data['CreatedAt'] ?? '';
        $this->UpdatedAt = $data['UpdatedAt'] ?? '';
        $this->CreatedBy = $data['CreatedBy'] ?? '';
        $this->UpdatedBy = $data['UpdatedBy'] ?? '';
        $this->IsActive = $data['IsActive'];

    } 
}