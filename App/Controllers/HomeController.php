<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Services\BlogServices\BlogService;
use App\Services\CategoryServices\CategoryService;
use App\Services\Common\AlertSession;
use App\Services\Common\Helper;
use App\Services\Identities\UserServices\UserService;
use App\Services\LazadaServices\LazadaService;
use App\Services\ShopeeServices\ShopeeService;

class HomeController extends Controller
{
    private ShopeeService $shopeeService;
    private CategoryService $categoryService;
    private LazadaService $lazadaService;
    private BlogService $blogService;
    private UserService $userService;
    public function __construct()
    {
        $this->shopeeService = new ShopeeService();
        $this->categoryService = new CategoryService();
        $this->lazadaService = new LazadaService();
        $this->blogService = new BlogService();
        $this->userService = new UserService();
        parent::__construct();
    }
    public function Index()
    {
        $categories = $this->categoryService->GetAll();
        $pages = $this->pageService->GetAllIsMenu(0);

        $topUsers = $this->userService->GetTopUser(10);

        $this->View("Home.Index", [
            "title" => "Shopee | Lazada  Mua hàng hoàn tiền",
            'layout' => '_ClientLayout',
            'categories' => $categories,
            'pagesHome' => $pages,
            'topUsers' => $topUsers,
        ]);
    }

    public function Trang($slug = null, $id = null)
    {
        if (empty($slug) || empty($id)) {
            AlertSession::error('Trang không tồn tại');
            $this->View("Home.Index", [
                "title" => "Trang chủ",
                'layout' => '_ClientLayout'
            ]);
            return;
        }
        $trang = $this->pageService->GetById($id);
        if ($trang == null) {
            AlertSession::error('Trang không' . $slug . ' tồn tại');
            $this->View("Home.Index", [
                "title" => "Trang chủ",
                'layout' => '_ClientLayout'
            ]);
            return;
        }
        $this->View("Home.Trang", [
            "title" => $trang->Title,
            'layout' => '_ClientLayout',
            'trang' => $trang
        ]);
    }

    // search product shoppe
    public function ShoppeSearch($link = null)
    {
        if (empty($link)) {
            AlertSession::error('Link không hợp lệ');
            $this->View("Home.Index", [
                "title" => "Trang chủ",
                'layout' => '_ClientLayout'
            ]);
            return;
        }
        $product = $this->shopeeService->GetProduct($link);
        if ($product == null) {
            AlertSession::error('Không tìm thấy sản phẩm');
            $this->View("Home.Index", [
                "title" => "Trang chủ",
                'layout' => '_ClientLayout'
            ]);
            return;
        }
        $this->View("Home.Search", [
            "title" => "Tìm kiếm sản phẩm",
            'layout' => '_ClientLayout',
            'product' => $product
        ]);
    }

    // search product lazada
    public function LazadaSearch($id = null)
    {
        if (empty($id)) {
            AlertSession::error('Link không hợp lệ');
            $this->View("Home.Index", [
                "title" => "Trang chủ",
                'layout' => '_ClientLayout'
            ]);
            return;
        }
        $ids = [$id];
        $products = $this->lazadaService->GetProductById($ids);
        if ($products->code != "0") {
            AlertSession::error('Không tìm thấy sản phẩm');
            $this->View("Home.Index", [
                "title" => "Trang chủ",
                'layout' => '_ClientLayout'
            ]);
            return;
        }
        $product = $products->result->data[0];
        $product->totalCommissionAmount = Helper::formatCurrencyVND($product->totalCommissionAmount * $this->lazadaService->category->Discount);


        $this->View("Home.SearchLazada", [
            "title" => "Tìm kiếm sản phẩm",
            'layout' => '_ClientLayout',
            'product' => $product
        ]);
    }

    //#region Category

    public function Category($name = null, $id = null)
    {
        if (empty($id)) {
            AlertSession::error('Danh mục không tồn tại');
            $this->View("Home.Index", [
                "title" => "Trang chủ",
                'layout' => '_ClientLayout'
            ]);
            return;
        }
        $category = $this->categoryService->GetById($id);
        if ($category == null) {
            AlertSession::error('Danh mục không tồn tại');
            $this->View("Home.Index", [
                "title" => "Trang chủ",
                'layout' => '_ClientLayout'
            ]);
            return;
        }

        $this->View("Home.Category", [
            "title" => "Danh mục",
            'layout' => '_ClientLayout',
            'category' => $category
        ]);
    }

    public function Blog()
    {

        $blogs = $this->blogService->GetAll();
        $this->View("Home.Blog", [
            "title" => "Tin Tức",
            'layout' => '_ClientLayout',
            'blogs' => $blogs
        ]);
    }

    public function BlogDetail($slug = null, $id = null)
    {
        if (empty($slug) || empty($id)) {
            AlertSession::error('Tin tức không tồn tại');
            $this->View("Home.Blog", [
                "title" => "Tin Tức",
                'layout' => '_ClientLayout'
            ]);
            return;
        }
        $blog = $this->blogService->GetById($id);
        if ($blog == null) {
            AlertSession::error('Tin tức không tồn tại');
            $this->View("Home.Blog", [
                "title" => "Tin Tức",
                'layout' => '_ClientLayout'
            ]);
            return;
        }
        $this->View("Home.BlogDetail", [
            "title" => $blog->Title,
            'layout' => '_ClientLayout',
            'blog' => $blog
        ]);
    }

    public function RejectOrder()
    {
        $this->View("Home.RejectOrder", [
            "title" => "Lý do đơn hàng bị hủy",
            'layout' => '_ClientLayout',
        ]);

    }

    //#endregion Category
}
