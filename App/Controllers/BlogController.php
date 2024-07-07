<?php

namespace App\Controllers;

use App\Controllers\Base\AdminController;
use App\Services\BlogServices\BlogService;
use App\Services\Common\AlertSession;
use App\Services\Common\Enums\EHttpMethod;
use App\Services\Common\Helper;
use App\Services\Common\Pagination;
use App\Services\Common\Request;
use App\Services\Common\Response;

class BlogController extends AdminController
{
    public BlogService $blogService;
    public function __construct()
    {
        $this->blogService = new BlogService();
        parent::__construct();
    }

    // GET: /admin/blog/page/{pageIndex}
    public function Index($pageIndex = 1)
    {
        $totalRecords   = $this->blogService->GetTotalRecords($this->blogService->tableName);
        $pagConfig = [
            'baseURL' => ADMIN_PATH.'/blog/page',
            'totalRows' => $totalRecords,
            'perPage' => $this->pageConfig['PageSize'],
        ];
        $pagination = new Pagination($pagConfig);

        // Retrieve all users from the database
        $blogs = $this->blogService->GetWithPaginate($pageIndex, $this->pageConfig['PageSize']);
        // Load the view and pass data
        $this->view('Blog.Index', [
            'blogs' => $blogs,
            'pagination' => $pagination->createLinks(),
            'title' => 'Danh Sách Tin Tức',
        ]);
    }
    // GET: /admin/blog/create
    public function Create()
    {
        // Handle form submission to create a new user
        if (Request::method(EHttpMethod::POST)) {
            $blog = [
                'Title' => Request::post('Title'),
                'Image' => Request::post('Image'),
                'Slug' => Helper::Slugify(Request::post('Title')),
                'Content' => $_POST['Content'],
            ];
            $rules = [
                'Title' => 'required|min:2|max:100',
            ];
            $messages = [
                'Title.required' => 'Bạn chưa nhập tiêu đề',
                'Title.min' => 'Tiêu đề có ít nhất từ 2 đến 250 ký tự',
                'Title.max' => 'Tiêu đề có ít nhất từ 2 đến 250 ký tự',
            ];
            if ($this->validator->validate($blog, $rules, $messages)) {
                $result = $this->blogService->Add($blog, $this->blogService->tableName);
                if ($result) {
                    AlertSession::Success('Thêm Mới Shop Thành Công');
                    $this->redirect(ADMIN_PATH.'/blog');
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
        $this->view('Blog.Create', [
            'title' => 'Thêm Mới Tin',
        ]);
    }

    // GET: /admin/blog/edit/{id}
    public function Edit($id)
    {
        $blog = $this->blogService->GetById($id);
        if (!$blog) {
            AlertSession::Error('Tin Tức Không Tồn Tại');
            $this->redirect(ADMIN_PATH.'/blog');
            return;
        }
        // Handle form submission to update an existing user
        if (Request::method(EHttpMethod::POST)) {
            $data = [
                'Title' => Request::post('Title'),
                'Image' => Request::post('Image'), // 'Image' => 'required|url
                'Slug' => Helper::Slugify(Request::post('Title')),
                'Content' => $_POST['Content'],
            ];
            $rules = [
                'Title' => 'required|min:2|max:100',
            ];
            $messages = [
                'Title.required' => 'Bạn chưa nhập Tiêu Đề',
                'Title.min' => 'Tiêu Đề có ít nhất từ 2 ký tự',
                'Title.max' => 'Tiêu Đề có tối thiểu 250 ký tự',
            ];
            if ($this->validator->validate($data, $rules, $messages)) {
                $result = $this->blogService->Update($data, $id, $this->blogService->tableName);
                if ($result) {
                    AlertSession::Success('Cập Nhật Shop Thành Công');
                    $this->redirect(ADMIN_PATH.'/blog/edit/' . $id);
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
        $this->view('Blog.Edit', [
            'blog' => $blog,
            'title' => 'Cập Nhật Shop',
        ]);
    }
    // DELETE: /admin/blog/delete/{id}
    public function Delete($id)
    {
        if (Request::method(EHttpMethod::DELETE)) {
            $blog = $this->blogService->GetById($id);
            if (!$blog) {
                Response::notFound([], 'Không Tồn Tại', 404);
                return;
            }
            $result = $this->blogService->Delete($id, $this->blogService->tableName);
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
