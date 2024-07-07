<?php
namespace App\Controllers;

use App\Controllers\Base\AdminController;
use App\Services\Common\AlertSession;
use App\Services\Common\Enums\EHttpMethod;
use App\Services\Common\Helper;
use App\Services\Common\Pagination;
use App\Services\Common\Request;
use App\Services\Common\Response;
use App\Services\PageServices\PageService;

class PageController extends AdminController
{
    public PageService $pageService;
    public function __construct()
    {
        $this->pageService = new PageService();
        parent::__construct();
    }

    // GET: /admin/category/page/{pageIndex}
    public function Index($pageIndex = 1)
    {
        $totalRecords = $this->pageService->GetTotalRecords($this->pageService->tableName);
        $pagConfig = [
            'baseURL' => ADMIN_PATH.'/trang/page',
            'totalRows' => $totalRecords,
            'perPage' => $this->pageConfig['PageSize'],
        ];
        $pagination = new Pagination($pagConfig);

        // Retrieve all users from the database
        $categories = $this->pageService->GetWithPaginate($pageIndex, $this->pageConfig['PageSize']);
        // Load the view and pass data
        $this->view('Page.Index', [
            'categories' => $categories,
            'pagination' => $pagination->createLinks(),
            'title' => 'Danh Sách Các Trang',
        ]);
    }

    // GET: /admin/category/create
    public function Create()
    {
        // Handle form submission to create a new user
        if (Request::method(EHttpMethod::POST)) {
            $category = [
                'Title' => Request::post('Title'),
                'Slug' => Helper::Slugify(Request::post('Title')),
                'Content' =>  $_POST['Content'], 
                'Code' => Request::post('Code'),
                'IsMenu' => Request::post('IsMenu') ?? 0,
            ];
            $rules = [
                'Title' => 'required|min:2|max:100',
                'Code' => 'required'
            ];
            $messages = [
                'Name.required' => 'Bạn chưa nhập Tên shop',
                'Name.min' => 'Tiêu đề có ít nhất từ 2 đến 100 ký tự',
                'Name.max' => 'Tiêu đề có ít nhất từ 2 đến 100 ký tự',
                'Code.required' => 'Bạn chưa nhập Mã Code'
            ];
            if ($this->validator->validate($category, $rules, $messages)) {
                $result = $this->pageService->Add($category, $this->pageService->tableName);
                if ($result) {
                    AlertSession::Success('Thêm Mới Trang Thành Công');
                    $this->redirect(ADMIN_PATH.'/trang');
                    return;
                }
                AlertSession::Error('Thêm Mới Trang Thất Bại');
                return;
            }
            $errors = $this->validator->getFormattedErrors();
            foreach ($errors as $field => $error) {
                AlertSession::Error($error);
            }
        }
        // Load the view
        $this->view('Page.Create', [
            'title' => 'Thêm Mới Trang',
        ]);
    }

    // GET: /admin/trang/edit/{id}
    public function Edit($id)
    {
        // Handle form submission to create a new user
        if (Request::method(EHttpMethod::POST)) {
            $page = [
                'Title' => Request::post('Title'),
                'Slug' => Helper::Slugify(Request::post('Title')),
                'Content' => $_POST['Content'], 
                'Code' => Request::post('Code'),
                'IsMenu' => Request::post('IsMenu') ?? 0,
            ];
            $rules = [
                'Title' => 'required|min:2|max:100',
                'Code' => 'required'
            ];
            $messages = [
                'Name.required' => 'Bạn chưa nhập Tên shop',
                'Name.min' => 'Tiêu đề có ít nhất từ 2 đến 100 ký tự',
                'Name.max' => 'Tiêu đề có ít nhất từ 2 đến 100 ký tự',
                'Code.required' => 'Bạn chưa nhập Mã Code'
            ];
            if ($this->validator->validate($page, $rules, $messages)) {
                $result = $this->pageService->Update($page, $id, $this->pageService->tableName);
                if ($result) {
                    AlertSession::Success('Cập Nhật Trang Thành Công');
                    $this->redirect(ADMIN_PATH.'/trang');
                    return;
                }
                AlertSession::Error('Cập Nhật Trang Thất Bại');
                return;
            }
            $errors = $this->validator->getFormattedErrors();
            foreach ($errors as $field => $error) {
                AlertSession::Error($error);
            }
        }
        $page = $this->pageService->GetById($id);
        // Load the view
        $this->view('Page.Edit', [
            'title' => 'Cập Nhật Trang',
            'page' => $page
        ]);
    }

    // GET: /admin/trang/delete/{id}
    public function Delete($id)
    {
        if (Request::method(EHttpMethod::DELETE)) {
            $page = $this->pageService->GetById($id);
            if (!$page) {
                Response::notFound([], 'Trang Không Tồn Tại', 404);
                return;
            }
            $result = $this->pageService->Delete($id, $this->pageService->tableName);
            if (!$result) {
                Response::badRequest([], 'Xóa Trang Thất Bại', 400);
                return;
            }
            Response::success(
                [], 
                'Xóa Trang Thành Công',
                200
            );
            return;
        }
        Response::methodNotAllowed();
    }
}