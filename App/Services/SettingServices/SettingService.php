<?php 
namespace App\Services\SettingServices;

use App\Models\Setting;
use App\Services\BaseService;
use App\Services\Common\SqlCommon;

class SettingService extends BaseService implements ISettingService
{
    public $tableName = 'settings';
	/**
	 * @return mixed
	 */
	public function GetAll() {
        $buildSql = SqlCommon::BuildQuery($this->tableName, NULL, NULL, NULL, NULL);
        $data = $this->context->fetch($buildSql);
        $settings = [];
        foreach ($data as $item) {
            $setting = new Setting($item);
            array_push($settings, $setting);
        }
        return $settings;
	}
	
	/**
	 *
	 * @param mixed $pageIndex
	 * @param mixed $pageSize
	 * @return mixed
	 */
	public function GetWithPaginate($pageIndex, $pageSize) {
		$offset = ($pageIndex - 1) * $pageSize;
        $buildSql = SqlCommon::BuildQuery($this->tableName, NULL, NULL, $offset, $pageSize);
        $data = $this->context->fetch($buildSql);
        $settings = [];
        foreach ($data as $item) {
            $setting = new Setting($item);
            array_push($settings, $setting);
        }
        return $settings;
	}
	
	/**
	 *
	 * @param mixed $id
	 * @return mixed
	 */
	public function GetById($id) {
        $buildSql = SqlCommon::BuildQuery($this->tableName,"Id=$id" ,NULL, NULL, NULL);
        $data = $this->context->fetch_one($buildSql);
		if(!$data) {
			return NULL;
		}
        $setting = new Setting($data);
        return $setting;
	}
	/**
	 * @param mixed $id
	 * @return mixed
	 */
	public function GetTopActive() {
        $buildSql = SqlCommon::BuildQuery($this->tableName, "IsActive=1", "Id", NULL, NULL);
        $data = $this->context->fetch_one($buildSql);
		if(!$data) {
			return NULL;
		}
        $setting = new Setting($data);
        return $setting;
	}

	/**
	 * @param mixed $id
	 * @return mixed
	 */
	public function GetSetting($type)
	{
		$buildSql = SqlCommon::BuildQuery($this->tableName, "IsActive= 1 AND Type='$type'", "Id", NULL, NULL);
		$data = $this->context->fetch_one($buildSql);
		if ($data == false) {
			return null;
		}
		$setting = new Setting($data);
		return $setting;
	}
}