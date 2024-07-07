<h4 class="mb-4 profile-title">Đổi mật khẩu</h4>
<div id="edit_profile">
    <div class="p-0">
        <form action="#">
            <div class="form-group">
                <label for="PasswordOld">Mật khẩu cũ</label>
                <input type="password" class="form-control" id="PasswordOld" />
            </div>
            <div class="form-group">
                <label for="PasswordNew">Mật khẩu mới</label>
                <input type="password" class="form-control" id="PasswordNew" />
            </div>
            <div class="text-center">
                <button type="button" onclick="changePassword()" class="btn btn-success btn-block btn-lg">
                    Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
    <div class="additional mt-3">
        <div class="change_password mb-1">
            <a href="/profile" class="p-3 btn-light border btn d-flex align-items-center">Đổi thông tin cá nhân
                <i class="icofont-rounded-right ml-auto"></i></a>
        </div>
        <div class="deactivate_account">
            <a href="/profile/cancel-account" class="p-3 btn-light border btn d-flex align-items-center">Đóng tài khoản
                <i class="icofont-rounded-right ml-auto"></i></a>
        </div>
    </div>
</div>