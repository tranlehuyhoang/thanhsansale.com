<div class="modal fade right-modal" id="login" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header p-0">
                <nav class="schedule w-100">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-link active col-5 py-4" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">
                            <p class="mb-0 font-weight-bold">Đăng nhập</p>
                        </a>
                        <a class="nav-link col-5 py-4" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">
                            <p class="mb-0 font-weight-bold">Đăng kí</p>
                        </a>
                        <a class="nav-link col-2 p-0 d-flex align-items-center justify-content-center" data-dismiss="modal" aria-label="Close">
                            <h1 class="m-0 h4 text-dark">
                                <i class="icofont-close-line"></i>
                            </h1>
                        </a>
                    </div>
                </nav>
            </div>
            <div class="modal-body p-0">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="osahan-signin p-4 rounded">
                            <div class="p-2">
                                <h2 class="my-0">Xin chào bạn!</h2>
                                <p class="small mb-4">Vui lòng đăng nhập để tiếp tục</p>
                                <form id="formLogin">
                                    <div class="form-group">
                                        <label>Tên tài khoản hoặc Email</label>
                                        <input placeholder="Enter Username or Email" type="text" class="form-control" id="UsernameLogin" />
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Mật khẩu
                                            <a href="/auth/forgot-password" class="text-success small">Quên mật
                                                khẩu?</a>

                                        </label>
                                        <input placeholder="Nhập mật khẩu" type="password" class="form-control" id="PasswordLogin" />
                                    </div>
                                    <button type="button" onclick="login()" class="btn btn-success btn-lg rounded btn-block">
                                        Đăng nhập
                                    </button>
                                </form>
                                <!-- <p class="text-muted text-center small m-0 py-3">hoặc</p>
                                <a href="#" class="btn btn-dark btn-block rounded btn-lg btn-apple">
                                    <i class="icofont-brand-apple mr-2"></i> Đăng kí với
                                    Apple
                                </a>
                                <a href="#" class="btn btn-light border btn-block rounded btn-lg btn-google">
                                    <i class="icofont-google-plus text-danger mr-2"></i>
                                    Đăng kí với Google
                                </a> -->
                                <p class="text-center mt-3 mb-0">
                                    <a href="/auth/register" class="text-dark">Bạn chưa có tài khoản? Đăng kí ngay!</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <div class="osahan-signup bg-white p-4">
                            <div class="p-2">
                                <h2 class="my-0">Xin chào bạn!</h2>
                                <p class="small mb-4">
                                    Tạo một tài khoản cho bạn
                                </p>
                                <form id="formRegister">
                                    <div class="form-group">
                                        <label for="name">Tên tài khoản</label>
                                        <small class="text-danger">
                                            <ul>
                                                <li>Chỉ nhập chữ và số viết thường</li>
                                            </ul>
                                        </small>
                                        <input type="text" class="form-control" id="Username" placeholder="Enter username" name="Username" pattern="[a-z0-9]+" title="Chỉ nhập chữ và số">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label> <br>
                                        <small class="text-danger">
                                            Nhập đúng email để nhập OTP đăng ký tài khoản và rút tiền
                                        </small>
                                        <input placeholder="Enter Email" type="email" class="form-control" id="Email" />
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Mật khẩu</label>
                                        <small class="text-danger">
                                            <ul>
                                                <li>Ít nhất 8 ký tự</li>
                                                <li>Ít nhất 1 chữ cái viết hoa</li>
                                                <li>Ít nhất 1 chữ cái viết thường</li>
                                                <li>Ít nhất 1 số</li>
                                            </ul>
                                        </small>
                                        <input placeholder="Enter Password" type="password" class="form-control" id="Password" />
                                    </div>
                                    <div class="form-group">
                                        <label for="ConfirmPassword">Confirm Password</label>
                                        <input placeholder="Enter Confirmation Password" type="password" class="form-control" id="ConfirmPassword" />
                                    </div>
                                    <div class="g-recaptcha" data-sitekey="<?= $googleKey ?>">
                                    </div>
                                    <button type="button" onclick="register1()" class="btn btn-success rounded btn-lg btn-block">
                                        Tạo tài khoản
                                    </button>
                                </form>
                                <p class="text-center mt-3 mb-0">
                                    <a href="/auth/login" class="text-dark">Bạn đã có tài khoản? Đăng nhập ngay</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-0 border-0">
                <div class="col-6 m-0 p-0">
                    <a href="#" class="btn border-top border-right btn-lg btn-block" data-dismiss="modal">Đóng</a>
                </div>
                <div class="col-6 m-0 p-0">
                    <a href="#" class="btn border-top btn-lg btn-block">Trợ giúp</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function register1() {
        var username = $("#Username").val();
        var password = $("#Password").val();
        var confirmPassword = $("#ConfirmPassword").val();
        var email = $("#Email").val();

        // Client-side validation
        if (username === '' || password === '' || confirmPassword === '' || email === '') {
            Swal.fire("Vui lòng nhập đầy đủ thông tin!", "", "warning");
            return;
        }
        var usernameRegex = /^[a-z0-9]+$/;
        if (!usernameRegex.test(username)) {
            Swal.fire("Tên tài khoản không hợp lệ!", "Chỉ được sử dụng chữ cái viết thường và số.", "warning");
            return;
        }
        // Validate password strength
        var passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/;
        if (!passwordRegex.test(password)) {
            Swal.fire("Mật khẩu không đủ mạnh!", "Vui lòng tuân thủ yêu cầu về mật khẩu.", "warning");
            return;
        }

        if (password !== confirmPassword) {
            Swal.fire("Mật khẩu nhập lại không khớp!", "", "error");
            return;
        }

        // Submit form using AJAX
        const data = {
            Username: username,
            Password: password,
            ConfirmPassword: confirmPassword,
            Email: email,
        };

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
                if (res.success) {
                    Swal.fire("Đăng ký thành công!", res.message, "success").then(() => {
                        window.location.href = "/auth/verify/" + username;
                    });
                } else {
                    Swal.fire("Đăng ký thất bại!", res.message, "error");
                }
            },
            error: function(err) {
                console.log(err);
                Swal.fire("Đăng ký thất bại!", "Đã xảy ra lỗi, vui lòng thử lại sau.", "error");
            },
        });
    }
</script>