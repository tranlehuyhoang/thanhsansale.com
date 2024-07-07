<?php

namespace App\Services\CategoryServices;

use App\Models\Category;
use App\Services\BaseService;
use App\Services\Common\Helper;
use App\Services\Common\SqlCommon;

class CategoryService extends BaseService implements ICategoryService
{
    public $tableName = 'categories';
    /**
     */
    public function GetAll()
    {
        $buildSql = SqlCommon::BuildQuery($this->tableName, NULL, NULL, NULL, NULL);
        $data = $this->context->fetch($buildSql);
        $orders = [];
        foreach ($data as $item) {
            $setting = new Category($item);
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
        $buildSql = SqlCommon::BuildQuery($this->tableName,"Id =$id", Null, NULL, NULL);
        $data = $this->context->fetch_one($buildSql);
        if (!$data) {
            return NULL;
        }
        $order = new Category($data);
        return $order;
    }

    /**
     *
     * @param mixed $pageIndex
     * @param mixed $pageSize
     */
    public function GetWithPaginate($pageIndex, $pageSize)
    {
        $offset = ($pageIndex - 1) * $pageSize;
        $buildSql = SqlCommon::BuildQuery($this->tableName, NULL, NULL, $offset, $pageSize);
        $data = $this->context->fetch($buildSql);
        $orders = [];
        foreach ($data as $item) {
            $setting = new Category($item);
            array_push($orders, $setting);
        }
        return $orders;
    }
    /**
     *
     * @param mixed $name
     */
    public function GetByName($name) {
        $slug = Helper::Slugify($name);
        $buildSql = SqlCommon::BuildQuery($this->tableName,"Slug = '$slug'", Null, NULL, NULL);
        $data = $this->context->fetch_one($buildSql);
        if (!$data) {
            return NULL;
        }
        $order = new Category($data);
        return $order;
    }
}
