<?php
namespace App\Services;

use App\Core\Database;
use App\Services\Common\Helper;
use App\Services\Common\Request;
use App\Services\Common\SqlCommon;

class BaseService
{
    protected $context;
    protected $rootPath = '';
    public function __construct()
    {
        // check is local or server in localhost
        if (strpos(Request::host(), 'localhost') !== false) {
            $this->rootPath = Request::root();
        } else {
            $this->rootPath = Request::root() . "/public";
        }
        $this->context = Database::getInstance();
    }
    /**
     *
     * @param mixed $data
     * @return mixed
     */
    public function Add($data, $tableName)
    {

        // add default value to $data
        $data['CreatedAt'] = date('Y-m-d H:i:s');
        $data['CreatedBy'] = $data['CreatedBy'] ?? 'Admin';
        $data['IsActive'] = $data['IsActive'] ?? 1;


        $sql = SqlCommon::Insert($data, $tableName);
        return $this->context->query($sql);
    }

    /**
     *
     * @param mixed $items
     * @return mixed
     */
    public function AddMany($items, $tableName)
    {
        $sql = '';
        foreach ($items as $item) {
            $item['CreatedAt'] = $item['CreatedAt'] ?? date('Y-m-d H:i:s');
            $item['UpdatedAt'] = $item['UpdatedAt'] ?? date('Y-m-d H:i:s');

            $item['CreatedBy'] = $item['CreatedBy'] ?? 'Admin';
            $item['IsActive'] = $item['IsActive'] ?? 1;
            $sql .= SqlCommon::Insert($item, $tableName) . ';';
        }
        // return $this->context->query($sql);
        return $this->context->closeCursor($sql);
    }

    /**
     *
     * @param mixed $items
     * @return mixed
     */
    public function UpdateMany($items, $tableName)
    {
        $sql = '';
        foreach ($items as $item) {
            $item['UpdatedAt'] = $item['UpdatedAt'] ?? date('Y-m-d H:i:s');
            $item['UpdatedBy'] = $item['UpdatedBy'] ?? 'Admin';
            $sql .= SqlCommon::Update($tableName, $item, $item['Id']) . ';';
        }
        return $this->context->closeCursor($sql);
    }

    /**
     *
     * @param mixed $items
     * @param mixed $tableName
     * @param mixed $primaryKey = "Id"
     * @return mixed
     */
    public function AddOrUpdateMany($items, $tableName, $primaryKey = "Id")
    {
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
            $value =  $item[$primaryKey];
            $exist = $this->context->fetch(SqlCommon::BuildQuery($tableName, "$primaryKey = '$value'", null, null, null));
            if ($exist) {
                // for Lazada
                $numberUpdate++;
                if(count($exist) > 1){
                    foreach ($exist as $ex) {
                       
                        $sql .= SqlCommon::Update($tableName, $item, $ex['Id']) . ';';
                    }
                }
                // for Shopee
                else if(count($exist) == 1){
                    $data = $exist[0];
                   
                    $sql .= SqlCommon::Update($tableName, $item, $data['Id']) . ';';
                }
            } 
            else {
                $numberAdd++;
                $sql .= SqlCommon::Insert($item, $tableName) . ';';
            }

        }
        if($sql == ''){
            return [
                'Add' => 0,
                'Update' => 0,
                'Total' => count($items),
                'Result' => null
            ];
        }
        $res = $this->context->query($sql);
        return [
            'Add' => $numberAdd,
            'Update' => $numberUpdate,
            'Total' => count($items),
            'Result' => $res
        ];
    }

    /**
     *
     * @param mixed $codes
     * @return mixed
     * Select IN
     */
    public function GetByCodes($tableName, $codes,$where = null)
    {
        // ex: $codes = ['24040995UUKJ3J','2404062S59YKEQ']
        // SELECT * FROM payment_transactions WHERE Code IN ('24040995UUKJ3J','2404062S59YKEQ') ORDER BY CreatedAt DESC
        $codeString = "'" . join("','", $codes) . "'";
        if($where != null){
            $buildSql = SqlCommon::BuildQuery($tableName, "Code IN ($codeString) AND $where", null, null, null);
        }
        else{
            $buildSql = SqlCommon::BuildQuery($tableName, "Code IN ($codeString)", null, null, null);
        }
        $data = $this->context->fetch($buildSql);
        $items = [];
        foreach ($data as $item) {
            array_push($items, (object)$item);
        }
        return $items;
    }

    /**
     *
     * @param mixed $ids
     * @return mixed
     * Select IN
     */
    public function GetByIds($tableName, $ids)
    {
        // ex: $ids = [1,2,3]
        // SELECT * FROM payment_transactions WHERE Id IN (1,2,3) ORDER BY CreatedAt DESC
        $idString = "'" . join("','", $ids) . "'"; // "1','2','3"
        $sql = SqlCommon::BuildQuery($tableName, "Id IN ($idString)", null, null, null);
        $data = $this->context->fetch($sql);
        $items = [];
        foreach ($data as $item) {
            array_push($items, (object)$item);
        }
        return $items;
    }

    /**
     *
     * @param mixed $data
     * @param mixed $id
     * @return mixed
     */
    public function Update($data, $id, $tableName)
    {
        // add default value to $data
        $data['UpdatedAt'] = date('Y-m-d H:i:s');
        $data['UpdatedBy'] = $data['UpdatedBy'] ?? 'Admin';
        $sql = SqlCommon::Update($tableName, $data, $id);
        return $this->context->query($sql);
    }
    /**
     *
     * @param mixed $id
     * @return mixed
     */
    public function Delete($id, $tableName)
    {
        $sql = SqlCommon::DELETE($tableName, $id);
        return $this->context->query($sql);
    }
    /**
     * @param mixed $tableName
     * @param mixed $condition
     * @return mixed
     */
    public function GetTotalRecords($tableName, $condition = null)
    {
        $sql = SqlCommon::Count($tableName, $condition);
        $data = $this->context->fetch_one($sql);
        return $data['Total'];
    }

    public function SQLQuery($sql)
    {
        return $this->context->query($sql);
    }

    public function SQLFetch($sql)
    {
        return $this->context->fetch($sql);
    }
    public function SQLFetchOne($sql)
    {
        return $this->context->fetch_one($sql);
    }
}