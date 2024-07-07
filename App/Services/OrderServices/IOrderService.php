<?php 
namespace App\Services\OrderServices;
use App\Services\Interfaces\IBaseInterface;

interface IOrderService extends IBaseInterface
{
    public function GetWithPaginate($pageIndex, $pageSize, $filter = []);
    public function GetByUserId($pageIndex, $pageSize, $userId,$filter = []);

    public function GetInStartDateAndEndDate($startDate, $endDate);
    public function RefundOrder($filter = []);
}