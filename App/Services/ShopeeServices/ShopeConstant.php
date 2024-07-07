<?php 
namespace App\Services\ShopeeServices;

use App\Models\Order;

 class ShopeConstant {
    const Host = 'https://affiliate.shopee.vn/';

    // Param: list_type=0&sort_type=1&page_offset=0&page_limit=20&client_type=1
    const Products = "api/v3/offer/product/list";
    // Param: item_id=123456
    const Product = "api/v3/offer/product";
   // Param: link=https://shopee.vn/...
    const GetLink = "api/v3/gql";
    const Order = "api/v3/report/list";
 }