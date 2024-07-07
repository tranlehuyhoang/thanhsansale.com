<?php

namespace App\Controllers;

use App\Controllers\Base\AdminController;
use App\Services\Common\Pagination;
use App\Services\Identities\UserServices\UserService;

class DashboardController extends AdminController
{
    private UserService $userService;
    public function __construct()
    {
        $this->userService = new UserService();
        parent::__construct();
    }
    public function Index($pageIndex = 1)
    {

        $totalRecords = $this->userService->GetTotalRecords($this->userService->tableName);
        $pagConfig = [
            'baseURL' => ADMIN_PATH. '/dashboard/page',
            'totalRows' => $totalRecords,
            'perPage' => $this->pageConfig['PageSize'],
        ];
        $pagination = new Pagination($pagConfig);

        // Retrieve all users from the database
        $users = $this->userService->GetWithPaginate($pageIndex, $this->pageConfig['PageSize'],[],'Money DESC');

        $this->view(
            'Dashboard.Index',
            [
                'title' => 'Thống kê',
                'users' => $users,
                'pagination' => $pagination->createLinks()
            ]
        );
    }
}
