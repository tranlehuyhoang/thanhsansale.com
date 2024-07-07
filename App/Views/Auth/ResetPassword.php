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
                            <p class="text-muted mt-2">Khôi phục mật khẩu</p>
                        </div>
                        <form class="mt-4 pt-2" action="#">
                            <div class="mb-3">
                                <label class="form-label">Nhập mã xác thực</label>
                                <input type="text" class="form-control" id="Token" name="Token">
                            </div>
                            <div class="mb-3">
                                <button onclick="resetPassword()" class="btn btn-primary w-100 waves-effect waves-light" type="button">Xắc
                                    thực</button>
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
    function resetPassword() {
        // class name
        const data = {
            Token: $('#Token').val(),
        }
        $.ajax({
            url: '/auth/reset-password',
            method: 'POST',
            data: data,
            success: function(res) {
                console.log(res);
                if (res.success == true) {
                    Swal.fire(
                        'Thành công!',
                        res.message,
                        'success'
                    )
                    setTimeout(() => {
                        window.location.href = '/auth/login';
                    }, 1500);
                } else {
                    Swal.fire(
                        'Thất bại!',
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