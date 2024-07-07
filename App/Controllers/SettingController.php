<?php

namespace App\Controllers;

use App\Controllers\Base\AdminController;
use App\Services\Common\AlertSession;
use App\Services\Common\Enums\EHttpMethod;
use App\Services\Common\Pagination;
use App\Services\Common\Request;
use App\Services\Common\Response;
use App\Services\SettingServices\SettingService;

class SettingController extends AdminController
{
    //public $settingService;
    public function __construct()
    {
        //$this->settingService = new SettingService();
        parent::__construct();
    }
    public function Index($pageIndex = 1)
    {
        $totalRecords   = $this->settingService->GetTotalRecords($this->settingService->tableName);
        $pagConfig = [
            'baseURL' => ADMIN_PATH.'/setting/page',
            'totalRows' => $totalRecords,
            'perPage' => $this->pageConfig['PageSize'],
        ];
        $pagination = new Pagination($pagConfig);

        // Retrieve all users from the database
        $settings = $this->settingService->GetWithPaginate($pageIndex, $this->pageConfig['PageSize']);
        // Load the view and pass data
        $this->view('Setting.Index', [
            'settings' => $settings,
            'pagination' => $pagination->createLinks(),
            'title' => 'Danh Sách Cài Đặt',
        ]);
    }
    public function Create()
    {
        // Handle form submission to create a new user
        if (Request::method(EHttpMethod::POST)) {
            $setting = [
                'Logo' => Request::post('Logo'),
                'Favicon' => Request::post('Favicon'),
                'SiteName' => Request::post('SiteName'),
                'Copyright' => Request::post('Copyright'),
                'Description' => Request::post('Description'),
                'Keyword'  => Request::post('Keyword'),
                'Address' => Request::post('Address'),
                'IsActive' => Request::post('IsActive'),
                'Type' => Request::post('Type'),
                'DatePayment' => Request::post('DatePayment'),
                'ShowTop' => Request::post('ShowTop') ?? 0,
                'DescriptionTop'=> $_POST['DescriptionTop'] ?? '',
            ];
            $result = $this->settingService->Add($setting, $this ->settingService->tableName);
            if ($result) {
                AlertSession::Success('Thêm Mới Cài Đặt Thành Công');
                $this->redirect(ADMIN_PATH.'/setting');
                return;
            }
            AlertSession::Error('Thêm Cài Đặt Thất Bại');
            return;
        }
        $this->view('Setting.Create', ['title' => 'Thêm Mới Cài Đặt']);
    }
    public function Edit($id)
    {
        // Retrieve the user from the database by ID
        $setting = $this->settingService->GetById($id);

        if (!$setting) {
            // Handle user not found
            $this->view('Error.404', ['title' => 'Setting Not Found']);
            return;
        }

        // Handle form submission to update the user
        if (Request::method(EHttpMethod::POST)) {
            $setting = [
                'Logo' => Request::post('Logo'),
                'Favicon' => Request::post('Favicon'),
                'SiteName' => Request::post('SiteName'),
                'Copyright' => Request::post('Copyright'),
                'Description' => Request::post('Description'),
                'Keyword'  => Request::post('Keyword'),
                'Address' => Request::post('Address'),
                'IsActive' => Request::post('IsActive'),
                'Type' => Request::post('Type'),
                'DatePayment' => Request::post('DatePayment'),
                'ShowTop' => Request::post('ShowTop') ?? 0,
                'DescriptionTop'=> $_POST['DescriptionTop'] ?? '',
            ];
            $result = $this->settingService->Update($setting, $id, $this->settingService->tableName);
            if ($result) {
                AlertSession::Success('Cập Nhập Cài Đặt Thành Công');
                $this->redirect(ADMIN_PATH.'/setting');
                return;
            }
            AlertSession::Error('Cập Nhập Cài Đặt Thất Bại');
            return;
        }
        $this->view('Setting.Edit', ['setting' => $setting, 'title' => 'Cập Nhập Cài Đặt']);
    }
    // API
    public function Delete($id)
    {
        if (Request::method(EHttpMethod::DELETE)) {
            $seting = $this->settingService->GetById($id);
            if (!$seting) {
                Response::notFound([], 'Cài đặt Không Tồn Tại', 404);
                return;
            }
            $result = $this->settingService->Delete($id, $this->settingService->tableName);
            if (!$result) {
                Response::badRequest([], 'Xóa Cài đặt Thất Bại', 400);
                return;
            }
            Response::success([], 'Xóa Cài đặt Thành Công', 200);
            return;
        }
        Response::methodNotAllowed();
    }
}
