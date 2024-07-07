<?php

namespace App\Controllers;

use App\Controllers\Base\BaseController;
use App\Services\BankServices\BankService;
use App\Services\Common\Enums\EHttpMethod;
use App\Services\Common\Helper;
use App\Services\Common\Request;
use App\Services\Common\Response;
use App\Services\Identities\UserServices\UserService;
use App\Services\MailServices\MailQuery;
use App\Services\MailServices\MailService;
use App\Services\OrderServices\OrderService;
use App\Services\PaymentTransactionServices\PaymentTransactionService;

class ProfileController extends BaseController
{
    private $userService;
    private OrderService $orderService;
    private PaymentTransactionService $transactionService;
    private BankService $bankService;
    private $mailService;
    public function __construct()
    {
        $this->userService = new UserService();
        $this->orderService = new OrderService();
        $this->transactionService = new PaymentTransactionService();
        $this->mailService = new MailService();
        $this->bankService = new BankService();
        parent::__construct();
    }

    public function Index()
    {
        $user = $this->userService->GetByUsername($this->userLogin->Username);
        // banks
        $result = $this->bankService->GetAll();
        $this->View("Profile.Index", [
            "title" => "Trang cá nhân | " . $this->userLogin->Username,
            'layout' => '_ClientLayout',
            'user' => $user,
            'banks' => $result,
        ]);
    }

    public function Orders()
    {
        $user = $this->userService->GetByUsername($this->userLogin->Username);
        $this->View("Profile.Orders", [
            "title" => "Trang cá nhân | " . $this->userLogin->Username,
            'layout' => '_ClientLayout',
            'user' => $user,
        ]);
    }
    public function Transactions()
    {
        $user = $this->userService->GetByUsername($this->userLogin->Username);
        $this->View("Profile.Transactions", [
            "title" => "Trang cá nhân | " . $this->userLogin->Username,
            'layout' => '_ClientLayout',
            'user' => $user,
        ]);
    }
    public function HistoryTransactions()
    {
        $user = $this->userService->GetByUsername($this->userLogin->Username);
        $this->View("Profile.HistoryTransactions", [
            "title" => "Trang cá nhân | " . $this->userLogin->Username,
            'layout' => '_ClientLayout',
            'user' => $user,
        ]);
    }


    //  Update user info
    public function ChangeInfo()
    {
        if (Request::method("POST")) {
            $user = $this->userService->GetByUsername($this->userLogin->Username);
            if (!$user) {
                Response::notFound([], 'Tài khoản không tồn tại', 404);
                return;
            }


            $data = [
                'FullName' => Request::post('FullName'),
                'Phone' => Request::post('Phone'),
                'Avatar' => Request::post('Avatar') ?? 'default.jpg',
                'NumberBank' => Request::post('NumberBank'),
                'NameBank' => Request::post('NameBank'),
            ];
            $rules = [
                'FullName' => 'required|min:2|max:100',
                'Phone' => 'required',
                'NumberBank' => 'required',
                'NameBank' => 'required',
            ];
            $messages = [
                'FullName.required' => 'Bạn chưa nhập Tên tài khoản',
                'FullName.min' => 'Tên tài khoản có ít nhất từ 2 đến 100 ký tự',
                'FullName.max' => 'Tên tài khoản có ít nhất từ 2 đến 100 ký tự',
                'Phone.required' => 'Bạn chưa nhập Số điện thoại',
                'NumberBank.required' => 'Bạn chưa nhập Số tài khoản ngân hàng',
                'NameBank.required' => 'Bạn chưa nhập Tên ngân hàng',
            ];
            if ($this->validator->validate($data, $rules, $messages)) {
                // convert to uppercase and  bỏ dấu tiếng việt
                $data['FullName'] = strtoupper(Helper::remove_vietnamese_diacritics($data['FullName']));
                $result = $this->userService->Update($data, $user->Id, $this->userService->tableName);
                if ($result) {


                    Response::success([], 'Cập Nhật Tài Khoản Thành Công', 200);
                    if($user->NumberBank != $data['NumberBank'] || $user->NameBank != $data['NameBank']) {
                        // send mail
                        $recipients = [
                            $user->Email,
                        ];
                        // web1: https://www.messenger.com/t/195838256942518
                        // web2: https://www.messenger.com/t/296832190183614

                        $bank = $this->bankService->GetByCode($data['NameBank']);
                        $nameBank = $bank->Name;
                        $numberBank = $data['NumberBank'];
                        $body = "
                            <strong style='color: red;'>Cảnh Báo: </strong> <br>
                            <p>
                                Thông tin tài khoản ngân hàng của bạn đã được thay đổi. 
                                Nếu bạn không thực hiện thay đổi này, vui lòng liên hệ với chúng tôi ngay lập tức.
                            </p>
                            <p>
                                <strong>Thông Tin Liên Hệ:</strong> <br>
                                <strong>Facebook:</strong> <a href='https://www.messenger.com/t/296832190183614'>Click me</a> <br>
                            </p>
                            <p>
                                <strong>Thông Tin Tài Khoản Bị Thay:</strong> <br>
                                <strong>Tên Ngân Hàng:</strong> $nameBank <br>
                                <strong>Số Tài Khoản:</strong> $numberBank
                            </p>
                        ";
                        $mailQuery = new MailQuery(Null, $recipients, '[Cảnh báo] Thay đổi thông tin', $body, []);
                        $this->mailService->SendMail($mailQuery);
                    }
                    return;
                }
                Response::badRequest([], 'Cập Nhật Tài Khoản Thất Bại', 400);
                return;
            }
            $errors = $this->validator->getFormattedErrors();
            // join errors
            Response::badRequest([], $errors ? implode('<br>', $errors) : 'Có lỗi xảy ra');
            return;
        }
    }

    // change password
    public function ChangePassword()
    {
        if (Request::method("POST")) {
            $user = $this->userService->Login($this->userLogin->Username, Request::post('PasswordOld'));
            if (!$user) {
                Response::notFound([], 'Mật khẩu cũ không đúng', 404);
                return;
            }
            
            $data = [
                'PasswordOld' => Request::post('PasswordOld'),
                'PasswordNew' => Request::post('PasswordNew')
            ];

            $rules = [
                'PasswordOld' => 'required|min:6|max:100',
                'PasswordNew' => 'required|min:6|max:100',
            ];
            $messages = [
                'PasswordOld.required' => 'Bạn chưa nhập Mật khẩu cũ',
                'PasswordOld.min' => 'Mật khẩu cũ có ít nhất từ 6 đến 100 ký tự',
                'PasswordOld.max' => 'Mật khẩu cũ có ít nhất từ 6 đến 100 ký tự',
                'PasswordNew.required' => 'Bạn chưa nhập Mật khẩu mới',
                'PasswordNew.min' => 'Mật khẩu mới có ít nhất từ 6 đến 100 ký tự',
                'PasswordNew.max' => 'Mật khẩu mới có ít nhất từ 6 đến 100 ký tự',
            ];
            if ($this->validator->validate($data, $rules, $messages)) {
                $result = $this->userService->UpdatePassword($user->Id, $data['PasswordNew']);
                if ($result) {
                    Response::success([], 'Cập Nhật Mật Khẩu Thành Công', 200);
                    return;
                }
                Response::badRequest([], 'Cập Nhật Mật Khẩu Thất Bại', 400);
                return;
            }
            $errors = $this->validator->getFormattedErrors();
            // join errors
            Response::badRequest([], $errors ? implode('<br>', $errors) : 'Có lỗi xảy ra');
            return;
        }
    }

    // API POST: /profile/orders
    public function GetOrders()
    {
        if(Request::method(EHttpMethod::POST)) {
            $pageIndex = Request::post('PageIndex');
            $filter = [
                'Code' => Request::post('Code'),
                'Type' => Request::post('Type'),
            ];
            $pageSize = 10;
            $userId = $this->userLogin->Id;
            $orders = $this->orderService->GetByUserId($pageIndex, $pageSize, $userId, $filter);
            $pageIndex = intval($pageIndex-1) * $pageSize;
            Response::success($orders, 'Lấy Dữ Liệu Thành Công', 200,$pageIndex);
            return;
        }
        Response::methodNotAllowed();
    }

    // API POST: /profile/transactions
    public function GetTransactions()
    {
        if(Request::method(EHttpMethod::POST)) {
            $pageIndex = Request::post('PageIndex');
            $filter = [
                'Status' => Request::post('Status'),
            ];
            $type = Request::post('Type');
            $pageSize = 10;
            $userId = $this->userLogin->Id;
            $transactions = $this->transactionService->GetByUserId($pageIndex, $pageSize, $userId, $filter,$type);
            Response::success($transactions, 'Lấy Dữ Liệu Thành Công', 200,($pageIndex - 1) * $pageSize);
            return;
        }
        Response::methodNotAllowed();
    }
}
