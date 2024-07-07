<?php

namespace App\Services\ShopeeServices;

use App\Models\Category;
use App\Services\CategoryServices\CategoryService;
use App\Services\Common\Helper;
use App\Services\HmzHttp;
use App\Services\ShopeeServices\IShopeeService;
use App\Services\ShopeeServices\ShopeConstant;

class ShopeeService implements IShopeeService
{

    public ShopeConstant $shoppeConst;
    private HmzHttp $httpClient;
    private CategoryService $categoryService;
    public Category $category;
    public function __construct()
    {
        $this->categoryService = new CategoryService();
        $this->category = $this->categoryService->GetById(1);
        if ($this->category == null) {
            $this->categoryService->Add([
                'Name' => 'Shopee',
                'Slug' => 'shopee',
                'Image' => '',
                'Config' => ''
            ], 'categories');
        }
        $this->shoppeConst = new ShopeConstant();
        $this->httpClient = new HmzHttp($this->category->Config);
    }
    /**
     *
     * @param mixed $link
     */
    public function GetLink($link, $userId)
    {
        //https://shopee.vn/universal-link/product/522043096/11940870006?utm_source=an_17374880151&amp;utm_medium=affiliates&amp;utm_campaign=-&amp;utm_content=----
        if (empty($link) || strpos($link, 'shopee.vn') === false || empty($userId)) {
            return null;
        }
        // change utm_content = user_id
        $link = str_replace('utm_content=----', 'utm_content=' . $userId, $link);
        return $link;
    }

    /**
     *
     * @param mixed $item_id
     */
    public function GetProduct($item_id)
    {
        $params = [
            'item_id' => $item_id
        ];
        $result = $this->httpClient->get(ShopeConstant::Host . ShopeConstant::Product, $params);
        $product = [];
        if ($result['msg'] == 'success') {

            $social_commission = $result['data']['commission_rate_detail']['seller_commission_rate'] + $result['data']['commission_rate_detail']['shopee_commission_detail']['social_media_check_out_base_exist_commission_rate'];
            $social_commission = $social_commission / 100000;
            $price = Helper::shoppePrice($result['data']['batch_item_for_item_card_full']['price'], false);
            $commission = $price * $social_commission;
            $social_commission = $commission > 50000 ? 50000 : $commission;

            $product = [
                "item_id" => $result['data']['item_id'],
                "long_link" => $result['data']['long_link'],
                "product_link" => $result['data']['product_link'],
                "name" => $result['data']['batch_item_for_item_card_full']['name'],
                "image" => $result['data']['batch_item_for_item_card_full']['image'] . '.webp',
                "images" => $result['data']['batch_item_for_item_card_full']['images'],
                "liked_count" => $result['data']['batch_item_for_item_card_full']['liked_count'],
                "product_offers" => $result['data']['similar_product_offers']['list'],
                "item_rating" => $result['data']['batch_item_for_item_card_full']['item_rating'],
                "shop_location" => $result['data']['batch_item_for_item_card_full']['shop_location'],
                "shop_name" => $result['data']['batch_item_for_item_card_full']['shop_name'],
                "shop_rating" => $result['data']['batch_item_for_item_card_full']['shop_rating'],
                "price" => Helper::formatCurrencyVND($price),
                "price_discount" => Helper::shoppePrice($result['data']['batch_item_for_item_card_full']['price_min_before_discount']),
                "discount" => $result['data']['batch_item_for_item_card_full']['discount'],
                "default_commission_rate" => $result['data']['commission_rate_detail']['default_commission_rate'] / 100000, // Hoa hong mac dinh
                "seller_commission_rate" => $result['data']['commission_rate_detail']['seller_commission_rate'] / 100000, // Hoa hong extra
                "shopee_commission_rate" => $result['data']['commission_rate_detail']['shopee_commission_rate'] / 100000, // Hoa tu shoppe
                "default_commission" => Helper::formatCurrency(Helper::currencyToNumber($result['data']['commission_rate']['default_commission'])*$this->category->Discount).' VNĐ', // Hoa hong mac dinh
                "commission_rate" => $this->category->Discount, // Hoa hong cua website
                "commission_return"=> Helper::formatCurrencyVND(Helper::shoppeReturn($result['data']['commission'])), // Hoa hong tra lai cho khach hang
                "commission" => Helper::formatCurrencyVND($social_commission* $this->category->Discount), // Hoa hong cua website
            ];
            return $product;
        }
        return null;
    }

    /**
     *
     * @param mixed $page_offset
     * @param mixed $list_type
     * @param mixed $sort_type
     * @param mixed $client_type
     */
    public function GetProducts($page_offset, $list_type, $sort_type, $client_type)
    {
        $params = [
            'list_type' => $list_type,
            'sort_type' => $sort_type,
            'page_offset' => $page_offset,
            'page_limit' => 8,
            'client_type' => $client_type
        ];
        $result = $this->httpClient->get(ShopeConstant::Host . ShopeConstant::Products, $params);
        //var_dump($result);
        $products = [];
        if ($result['msg'] == 'success') {
            $products = [
                "list" => $this->renderListProduct($result['data']['list']),
                "total_count" => $result['data']['total_count'],
                "page_offset" => $result['data']['page_offset'],
                "page_limit" => $result['data']['page_limit'],
            ];
            return $products;
        }
        return [];
    }

    private function renderListProduct($list)
    {
        $products = [];
        foreach ($list as $item) {
            $price  = Helper::shoppePrice($item['batch_item_for_item_card_full']['price'], false);
            $price_discount = Helper::shoppePrice($item['batch_item_for_item_card_full']['price_min_before_discount'], false);
            $default_commission_rate = Helper::shoppeCommission($item['default_commission_rate']);
            $seller_commission_rate = Helper::shoppeCommission($item['seller_commission_rate']);
            $commission = Helper::formatCurrency(($price * $default_commission_rate) * $this->category->Discount). ' VNĐ';
            $product = [
                'item_id' => $item['item_id'],
                'long_link' => $item['long_link'],
                'product_link' => $item['product_link'],
                'default_commission_rate' => $default_commission_rate,
                'seller_commission_rate' => $seller_commission_rate,

                "name" => $item['batch_item_for_item_card_full']['name'],
                "image" => $item['batch_item_for_item_card_full']['image'] . '.webp',
                "images" => $item['batch_item_for_item_card_full']['images'],
                "liked_count" => $item['batch_item_for_item_card_full']['liked_count'],
                "item_rating" => $item['batch_item_for_item_card_full']['item_rating'],
                "shop_location" => $item['batch_item_for_item_card_full']['shop_location'],
                "shop_name" => $item['batch_item_for_item_card_full']['shop_name'],
                "shop_rating" => $item['batch_item_for_item_card_full']['shop_rating'],
                "price" => Helper::formatCurrencyVND($price),
                "price_discount" => Helper::formatCurrencyVND($price_discount),
                "discount" => $item['batch_item_for_item_card_full']['discount'],

                "commission_rate" => $this->category->Discount,
                "commission" => $commission

            ];
            array_push($products, $product);
        }
        return $products;
    }
    /**
     *
     * @param mixed $link
     * @return null | string
     */
    public function RenderIdByLink($link)
    {
        if (empty($link) || strpos($link, 'shopee.vn') === false) {
            return null;
        }
        // check link is match: https://shopee.vn/product/961376349/15097230635
        // Get product id: 15097230635
        // Extract product id from link
        preg_match('/product\/\d+\/(\d+)/', $link, $matches);
        if (isset($matches[1])) {
            $productId = $matches[1];
            return $productId;
        }


        // check link is: https://shopee.vn/product/37266383/19185545328?d_id=9412d&amp;uls_trackid=4vjo7pdh00ab&amp;utm_content=3YvQDuManhvEJ4YkqVk2K8CJZ1ZH
        // Get product id: 19185545328
        // Extract product id from link
        preg_match('/product\/\d+\/(\d+)\?/', $link, $matches);
        if (isset($matches[1])) {
            $productId = $matches[1];
            return $productId;
        }

        $link = $link . "?url=hmzteam.com";
        //https://shopee.vn/G%C4%83ng-Tay-%C4%91i-ph%C6%B0%E1%BB%A3t-511-Ng%C3%B3n-C%E1%BB%A5t-T%E1%BA%ADp-Gym-L%C3%A1i-xe-%C4%90i-ph%C6%B0%E1%BB%A3t-G%C4%83ng-Tay-Bao-Tay-Nam-C%E1%BB%A5t-Ng%C3%B3n-Ch%E1%BB%91ng-Gi%C3%B3-S%C6%B0%C6%A1ng-%C4%90i-Xe-M%C3%A1y-i.740333928.24602751299?publish_id=&sp_atk=b6dbcff2-6ded-48da-a882-79dbf4bf4051&xptdk=b6dbcff2-6ded-48da-a882-79dbf4bf4051
        // Get product id: i.740333928.24602751299
        // Extract product id from link
        preg_match('/i\.(\d+\.\d+)\?/', $link, $matches);
        if (isset($matches[1])) {
            $productId = explode('.', $matches[1])[1];
            return $productId;
        }
        return null;
    }
    /**
     *
     * @param mixed $link
     */
    public function GetFinalLink($link)
    {
        // check link is: https://s.shopee.vn/AUY3S8Z8cL has s.shopee.vn
        $matches = [];
        preg_match('/s\.shopee\.vn/', $link, $matches);
        if (isset($matches[0])) {
            $result = $this->httpClient->get_final_url($link);
            return $result;
        }
        $result = $this->httpClient->get_final_url($link);
        if ($result) {
            return $result;
        }
        return null;
    }
    /**
     *
     * @param mixed $page_size
     * @param mixed $page_num
     * @param mixed $purchase_time_s
     * @param mixed $purchase_time_e
     * @param mixed $version
     * @param mixed $order_sn
     */
    public function GetOrders($page_size, $page_num, $purchase_time_s, $purchase_time_e, $display_order_status = null, $subId = null, $version = 1, $order_sn = null)
    {
        $params = [
            'page_size' => $page_size,
            'page_num' => $page_num,
            // convert date to timestamp
            'purchase_time_s' => strtotime($purchase_time_s),
            'purchase_time_e' => strtotime($purchase_time_e),
            'sub_id' => $subId,
            'display_order_status' => $display_order_status,
            'version' => $version,
            'order_sn' => $order_sn
        ];
        $result = $this->httpClient->get(ShopeConstant::Host . ShopeConstant::Order, $params);
        $orders = [];
        if ($result['msg'] == 'success') {
            $orders = [
                "list" => $result['data']['list'],
                "total_count" => $result['data']['total_count'],
                "page_size" => $result['data']['page_size'],
                "page_num" => $result['data']['page_num'],
            ];
            return $orders;
        }
        return [];
    }

    /**
     *
     * @param mixed $link
     * @param mixed $userId
     */
    public function CreateLink($link, $userId)
    {
        // convert user id to string
        $userId = strval($userId);
        $operationName = "batchGetCustomLink";
        $params = [
            'operationName' => $operationName,
            'query' => '
                        query batchGetCustomLink($linkParams: [CustomLinkParam!], $sourceCaller: SourceCaller){
                        batchCustomLink(linkParams: $linkParams, sourceCaller: $sourceCaller){
                            shortLink
                            longLink
                            failCode
                        }
                        }
                        ',
            'variables' => [
                'linkParams' => [
                    'originalLink' => $link,
                    'advancedLinkParams' => [
                        'subId1' => $userId,
                    ]
                ],
                'sourceCaller' => 'CUSTOM_LINK_CALLER'
            ]
        ];
        $result = $this->httpClient->post(ShopeConstant::Host . ShopeConstant::GetLink, $params);
        $items = $result['data'];
        $items = $items['batchCustomLink'];

        if (count($items) > 0) {
            return $items;
        }
        return null;
    }
}
