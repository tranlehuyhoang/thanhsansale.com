<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Logger;
use App\Services\Common\Enums\EHttpMethod;
use App\Services\Common\Enums\EOrderType;
use App\Services\Common\Helper;
use App\Services\Common\Request;
use App\Services\Common\Response;
use App\Services\Common\Session;
use App\Services\LazadaServices\LazadaService;
use App\Services\OrderServices\OrderService;

class LazadaController extends Controller
{
    private LazadaService $lazadaService;
    private OrderService $orderService;
    private Logger $logger;
    public function __construct()
    {
        $this->lazadaService = new LazadaService();
        $this->orderService = new OrderService();
        $this->logger = new Logger();

        parent::__construct();
    }

    public function Offers()
    {
        $res = $this->lazadaService->GetOfferList(20, 1);
        $res = json_decode($res, true);
        Response::success($res);
    }

    // API: /lazada/product-feed
    public function ProductFeed()
    {
        if (Request::method(EHttpMethod::POST) == true) {

            $offerType = 1;
            $limit = 8;
            $page = Request::post('pageIndex') ?? 1;
            $categoryL1 = null;
            $mmCampaignId = null;
            $productIds = [];

            $res = $this->lazadaService->GetProductFeed($offerType, $limit, $page, $categoryL1, $mmCampaignId, $productIds);
            if ($res->result->success == false) {
                Response::badRequest($res->code);
            }
            $data = array_map(function ($item) {
                $item->discountPrice = Helper::formatCurrencyVND($item->discountPrice);
                $item->totalCommissionAmount = Helper::formatCurrencyVND($item->totalCommissionAmount * $this->lazadaService->category->Discount);
                return $item;
            }, $res->result->data);

            Response::success($data, "Lấy danh sách sản phẩm thành công", 200, $page);
            return;
        }
        Response::methodNotAllowed();
    }
    public function Test()
    {


        $offerType = 1;
        $limit = 10;
        $page = Request::post('pageIndex') ?? 1;
        $categoryL1 = null;
        $mmCampaignId = null;
        $productIds = [];

        $res = $this->lazadaService->GetProductFeed($offerType, $limit, $page, $categoryL1, $mmCampaignId, $productIds);
        if ($res->result->success == false) {
            Response::badRequest($res->code);
        }
        $data = array_map(function ($item) {
            return $item;
        }, $res->result->data);

        Response::success($data, "Lấy danh sách sản phẩm thành công", 200, $page);

    }

    public function TrackingLinkByProductId($productId)
    {

        $res = $this->lazadaService->TrackingLinkByProductId($productId);

        // userLogin
        if (Session::Get('user') == null) {
            return Response::badRequest([], 'Vui lòng đăng nhập để mua hàng');
        }

        $userId = $this->userLogin->Id ?? null;
        $subId1 = "?sub_id1=" . $userId;
        if ($userId == null) {
            $subId1 = "";
        }
        if ($res->code != '0') {
            Response::badRequest($res->code);
            return;
        }
        $res->result->data->trackingLink = $res->result->data->trackingLink . $subId1;
        //$res->result->data->totalCommissionAmount = Helper::formatCurrencyVND($res->result->data->totalCommissionAmount * $this->lazadaService->category->Discount);
        Response::success($res->result->data);
    }

    // API: /api/lazada/get-by-link
    public function GetProductByLink()
    {
        if (Request::method(EHttpMethod::POST) == true) {
            $link = Request::post('link');
            $res = $this->lazadaService->GetProductByLink($link);
            if ($res->result->success == false) {
                Response::badRequest($res->code);
            }
            Response::success($res->result->data);
            return;
        }
        Response::methodNotAllowed();
    }

    #region API Crojob

    // API: /api/lazada/auto-check-order
    public function AutoCheckOrder()
    {
        if (Request::method(EHttpMethod::GET)) {
            // Write log
            $this->logger->log('Bắt đầu kiểm tra đơn hàng', 'AUTO_CHECK_ORDER');

            $pageSize = 500;
            $pageNum = 1;
            // Get order from Lazada
            $dateNow = date('d/m/Y');

            // param
            $fromDate = date('01/m/Y');
            $toDate = $dateNow;
            $params = Request::queryString();
            if (!empty($params)) {
                $fromDate = date('d/m/Y', strtotime($params['FromDate']));
                $toDate = date('d/m/Y', strtotime($params['ToDate']));
            }

            $purchase_time_s = $fromDate;
            $purchase_time_e = $toDate; 

            $data = [];
            $res = $this->lazadaService->GetReports($purchase_time_s, $purchase_time_e, $pageSize, $pageNum);
            if (count($res->result->data) > 0) {
                $data = array_merge($data, $res->result->data);
                while (count($res->result->data) > 0) {
                    $pageNum++;
                    $res = $this->lazadaService->GetReports($purchase_time_s, $purchase_time_e, $pageSize, $pageNum);
                    $data = array_merge($data, $res->result->data);
                }
            }
            if ($res->code != '0') {
                $this->logger->log($res->message, 'AUTO_CHECK_ORDER');
                return Response::badRequest($res, $res->message);
            }
            if (empty($data)) {
                $this->logger->log('Lazada - Không có đơn hàng mới', 'AUTO_CHECK_ORDER');
                return Response::success($res, 'Lazada - Không có đơn hàng mới', 200);
            }

            // Filter orders with subId1
            $ordersFilter = array_filter($data, function ($item) {
                return !empty($item->subId1);
            });

            // Response::success($ordersFilter, 'Lấy dữ liệu thành công', 200);
            // return;

            $orderCount = count($ordersFilter);
            $this->logger->log('Có ' . $orderCount . ' đơn hàng mới', 'AUTO_CHECK_ORDER');

            if ($orderCount === 0) {
                return Response::success([], 'Không có đơn hàng hợp lệ', 200);
            }

            $orders = [];
            foreach ($ordersFilter as $item) {
                $id = $item->memberId . '-' . $item->orderId . '-' . $item->sku . '-' . $item->offerId . '-' . $item->subId1;
                $createdAt = date('Y-m-d H:i:s', strtotime($item->fulfilledTime));

                $updateAt = null;
                if (isset($item->returnedTime)) {
                    $updateAt = $item->returnedTime;
                }
                if (isset($item->deliveredTime)) {
                    $updateAt = $item->deliveredTime;
                }
                $updateAt = date('Y-m-d H:i:s', isset($updateAt) ? strtotime($updateAt) : null);
                $status = $this->RenderStatus($item->status);
                $order = [
                    'UserId' => $item->subId1,
                    'ProductName' => $item->skuName,
                    'Code' => $id,
                    'Price' => $item->estPayout,
                    'Discount' => ($item->estPayout * $this->lazadaService->category->Discount),
                    'Status' => $status,
                    'Type' => EOrderType::Lazada,
                    'Note' => '',
                    'CreatedAt' => $createdAt,
                    'UpdatedAt' => $status == 1 ? $createdAt : $updateAt,
                ];
                $orders[] = $order;
            }

            $result = $this->orderService->AddOrUpdateMany($orders, $this->orderService->tableName, 'Code');

            if ($result["Result"]) {
                $message = 'Thêm mới ' . $result['Add'] . ' đơn hàng, cập nhật ' . $result['Update'] . ' đơn hàng';
                $this->logger->log($message, 'AUTO_CHECK_ORDER');
                return Response::success($orders, $message, 200);
            }

            $this->logger->log('Kết thúc kiểm tra đơn hàng', 'AUTO_CHECK_ORDER');
            return Response::success($orders, 'Lấy dữ liệu thành công', 200);
        }
    }


    #endregion API Crojob

    private function RenderStatus($status)
    {
        switch ($status) {
            case 'Pending':
                return 1;
            case 'Fulfilled':
                return 1;
            case 'Delivered':
                return 2;
            default:
                return 3;
        }
    }
}