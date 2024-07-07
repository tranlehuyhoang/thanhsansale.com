<div class="row g-0">
    <div class="col-xxl-3 col-lg-4 col-md-5">
        <div class="auth-full-page-content d-flex p-sm-5 p-4">
            <div class="w-100">
                <div class="d-flex flex-column h-100">
                    <div class="mb-4 mb-md-5 text-center">
                        <a href="<?= ADMIN_PATH ?>" class="d-block auth-logo">
                            <img src="<?= $settingClient->Logo ?>" alt="" height="28"> <span class="logo-txt"><?= $settingClient->SiteName ?></span>
                        </a>
                    </div>
                    <div class="auth-content my-auto">
                        <div class="text-center">
                            <h5 class="mb-0">Welcome Back !</h5>
                            <p class="text-muted mt-2">Tạo tài khoản mới</p>
                        </div>
                        <form class="mt-4 pt-2" action="#">
                            <div class="mb-3">
                                <label class="form-label">Tên tài khoản</label>
                                <input type="text" class="form-control" id="Username" placeholder="Enter username" name="Username">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <br>
                                <small class="text-danger">
                                    Nhập đúng email để nhập OTP đăng ký tài khoản và rút tiền
                                </small>
                                <input type="email" class="form-control" id="Email" placeholder="Enter Email" name="Email">
                            </div>
                            <div class="mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <label class="form-label">Password</label>
                                        <small class="text-danger">
                                            <ul>
                                                <li>Ít nhất 8 ký tự</li>
                                                <li>Ít nhất 1 chữ cái viết hoa</li>
                                                <li>Ít nhất 1 chữ cái viết thường</li>
                                                <li>Ít nhất 1 số</li>
                                            </ul>
                                        </small>
                                    </div>
                                </div>
                                <div class="input-group auth-pass-inputgroup">
                                    <input type="password" class="form-control" id="Password" name="Password" placeholder="Enter password" aria-label="Password" aria-describedby="password-addon">
                                    <button class="btn btn-light shadow-none ms-0" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nhập lại mật khẩu</label>
                                <input type="password" class="form-control" id="ConfirmPassword" name="ConfirmPassword" placeholder="Enter password">
                            </div>
                            <div class="row mb-4">
                                <div class="col">
                                    <div class="form-check">
                                        <label class="form-check-label" for="remember-check">
                                            Đã có tài khoản?
                                        </label>
                                        <a href="/auth/login" class=" mr-5">Đăng nhập</a>
                                    </div>
                                </div>

                            </div>
                            <div class="mb-3">
                                <div class="g-recaptcha" data-sitekey="<?= $googleKey ?>">
                                </div>
                                <button onclick="register()" class="btn btn-primary w-100 waves-effect waves-light" type="button">Đăng kí</button>
                            </div>
                        </form>
                    </div>
                    <div class="mt-4 mt-md-5 text-center">
                        <p class="mb-0">©
                            <script>
                                document.write(new Date().getFullYear())
                            </script> <?= $settingClient->Copyright ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- end auth full page content -->
    </div>
    <!-- end col -->
    <div class="col-xxl-9 col-lg-8 col-md-7">
        <div class="auth-bg pt-md-5 p-4 d-flex">
            <div class="bg-overlay bg-primary"></div>
        </div>
    </div>
    <!-- end col -->
</div>
<script>
    //  Register
    function register() {
        // class name
        var username = $("#Username").val();
        var password = $("#Password").val();
        var confirmPassword = $("#ConfirmPassword").val();
        var email = $("#Email").val();
        // google recaptcha
        var token = grecaptcha.getResponse();

        const data = {
            Username: username,
            Password: password,
            ConfirmPassword: confirmPassword,
            Email: email,
            Token: token
        };

        grecaptcha.ready(function() {
            $.ajax({
                url: "/auth/register",
                method: "POST",
                data: data,
                beforeSend: function() {
                    Swal.fire({
                        title: "Vui lòng chờ...",
                        onBeforeOpen: () => {
                            Swal.showLoading();
                        },
                        onAfterClose: () => {},
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                    });
                },
                success: function(res) {
                    if (res.success == true) {
                        Swal.fire("Đăng ký thành công!", res.message, "success");
                        setTimeout(function() {
                            window.location.href = "/auth/verify/" + username;
                        }, 1500);
                    }
                    else if (res.statusCode == 401) {
                        Swal.fire("Đăng ký thất bại!", res.message, "error");
                        setTimeout(function() {
                            window.location.href = "/auth/verify/" + username;
                        }, 1000);
                    }
                    else {
                        //reset captcha
                        grecaptcha.reset();
                        Swal.fire("Đăng ký thất bại!", res.message, "error");
                    }
                },
                error: function(err) {
                    console.log(err);
                    Swal.fire({
                        icon: "error",
                        title: err.message,
                        showConfirmButton: false,
                        timer: 1500,
                    });
                },
            });
        });

    }
</script>