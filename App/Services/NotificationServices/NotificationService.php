<?php

namespace App\Services\NotificationServices;

use App\Models\Notification;
use App\Services\BaseService;
use App\Services\Common\SqlCommon;
use App\Services\NotificationServices\INotificationService;

class NotificationService extends BaseService implements INotificationService
{
    public $tableName = 'notifications';
    /**
     */
    public function GetAll()
    {
        $buildSql = SqlCommon::BuildQuery($this->tableName, NULL, NULL, NULL, NULL);
        $data = $this->context->fetch($buildSql);
        $notifications = [];
        foreach ($data as $item) {
            $notification = new Notification($item);
            array_push($notifications, $notification);
        }
        return $notifications;
    }
    /**
     *
     * @param mixed $type
     */
    public function GetByType($type = 0) {
        $buildSql = SqlCommon::BuildQuery($this->tableName, "Type = $type", "Id", NULL, NULL);
        $data = $this->context->fetch($buildSql);
        $notifications = [];
        foreach ($data as $item) {
            $notification = new Notification($item);
            array_push($notifications, $notification);
        }
        return $notifications;
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
        $notification = new Notification($data);
        return $notification;
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
        $notifications = [];
        foreach ($data as $item) {
            $notification = new Notification($item);
            array_push($notifications, $notification);
        }
        return $notifications;
    }
}