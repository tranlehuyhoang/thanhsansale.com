<?php 
namespace App\Controllers;
use App\Controllers\Base\AdminController;
use App\Services\Common\Enums\EHttpMethod;
use App\Services\Common\Pagination;
use App\Services\Common\Request;
use App\Services\Common\Response;
use App\Services\PaymentTransactionServices\PaymentTransactionService;

class PaymentTransactionController extends AdminController
{
    public PaymentTransactionService $paymentTransactionService;
    public function __construct()
    {
        $this->paymentTransactionService = new PaymentTransactionService();
        parent::__construct();
    }

    // GET: /admin/payment-transaction/page/{pageIndex}
    public function Index($pageIndex = 1)
    {
        $totalRecords = $this->paymentTransactionService->GetTotalRecords($this->paymentTransactionService->tableName);
        $pagConfig = [
            'baseURL' => ADMIN_PATH.'/payment-transaction/page',
            'totalRows' => $totalRecords,
            'perPage' => $this->pageConfig['PageSize'],
        ];
        $pagination = new Pagination($pagConfig);

        // Retrieve all payment-transaction from the database
        $paymentTransactions = $this->paymentTransactionService->GetWithPaginate($pageIndex, $this->pageConfig['PageSize']);
        // Load the view and pass data
        $this->view('PaymentTransaction.Index', [
            'paymentTransactions' => $paymentTransactions,
            'pagination' => $pagination->createLinks(),
            'showing' => $pagination->createdShowing(),
            'title' => 'Danh Sách Giao Dịch',
        ]);
    }

     // GET: /admin/history-transaction/page/{pageIndex}
     public function HistoryPayment($pageIndex = 1)
     {
         $totalRecords = $this->paymentTransactionService->GetTotalRecords($this->paymentTransactionService->tableName);
         $pagConfig = [
             'baseURL' => ADMIN_PATH.'/history-transaction/page',
             'totalRows' => $totalRecords,
             'perPage' => $this->pageConfig['PageSize'],
         ];
         $pagination = new Pagination($pagConfig);
 
         // Retrieve all payment-transaction from the database
         $paymentTransactions = $this->paymentTransactionService->GetWithPaginate($pageIndex, $this->pageConfig['PageSize'],[], 'Id DESC', 1);
         // Load the view and pass data
         $this->view('PaymentTransaction.HistoryPayment', [
             'paymentTransactions' => $paymentTransactions,
             'pagination' => $pagination->createLinks(),
             'showing' => $pagination->createdShowing(),
             'title' => 'Danh Sách Rút Tiền',
         ]);
     }

    // API POST: /payment-transaction/approve
    public function Approve()
    {
        // Handle form submission to create a new user
        if (Request::method(EHttpMethod::POST)) {
            $id = Request::post('Id');
            // check exist
            $paymentTransaction = $this->paymentTransactionService->GetById($id);
            if (!$paymentTransaction) {
                Response::badRequest([], 'Không Tìm Thấy Giao Dịch', 400);
                return;
            }
            $data = [
                'Status' => 1,
            ];
            $result = $this->paymentTransactionService->Update($data, $id, $this->paymentTransactionService->tableName);
            if ($result) {
                Response::success([], 'Cập Nhật Thành Công', 200);
                return;
            }
            Response::badRequest([], 'Cập Nhật Thất Bại', 400);
        }
        
    }

    // API POST: /payment-transaction/approve-all
    public function ApproveAll()
    {
        // Handle form submission to create a new user
        if (Request::method(EHttpMethod::POST)) {
            $result = $this->paymentTransactionService->ApproveAll();
            if ($result) {
                Response::success([], 'Cập Nhật Thành Công', 200);
                return;
            }
            Response::badRequest([], 'Cập Nhật Thất Bại', 400);
        }
    }

    // API DELETE: /admin/payment-transaction/delete/{id}
    public function Delete($id)
    {
        if (Request::method(EHttpMethod::DELETE)) {
            $result = $this->paymentTransactionService->Delete($id, $this->paymentTransactionService->tableName);
            if ($result) {
                Response::success([], 'Xóa Thành Công', 200);
                return;
            }
            Response::badRequest([], 'Xóa Thất Bại', 400);
            return;
        }
    }

    // API POST: /admin/payment-transaction/search
    public function Search()
    {
        if (Request::method(EHttpMethod::POST)) {
            $filter = [
                'Username' => Request::post('Username'), // 'Username'
                'Status' => Request::post('Status'), // 'Status'
                
            ];
            $type = Request::post('Type') ?? 0;
            $users = $this->paymentTransactionService->GetWithPaginate(1, 1000000, $filter, 'Id DESC', $type);
            Response::success($users, 'Lấy Dữ Liệu Thành Công', 200);
            return;
        }
    }

    // POST: /admin/payment-transaction/export-excel
    public function ExportExcel()
    {
        if (Request::method(EHttpMethod::POST)) {
            $filter = [
                'Username' => Request::post('Username'), // 'Username'
                'Status' => Request::post('Status'), // 'Status'
            ];
            $result = $this->paymentTransactionService->ExportExcel($filter);
            if (!empty($result)) {
               // download file from url using php
               header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
               header('Content-Disposition: attachment; filename="transaction.xlsx"');
               header('Cache-Control: max-age=0');
               $file = file_get_contents($result);
               echo $file;
               return;
           }
           Response::badRequest([], 'Xuất Excel Thất Bại', 400);
           return;
        }
    }
}