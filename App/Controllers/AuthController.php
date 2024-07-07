<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\Common\AlertSession;
use App\Services\Common\Enums\EHttpMethod;
use App\Services\Common\Enums\ERole;
use App\Services\Common\Helper;
use App\Services\Common\Request;
use App\Services\Common\Response;
use App\Services\Common\Session;
use App\Services\Identities\UserServices\UserService;
use App\Services\Identities\UserTokenServices\UserTokenService;
use App\Services\MailServices\MailQuery;
use App\Services\MailServices\MailService;

class AuthController extends Controller
{
    private $userService;
    private $userTokenService;
    private $mailService;
    public function __construct()
    {
        $this->userService = new UserService();
        $this->userTokenService = new UserTokenService();
        $this->mailService = new MailService();
        parent::__construct();
    }
    public function Login()
    {
        if (Request::method(EHttpMethod::POST)) {
            $username = Request::post('Username');
            $password = Request::post('Password');
            if ($username == null || $password == null) {
                Response::badRequest([], 'Vui lòng nhập đầy đủ thông tin');
                return;
            }

            $user = $this->userService->Login($username, $password);
            if ($user == null) {
                Response::notFound([], 'Tài khoản hoặc Mật khẩu không đúng');
                return;
            }
            // check is active
            if ($user->IsActive == 0) {
                Response::unauthorized($user, 'Tài khoản chưa được xác thực');
                return;
            }

            Session::set('user', $user);
            Session::set('role', $user->Role);
            Response::success([
                'userId' => $user->Id,
                'role' => $user->Role,
            ], 'Đăng nhập thành công');
            return;
        }
        $this->render('Auth.Login', '_AuthenLayout', ['title' => 'Login']);
    }
    public function Register()
    {
        if (Request::method("POST")) {
            // Google Recaptcha v2
            $token = Request::post('Token');
            $validCaptcha = Helper::ReCAPTCHA($token, $this->config::GoogleRecaptcha_SECRET);
            if (!$validCaptcha) {
                Response::badRequest([], 'Captcha không hợp lệ');
                return;
            }

            $user = [
                'Username' => Request::post('Username'),
                'Password' => Request::post('Password'),
                'ConfirmPassword' => Request::post('ConfirmPassword'),
                'Email' => Request::post('Email'),
            ];
            // check username is type username
            if (!Helper::isUsername($user['Username'])) {
                Response::badRequest([], 'Tên tài khoản không hợp lệ! ví dụ: nguyenvana123, trangthi123');
                return;
            }

            // check email exist
            $userExist = $this->userService->GetByEmail($user['Email']);
            if ($userExist != null) {
                // check user is active
                if ($userExist->IsActive == 1) {
                    Response::badRequest([], 'Email đã tồn tại');
                    return;
                }
                // check user is not active
                if ($userExist->IsActive == 0) {
                    $userSave = [
                        'Username' => Request::post('Username'),
                        'Password' => Request::post('Password'),
                        'Email' => Request::post('Email'),
                        'Role' => ERole::Member,
                        'IsActive' => 0,
                    ];
                    // remove user exist
                    $this->userService->Delete($userExist->Id, $this->userService->tableName);
                    $result = $this->userService->Register($userSave);
                    Response::success($userExist, 'Bạn sẽ được chuyển hướng đến trang xác thực');
                    return;
                }
            }

            $rules = [
                'Username' => 'required|min:3|max:100|unique:users,Username',
                'Email' => 'required|email|unique:users,Email',
                'Password' => 'required|min:8|max:100|password_strength'
            ];
            $messages = [
                'Username.required' => 'Bạn chưa nhập tên tài khoản',
                'Username.min' => 'Tên tài khoản phải có ít nhất từ 3 đến 100 ký tự',
                'Username.max' => 'Tên tài khoản phải nhỏ hơn 100 ký tự',
                'Username.unique' => 'Tên tài khoản đã tồn tại',
                'Email.required' => 'Bạn chưa nhập email',
                'Email.email' => 'Email không hợp lệ',
                'Email.unique' => 'Email đã tồn tại',
                'Password.required' => 'Bạn chưa nhập mật khẩu',
                'Password.min' => 'Mật khẩu phải có ít nhất từ 8 đến 100 ký tự',
                'Password.max' => 'Mật khẩu phải nhỏ hơn 100 ký tự',
                'Password.password_strength' => 'Mật khẩu phải chứa ít nhất một ký tự đặc biệt, một ký tự viết hoa và một số',
            ];
            if ($user['ConfirmPassword'] != $user['Password']) {
                Response::badRequest([], 'Mật khẩu không khớp');
                return;
            }

            if ($this->validator->validate($user, $rules, $messages)) {
                $userSave = [
                    'Username' => Request::post('Username'),
                    'Password' => Request::post('Password'),
                    'Email' => Request::post('Email'),
                    'Role' => ERole::Member,
                    'IsActive' => 0,
                ];

                $result = $this->userService->Register($userSave);
                if (!$result) {
                    Response::notFound([], 'Tài khoản đã tồn tại');
                    return;
                }
                // send code active
                $user = $this->userService->GetByEmail($user['Email']);
                $token = Helper::randomNumber(6);
                $userToken = [
                    'UserId' => $user->Id,
                    'Token' => $token,
                    // 15 minutes
                    'ExpiredTime' => date('Y-m-d H:i:s', strtotime('+15 minutes'))
                ];

                $result = $this->userTokenService->Add($userToken, $this->userTokenService->tableName);
                if (!$result) {
                    Response::badRequest([], 'Có lỗi xảy ra');
                    return;
                }
                // send mail
                $recipients = [
                    $user->Email,
                ];
                $body = "
                <span>Mã xác nhận của bạn là: <strong>$token</strong></span>
                <p>Mã xác nhận sẽ hết hạn trong vòng 15 phút</p>
                ";
                // get host
                $mailQuery = new MailQuery(Null, $recipients, ' Xác thực tài khoản', $body, []);
                $res = $this->mailService->SendMail($mailQuery);
                Response::success([], 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản');
                return;
            }
            $errors = $this->validator->getFormattedErrors();
            // join errors
            Response::badRequest([], $errors ? implode('<br>', $errors) : 'Có lỗi xảy ra');
            return;
        }

        $googleKey = $this->config::GoogleRecaptcha_KEY;
        $this->render('Auth.Register', '_AuthenLayout', ['title' => 'Đăng kí tài khoản mới', 'googleKey' => $googleKey]);
    }

    // Verify account
    public function Verify($username)
    {
        if (Request::method("POST")) {
            $token = Request::post('Token');
            $username = Request::post('Username');
            if ($token == null) {
                Response::notFound([], 'Vui lòng nhập mã xác nhận');
                return;
            }
            $userToken = $this->userTokenService->GetByToken($token);
            if ($userToken == null) {
                Response::notFound([], 'Mã xác nhận không tồn tại');
                return;
            }
            $user = $this->userService->GetById($userToken->UserId);
            if ($user == null) {
                Response::notFound([], 'Tài khoản không tồn tại');
                return;
            }
            // check account is active
            if ($user->IsActive == 1) {
                Response::unauthorized([], 'Tài khoản đã được xác thực');
                return;
            }
            // Check expired time
            $timeNow = date('Y-m-d H:i:s');
            if ($timeNow > $userToken->ExpiredTime) {
                Response::notFound([], 'Mã xác nhận đã hết hạn');
                return;
            }
            $data = [
                'IsActive' => 1,
            ];

            $result = $this->userService->Update($data, $user->Id, $this->userService->tableName);
            if (!$result) {
                Response::badRequest([], 'Có lỗi xảy ra');
                return;
            }
            $this->userTokenService->Delete($userToken->Id, $this->userTokenService->tableName);
            Response::success([], 'Xác thực tài khoản thành công');
            return;
        }
        if ($username == null) {
            AlertSession::Error('Tài khoản không tồn tại');
            $this->redirect('/auth/login');
            return;
        }
        // check user is active
        $user = $this->userService->GetByUsername($username);
        if ($user == null) {
            AlertSession::Error('Tài khoản không tồn tại');
            $this->redirect('/auth/login');
            return;
        }
        if ($user->IsActive == 1) {
            AlertSession::Error('Tài khoản đã được xác thực');
            $this->redirect('/auth/login');
            return;
        }
        $this->render('Auth.Verify', '_AuthenLayout', ['title' => 'Verify Account', 'username' => $username]);

    }

    // resend code active
    public function Resend()
    {
        if (Request::method("POST")) {
            $username = Request::post('Username');
            $user = $this->userService->GetByUsername($username);
            if ($user == null) {
                Response::notFound([], 'Tài khoản không tồn tại');
                return;
            }

            $userTokens = $this->userTokenService->GetByUserId($user->Id);
            // Get the current date
            $dateNow = date('Y-m-d');

            // Filter tokens created today
            $tokensToday = [];
            if ($userTokens != null) {
                $tokensToday = array_filter($userTokens, function ($item) use ($dateNow) {
                    return date('Y-m-d', strtotime($item->CreatedAt)) == $dateNow;
                });
            }

            // Count the tokens created today
            $count = count($tokensToday);

            // Check if there are 3 or more tokens
            if ($count >= 3) {
                // Calculate the remaining time until the user can try again
                $nextTryTime = date('H:i:s', strtotime(end($tokensToday)->CreatedAt) + 24*60*60);
                $remainingTime = date('H:i:s', strtotime($nextTryTime) - time());
                // to giờ phút giây
                $hour = date('H', strtotime($remainingTime));
                $minute = date('i', strtotime($remainingTime));
                $second = date('s', strtotime($remainingTime));

                // Send a bad request response with the remaining time
                Response::badRequest([], 'Vui lòng thử lại sau ' . $hour . ' giờ ' . $minute . ' phút ' . $second . ' giây');
                return;
            }

            $userToken = $userTokens[0] ?? null;
            if ($userToken != null) {
                // Check if the token is not older than 60 seconds
                $timeOver = date('Y-m-d H:i:s', strtotime('+60 seconds'));
                // Get the time the token was created
                $timeCreatedAt = $userToken->CreatedAt;

                // Convert both times to timestamps for comparison
                $timeOverTimestamp = strtotime($timeOver);
                $timeCreatedAtTimestamp = strtotime($timeCreatedAt);

                // Check if the token is older than 60 seconds
                if ($timeOverTimestamp < $timeCreatedAtTimestamp) {
                    Response::badRequest([], 'Vui lòng đợi 60 giây để gửi lại mã xác nhận');
                    return;
                }
            }


            // resend code active
            $token = Helper::randomNumber(6);
            $userToken = [
                'UserId' => $user->Id,
                'Token' => $token,
                // 15 minutes
                'ExpiredTime' => date('Y-m-d H:i:s', strtotime('+15 minutes'))
            ];

            $result = $this->userTokenService->Add($userToken, $this->userTokenService->tableName);
            if (!$result) {
                Response::badRequest([], 'Có lỗi xảy ra');
                return;
            }
            // send mail
            $recipients = [
                $user->Email,
            ];
            $body = "
                <span>Mã xác nhận của bạn là: <strong>$token</strong></span>
                <p>Mã xác nhận sẽ hết hạn trong vòng 15 phút</p>
            ";
            $mailQuery = new MailQuery(Null, $recipients, 'Xác thực tài khoản', $body, []);
            $res = $this->mailService->SendMail($mailQuery);
            Response::success([], 'Mã xác nhận đã được gửi vào email của bạn');
            return;
        }
    }


    public function ForgotPassword()
    {
        if (Request::method("POST")) {
            $email = Request::post('Email');
            $user = $this->userService->GetByEmail($email);
            if ($user == null) {
                Response::notFound([], 'Email không tồn tại');
                return;
            }
            $token = Helper::randomNumber(6);
            $userToken = [
                'UserId' => $user->Id,
                'Token' => $token,
                // 15 minutes
                'ExpiredTime' => date('Y-m-d H:i:s', strtotime('+15 minutes'))
            ];

            $result = $this->userTokenService->Add($userToken, $this->userTokenService->tableName);
            if (!$result) {
                Response::badRequest([], 'Có lỗi xảy ra');
                return;
            }
            // send mail
            $recipients = [
                $user->Email,
            ];
            $body = "
                <span>Mã xác nhận của bạn là: <strong>$token</strong></span>
                <p>Mã xác nhận sẽ hết hạn trong vòng 15 phút</p>
            ";
            $mailQuery = new MailQuery(Null, $recipients, 'Reset Password', $body, []);
            $res = $this->mailService->SendMail($mailQuery);
            if ($res['Success'] == true) {
                Response::success([], 'Vui lòng kiểm tra email để lấy lại mật khẩu');
                return;
            }
            Response::success([], $res['Message']);
            return;
        }
        $this->render('Auth.ForgotPassword', '_AuthenLayout', ['title' => 'Forgot Password']);
    }

    // Reset password
    public function ResetPassword()
    {
        if (Request::method("POST")) {
            $token = Request::post('Token');
            if ($token == null) {
                Response::notFound([], 'Vui lòng nhập mã xác nhận');
                return;
            }
            $userToken = $this->userTokenService->GetByToken($token);
            if ($userToken == null) {
                Response::notFound([], 'Mã xác nhận không tồn tại');
                return;
            }
            $user = $this->userService->GetById($userToken->UserId);
            if ($user == null) {
                Response::notFound([], 'Tài khoản không tồn tại');
                return;
            }
            // Check expired time
            $timeNow = date('Y-m-d H:i:s');
            if ($timeNow > $userToken->ExpiredTime) {
                Response::notFound([], 'Mã xác nhận đã hết hạn');
                return;
            }

            $password = Helper::randomString(8);
            $result = $this->userService->UpdatePassword($user->Id, $password);
            if (!$result) {
                Response::badRequest([], 'Có lỗi xảy ra');
                return;
            }
            $this->userTokenService->Delete($userToken->Id, $this->userTokenService->tableName);
            // send mail
            $recipients = [
                $user->Email,
            ];
            $body = "
                <span>Mật khẩu mới của bạn là: <strong>$password</strong></span>
                <p>Vui lòng đổi mật khẩu sau khi đăng nhập</p>
            ";
            $mailQuery = new MailQuery(Null, $recipients, 'New Password', $body, []);
            $res = $this->mailService->SendMail($mailQuery);
            if ($res['Success'] == true) {
                Response::success([], 'Mật khẩu mới đã được gửi vào email của bạn');
                return;
            }
            Response::success([], $res['Message']);
            return;
        }
        $this->render('Auth.ResetPassword', '_AuthenLayout', ['title' => 'Reset Password']);
    }
    public function Logout()
    {
        Session::destroy();
        $this->redirect('/');
    }
}