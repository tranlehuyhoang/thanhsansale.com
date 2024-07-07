<?php 
namespace App\Services\BankServices;

use App\Models\Bank;
use App\Services\BaseService;
use App\Services\Common\SqlCommon;

class BankService extends BaseService implements IBankService
{

    public $tableName = 'banks';
    /**
     */
    public function GetAll() {
        $buildSql = SqlCommon::BuildQuery($this->tableName, NULL, NULL, NULL, NULL);
        $data = $this->context->fetch($buildSql);
        $banks = [];
        foreach ($data as $item) {
            $bank = new Bank($item);
            array_push($banks, $bank);
        }
        return $banks;
    }
    
    /**
     *
     * @param mixed $id
     */
    public function GetById($id) {
        $buildSql = SqlCommon::BuildQuery($this->tableName,"Id =$id", Null, NULL, NULL);
        $data = $this->context->fetch_one($buildSql);
        if (!$data) {
            return NULL;
        }
        $bank = new Bank($data);
        return $bank;
    }
    
    /**
     *
     * @param mixed $pageIndex
     * @param mixed $pageSize
     */
    public function GetWithPaginate($pageIndex, $pageSize) {
        $offset = ($pageIndex - 1) * $pageSize;
        $buildSql = "SELECT * FROM $this->tableName ORDER BY CreatedAt DESC LIMIT $offset, $pageSize";
        $data = $this->context->fetch($buildSql);
        $banks = [];
        foreach ($data as $item) {
            $bank = new Bank($item);
            array_push($banks, $bank);
        }
        return $banks;
    }
    /**
     *
     * @param mixed $code
     */
    public function GetByCode($code) {
        $buildSql = SqlCommon::BuildQuery($this->tableName,"Code ='$code'", Null, NULL, NULL);
        $data = $this->context->fetch_one($buildSql);
        if (!$data) {
            return NULL;
        }
        $bank = new Bank($data);
        return $bank;
    }
}