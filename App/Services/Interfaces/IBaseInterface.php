<?php 
namespace App\Services\Interfaces;

interface IBaseInterface
{
    public function GetAll();
    public function GetWithPaginate($pageIndex, $pageSize);
    public function GetTotalRecords($tableName, $condition = null);
    public function GetById($id);
    public function Add($data, $tableName);
    public function Update($data, $id, $tableName);
    public function Delete($id, $tableName);
}