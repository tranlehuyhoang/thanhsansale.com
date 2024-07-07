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

class BankController extends AdminController
{
    public BankService $bankService;
    public function __construct()
    {
        $this->bankService = new BankService();
        parent::__construct();
    }

    // GET: /admin/blog/page/{pageIndex}
    public function Index($pageIndex = 1)
    {
        $totalRecords   = $this->bankService->GetTotalRecords($this->bankService->tableName);
        $pagConfig = [
            'baseURL' => ADMIN_PATH . '/bank/page',
            'totalRows' => $totalRecords,
            'perPage' => $this->pageConfig['PageSize'],
        ];
        $pagination = new Pagination($pagConfig);

        // Retrieve all users from the database
        $banks = $this->bankService->GetWithPaginate($pageIndex, $this->pageConfig['PageSize']);
        // Load the view and pass data
        $this->view('Bank.Index', [
            'banks' => $banks,
            'pagination' => $pagination->createLinks(),
            'title' => 'Danh Sách Ngân Hàng',
        ]);
    }
    // GET: /admin/blog/create
    public function Create()
    {
        // Handle form submission to create a new user
        if (Request::method(EHttpMethod::POST)) {
            $bank = [
                'Code' => Request::post('Code'),
                'Name' => Request::post('Name'),
                'NameTCB' => Request::post('NameTCB'),
                'NameVPBank' => Request::post('NameVPBank'),
                'Logo' => Request::post('Logo'),
            ];
            $rules = [
                'Code' => 'required|unique:banks,Code',
                'Name' => 'required',
                'NameTCB' => 'required',
                'NameVPBank' => 'required',
            ];
            $messages = [
                'Code.required' => 'Bạn chưa nhập tiêu đề',
                'Code.unique' => 'Mã đã tồn tại',
                'Name.required' => 'Bạn chưa nhập tên ngân hàng',
                'NameTCB.required' => 'Bạn chưa nhập tên ngân hàng TCB',
                'NameVPBank.required' => 'Bạn chưa nhập tên ngân hàng VPBank',
            ];
            if ($this->validator->validate($bank, $rules, $messages)) {
                $result = $this->bankService->Add($bank, $this->bankService->tableName);
                if ($result) {
                    AlertSession::Success('Thêm Mới Thành Công');
                    $this->redirect(ADMIN_PATH . '/bank');
                    return;
                }
                AlertSession::Error('Thêm Mới Thất Bại');
                return;
            }
            $errors = $this->validator->getFormattedErrors();
            foreach ($errors as $field => $error) {
                AlertSession::Error($error);
            }
        }
        // Load the view
        $this->view('Bank.Create', [
            'title' => 'Thêm Mới Ngân Hàng',
        ]);
    }

    // GET: /admin/blog/edit/{id}
    public function Edit($id)
    {
        $bank = $this->bankService->GetById($id);
        if (!$bank) {
            AlertSession::Error('Tin Tức Không Tồn Tại');
            $this->redirect(ADMIN_PATH . '/blog');
            return;
        }
        // Handle form submission to update an existing user
        if (Request::method(EHttpMethod::POST)) {
            $data = [
                'Name' => Request::post('Name'),
                'NameTCB' => Request::post('NameTCB'),
                'NameVPBank' => Request::post('NameVPBank'),
                'Logo' => Request::post('Logo'),
            ];
            $rules = [
                'Name' => 'required',
                'NameTCB' => 'required',
                'NameVPBank' => 'required',
            ];
            $messages = [
              
                'Name.required' => 'Bạn chưa nhập tên ngân hàng',
                'NameTCB.required' => 'Bạn chưa nhập tên ngân hàng TCB',
                'NameVPBank.required' => 'Bạn chưa nhập tên ngân hàng VPBank',
            ];
            if ($this->validator->validate($data, $rules, $messages)) {
                $result = $this->bankService->Update($data, $id, $this->bankService->tableName);
                if ($result) {
                    AlertSession::Success('Cập Nhật Thành Công');
                    $this->redirect(ADMIN_PATH . '/bank/edit/' . $id);
                    return;
                }
                AlertSession::Error('Cập Nhật Thất Bại');
                return;
            }
            $errors = $this->validator->getFormattedErrors();
            foreach ($errors as $field => $error) {
                AlertSession::Error($error);
            }
        }

        // Load the view and pass data
        $this->view('Bank.Edit', [
            'bank' => $bank,
            'title' => 'Cập Nhật Shop',
        ]);
    }
    // DELETE: /admin/blog/delete/{id}
    public function Delete($id)
    {
        if (Request::method(EHttpMethod::DELETE)) {
            $blog = $this->bankService->GetById($id);
            if (!$blog) {
                Response::notFound([], 'Không Tồn Tại', 404);
                return;
            }
            $result = $this->bankService->Delete($id, $this->bankService->tableName);
            if (!$result) {
                Response::badRequest([], 'Xóa Thất Bại', 400);
                return;
            }
            Response::success(
                [],
                'Xóa Thành Công',
                200
            );
            return;
        }
        Response::methodNotAllowed();
    }
}
