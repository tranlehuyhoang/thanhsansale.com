<?php 
namespace App\Services\BlogServices;

use App\Models\Blog;
use App\Services\BaseService;
use App\Services\Common\SqlCommon;

class BlogService extends BaseService implements IBlogService
{

    public $tableName = 'blogs';
    
    /**
     */
    public function GetAll() {
        $buildSql = SqlCommon::BuildQuery($this->tableName, NULL, NULL, NULL, NULL);
        $data = $this->context->fetch($buildSql);
        $blogs = [];
        foreach ($data as $item) {
            $blog = new Blog($item);
            array_push($blogs, $blog);
        }
        return $blogs;
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
        $blog = new Blog($data);
        return $blog;
    }
    
    /**
     *
     * @param mixed $pageIndex
     * @param mixed $pageSize
     */
    public function GetWithPaginate($pageIndex, $pageSize) {
        $offset = ($pageIndex - 1) * $pageSize;
        $buildSql = SqlCommon::BuildQuery($this->tableName, NULL, NULL, $offset, $pageSize);
        $data = $this->context->fetch($buildSql);
        $blogs = [];
        foreach ($data as $item) {
            $blog = new Blog($item);
            array_push($blogs, $blog);
        }
        return $blogs;
    }
}