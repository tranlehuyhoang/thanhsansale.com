<?php

namespace App\Controllers;

use App\Controllers\Base\AdminController;
use App\Services\Common\Enums\EHttpMethod;
use App\Services\Common\Pagination;
use App\Services\Common\Request;
use App\Services\Common\Response;
use App\Services\OrderServices\OrderService;

class OrderController extends AdminController
{
    public OrderService $orderService;
    public function __construct()
    {
        $this->orderService = new OrderService();
        parent::__construct();
    }

    // GET: /admin/order/page/{pageIndex}
    public function Index($pageIndex = 1)
    {
        $totalRecords = $this->orderService->GetTotalRecords($this->orderService->tableName);
        $pagConfig = [
            'baseURL' => ADMIN_PATH . '/order/page',
            'totalRows' => $totalRecords,
            'perPage' => $this->pageConfig['PageSize'],
        ];
        $pagination = new Pagination($pagConfig);
        // Retrieve all orders from the database
        $orders = $this->orderService->GetWithPaginate($pageIndex, $this->pageConfig['PageSize']);
        // Load the view and pass data
        $this->view('Order.Index', [
            'orders' => $orders,
            'pagination' => $pagination->createLinks(),
            'showing' => $pagination->createdShowing(),
            'title' => 'Danh Sách Đơn Hàng',
        ]);
    }

    // API POST: /admin/order/search
    public function Search()
    {

        if (Request::method(EHttpMethod::POST)) {
            $filter = [
                'Code' => Request::post('Code'),
                'Type' => Request::post('Type'),
                'Username' => Request::post('Username'),
                'ProductName' => Request::post('ProductName'),
                'Status' => Request::post('Status'),
                'FromCreatedAt' => Request::post('FromCreatedAt'),
                'ToCreatedAt' => Request::post('ToCreatedAt'),
                'FromUpdatedAt' => Request::post('FromUpdatedAt'),
                'ToUpdatedAt' => Request::post('ToUpdatedAt'),
            ];
            // filter property is = '' => remove it, type = 0  not remove
            $filter = array_filter($filter, function ($value) {
                return $value !== '';
            });

            $orders = $this->orderService->GetWithPaginate(1, 1000000, $filter);
            Response::success($orders, 'Lấy Dữ Liệu Thành Công', 200);
            return;
        }
        Response::methodNotAllowed();
    }

    // POST: /admin/order/refund
    public function RefundOrder()
    {
        if (Request::method(EHttpMethod::POST)) {
            $filter = [
                'Code' => Request::post('Code'),
                'Type' => Request::post('Type'),
                'Username' => Request::post('Username'),
                'ProductName' => Request::post('ProductName'),
                'Status' => Request::post('Status'),
            ];
            $result = $this->orderService->RefundOrder($filter);
            if ($result) {
                Response::success('Hoàn Tiền Thành Công');
                return;
            }
            Response::badRequest([], 'Không có đơn hàng nào cần hoàn tiền');
            return;
        }
        Response::methodNotAllowed();
    }

    // GET: /admin/order/detail/{id}
    public function Detail($id)
    {
        // Retrieve all orders from the database
        $order = $this->orderService->GetById($id);
        // Load the view and pass data
        $this->view('Order.Detail', [
            'id' => $id,
            'order' => $order,
            'title' => 'Chi Tiết Đơn Hàng',
        ]);
    }
    // API GET: /admin/order/create
    public function Create()
    {
        // Handle form submission to create a new order
        if (Request::method(EHttpMethod::POST)) {
            $data = [
                'UserId' => Request::post('UserId'),
                'ProductName' => Request::post('ProductName'),
                'Code' => Request::post('Code'),
                'Price' => Request::post('Price'),
                'Discount' => Request::post('Discount'),
                'Status' => Request::post('Status'),
                'Type' => Request::post('Type'),
                'Note' => Request::post('Note'),
            ];
            $rules = [
                'UserId' => 'required',
                'ProductName' => 'required',
                'Code' => 'required|unique:orders,Code',
                'Price' => 'required',
                'Discount' => 'required'
            ];
            $messages = [
                'UserId.required' => 'UserId không được để trống',
                'ProductName.required' => 'Tên sản phẩm không được để trống',
                'Code.required' => 'Mã đơn hàng không được để trống',
                'Code.unique' => 'Mã đơn hàng đã tồn tại',
                'Price.required' => 'Giá sản phẩm không được để trống',
                'Discount.required' => 'Giảm giá không được để trống'

            ];
            if ($this->validator->validate($data, $rules, $messages)) {
                $result = $this->orderService->Add($data, $this->orderService->tableName);
                if ($result) {
                    Response::success('Thêm Mới Đơn Hàng Thành Công');
                    $this->redirect(ADMIN_PATH . '/category');
                    return;
                }
                Response::badRequest('Thêm Mới Đơn Hàng Thất Bại');
                return;
            }
            $errors = $this->validator->getFormattedErrors();
            if (count($errors) > 0) {
                Response::badRequest($errors);
            }
            return;
        }
        Response::methodNotAllowed();
    }


    // API DELETE: /admin/order/delete/{id}
    public function Delete($id)
    {
        if (Request::method(EHttpMethod::DELETE)) {
            $order = $this->orderService->GetById($id);
            if (!$order) {
                Response::notFound('Đơn Hàng Không Tồn Tại');
                return;
            }
            $result = $this->orderService->Delete($id, $this->orderService->tableName);
            if ($result) {
                Response::success('Xóa Đơn Hàng Thành Công');
                return;
            }
            Response::badRequest('Xóa Đơn Hàng Thất Bại');
            return;
        }
        Response::methodNotAllowed();
    }
}
