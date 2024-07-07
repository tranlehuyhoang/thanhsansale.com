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
                            <p class="text-muted mt-2">Xác thực tài khoản</p>
                        </div>
                        <form class="mt-4 pt-2" action="#">
                            <div class="mb-3">
                                <label class="form-label">Tài khoản</label>
                                <input disabled type="text" class="form-control" id="Username" name="Username" value="<?= $username ?? '' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nhập mã xác thực</label>
                                <br>
                                <small class="text-danger">
                                    Vui lòng kiểm hòn thư email bao gồm cả hòm thư spam để lấy mã xác thực
                                </small>
                                <input type="text" class="form-control" id="Token" name="Token">
                            </div>
                            <div class="mb-3">
                                <button onclick="verify()" class="btn btn-primary w-100 waves-effect waves-light mb-2" type="button">Xác thực</button>
                                <!-- resend -->
                                <button class="btn btn-danger w-100 waves-effect waves-light" id="btnResend" onclick="resend()" type="button">Gửi lại</button>
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
    // document ready js
    document.addEventListener("DOMContentLoaded", () => {
        // countdown();
    });

    function verify() {
        // class name
        const data = {
            Token: $('#Token').val(),
            Username: $('#Username').val()
        }
        $.ajax({
            url: '/auth/verify/<?= $username ?>',
            method: 'POST',
            data: data,
            beforeSend: function() {
                Swal.fire({
                    icon: 'info',
                    title: 'Vui lòng chờ',
                    html: 'Đang xác thực tài khoản',
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                })
            },
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

    function resend() {
        const data = {
            Token: $('#Token').val(),
            Username: $('#Username').val()
        }
        $.ajax({
            url: '/auth/resend',
            method: 'POST',
            data: data,
            beforeSend: function() {
                Swal.fire({
                    icon: 'info',
                    title: 'Vui lòng chờ',
                    html: 'Đang gửi lại mã xác thực',
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                })
            },
            success: function(res) {
                console.log(res);
                if (res.success == true) {
                    Swal.fire(
                        'Thành công!',
                        res.message,
                        'success'
                    )
                    countdown();
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
    // đồn hồ đếm ngược 60s gửi lại
    function countdown() {
        //disable button
        $('#btnResend').attr('disabled', true);
        var i = 60;
        var interval = setInterval(function() {
            i--;
            $('#btnResend').text('Gửi lại sau ' + i + 's');
            if (i == 0) {
                clearInterval(interval);
                $('#btnResend').attr('disabled', false);
                $('#btnResend').text('Gửi lại');
            }
        }, 1000);
    }
</script>