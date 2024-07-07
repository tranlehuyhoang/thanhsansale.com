<?php

namespace App\Services\OrderServices;

use App\Models\Order;
use App\Services\BaseService;
use App\Services\Common\Enums\EShopeeStatus;
use App\Services\Common\Helper;
use App\Services\Common\SqlCommon;

class OrderService extends BaseService implements IOrderService
{
    public $tableName = 'orders';
    /**
     */
    public function GetAll()
    {
        $buildSql = SqlCommon::BuildQuery($this->tableName, NULL, NULL, NULL, NULL);
        $data = $this->context->fetch($buildSql);
        $orders = [];
        foreach ($data as $item) {
            $setting = (object) ($item);
            array_push($orders, $setting);
        }
        return $orders;
    }

    /**
     *
     * @param mixed $id
     */
    public function GetById($id)
    {
        $buildSql = SqlCommon::BuildQuery($this->tableName, "Id =$id", Null, NULL, NULL);
        $data = $this->context->fetch_one($buildSql);
        if (!$data) {
            return NULL;
        }
        $order = (object) $data;
        return $order;
    }

    /**
     *
     * @param mixed $pageIndex
     * @param mixed $pageSize
     */

    public function GetWithPaginate($pageIndex, $pageSize, $filter = [])
    {
        $offset = ($pageIndex - 1) * $pageSize;
        $whereSql = '';
        if (!empty($filter)) {
            $filterParts = [];
            foreach ($filter as $key => $value) {
                // check null
                if (empty($value) && $value != 0) {
                    continue;
                }
                // check is int
                if (is_numeric($value) && $key != 'Username') {
                    $filterParts[] = "o.$key = $value";
                    continue;
                }
                // check is FromCreatedAt and ToCreatedAt
                if ($key == 'FromCreatedAt') {
                    $startDate = $filter['FromCreatedAt'];
                    $filterParts[] = "o.CreatedAt >= '$startDate'";
                    continue;
                }
                if ($key == 'ToCreatedAt') {
                    $endDate = $filter['ToCreatedAt'];
                    $filterParts[] = "o.CreatedAt <= '$endDate'";
                    continue;
                }
                // check key is FromUpdatedAt and ToUpdatedAt
                if ($key == 'FromUpdatedAt') {
                    $startDate = $filter['FromUpdatedAt'];
                    $filterParts[] = "o.UpdatedAt >= '$startDate'";
                    continue;
                }
                if ($key == 'ToUpdatedAt') {
                    $endDate = $filter['ToUpdatedAt'];
                    $filterParts[] = "o.UpdatedAt <= '$endDate'";
                    continue;
                }
                
                if ($key == 'Username') {
                    $filterParts[] = "u.$key LIKE '%$value%'";
                    continue;
                }
                $filterParts[] = "o.$key LIKE '%$value%'";
            }
            if (!empty($filterParts))
                $whereSql = 'WHERE ' . implode(' AND ', $filterParts);
        }
        $buildSql = "
            SELECT o.*, u.Username FROM $this->tableName as o
            LEFT JOIN users as u ON o.UserId = u.Id
            $whereSql
            ORDER BY CreatedAt DESC
            LIMIT $offset, $pageSize
        ";
        $data = $this->context->fetch($buildSql);
        $orders = [];
        foreach ($data as $item) {
            $order = new Order($item);
            $order->Price = Helper::formatCurrencyVND($order->Price);
            $order->Discount = Helper::formatCurrencyVND($order->Discount);
            $order->Status = $this->RenderShopeeStatus($order->Status);
            $order->Type = $this->RenderType($order->Type);
            $order->StatusCode = $item['Status'];
            array_push($orders, $order);
        }
        return $orders;
    }
    /**
     *
     * @param mixed $pageIndex
     * @param mixed $pageSize
     * @param mixed $userId
     */
    public function GetByUserId($pageIndex, $pageSize, $userId, $filter = [])
    {
        $offset = ($pageIndex - 1) * $pageSize;
        $whereSql = '';

        // Apply filters
        if (!empty($filter)) {
            $filterParts = [];
            foreach ($filter as $key => $value) {
                // check is int
                if (is_numeric($value)) {
                    $filterParts[] = "$key = $value";
                    continue;
                }
                if ($key == 'Username') {
                    $filterParts[] = "u.$key LIKE '%$value%'";
                    continue;
                }
                $filterParts[] = "$key LIKE '%$value%'";
            }
            $whereSql = ' AND ' . implode(' AND ', $filterParts);
        }

        // Get the total count of orders for the user
        $countSql = "SELECT COUNT(*) as TotalOrders FROM $this->tableName WHERE UserId = $userId";
        $totalCountResult = $this->context->fetch($countSql);
        $totalCount = $totalCountResult[0]['TotalOrders'];

        // Retrieve the orders
        $buildSql = "
        SELECT 
            o.*, 
            u.Username 
        FROM 
            $this->tableName as o
        LEFT JOIN 
            users as u ON o.UserId = u.Id
        WHERE 
            o.UserId = $userId
            $whereSql
        ORDER BY 
            o.Id DESC
        LIMIT 
            $offset, $pageSize
    ";

        $data = $this->context->fetch($buildSql);
        $orders = [];

        // Process the result to calculate the descending index
        foreach ($data as $index => $item) {
            $order = (object) ($item);
            $order->Price = Helper::formatCurrencyVND($order->Price);
            $order->Discount = Helper::formatCurrencyVND($order->Discount);
            $order->CreatedAt = date('d/m/Y H:m:s', strtotime($order->CreatedAt));
            $order->Status = $this->RenderShopeeStatus($order->Status);
            $order->Type = $this->RenderType($order->Type);
            // Calculate descending index based on the total count and the current page
            $order->Index = $totalCount - ($offset + $index);
            array_push($orders, $order);
        }

        return $orders;
    }


    public function RefundOrder($filter = [])
    {
        $whereSql = '';
        if (!empty($filter)) {
            $filterParts = [];
            foreach ($filter as $key => $value) {
                // check null
                if ($value == null || empty($value)) {
                    continue;
                }
                // check is int
                if (is_numeric($value)) {
                    $filterParts[] = "o.$key = $value";
                    continue;
                }

                // check key is Username
                if ($key == 'Username') {
                    $filterParts[] = "u.$key LIKE '%$value%'";
                    continue;
                }
                $filterParts[] = "$key LIKE '%$value%'";
            }
            if (!empty($filterParts))
                $whereSql = 'WHERE ' . implode(' AND ', $filterParts);
        }
        $buildSql = "
            SELECT o.*, u.Username FROM $this->tableName as o
            LEFT JOIN users as u ON o.UserId = u.Id
            $whereSql AND o.Refund = 0
            ORDER BY CreatedAt DESC
        ";
        $data = $this->context->fetch($buildSql);
        if (empty($data)) {
            return false;
        }
        $sqlDiffPrice = "";
        foreach ($data as $item) {
            $orderId = $item['Id'];
            $userId = $item['UserId'];
            $price = $item['Discount'];
            $sqlDiffPrice .= "
                    UPDATE users SET Money = Money - $price WHERE Id = $userId;
                    UPDATE orders SET Refund = 1 WHERE Id = $orderId;
                ";
        }
        $res = $this->SQLQuery($sqlDiffPrice);
        return $res;
    }


    public function GetInStartDateAndEndDate($startDate, $endDate)
    {
        $status = EShopeeStatus::Completed;
        $buildSql = "
            SELECT * FROM $this->tableName
            WHERE CreatedAt BETWEEN '$startDate' AND '$endDate'
            AND Status = $status AND UserId IS NOT NULL AND UserId != 0
        ";
        $data = $this->context->fetch($buildSql);
        $orders = [];
        foreach ($data as $item) {
            $order = (object) ($item);
            array_push($orders, $order);
        }
        return $orders;
    }


    //'0: Pending, 1: Success, 2: Cancel',

    //#region Private Methods

    public function RenderShopeeStatus($status)
    {
        switch ($status) {
            case EShopeeStatus::Pending:
                return '<span class="badge rounded-pill bg-info">Đang xử lý</span>';
            case EShopeeStatus::Completed:
                return '<span class="badge rounded-pill bg-success">Hoàn thành</span>';
            case EShopeeStatus::Cancel:
                return '<span class="badge rounded-pill bg-danger">Đã hủy</span>';
            case EShopeeStatus::NotPaid:
                return '<span class="badge rounded-pill bg-warning">Chưa thanh toán</span>';
            default:
                return '<span class="badge rounded-pill bg-dark">Không xác định</span>';
        }
    }



    //'0: Shopee, 1: Lazada, 2: Tiktok Shop', 
    private function RenderType($type)
    {
        switch ($type) {
            case 0:
                return 'Shopee';
            case 1:
                return 'Lazada';
            case 2:
                return 'Tiktok Shop';
            default:
                return 'Không xác định';
        }
    }

    //#endregion Private Methods
}
