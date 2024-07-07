<?php 
namespace App\Services\PaymentTransactionServices;

use App\Services\Interfaces\IBaseInterface;

interface IPaymentTransactionService extends IBaseInterface
{
    public function GetWithPaginate($pageIndex, $pageSize, $filter= [], $orderBy = null,$type = 0);
    public function GetByUserId($pageIndex, $pageSize, $userId,$filter = [],$type=0);
    public function GetByStatus($status);
    public function ExportExcel($filter = []);
    public function ApproveAll();
}