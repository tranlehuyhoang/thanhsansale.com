<?php 

namespace App\Services\PageServices;

use App\Models\Page;
use App\Services\BaseService;
use App\Services\Common\SqlCommon;
class PageService extends BaseService implements IPageService
{
    public $tableName = 'pages';
    /**
     */
    public function GetAll() {
        $buildSql = SqlCommon::BuildQuery($this->tableName, NULL, NULL, NULL, NULL);
        $data = $this->context->fetch($buildSql);
        $pages = [];
        foreach ($data as $item) {
            $page = new Page($item);
            array_push($pages, $page);
        }
        return $pages;
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
        $order = new Page($data);
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
        $pages = [];
        foreach ($data as $item) {
            $page = new Page($item);
            array_push($pages, $page);
        }
        return $pages;
    }
    /**
     *
     * @param mixed $code
     */
    public function GetByCode($code) {
        $buildSql = SqlCommon::BuildQuery($this->tableName, "Code = '$code'", NULL, NULL, NULL);
        $data = $this->context->fetch_one($buildSql);
        if (!$data) {
            return NULL;
        }
        $page = new Page($data);
        return $page;
    }
    /**
     *
     * @param mixed $isMenu
     */
    public function GetAllIsMenu($isMenu = 0) {
        $buildSql = SqlCommon::BuildQuery($this->tableName, "IsMenu = $isMenu", NULL, NULL, NULL);
        $data = $this->context->fetch($buildSql);
        $pages = [];
        foreach ($data as $item) {
            $page = new Page($item);
            array_push($pages, $page);
        }
        return $pages;
    }
}