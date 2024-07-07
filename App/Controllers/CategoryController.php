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

class CategoryController extends AdminController
{
    public CategoryService $categoryService;
    public function __construct()
    {
        $this->categoryService = new CategoryService();
        parent::__construct();
    }

    // GET: /admin/category/page/{pageIndex}
    public function Index($pageIndex = 1)
    {
        $totalRecords   = $this->categoryService->GetTotalRecords($this->categoryService->tableName);
        $pagConfig = [
            'baseURL' => ADMIN_PATH.'/category/page',
            'totalRows' => $totalRecords,
            'perPage' => $this->pageConfig['PageSize'],
        ];
        $pagination = new Pagination($pagConfig);

        // Retrieve all users from the database
        $categories = $this->categoryService->GetWithPaginate($pageIndex, $this->pageConfig['PageSize']);
        // Load the view and pass data
        $this->view('Category.Index', [
            'categories' => $categories,
            'pagination' => $pagination->createLinks(),
            'title' => 'Danh Sách Các Shop',
        ]);
    }
    // GET: /admin/category/create
    public function Create()
    {
        // Handle form submission to create a new user
        if (Request::method(EHttpMethod::POST)) {
            $category = [
                'Name' => Request::post('Name'),
                'Image' => Request::post('Image'),
                'Discount' => Request::post('Discount'), // chiêu khấu
                'Slug' => Helper::Slugify(Request::post('Name')),
                'Config' => Request::post('Config'),
                'Content' => $_POST['Content'],
            ];
            $rules = [
                'Name' => 'required|min:2|max:100',
                'Config' => 'required',
                'Discount' => 'required'
            ];
            $messages = [
                'Name.required' => 'Bạn chưa nhập Tên shop',
                'Name.min' => 'Tên Shop có ít nhất từ 2 đến 100 ký tự',
                'Name.max' => 'Tên Shop có ít nhất từ 2 đến 100 ký tự',
                'Config.required' => 'Bạn chưa nhập Cấu Hình Shop',
                'Discount.required' => 'Bạn chưa nhập Chiết Khấu Shop'
            ];
            if ($this->validator->validate($category, $rules, $messages)) {
                $result = $this->categoryService->Add($category, $this->categoryService->tableName);
                if ($result) {
                    AlertSession::Success('Thêm Mới Shop Thành Công');
                    $this->redirect(ADMIN_PATH.'/category');
                    return;
                }
                AlertSession::Error('Thêm Mới Shop Thất Bại');
                return;
            }
            $errors = $this->validator->getFormattedErrors();
            foreach ($errors as $field => $error) {
                AlertSession::Error($error);
            }
        }
        // Load the view
        $this->view('Category.Create', [
            'title' => 'Thêm Mới Shop',
        ]);
    }

    // GET: /admin/category/edit/{id}
    public function Edit($id)
    {
        $category = $this->categoryService->GetById($id);
        if (!$category) {
            AlertSession::Error('Shop Không Tồn Tại');
            $this->redirect(ADMIN_PATH.'/category');
            return;
        }
        // Handle form submission to update an existing user
        if (Request::method(EHttpMethod::POST)) {
            $data = [
                'Name' => Request::post('Name'),
                'Image' => Request::post('Image'),
                'Discount' => Request::post('Discount'), // chiêu khấu
                'Slug' => Helper::Slugify(Request::post('Name')),
                'Config' => Request::post('Config'),
                'Content' => $_POST['Content'],
            ];
            $rules = [
                'Name' => 'required|min:2|max:100',
                'Config' => 'required',
                'Discount' => 'required'
            ];
            $messages = [
                'Name.required' => 'Bạn chưa nhập Tên shop',
                'Name.min' => 'Tên Shop có ít nhất từ 2 đến 100 ký tự',
                'Name.max' => 'Tên Shop có ít nhất từ 2 đến 100 ký tự',
                'Config.required' => 'Bạn chưa nhập Cấu Hình Shop',
                'Discount.required' => 'Bạn chưa nhập Chiết Khấu Shop'
            ];
            if ($this->validator->validate($data, $rules, $messages)) {
                $result = $this->categoryService->Update($data, $id, $this->categoryService->tableName);
                if ($result) {
                    AlertSession::Success('Cập Nhật Shop Thành Công');
                    $this->redirect(ADMIN_PATH.'/category/edit/' . $id);
                    return;
                }
                AlertSession::Error('Cập Nhật Shop Thất Bại');
                return;
            }
            $errors = $this->validator->getFormattedErrors();
            foreach ($errors as $field => $error) {
                AlertSession::Error($error);
            }
        }
        
        // Load the view and pass data
        $this->view('Category.Edit', [
            'category' => $category,
            'title' => 'Cập Nhật Shop',
        ]);
    }
    // DELETE: /admin/category/delete/{id}
    public function Delete($id)
    {
        if (Request::method(EHttpMethod::DELETE)) {
            $category = $this->categoryService->GetById($id);
            if (!$category) {
                Response::notFound([], 'Shop Không Tồn Tại', 404);
                return;
            }
            $result = $this->categoryService->Delete($id, $this->settingService->tableName);
            if (!$result) {
                Response::badRequest([], 'Xóa Shop Thất Bại', 400);
                return;
            }
            Response::success(
                [],
                'Xóa Shop Thành Công',
                200
            );
            return;
        }
        Response::methodNotAllowed();
    }
}
