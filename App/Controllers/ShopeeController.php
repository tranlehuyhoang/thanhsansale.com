<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Logger;
use App\Services\Common\Enums\EHttpMethod;
use App\Services\Common\Enums\EShopeeStatus;
use App\Services\Common\Helper;
use App\Services\Common\Request;
use App\Services\Common\Response;
use App\Services\Common\Session;
use App\Services\Identities\UserServices\UserService;
use App\Services\OrderServices\OrderService;
use App\Services\PaymentTransactionServices\PaymentTransactionService;
use App\Services\ShopeeServices\ShopeeService;

class ShopeeController extends Controller
{
    private Logger $logger;
    private ShopeeService $shopeeService;
    private OrderService $orderService;
    private PaymentTransactionService $paymentTransactionService;
    private UserService $userService;
    public function __construct()
    {
        $this->shopeeService = new ShopeeService();
        $this->logger = new Logger();
        $this->orderService = new OrderService();
        $this->paymentTransactionService = new PaymentTransactionService();
        $this->userService = new UserService();
        parent::__construct();
    }

    //api/shopee/products/page/2-2-1-1 => list_type=2&sort_type=2&page_offset=1&client_type=1
    public function Index($page_offset = 0, $list_type = 2, $sort_type = 1, $client_type = 1)
    {
        $res = $this->shopeeService->GetProducts($page_offset, $list_type, $sort_type, $client_type);
        return Response::success($res, 'Lấy dữ liệu thành công', 200);
    }

    // Get link
    public function GetLink()
    {
        if (Request::method(EHttpMethod::POST)) {
            // check login
            if (!Session::IsAuth()) {
                return Response::unauthorized('Vui lòng đăng nhập để mua hàng', 401);
            }
            $link = Request::post('link');
            // change utm_content = user_id
            $userId = $this->userLogin->Id;
            $link = $this->shopeeService->GetLink($link, $userId);
            if ($link == null) {
                return Response::badRequest(null, 'Link không hợp lệ', 400);
            }

            return Response::success($link, 'Lấy link thành công', 200);
        }
    }

    // create link
    public function CreateLink()
    {
        if (Request::method(EHttpMethod::POST)) {
            // check login
            if (!Session::IsAuth()) {
                return Response::unauthorized('Vui lòng đăng nhập để mua hàng', 401);
            }
            $link = Request::post('link');
            $userId = $this->userLogin->Id;
            $link = $this->shopeeService->CreateLink($link, $userId);
            if ($link == null) {
                return Response::badRequest(null, 'Link không hợp lệ', 400);
            }
            // array to object 
            return Response::success($link[0], 'Lấy link thành công', 200);
        }
    }

    // get one product
    public function GetProduct($item_id)
    {
        $res = $this->shopeeService->GetProduct($item_id);
        if ($res == null) {
            return Response::badRequest(null, 'Không tìm thấy sản phẩm', 400);
        }
        return Response::success($res, 'Lấy dữ liệu thành công', 200);
    }

    // render link

    public function RenderIdByLink()
    {
        if (Request::method(EHttpMethod::POST)) {
            $link = Request::post('link');
            if (empty($link)) {
                return Response::badRequest(null, 'Link không hợp lệ', 400);
            }
            $final = $this->shopeeService->GetFinalLink($link);

            if (empty($final) || strpos($final, 'shopee.vn') === false) {
                return Response::badRequest(null, 'Link không hợp lệ', 400);
            }
            $res = $this->shopeeService->RenderIdByLink($final);
            if ($res == null) {
                return Response::badRequest(null, 'Link không hợp lệ', 400);
            }
            return Response::success($res, 'Lấy link thành công', 200);
        }
        Response::methodNotAllowed();
    }


    // get final link
    public function GetFinalLink()
    {
        if (Request::method(EHttpMethod::POST)) {
            $link = Request::post('link');
            if (empty($link)) {
                return Response::badRequest(null, 'Link không hợp lệ', 400);
            }
            $res = $this->shopeeService->GetFinalLink($link);
            if ($res == null) {
                return Response::badRequest(null, 'Link không hợp lệ', 400);
            }
            return Response::success($res, 'Lấy link thành công', 200);
        }
        Response::methodNotAllowed();
    }

    // get Orders
    public function GetOrders()
    {
        if (Request::method(EHttpMethod::POST)) {
            $pageSize = Request::post('pageSize');
            $pageNum = Request::post('pageNum');
            $purchase_time_s = Request::post('startDate');
            $purchase_time_e = Request::post('endDate');
            $res = $this->shopeeService->GetOrders($pageSize, $pageNum, $purchase_time_s, $purchase_time_e);
            return Response::success($res, 'Lấy dữ liệu thành công', 200);
        }
    }
    // auto check order
    // API: /api/shopee/auto-check-order
    public function AutoCheckOrder($isAll = 0)
    {
        if (Request::method(EHttpMethod::GET)) {
            // write log
            $this->logger->log('Bắt đầu kiểm tra đơn hàng', 'AUTO_CHECK_ORDER');
            $pageSize = 100;
            $pageNum = 1;
            $totalCount = 0;

            $display_order_status = $isAll == 0 ? EShopeeStatus::Completed : null;

            $purchase_time_s = date('Y-m-d', strtotime('-30 days'));
            $purchase_time_e = date('Y-m-d');
            $res = $this->shopeeService->GetOrders($pageSize, $pageNum, $purchase_time_s, $purchase_time_e, $display_order_status);
            $totalCount = $res['total_count'];
            $result = $res['list'];
            
            $allPage = ceil($totalCount / $pageSize);
            for ($i = 2; $i <= $allPage; $i++) {
                $resNext = $this->shopeeService->GetOrders($pageSize, $i, $purchase_time_s, $purchase_time_e, $display_order_status);
                if (count($resNext['list']) > 0)
                    $result = array_merge($result, $resNext['list']);
            }

            // filter order with utm_content
            $result = array_filter($result, function ($item) {
                $subId = explode('-', $item['utm_content']);
                return !empty($subId[0]);
            });

            if (count(($result)) == 0) {
                $this->logger->log('Không có đơn hàng mới', 'AUTO_CHECK_ORDER');
                return Response::success($res, 'Không có đơn hàng mới', 200);
            }
            $this->logger->log('Có ' . count($result) . ' đơn hàng mới', 'AUTO_CHECK_ORDER');
            $orders = [];
            foreach ($result as $item) {
                $order = [
                    //1-2-4-5 => 1
                    'UserId' => explode('-', $item['utm_content'])[0],
                    'ProductName' => $this->JoinProductName($item['orders'][0]['items']),
                    'Code' => $item['orders'][0]['order_sn'],
                    'Price' => Helper::shoppePrice($item['estimated_total_commission'], false),
                    'Discount' => ($item['estimated_total_commission'] / 100000) * $this->shopeeService->category->Discount,
                    'Status' => $item['orders'][0]['display_order_status'],
                    'Type' => 0,
                    'Note' => '',
                    'CreatedAt' => Helper::convertTimestampToDate($item['purchase_time']),
                    'UpdatedAt' => $item['checkout_complete_time'] == 0 ? null : Helper::convertTimestampToDate($item['checkout_complete_time']),
                ];
                if ($order['UserId'] == null) {

                    continue;
                }

                array_push($orders, $order);
            }
            $result = $this->orderService->AddOrUpdateMany($orders, $this->orderService->tableName, 'Code');
            if ($result["Result"]) {
                $message = 'Thêm mới ' . $result['Add'] . ' đơn hàng, cập nhật ' . $result['Update'] . ' đơn hàng';
                $this->logger->log($message, 'AUTO_CHECK_ORDER');
                Response::success($orders, $message, 200);
                return;
            }

            $this->logger->log('Kết thúc kiểm tra đơn hàng', 'AUTO_CHECK_ORDER');
            return Response::success($orders, 'Lấy dữ liệu thành công', 200);
        }
    }

    // API: /api/shopee/auto-check-order-cancel
    public function AutoCheckOrderCancel()
    {
        if (Request::method(EHttpMethod::GET)) {
            // write log
            $this->logger->log('Bắt đầu kiểm tra đơn hàng', 'AUTO_CHECK_ORDER');
            $pageSize = 100;
            $pageNum = 1;
            $totalCount = 0;

            $display_order_status = EShopeeStatus::Cancel;

            $purchase_time_s = date('Y-m-d', strtotime('-30 days'));
            $purchase_time_e = date('Y-m-d');
            $res = $this->shopeeService->GetOrders($pageSize, $pageNum, $purchase_time_s, $purchase_time_e, $display_order_status);
            $totalCount = $res['total_count'];
            $result = $res['list'];

            while ($totalCount > count($result)) {
                $pageNum++;
                $resNext = $this->shopeeService->GetOrders($pageSize, $pageNum, $purchase_time_s, $purchase_time_e, $display_order_status);
                if (count($resNext['list']) > 0)
                    $result = array_merge($result, $resNext['list']);
            }

            // filter order with utm_content
            $result = array_filter($result, function ($item) {
                $subId = explode('-', $item['utm_content']);
                return !empty($subId[0]);
            });

            if (count(($res['list'])) == 0) {
                $this->logger->log('Không có đơn hàng mới', 'AUTO_CHECK_ORDER');
                return Response::success($res, 'Không có đơn hàng mới', 200);
            }
            $this->logger->log('Có ' . count($res['list']) . ' đơn hàng mới', 'AUTO_CHECK_ORDER');
            $orders = [];
            foreach ($res['list'] as $item) {
                $order = [
                    //1-2-4-5 => 1
                    'UserId' => explode('-', $item['utm_content'])[0],
                    'ProductName' => $this->JoinProductName($item['orders'][0]['items']),
                    'Code' => $item['orders'][0]['order_sn'],
                    'Price' => Helper::shoppePrice($item['estimated_total_commission'], false),
                    'Discount' => ($item['estimated_total_commission'] / 100000) * $this->shopeeService->category->Discount,
                    'Status' => $item['orders'][0]['display_order_status'],
                    'Type' => 0,
                    'Note' => '',
                    'CreatedAt' => date('Y-m-d H:i:s', $item['purchase_time']),
                    'UpdatedAt' => $item['checkout_complete_time'] == 0 ? null : date('Y-m-d H:i:s', $item['checkout_complete_time']),
                ];
                if ($order['UserId'] == null) {
                    continue;
                }

                array_push($orders, $order);
            }
            $result = $this->orderService->AddOrUpdateMany($orders, $this->orderService->tableName, 'Code');
            if ($result["Result"]) {
                $message = 'Thêm mới ' . $result['Add'] . ' đơn hàng, cập nhật ' . $result['Update'] . ' đơn hàng';
                $this->logger->log($message, 'AUTO_CHECK_ORDER');
                Response::success($orders, $message, 200);
                return;
            }

            $this->logger->log('Kết thúc kiểm tra đơn hàng', 'AUTO_CHECK_ORDER');
            return Response::success($orders, 'Lấy dữ liệu thành công', 200);
        }
    }

    // API: /api/shopee/auto-check-order-pending
    public function AutoCheckOrderPending()
    {
        if (Request::method(EHttpMethod::GET)) {
            // write log
            $this->logger->log('Bắt đầu kiểm tra đơn hàng', 'AUTO_CHECK_ORDER');
            $pageSize = 100;
            $pageNum = 1;
            $totalCount = 0;

            $display_order_status = EShopeeStatus::Pending;

            $purchase_time_s = date('Y-m-d', strtotime('-30 days'));
            $purchase_time_e = date('Y-m-d');
            $res = $this->shopeeService->GetOrders($pageSize, $pageNum, $purchase_time_s, $purchase_time_e, $display_order_status);
            $totalCount = $res['total_count'];
            $result = $res['list'];

            while ($totalCount > count($result)) {
                $pageNum++;
                $resNext = $this->shopeeService->GetOrders($pageSize, $pageNum, $purchase_time_s, $purchase_time_e, $display_order_status);
                if (count($resNext['list']) > 0)
                    $result = array_merge($result, $resNext['list']);
            }

            // filter order with utm_content
            $result = array_filter($result, function ($item) {
                $subId = explode('-', $item['utm_content']);
                return !empty($subId[0]);
            });

            if (count(($res['list'])) == 0) {
                $this->logger->log('Không có đơn hàng mới', 'AUTO_CHECK_ORDER');
                return Response::success($res, 'Không có đơn hàng mới', 200);
            }
            $this->logger->log('Có ' . count($res['list']) . ' đơn hàng mới', 'AUTO_CHECK_ORDER');
            $orders = [];
            foreach ($res['list'] as $item) {
                $order = [
                    //1-2-4-5 => 1
                    'UserId' => explode('-', $item['utm_content'])[0],
                    'ProductName' => $this->JoinProductName($item['orders'][0]['items']),
                    'Code' => $item['orders'][0]['order_sn'],
                    'Price' => Helper::shoppePrice($item['estimated_total_commission'], false),
                    'Discount' => ($item['estimated_total_commission'] / 100000) * $this->shopeeService->category->Discount,
                    'Status' => $item['orders'][0]['display_order_status'],
                    'Type' => 0,
                    'Note' => '',
                    'CreatedAt' => date('Y-m-d H:i:s', $item['purchase_time']),
                    'UpdatedAt' => $item['checkout_complete_time'] == 0 ? null : date('Y-m-d H:i:s', $item['checkout_complete_time']),
                ];
                if ($order['UserId'] == null) {

                    continue;
                }

                array_push($orders, $order);
            }
            $result = $this->orderService->AddOrUpdateMany($orders, $this->orderService->tableName, 'Code');
            if ($result["Result"]) {
                $message = 'Thêm mới ' . $result['Add'] . ' đơn hàng, cập nhật ' . $result['Update'] . ' đơn hàng';
                $this->logger->log($message, 'AUTO_CHECK_ORDER');
                Response::success($orders, $message, 200);
                return;
            }

            $this->logger->log('Kết thúc kiểm tra đơn hàng', 'AUTO_CHECK_ORDER');
            return Response::success($orders, 'Lấy dữ liệu thành công', 200);
        }
    }


    // auto add payment transaction
    // API: /api/shopee/auto-add-payment-transaction
    public function AutoAddPaymentTransaction()
    {
        if (Request::method(EHttpMethod::GET)) {

            $startDate = date('Y-m-d', strtotime('-15 days'));
            $endDate = date('Y-m-d');
            $orders = $this->orderService->GetInStartDateAndEndDate($startDate, $endDate);

            if (count($orders) == 0) {
                $this->logger->log('Không có đơn hàng mới', 'AUTO_ADD_PAYMENT_TRANSACTION');
                Response::success(null, 'Không có đơn hàng mới', 200);
                return;
            }

            $transactions = [];
           // $dateNow = date('Y-m-d');
            foreach ($orders as $order) {
                // add 15 days to updatedAt
                //$updatedAt = date('Y-m-d', strtotime($order->UpdatedAt . ' +15 days'));
                if ($order->Status == EShopeeStatus::Completed) {
                    $transaction = [
                        'UserId' => $order->UserId,
                        'Code' => $order->Id,
                        'Price' => $order->Discount,
                        'Type' => 0,
                        'Status' => 0, // 0: Chưa quyết toán, 1: Đã quyết toán 2: Đã hủy
                        'Note' => 'Thanh toán tiền hoa hồng ' . $order->Discount,
                    ];
                    array_push($transactions, $transaction);
                }
            }
            $result = $this->paymentTransactionService->AddOrUpdateManyTransaction($transactions, $this->paymentTransactionService->tableName, "Code");
            if ($result["Result"] == true && $result["Total"] > 0) {
                $this->logger->log('Thêm mới ' . $result["Total"] . ' giao dịch thanh toán', 'PAYMENT_TRANSACTION');
                Response::success([], 'Thêm mới ' . $result["Total"] . ' giao dịch thanh toán', 200);
                return;
            }
            if ($result["Total"] == 0) {
                $this->logger->log('Không có giao dịch mới', 'PAYMENT_TRANSACTION');
                Response::success(null, 'Không có giao dịch mới', 200);
                return;
            }
            Response::badRequest(null, 'Thêm mới giao dịch thanh toán thất bại', 400);
            $this->logger->log('Thêm mới giao dịch thanh toán thất bại ShopeeController Line 228', 'PAYMENT_TRANSACTION');
        }
    }

    // auto add Money 
    // API: /api/shopee/auto-add-money
    public function AutoAddMoney()
    {
        if (Request::method(EHttpMethod::GET)) {

            $payments = $this->paymentTransactionService->GetByStatus(0); // 0: Chưa quyết toán, 1: Đã quyết toán 2: Đã hủy

            if (count($payments) == 0) {
                $this->logger->log('Không có giao dịch mới', 'AUTO_ADD_MONEY');
                Response::success(null, 'Không có giao dịch mới', 200);
                return;
            }

            $userUpdate = [];
            $transactionsUpdate = [];
            foreach ($payments as $transaction) {
                $user = [
                    'UserId' => $transaction->UserId,
                    'Price' => $transaction->Price
                ];
                $transactionUpdate = [
                    'Id' => $transaction->Id,
                    'Status' => 1
                ];
                array_push($transactionsUpdate, $transactionUpdate);
                array_push($userUpdate, $user);
            }

            $result = $this->userService->AddMoney($userUpdate);
            if ($result) {
                $res = $this->paymentTransactionService->UpdateMany($transactionsUpdate, $this->paymentTransactionService->tableName);
                if ($res) {
                    $this->logger->log('Cập nhật ' . count($transactionsUpdate) . ' giao dịch quyết toán', 'ADD_MONEY');
                    Response::success($transactionsUpdate, 'Cập nhật ' . count($transactionsUpdate) . ' giao dịch quyết toán', 200);
                    // send Mail
                    return;
                }

            }
            Response::badRequest(null, 'Thêm mới giao dịch nhận tiền thất bại', 400);
            $this->logger->log('Thêm mới giao dịch nhận tiền thất bại ShopeeController Line 258', 'ADD_MONEY');
        }
    }

    private function JoinProductName($items)
    {
        $res = '';
        foreach ($items as $item) {
            $res .= '[' . $item['shop_name'] . '] - ' . $item['item_name'] . '<br>';
        }
        return $res;
    }
}
