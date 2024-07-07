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
                            <p class="text-muted mt-2">Đang đăng nhập vào hệ thống</p>
                        </div>
                        <form class="mt-4 pt-2" action="#">
                            <div class="mb-3">
                                <label class="form-label">Tên tài khoản hoặc Email</label>
                                <input type="text" class="form-control" id="Username" placeholder="Enter username or Email" name="Username">
                            </div>
                            <div class="mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <label class="form-label">Mật khẩu</label>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="">
                                            <a href="/auth/forgot-password" class="text-muted">Quên mật khẩu?</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="input-group auth-pass-inputgroup">
                                    <input type="password" class="form-control" id="Password" name="Password" placeholder="Enter password" aria-label="Password" aria-describedby="password-addon">
                                    <button class="btn btn-light shadow-none ms-0" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col">
                                    <div class="form-check">
                                        <label class="form-check-label" for="remember-check">
                                            Chưa có tài khoản
                                        </label>
                                        <a href="/auth/register" class=" mr-5">Tạo</a>
                                    </div>
                                </div>

                            </div>
                            <div class="mb-3">
                                <button onclick="login()" class="btn btn-primary w-100 waves-effect waves-light" type="button">Đăng nhập</button>
                            </div>
                        </form>
                    </div>
                    <div class="mt-4 mt-md-5 text-center">
                        <p class="mb-0">© <script>
                                document.write(new Date().getFullYear())
                            </script> <?= $settingClient->Copyright ?>
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
    function login() {
        // class name
        var username = $('#Username').val();
        var password = $('#Password').val();
        const data = {
            Username: username,
            Password: password
        }
        console.log(data);
        $.ajax({
            url: '/auth/login',
            method: 'POST',
            data: {
                Username: username,
                Password: password
            },
            success: function(res) {
                console.log(res);
                if (res.success == true) {
                    Swal.fire(
                        'Đăng nhập thành công!',
                        res.message,
                        'success'
                    )
                    // set local storage
         
                    setTimeout(function() {
                        if (res.data.role == 2)
                            window.location.href = '<?= ADMIN_PATH ?>';
                        else
                            window.location.href = '/';
                    }, 1500);
                } else if (res.statusCode == 401) {
                    Swal.fire(
                        'Thông báo',
                        res.message,
                        'info'
                    )
                    setTimeout(function() {
                        // Ensure username is defined
                        if (typeof username !== "undefined") {
                            // check if username is an email
                            username = username.includes("@") ?
                                username.split("@")[0] :
                                username;
                            window.location.href = "/auth/verify/" + username;
                        }
                    }, 1500);
                } else {
                    Swal.fire(
                        'Đăng nhập thất bại!',
                        res.message,
                        'error'
                    )
                }
            },
            error: function(err) {
                console.log(err);
                Swal.fire({
                    icon: 'error',
                    title: 'server error',
                    showConfirmButton: false,
                    timer: 1500
                })
            }

        });
    }
</script>