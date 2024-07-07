<?php
namespace App\Services\PaymentTransactionServices;

use App\Models\PaymentTransaction;
use App\Services\BaseService;
use App\Services\Common\ExcelHelper;
use App\Services\Common\Helper;
use App\Services\Common\SqlCommon;
use App\Services\PaymentTransactionServices\IPaymentTransactionService;

class PaymentTransactionService extends BaseService implements IPaymentTransactionService
{
    public $tableName = 'payment_transactions';
    /**
     */
    public function GetAll()
    {
        $buildSql = SqlCommon::BuildQuery($this->tableName, NULL, NULL, NULL, NULL);
        $data = $this->context->fetch($buildSql);
        $paymentTransactions = [];
        foreach ($data as $item) {
            $paymentTransaction = new PaymentTransaction($item);
            array_push($paymentTransactions, $paymentTransaction);
        }
        return $paymentTransactions;
    }

    /**
     *
     * @param mixed $id
     */
    public function GetById($id)
    {
        $buildSql = SqlCommon::BuildQuery($this->tableName, "Id=$id", NULL, NULL, NULL);
        $data = $this->context->fetch_one($buildSql);
        if (!$data) {
            return NULL;
        }
        $paymentTransaction = new PaymentTransaction($data);
        return $paymentTransaction;
    }

    /**
     *
     * @param mixed $pageIndex
     * @param mixed $pageSize
     */
    public function GetWithPaginate($pageIndex, $pageSize, $filter = [], $orderBy = 'Id DESC',$type = 0)
    {
        $offset = ($pageIndex - 1) * $pageSize;
        $whereSql = 'WHERE pt.Type = '.$type;
        if (!empty($filter)) {
            $filterParts = [];
            foreach ($filter as $key => $value) {
                if ($key == 'Username') {
                    $filterParts[] = "u.Username LIKE '%$value%'";
                    continue;
                }
                $filterParts[] = "pt.$key LIKE '%$value%'";
            }
            $whereSql = 'WHERE ' . implode(' AND ', $filterParts) . ' AND pt.Type = '.$type;
        }
        $buildSql = "
            SELECT pt.*, u.Username as Username
            FROM $this->tableName pt
            LEFT JOIN users u ON pt.UserId = u.Id
            $whereSql
            ORDER BY $orderBy
            LIMIT $offset, $pageSize

        ";
        $data = $this->context->fetch($buildSql);
        $paymentTransactions = [];
        foreach ($data as $item) {
            $paymentTransaction = new PaymentTransaction($item);
            $paymentTransaction->Price = $paymentTransaction->Price > 0 ? Helper::formatCurrencyVND($paymentTransaction->Price) : 0;
            $paymentTransaction->StatusString = $this->RenderStatus($paymentTransaction->Status);
            $paymentTransaction->CreatedAt = date('d/m/Y H:i:s', strtotime($paymentTransaction->CreatedAt));

            array_push($paymentTransactions, $paymentTransaction);
        }
        return $paymentTransactions;
    }
    /**
     *
     * @param mixed $pageIndex
     * @param mixed $pageSize
     * @param mixed $userId
     * @param mixed $filter
     */
    public function GetByUserId($pageIndex, $pageSize, $userId, $filter = [],$type = 0)
    {
        $offset = ($pageIndex - 1) * $pageSize;
        $type = $type ?? 0;
        $whereSql = $type ? ' AND Type = '.$type : '';
        if (!empty($filter)) {
            $filterParts = [];
            foreach ($filter as $key => $value) {
                $filterParts[] = "$key LIKE '%$value%'";
            }
            $whereSql = ' AND ' . implode(' AND ', $filterParts). " AND Type = $type ";
        }
        $buildSql = "
            SELECT pt.*, u.Username as Username
            FROM $this->tableName pt
            LEFT JOIN users u ON pt.UserId = u.Id
            WHERE pt.UserId = $userId
            $whereSql
            ORDER BY Id DESC
            LIMIT $offset, $pageSize
        ";
        $data = $this->context->fetch($buildSql);
        $paymentTransactions = [];
        foreach ($data as $item) {
            $paymentTransaction = new PaymentTransaction($item);
            $paymentTransaction->Price = Helper::formatCurrencyVND($paymentTransaction->Price);
            $paymentTransaction->Status = $this->RenderStatus($paymentTransaction->Status);
            $paymentTransaction->CreatedAt = date('d/m/Y H:i:s', strtotime($paymentTransaction->CreatedAt));
            array_push($paymentTransactions, $paymentTransaction);
        }
        return $paymentTransactions;
    }

    /**
     *
     * @param mixed $status
     */
    public function GetByStatus($status)
    {
        $buildSql = "
            SELECT pt.*, u.Username as Username
            FROM $this->tableName pt
            LEFT JOIN users u ON pt.UserId = u.Id
            WHERE pt.Status = $status AND pt.Type = 0
            ORDER BY CreatedAt ASC
        ";
        $data = $this->context->fetch($buildSql);
        $paymentTransactions = [];
        foreach ($data as $item) {
            $paymentTransaction = new PaymentTransaction($item);
            array_push($paymentTransactions, $paymentTransaction);
        }
        return $paymentTransactions;
    }

    public function AddOrUpdateManyTransaction($items, $tableName, $primaryKey = "Id")
    {
        if (empty($items)) {
            return [
                'Add' => 0,
                'Update' => 0,
                'Total' => 0,
                'Result' => false
            ];
        }
        $sql = '';
        $numberAdd = 0;
        $numberUpdate = 0;
        foreach ($items as $item) {
            $item['CreatedAt'] = $item['CreatedAt'] ?? date('Y-m-d H:i:s');
            $item['UpdatedAt'] = $item['UpdatedAt'] ?? date('Y-m-d H:i:s');
            $item['UpdatedBy'] = $item['UpdatedBy'] ?? 'Admin';

            $item['CreatedBy'] = $item['CreatedBy'] ?? 'Admin';
            $item['IsActive'] = $item['IsActive'] ?? 1;
            // check if item exist
            $value = $item[$primaryKey];
            $exist = $this->context->fetch_one(SqlCommon::BuildQuery($tableName, "$primaryKey = '$value'", null, null, null));
            if ($exist) {
                continue;

            }
            $numberAdd++;
            $sql .= SqlCommon::Insert($item, $tableName) . ';';
        }
        if (empty($sql)) {
            return [
                'Add' => 0,
                'Update' => 0,
                'Total' => 0,
                'Result' => true
            ];
        }
        $res = $this->context->query($sql);
        return [
            'Add' => $numberAdd,
            'Update' => $numberUpdate,
            'Total' => $numberAdd + $numberUpdate,
            'Result' => $res
        ];
    }


    private function RenderStatus($status)
    {
        switch ($status) {
            case 0:
                return '<span class="text-info">Chờ xác nhận</span>';
            case 1:
                return '<span class="text-success">Đã xác nhận</span>';
            case 2:
                return '<span class="text-danger">Đã hủy</span>';
            default:
                return '<span class="text-dark">Không xác định</span>';
        }
    }

    /**
     *
     * @param mixed $filter
     */
    public function ExportExcel($filter = [])
    {
        $paymentTransactions = $this->GetWithPaginate(1, 100000, $filter, 'Price DESC');
        $data = [];
        foreach ($paymentTransactions as $item) {
            $data[] = [
                'Id' => $item->Id,
                'Username' => $item->Username,
                'Code' => $item->Code,
                'Price' => $item->Money,
                'Note' => $item->Note,
                'Status' => $item->Status,
                'CreatedAt' => $item->CreatedAt,
                'UpdatedAt' => $item->UpdatedAt,
            ];
        }

        $templatePath = $this->rootPath . '/Resources/Templates/DanhSachLichSuCongTien.xlsx';
        $outputPath = $this->rootPath . '/Resources/Exports/DanhSachLichSuCongTien_' . time() . '.xlsx';
      
        $file = ExcelHelper::exportToExcelWithTemplate($data, $templatePath, $outputPath);
        return $file;
    }
    /**
     */
    public function ApproveAll() {
        $sql = "UPDATE $this->tableName SET Status = 1 WHERE Status = 0";
        $res = $this->context->query($sql);
        return $res;
    }
}