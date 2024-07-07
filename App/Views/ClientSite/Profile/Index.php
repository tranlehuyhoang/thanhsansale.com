<div class="row">
    <div class="col-lg-4">
        <div class="osahan-account bg-white rounded shadow-sm overflow-hidden">
            <div class="p-4 profile text-center border-bottom">
                <img src="https://cdn-icons-png.freepik.com/512/219/219986.png" alt="" style="width: 100px; height: 100px; border-radius: 50%;">
                <h6 class="font-weight-bold m-0 mt-2"><?= $userLogin->FullName ?? $userLogin->Username ?></h6>
                <p class="small text-muted m-0">
                    <a href="#" class="__cf_email__"><?= $userLogin->Email ?></a>
                </p>
            </div>
            <div class="account-sections">
                <ul class="list-group">

                    <a href="#" class="text-decoration-none text-dark">
                        <li class="border-bottom bg-white d-flex align-items-center p-3">
                            <i class="icofont-sale-wallet osahan-icofont bg-success"></i>
                            Số dư:&nbsp;<span class="badge badge-info p-1 badge-pill ml-auto"><?= $userLogin->Money ?>
                            </span>
                            </span>
                        </li>
                    </a>
                    <a href="/profile" class="text-decoration-none text-dark">
                        <li class="border-bottom bg-white d-flex align-items-center p-3">
                            <i class="icofont-user"></i> Thông tin cá nhân
                            <span class="badge badge-success p-1 badge-pill ml-auto"><i class="icofont-simple-right"></i></span>
                        </li>
                    </a>


                    <a href="/profile/orders" class="text-decoration-none text-dark">
                        <li class="border-bottom bg-white d-flex align-items-center p-3">
                            <i class="icofont-sale-discount osahan-icofont bg-success"></i> Đơn hàng
                            <span class="badge badge-success p-1 badge-pill ml-auto"><i class="icofont-simple-right"></i></span>
                        </li>
                    </a>
                    <!-- <a href="/profile/transactions" class="text-decoration-none text-dark">
                        <li class="border-bottom bg-white d-flex align-items-center p-3">
                            <i class="icofont-phone osahan-icofont bg-success"></i>Lịch sử trả tiền đơn hàng
                            <span class="badge badge-success p-1 badge-pill ml-auto"><i class="icofont-simple-right"></i></span>
                        </li>
                    </a> -->
                    <a href="/profile/history-transactions" class="text-decoration-none text-dark">
                        <li class="border-bottom bg-white d-flex align-items-center p-3">
                            <i class="icofont-phone osahan-icofont bg-success"></i>Lịch sử trả tiền
                            <span class="badge badge-success p-1 badge-pill ml-auto"><i class="icofont-simple-right"></i></span>
                        </li>
                    </a>
                    <a href="/auth/logout" class="text-decoration-none text-dark">
                        <li class="border-bottom bg-white d-flex align-items-center p-3">
                            <i class="icofont-lock osahan-icofont bg-danger"></i>
                            Đăng xuất
                        </li>
                    </a>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-8 p-4 bg-white rounded shadow-sm">
        <?php
        // get route
        $route = $_SERVER['REQUEST_URI'];
        $route = explode("/", $route);
        $route = $route[count($route) - 1];

        switch ($route) {
            case 'change-password':
                include_once "_PartialView/ChangePassword.php";
                break;
            case 'cancel-account':
                include_once "_PartialView/CancelAccount.php";
                break;
            case 'orders':
                include_once "_PartialView/Orders.php";
                break;
            case 'transactions':
                include_once "_PartialView/Transactions.php";
                break;
            case 'history-transactions':
                include_once "_PartialView/HistoryTransactions.php";
                break;
            default:
                include_once "_PartialView/ChangeInfo.php";
                break;
        }
        ?>

    </div>
</div>
<script>
    function changeInfo() {
        const data = {
            Username: '<?= $userLogin->Username ?>',
            FullName: $("#FullName").val(),
            Phone: $("#Phone").val(),
            NumberBank: $("#NumberBank").val(),
            NameBank: $("#NameBank").val(),
        };
        // swal ask
        Swal.fire({
            title: "Bạn có chắc chắn muốn cập nhật thông tin?",
            text: "Hãy kiểm tra kỹ thông tin trước khi cập nhật!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Cập nhật",
            cancelButtonText: "Hủy",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/profile/change-info",
                    method: "POST",
                    data: data,
                    beforeSend: function() {
                        Swal.fire({
                            title: "Đang cập nhật thông tin...",
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            willOpen: () => {
                                Swal.showLoading();
                            },
                        });
                    },
                    success: function(res) {
                        if (res.success == true) {
                            Swal.fire("Cập nhật thông tin thành công!", res.message, "success");
                            setTimeout(function() {
                                window.location.href = "/profile";
                            }, 1500);
                        } else {
                            Swal.fire("Cập nhật thông tin thất bại!", res.message, "error");
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
            }
        });


    }

    //  change password
    function changePassword() {
        const data = {
            Username: '<?= $userLogin->Username ?>',
            PasswordOld: $("#PasswordOld").val(),
            PasswordNew: $("#PasswordNew").val(),
        };
        $.ajax({
            url: "/profile/change-password",
            method: "POST",
            data: data,
            success: function(res) {
                if (res.success == true) {
                    Swal.fire("Cập nhật mật khẩu thành công!", res.message, "success");
                    setTimeout(function() {
                        window.location.href = "/profile";
                    }, 1500);
                } else {
                    Swal.fire("Cập nhật mật khẩu thất bại!", res.message, "error");
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
    }
</script>