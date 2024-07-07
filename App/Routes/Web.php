<?php
// App/Routes/Web.php
use App\Core\Router;
define('ADMIN_PATH', '/admin');
$router = new Router();

#region Auth
// ==================== Auth Client ====================
$router->get('/auth/login', 'AuthController@Login');
$router->post('/auth/login', 'AuthController@Login');

$router->get('/auth/register', 'AuthController@Register');
$router->post('/auth/register', 'AuthController@Register');

$router->get('/auth/forgot-password', 'AuthController@ForgotPassword');
$router->post('/auth/forgot-password', 'AuthController@ForgotPassword');

$router->get('/auth/reset-password', 'AuthController@ResetPassword');
$router->post('/auth/reset-password', 'AuthController@ResetPassword');

$router->get('/auth/verify/{email}', 'AuthController@Verify');
$router->post('/auth/verify/{email}', 'AuthController@Verify');
$router->post('/auth/resend', 'AuthController@Resend');


$router->get('/auth/logout', 'AuthController@Logout');
#endregion Auth

#region Client Site
$router->get('/', 'HomeController@Index');
$router->post('/render-link', 'HomeController@RenderLink');
$router->get('/san-pham', 'HomeController@ShoppeSearch');
$router->get('/san-pham/{link}', 'HomeController@ShoppeSearch');
$router->get('/san-pham/lazada/{id}', 'HomeController@LazadaSearch');

$router->get('/blog', 'HomeController@Blog');
$router->get('/blog/{slug}-{id}', 'HomeController@BlogDetail');
$router->get('/danh-muc/{name}-{id}', 'HomeController@Category');
$router->get('/reject-order', 'HomeController@RejectOrder');


$router->get('/profile', 'ProfileController@Index');
$router->post('/profile/change-info', 'ProfileController@ChangeInfo');

$router->get('/profile/change-password', 'ProfileController@Index');
$router->post('/profile/change-password', 'ProfileController@ChangePassword');
$router->get('/profile/cancel-account', 'ProfileController@Index');

$router->get('/profile/orders', 'ProfileController@Orders');
$router->post('/profile/orders', 'ProfileController@GetOrders');

$router->get('/profile/transactions', 'ProfileController@Transactions');
$router->get('/profile/history-transactions', 'ProfileController@HistoryTransactions');
$router->post('/profile/transactions', 'ProfileController@GetTransactions');

$router->get('/trang/{slug}-{id}', 'HomeController@Trang');

#endregion Client Site

#region Admin
$router->get(ADMIN_PATH.'', 'DashboardController@Index');
$router->get(ADMIN_PATH.'/page/{page}', 'DashboardController@Index');
$router->get(ADMIN_PATH.'/dashboard', 'DashboardController@Index');
$router->get(ADMIN_PATH.'/dashboard/page/{page}', 'DashboardController@Index');



// User
$router->get(ADMIN_PATH.'/user', 'UserController@Index');
$router->get(ADMIN_PATH.'/user/page/{page}', 'UserController@Index');

$router->get(ADMIN_PATH.'/user/not-active', 'UserController@AccountInActive');
$router->get(ADMIN_PATH.'/user/not-active/page/{page}', 'UserController@AccountInActive');
$router->get(ADMIN_PATH.'/user/delete-account-not-active', 'UserController@RemoveAllNotActive');

$router->get(ADMIN_PATH.'/user/edit/{id}', 'UserController@Edit');
$router->post(ADMIN_PATH.'/user/edit/{id}', 'UserController@Edit');

$router->get(ADMIN_PATH.'/user/create', 'UserController@Create');
$router->post(ADMIN_PATH.'/user/create', 'UserController@Create');

$router->delete(ADMIN_PATH.'/user/delete/{id}', 'UserController@Delete');
$router->post(ADMIN_PATH.'/user/search', 'UserController@Search');
$router->post(ADMIN_PATH.'/user/export-excel', 'UserController@ExportExcel');
$router->post(ADMIN_PATH.'/user/export-excel-vpbank', 'UserController@ExportVPBankExcel');
$router->post(ADMIN_PATH.'/user/export-excel-bidv', 'UserController@ExportBIDVExcel');
$router->post(ADMIN_PATH.'/user/export-excel-tcb', 'UserController@ExportTCBExcel');

$router->post(ADMIN_PATH.'/user/reset-money', 'UserController@ResetMoney');
$router->post(ADMIN_PATH.'/user/add-money', 'UserController@AddMoney');





// Setting
$router->get(ADMIN_PATH.'/setting', 'SettingController@Index');
$router->get(ADMIN_PATH.'/setting/page/{page}', 'SettingController@Index');

$router->get(ADMIN_PATH.'/setting/create', 'SettingController@Create');
$router->post(ADMIN_PATH.'/setting/create', 'SettingController@Create');

$router->get(ADMIN_PATH.'/setting/edit/{id}', 'SettingController@Edit');
$router->post(ADMIN_PATH.'/setting/edit/{id}', 'SettingController@Edit');

$router->delete(ADMIN_PATH.'/setting/delete/{id}', 'SettingController@Delete');

// Auth Admin
$router->get(ADMIN_PATH.'/auth/login', 'AuthController@Login');
$router->post(ADMIN_PATH.'/auth/login', 'AuthController@Login');

$router->get(ADMIN_PATH.'/auth/register', 'AuthController@Register');
$router->post(ADMIN_PATH.'/auth/register', 'AuthController@Register');

$router->get(ADMIN_PATH.'/auth/forgot-password', 'AuthController@ForgotPassword');
$router->post(ADMIN_PATH.'/auth/forgot-password', 'AuthController@ForgotPassword');

$router->get(ADMIN_PATH.'/auth/reset-password', 'AuthController@ResetPassword');
$router->post(ADMIN_PATH.'/auth/reset-password', 'AuthController@ResetPassword');

$router->get(ADMIN_PATH.'/auth/logout', 'AuthController@Logout');

// Category
$router->get(ADMIN_PATH.'/category', 'CategoryController@Index');
$router->get(ADMIN_PATH.'/category/page/{page}', 'CategoryController@Index');

$router->get(ADMIN_PATH.'/category/edit/{id}', 'CategoryController@Edit');
$router->post(ADMIN_PATH.'/category/edit/{id}', 'CategoryController@Edit');

$router->get(ADMIN_PATH.'/category/create', 'CategoryController@Create');
$router->post(ADMIN_PATH.'/category/create', 'CategoryController@Create');

$router->delete(ADMIN_PATH.'/category/delete/{id}', 'CategoryController@Delete');

// Order
$router->get(ADMIN_PATH.'/order', 'OrderController@Index');
$router->get(ADMIN_PATH.'/order/page/{page}', 'OrderController@Index');

$router->get(ADMIN_PATH.'/order/create', 'OrderController@Create');
$router->post(ADMIN_PATH.'/order/create', 'OrderController@Create');

$router->delete(ADMIN_PATH.'/order/delete/{id}', 'OrderController@Delete');
$router->post(ADMIN_PATH.'/order/search', 'OrderController@Search');
$router->post(ADMIN_PATH.'/order/refund', 'OrderController@RefundOrder');

// payment transaction
$router->get(ADMIN_PATH.'/payment-transaction', 'PaymentTransactionController@Index');
$router->get(ADMIN_PATH.'/payment-transaction/page/{page}', 'PaymentTransactionController@Index');
$router->get(ADMIN_PATH.'/history-transaction', 'PaymentTransactionController@HistoryPayment');
$router->get(ADMIN_PATH.'/history-transaction/page/{page}', 'PaymentTransactionController@HistoryPayment');


$router->get(ADMIN_PATH.'/payment-transaction/create', 'PaymentTransactionController@Create');
$router->post(ADMIN_PATH.'/payment-transaction/create', 'PaymentTransactionController@Create');

$router->delete(ADMIN_PATH.'/payment-transaction/delete/{id}', 'PaymentTransactionController@Delete');
$router->post(ADMIN_PATH.'/payment-transaction/search', 'PaymentTransactionController@Search');

$router->post(ADMIN_PATH.'/payment-transaction/export-excel', 'PaymentTransactionController@ExportExcel');
$router->post(ADMIN_PATH.'/payment-transaction/approve', 'PaymentTransactionController@Approve');
$router->post(ADMIN_PATH . '/payment-transaction/approve-all', 'PaymentTransactionController@ApproveAll');

// Tools
$router->get(ADMIN_PATH.'/tools/shopee', 'ToolsController@Shopee');
$router->post(ADMIN_PATH.'/tools/shopee', 'ToolsController@Shopee');

$router->get(ADMIN_PATH.'/tools/tiktok', 'ToolsController@TikTokShop');
$router->post(ADMIN_PATH.'/tools/tiktok', 'ToolsController@TikTokShop');

$router->get(ADMIN_PATH.'/tools/lazada', 'ToolsController@Lazada');
$router->post(ADMIN_PATH.'/tools/lazada', 'ToolsController@Lazada');

// Page Trang
$router->get(ADMIN_PATH.'/trang', 'PageController@Index');
$router->get(ADMIN_PATH.'/trang/page/{page}', 'PageController@Index');

$router->get(ADMIN_PATH.'/trang/edit/{id}', 'PageController@Edit');
$router->post(ADMIN_PATH.'/trang/edit/{id}', 'PageController@Edit');

$router->get(ADMIN_PATH.'/trang/create', 'PageController@Create');
$router->post(ADMIN_PATH.'/trang/create', 'PageController@Create');

$router->delete(ADMIN_PATH.'/trang/delete/{id}', 'PageController@Delete');

// Notification
$router->get(ADMIN_PATH.'/notification', 'NotificationController@Index');
$router->get(ADMIN_PATH.'/notification/page/{page}', 'NotificationController@Index');

$router->get(ADMIN_PATH.'/notification/edit/{id}', 'NotificationController@Edit');
$router->post(ADMIN_PATH.'/notification/edit/{id}', 'NotificationController@Edit');

$router->get(ADMIN_PATH.'/notification/create', 'NotificationController@Create');
$router->post(ADMIN_PATH.'/notification/create', 'NotificationController@Create');

$router->delete(ADMIN_PATH.'/notification/delete/{id}', 'NotificationController@Delete');

// Blog
$router->get(ADMIN_PATH.'/blog', 'BlogController@Index');
$router->get(ADMIN_PATH.'/blog/page/{page}', 'BlogController@Index');

$router->get(ADMIN_PATH.'/blog/edit/{id}', 'BlogController@Edit');
$router->post(ADMIN_PATH.'/blog/edit/{id}', 'BlogController@Edit');

$router->get(ADMIN_PATH.'/blog/create', 'BlogController@Create');
$router->post(ADMIN_PATH.'/blog/create', 'BlogController@Create');

$router->delete(ADMIN_PATH.'/blog/delete/{id}', 'BlogController@Delete');

// Bank
$router->get(ADMIN_PATH.'/bank', 'BankController@Index');
$router->get(ADMIN_PATH.'/bank/page/{page}', 'BankController@Index');

$router->get(ADMIN_PATH.'/bank/edit/{id}', 'BankController@Edit');
$router->post(ADMIN_PATH.'/bank/edit/{id}', 'BankController@Edit');

$router->get(ADMIN_PATH.'/bank/create', 'BankController@Create');
$router->post(ADMIN_PATH.'/bank/create', 'BankController@Create');

$router->delete(ADMIN_PATH.'/bank/delete/{id}', 'BankController@Delete');


#endregion Admin

#region Shoppe Api

$router->get('/api/shopee/products', 'ShopeeController@Index');
$router->get('/api/shopee/products/page/{page_offset}-{list_type}-{sort_type}-{client_type}', 'ShopeeController@Index');
$router->post('/api/shopee/products/get-link', 'ShopeeController@GetLink');
$router->get('/api/shopee/products/get-product/{item_id}', 'ShopeeController@GetProduct');
$router->post('/api/shopee/render-link', 'ShopeeController@RenderIdByLink');
$router->post('/api/shopee/get-final-link', 'ShopeeController@GetFinalLink');

$router->post('/api/shopee/create-link','ShopeeController@CreateLink');

$router->get('/api/shopee/auto-check-order','ShopeeController@AutoCheckOrder');
$router->get('/api/shopee/auto-check-order/{isAll}','ShopeeController@AutoCheckOrder');
$router->get('/api/shopee/auto-check-order-cancel', 'ShopeeController@AutoCheckOrderCancel');
$router->get('/api/shopee/auto-check-order-pending', 'ShopeeController@AutoCheckOrderPending');



$router->get('/api/shopee/auto-add-payment-transaction','ShopeeController@AutoAddPaymentTransaction');
$router->get('/api/shopee/auto-add-money','ShopeeController@AutoAddMoney');


#endregion Shoppe Api

#region Lazada Api

$router->get('/api/lazada/offers', 'LazadaController@Offers');
$router->post('/api/lazada/product-feed', 'LazadaController@ProductFeed');
$router->get('/api/lazada/test', 'LazadaController@Test');
$router->get('/api/lazada/tracking-link/{productId}', 'LazadaController@TrackingLinkByProductId');
$router->post('/api/lazada/get-by-link', 'LazadaController@GetProductByLink');

$router->get('/api/lazada/auto-check-order','LazadaController@AutoCheckOrder');
$router->get('/api/lazada/auto-check-order/{isAll}','LazadaController@AutoCheckOrder');
$router->get('/api/lazada/auto-add-payment-transaction','LazadaController@AutoAddPaymentTransaction');
$router->get('/api/lazada/auto-add-money','LazadaController@AutoAddMoney');


#endregion Lazada Api

$router->run();