<?php

namespace App\Controllers;

use App\Controllers\Base\AdminController;
use App\Services\BankServices\BankService;
use App\Services\Common\AlertSession;
use App\Services\Common\Enums\EHttpMethod;
use App\Services\Common\Helper;
use App\Services\Common\Pagination;
use App\Services\Common\Request;
use App\Services\Common\Response;
use App\Services\Identities\UserServices\UserService;
use App\Services\PaymentTransactionServices\PaymentTransactionService;

class UserController extends AdminController
{
    private UserService $userService;
    private PaymentTransactionService $paymentTransactionService;
    private BankService $bankService;
    public function __construct()
    {
        $this->userService = new UserService();
        $this->paymentTransactionService = new PaymentTransactionService();
        $this->bankService = new BankService();
        parent::__construct();
    }
    public function Index($pageIndex = 1)
    {
        $totalRecords = $this->userService->GetTotalRecords($this->userService->tableName);
        $pagConfig = [
            'baseURL' => ADMIN_PATH . '/user/page',
            'totalRows' => $totalRecords,
            'perPage' => $this->pageConfig['PageSize'],
        ];
        $pagination = new Pagination($pagConfig);

        // Retrieve all users from the database
        $users = $this->userService->GetWithPaginate($pageIndex, $this->pageConfig['PageSize']);
        // Load the view and pass data
        $this->view('User.Index', [
            'users' => $users,
            'pagination' => $pagination->createLinks(),
            'showing' => $pagination->createdShowing(),
            'title' => 'Danh Sách Tài Khoản',
        ]);
    }
    public function AccountInActive($pageIndex = 1)
    {
        $totalRecords = $this->userService->GetTotalRecords($this->userService->tableName, "IsActive = 0");
        $pagConfig = [
            'baseURL' => ADMIN_PATH . '/user/not-active/page',
            'totalRows' => $totalRecords,
            'perPage' => $this->pageConfig['PageSize'],
        ];
        $pagination = new Pagination($pagConfig);
        $filter = [
            'IsActive' => false,
        ];
        // Retrieve all users from the database
        $users = $this->userService->GetWithPaginate($pageIndex, $this->pageConfig['PageSize'], $filter);
        // Load the view and pass data
        $this->view('User.UserNotActive', [
            'users' => $users,
            'pagination' => $pagination->createLinks(),
            'showing' => $pagination->createdShowing(),
            'title' => 'Danh Sách Tài Khoản Chưa Kích Hoạt',
        ]);
    }

    // remove all users not active
    public function RemoveAllNotActive()
    {
        if (Request::method(EHttpMethod::DELETE)) {
            $sql = "DELETE FROM users WHERE IsActive = 0";
            $result = $this->userService->SQLQuery($sql);
            if ($result) {
                Response::success([], 'Xóa Tài Khoản Chưa Kích Hoạt Thành Công', 200);
                return;
            }
        }
    }


    // API POST: /admin/user/search
    public function Search()
    {
        if (Request::method(EHttpMethod::POST)) {
            $filter = [
                'Username' => Request::post('Username'),
                'Email' => Request::post('Email'),
                'Role' => Request::post('Role'),
                'Money' => Request::post('Money'),
            ];
            $users = $this->userService->GetWithPaginate(1, $this->pageConfig['PageSize'], $filter);
            Response::success($users, 'Lấy Dữ Liệu Thành Công', 200);
            return;
        }
        Response::methodNotAllowed();
    }

    public function Detail($id)
    {
        // Retrieve all users from the database
        $user = $this->userService->GetById($id);
        // Load the view and pass data
        $this->view('User.Detail', [
            'id' => $id,
            'user' => $user,
            'title' => 'Chi Tiết Tài Khoản',
        ]);
    }

    public function Create()
    {
        // Handle form submission to create a new user
        if (Request::method(EHttpMethod::POST)) {
            $user = [
                'Username' => Request::post('Username'),
                'Email' => Request::post('Email'),
                'FullName' => Request::post('FullName'),
                'Password' => Request::post('Password'),
                'Role' => (int) Request::post('Role'),
            ];
            $rules = [
                'Username' => 'required|min:2|max:100|unique:users,Username', // 'users' là tên bảng, 'username' là tên cột
                'Email' => 'required|unique:users,Email', // 'users' là tên bảng, 'email' là tên cột
                'Password' => 'required|min:8|max:100|password_strength',
            ];
            $messages = [
                'Username.required' => 'Bạn chưa nhập Tên tài khoản',
                'Username.min' => 'Tên tài khoản có ít nhất từ 2 đến 100 ký tự',
                'Username.max' => 'Tên tài khoản có ít nhất từ 2 đến 100 ký tự',
                'Username.unique' => 'Tên tài khoản đã tồn tại',
                'Email.required' => 'Bạn chưa nhập email',
                'Email.unique' => 'Email đã tồn tại',
                'Password.required' => 'Bạn chưa nhập mật khẩu',
                'Password.min' => 'Mật khẩu phải có ít nhất từ 6 đến 100 ký tự',
                'Password.max' => 'Mật khẩu phải nhỏ hơn 100 ký tự',
                'Password.password_strength' => 'Mật khẩu phải chứa ít nhất một ký tự đặc biệt, một ký tự viết hoa và một số'
            ];
            if ($this->validator->validate($user, $rules, $messages)) {
                $user['Password'] = Helper::HashBcrypt($user['Password']);
                $result = $this->userService->Add($user, "users");
                if ($result) {
                    AlertSession::Success('Thêm Mới Tài Khoản Thành Công');
                    $this->redirect(ADMIN_PATH . '/user');
                    return;
                }
                AlertSession::Error('Thêm Mới Tài Khoản Thất Bại');
                return;
            }
            $errors = $this->validator->getFormattedErrors();
            foreach ($errors as $field => $error) {
                AlertSession::Error($error);
            }
        }
        $this->view('User.Create', ['title' => 'Thêm Mới Tài Khoản']);
    }

    public function Edit($id)
    {
        // Retrieve the user from the database by ID
        $user = $this->userService->GetById($id);
        $banks = $this->bankService->GetAll();

        if (!$user) {
            // Handle user not found
            $this->view('Error.404', ['title' => 'User Not Found']);
            return;
        }

        // Handle form submission to update the user
        if (Request::method(EHttpMethod::POST)) {
            $user = [
                'Username' => Request::post('Username'),
                'Email' => Request::post('Email'),
                'FullName' => Request::post('FullName'),
                'Password' => Request::post('Password'),
                'Role' => Request::post('Role'),
                'NameBank' => Request::post('NameBank'),
                'NumberBank' => Request::post('NumberBank'),
            ];
            $rules = [
                'Username' => 'required|min:2|max:100',
                'Email' => 'required'
            ];
            $messages = [
                'Username.required' => 'Bạn chưa nhập Tên tài khoản',
                'Username.min' => 'Tên tài khoản có ít nhất từ 2 đến 100 ký tự',
                'Username.max' => 'Tên tài khoản có ít nhất từ 2 đến 100 ký tự',
                'Email.required' => 'Bạn chưa nhập email'
            ];
            if ($this->validator->validate($user, $rules, $messages)) {

                // check password is empty
                if (empty($user['Password'])) {
                    unset($user['Password']);
                } else {
                    $user['Password'] = Helper::HashBcrypt($user['Password']);
                }
                $result = $this->userService->Update($user, $id, $this->userService->tableName);
                if ($result) {
                    AlertSession::Success('Cập Nhật Tài Khoản Thành Công');
                    $this->redirect(ADMIN_PATH . '/user/edit/' . $id);
                    return;
                }
                AlertSession::Error('Cập Nhật Tài Khoản Thất Bại');
                return;
            }
            $errors = $this->validator->getFormattedErrors();
            foreach ($errors as $field => $error) {
                AlertSession::Error($error);
            }
        }
        $this->view('User.Edit', [
            'user' => $user,
            'banks' => $banks, // pass banks to the view
            'title' => 'Cập Nhật Tài Khoản'
        ]);
    }

    // API
    public function Delete($id)
    {
        if (Request::method(EHttpMethod::DELETE)) {
            $user = $this->userService->GetById($id);
            if (!$user) {
                Response::notFound([], 'Tài Khoản Không Tồn Tại', 404);
                return;
            }
            $result = $this->userService->Delete($id, $this->userService->tableName);
            if (!$result) {
                Response::badRequest([], 'Xóa Tài Khoản Thất Bại', 400);
                return;
            }
            Response::success([], 'Xóa Tài Khoản Thành Công', 200);
            return;
        }
        Response::methodNotAllowed();
    }

    // api: /admin/user/export
    public function ExportExcel()
    {
        if (Request::method(EHttpMethod::POST)) {
            $filters = [
                'Username' => Request::get('Username'),
                'Email' => Request::get('Email'),
                'Role' => Request::get('Role'),
            ];
            $result = $this->userService->ExportExcel($filters);
            if (!empty($result)) {

                // download file from url using php
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="users.xlsx"');
                header('Cache-Control: max-age=0');
                $file = file_get_contents($result);
                echo $file;
                return;
            }
            Response::badRequest([], 'Xuất Excel Thất Bại', 400);
            return;
        }
    }

    //#region ExportExcel
    // api: /admin/user/export-vpbank
    public function ExportVPBankExcel()
    {
        if (Request::method(EHttpMethod::POST)) {
            $filters = [
                'Username' => Request::get('Username'),
                'Email' => Request::get('Email'),
                'Role' => Request::get('Role'),
            ];

            $result = $this->userService->ExportVPBankExcel($filters);
            if (empty($result)) {
                // $result is empty, so there's no file to get contents from
                echo "No file to display";
                return;
            }
            // download file from url using php
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="users.xlsx"');
            header('Cache-Control: max-age=0');
            $file = file_get_contents($result);
            echo $file;
        }
    }

    // api: /admin/user/export-bidv
    public function ExportBIDVExcel()
    {
        if (Request::method(EHttpMethod::POST)) {
            $filters = [
                'Username' => Request::get('Username'),
                'Email' => Request::get('Email'),
                'Role' => Request::get('Role'),
            ];
            $result = $this->userService->ExportBIDVExcel($filters);
            if (empty($result)) {
                // $result is empty, so there's no file to get contents from
                echo "No file to display";
                return;
            }
            // download file from url using php
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="users.xlsx"');
            header('Cache-Control: max-age=0');
            $file = file_get_contents($result);
            echo $file;
        }
    }

    // api: /admin/user/export-tcb
    public function ExportTCBExcel()
    {
        if (Request::method(EHttpMethod::POST)) {
            $filters = [
                'Username' => Request::get('Username'),
                'Email' => Request::get('Email'),
                'Role' => Request::get('Role'),
            ];
            $result = $this->userService->ExportTCBExcel($filters);
            if (empty($result)) {
                // $result is empty, so there's no file to get contents from
                echo "No file to display";
                return;
            }
            // download file from url using php
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="users.xlsx"');
            header('Cache-Control: max-age=0');
            $file = file_get_contents($result);
            echo $file;
        }
    }

    //#endregion ExportExcel

    // api /admin/user/reset-money
    public function ResetMoney()
    {
        if (Request::method(EHttpMethod::POST)) {
            $userId = Request::post('userId'); // Null to reset all users
            $users = [];
            $user = null;
            if ($userId) {
                $user = $this->userService->GetById($userId);
                if (!$user) {
                    Response::notFound([], 'Tài Khoản Không Tồn Tại', 404);
                    return;
                }
            } else {
                $users = $this->userService->GetAll();
            }
            // filter users has money > 10000
            //WHERE Money >= 10000 AND NameBank IS NOT NULL AND NumberBank IS NOT NULL AND FullName IS NOT NULL
            $users = array_filter($users, function ($res) {
                // check type is int 
                if(!is_int($res->NameBank)){
                    $res->NameBank = null;
                }
                return $res->Price >= 10000 && $res->NameBank != null && $res->NumberBank != null && $res->FullName != null;
            });

            $result = $this->userService->ResetAllMoney($userId);
            if ($result) {
                Response::success([], 'Reset Money Thành Công', 200);
                if (count($users) > 0 || $user)
                    $this->AddPaymentTransaction($users, $user);
                return;
            }
            Response::badRequest([], 'Reset Money Thất Bại', 400);
            return;
        }
        Response::methodNotAllowed();
    }

    private function AddPaymentTransaction($users, $user)
    {
        if ($user) {
            $paymentTransaction = [
                'UserId' => $user->Id,
                'Code' => Helper::generateRandomString(10, 'RT'),
                'Type' => 1,
                'Price' => $user->Price,
                'Status' => 0,
                'Note' => 'Rút tiền từ tài khoản' . $user->Username,
            ];
            $result = $this->paymentTransactionService->Add($paymentTransaction, 'payment_transactions');
            if ($result) {
                return true;
            }
            return false;
        }
        $paymentTransactions = [];
        foreach ($users as $user) {
            $paymentTransaction = [
                'UserId' => $user->Id,
                'Code' => Helper::generateRandomString(10, 'RT'),
                'Type' => 1,
                'Price' => $user->Price,
                'Status' => 0,
                'Note' => 'Rút tiền từ tài khoản: ' . $user->Username,
            ];
            $paymentTransactions[] = $paymentTransaction;
        }
        $result = $this->paymentTransactionService->AddMany($paymentTransactions, 'payment_transactions');
        if ($result) {
            return true;
        }
        return false;
    }

    // api /admin/user/add-money
    public function AddMoney()
    {
        if (Request::method(EHttpMethod::POST)) {
            $userId = Request::post('userId');
            $money = Request::post('money');
            $result = $this->userService->AddMoneyByUser($userId, $money);
            if ($result) {
                Response::success([], 'Thêm Tiền Thành Công', 200);
                return;
            }
            Response::badRequest([], 'Thêm Tiền Thất Bại', 400);
            return;
        }
        Response::methodNotAllowed();
    }
}
