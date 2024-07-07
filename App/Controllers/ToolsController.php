<?php

namespace App\Controllers;

use App\Controllers\Base\AdminController;
use App\Services\Common\Enums\EHttpMethod;
use App\Services\Common\Helper;
use App\Services\Common\Request;
use App\Services\Common\Response;
use App\Services\LazadaServices\LazadaService;
use App\Services\ShopeeServices\ShopeeService;
use stdClass;

class ToolsController extends AdminController
{
    private ShopeeService $shopeeService;
    private LazadaService $lazadaService;
    public function __construct()
    {
        $this->shopeeService = new ShopeeService();
        $this->lazadaService = new LazadaService();
        parent::__construct();
    }

    public function Shopee()
    {
        if (Request::method(EHttpMethod::POST)) {
            $page_size = Request::post('pageSize');
            $page_num = Request::post('pageNum');
            $purchase_time_s = Request::post('startDate');
            $purchase_time_e = Request::post('endDate');
            $order_id = Request::post('orderId');
            $res = $this->shopeeService->GetOrders($page_size, $page_num, $purchase_time_s, $purchase_time_e,null, null,null, $order_id);
            if ($res == null) {
                Response::badRequest('Không có dữ liệu');
                return;
            }
            $data = [
                'list' => self::renderItems($res['list']),
                'total' => $res['total_count'],
                'page_size' => $res['page_size'],
                'page_num' => $res['page_num'],
            ];

            Response::success($data);
            return;
        }
        // get first date of month
        $startDate = date('d/m/Y', strtotime('first day of this month'));
        // get last date of month
        $endDate = date('d/m/Y', strtotime('last day of this month'));


        $this->view('Tools.Shopee', [
            'title' => 'Danh Sách Các Shop',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'category' => $this->shopeeService->category,
        ]);
    }

    public function TikTokShop()
    {
        $this->view('Tools.TikTokShop', [
            'title' => 'Danh Sách Các Shop',
        ]);
    }

    public function Lazada()
    {
        if (Request::method(EHttpMethod::POST)) {
            $page_size = Request::post('pageSize');
            $page_num = Request::post('pageNum');
            $dateStart = Request::post('dateStart');
            $dateEnd = Request::post('dateEnd');
            $res = $this->lazadaService->GetReports($dateStart, $dateEnd, $page_size, $page_num);
            if ($res == null) {
                Response::badRequest('Không có dữ liệu');
                return;
            }
            if($res->code != '0'){
                Response::badRequest($res->message);
                return;
            }
            $data = [];
            foreach ($res->result->data as $item) {
                $order = new stdClass();
                $order = $item;
                $order->lazadaCommission = Helper::formatCurrencyVND($item->bonusPayout);
                $order->commissionWebsite = Helper::formatCurrencyVND($item->bonusPayout * $this->lazadaService->category->Discount);
                $order->fulfilledTime = date('d/m/Y H:i:s', strtotime($item->fulfilledTime));
                array_push($data, $order);
            }
            Response::success($data);
            return;
        }

         $startDate = date('d/m/Y', strtotime('first day of this month'));
         $now = date('d/m/Y');
         
        $this->view('Tools.Lazada', [
            'title' => 'Danh Sách Các Shop',
            'startDate' => $startDate,
            'endDate' => $now,
            'category'=> $this->lazadaService->category,
        ]);
    }

    private function renderItems($items)
    {
        $orders = [];
        if ($items == null) {
            return $orders;
        }
        foreach ($items as $item) {
            if (count($item['orders']) == 0) {
                continue;
            }
            $order = [
                'order_sn' => $item['orders'][0]['order_sn'],
                'order_status' => $item['orders'][0]['display_order_status'],
                'purchase_time' => Helper::convertTimestampToDate($item['purchase_time']),
                'checkout_complete_time' => $item['checkout_complete_time'] > 0 ? Helper::convertTimestampToDate($item['checkout_complete_time']) : null,
                'utm_content' => $item['utm_content'],
                'estimated_total_commission' => Helper::shoppePrice($item['estimated_total_commission']),
                'commission' => Helper::formatCurrencyVND(($item['estimated_total_commission'] / 100000) * $this->shopeeService->category->Discount)
            ];
            array_push($orders, $order);
        }
        return $orders;
    }
}
