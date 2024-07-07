<?php

namespace App\Controllers;

use App\Controllers\Base\AdminController;
use App\Services\CategoryServices\CategoryService;
use App\Services\Common\AlertSession;
use App\Services\Common\Enums\EHttpMethod;
use App\Services\Common\Helper;
use App\Services\Common\Pagination;
use App\Services\Common\Request;
use App\Services\Common\Response;
use App\Services\Identities\UserServices\UserService;
use App\Services\NotificationServices\NotificationService;

class NotificationController extends AdminController
{
    public NotificationService $notificationService;
    public UserService $userService;
    public $notifications;
    public function __construct()
    {
        $this->notificationService = new NotificationService();
        $this->userService = new UserService();
        parent::__construct();
    }

    // GET: /admin/notification/page/{pageIndex}
    public function Index($pageIndex = 1)
    {
        $totalRecords = $this->notificationService->GetTotalRecords($this->notificationService->tableName);
        $pagConfig = [
            'baseURL' => ADMIN_PATH.'/notification/page',
            'totalRows' => $totalRecords,
            'perPage' => $this->pageConfig['PageSize'],
        ];
        $pagination = new Pagination($pagConfig);

        // Retrieve all users from the database
        $notifications = $this->notificationService->GetWithPaginate($pageIndex, $this->pageConfig['PageSize']);
        // Load the view and pass data
        $this->view('Notification.Index', [
            'pagination' => $pagination->createLinks(),
            'title' => 'Danh Sách Thông Báo',
            'notifications' => $notifications ,
        ]);
    }
    // GET: /admin/category/create
    public function Create()
    {
        // Handle form submission to create a new user
        if (Request::method(EHttpMethod::POST)) {
            $notification = [
                'Title' => Request::post('Title'),
                'Content' => $_POST['Content'] ?? '',
                'Type' => Request::post('Type') ?? 0,
                'UserId' => Request::post('UserId') == "0" ? NULL : Request::post('UserId'),
                'IsRead' => Request::post('IsRead') ?? 0,
            ];
            $rules = [
                'Title' => 'required|min:2|max:250',
                'Content' => 'required',
            ];
            $messages = [
                'Title.required' => 'Bạn chưa nhập Tên shop',
                'Title.min' => 'Tên Shop có ít nhất từ 2 đến 250 ký tự',
                "Title.max" => "Tên Shop có ít nhất từ 2 đến 250 ký tự",
                'Content.required' => 'Bạn chưa nhập Nội dung',
            ];
            if ($this->validator->validate($notification, $rules, $messages)) {
                $result = $this->notificationService->Add($notification, $this->notificationService->tableName);
                if ($result) {
                    AlertSession::Success('Thêm Mới Thông Báo Thành Công');
                    $this->redirect(ADMIN_PATH.'/notification');
                    return;
                }
                AlertSession::Error('Thêm Mới Thông Báo Thất Bại');
                return;
            }
            $errors = $this->validator->getFormattedErrors();
            foreach ($errors as $field => $error) {
                AlertSession::Error($error);
            }
        }
        // Load the view
        $users = $this->userService->GetAll();
        $this->view('Notification.Create', [
            'title' => 'Thêm Mới Thông Báo',
            'users' => $users,
        ]);
    }

    // GET: /admin/notification/edit/{id}
    public function Edit($id)
    {
        $notification = $this->notificationService->GetById($id);
        if ($notification == null) {
            AlertSession::error('Không tìm thấy thông báo');
            $this->redirect(ADMIN_PATH.'/notification');
            return;
        }
        // Handle form submission to create a new user
        if (Request::method(EHttpMethod::POST)) {
            $notification = [
                'Title' => Request::post('Title'),
                'Content' => $_POST['Content'] ?? '',
                'Type' => Request::post('Type') ?? 0,
                'UserId' => Request::post('UserId') == "0" ? 0 : Request::post('UserId'),
                'IsRead' => Request::post('IsRead') ?? 0,
            ];
            $rules = [
                'Title' => 'required|min:2|max:250',
                'Content' => 'required',
            ];
            $messages = [
                'Title.required' => 'Bạn chưa nhập Tên shop',
                'Title.min' => 'Tên Shop có ít nhất từ 2 đến 250 ký tự',
                "Title.max" => "Tên Shop có ít nhất từ 2 đến 250 ký tự",
                'Content.required' => 'Bạn chưa nhập Nội dung',
            ];
            if ($this->validator->validate($notification, $rules, $messages)) {
                $result = $this->notificationService->Update($notification, $id, $this->notificationService->tableName);
                if ($result) {
                    AlertSession::Success('Cập Nhật Thông Báo Thành Công');
                    $this->redirect(ADMIN_PATH.'/notification');
                    return;
                }
                AlertSession::Error('Cập Nhật Thông Báo Thất Bại');
                return;
            }
            $errors = $this->validator->getFormattedErrors();
            foreach ($errors as $field => $error) {
                AlertSession::Error($error);
            }
        }
        // Load the view
        $users = $this->userService->GetAll();

        $this->view('Notification.Edit', [
            'title' => 'Cập Nhật Thông Báo',
            'notification' => $notification,
            'users' => $users,
        ]);
    }

    // DELETE: /admin/notification/delete/{id}
    public function Delete($id)
    {
        if (Request::method(EHttpMethod::DELETE)) {
            $category = $this->notificationService->GetById($id);
            if (!$category) {
                Response::notFound([], 'Thông Báo Không Tồn Tại', 404);
                return;
            }
            $result = $this->notificationService->Delete($id, $this->notificationService->tableName);
            if (!$result) {
                Response::badRequest([], 'Xóa Thông Báo Thất Bại', 400);
                return;
            }
            Response::success(
                [],
                'Xóa Thông Báo Thành Công',
                200
            );
            return;
        }
        Response::methodNotAllowed();
    }
}