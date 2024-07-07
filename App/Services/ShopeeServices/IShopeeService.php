<?php
namespace App\Services\ShopeeServices;

interface IShopeeService
{

    // $page_offset = 0, $list_type = 0, $sort_type = 1, $client_type = 1
    public function GetProducts($page_offset, $list_type, $sort_type, $client_type);
    public function GetProduct($item_id);
    public function GetLink($link, $userId);
    public function RenderIdByLink($link);
    public function GetFinalLink($link);
    public function CreateLink($link, $userId);

    public function GetOrders($page_size, $page_num, $purchase_time_s, $purchase_time_e, $display_order_status = null, $subId = null, $version = 1, $order_id = null);
}