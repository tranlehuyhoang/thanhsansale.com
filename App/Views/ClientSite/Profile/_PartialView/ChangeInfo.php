<style>
    /* Adjustments for images */
    .select2-results__option--highlighted[aria-selected] {
        background-color: #5897fb !important;
        color: white;
    }

    .img-flag {
        height: 30px;
        width: auto;
        margin-right: 10px;
        object-fit: cover;
        background-color: #dddddd;
        border-radius: 5px;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<h4 class="mb-4 profile-title">Thông tin cá nhân</h4>
<div class="row">
    <div class="col-12">
        <h4 class="text-info"><?= $settingClient->DatePayment ?></h4> <br>
        <strong class="text-danger">Lưu ý:</strong>
        <p class="text-danger">Vui lòng cung cấp thông tin chính xác, đầy đủ để rút tiền</p>
    </div>
</div>
<div id="edit_profile">
    <div class="p-0">
        <form action="my_account.html">
            <div class="form-group">
                <label for="exampleInputName1">Nhập chính xác tên viết hoa không dấu ( trùng tên với tài khoản ngân hàng
                    )</label>
                <input type="text" class="form-control" id="FullName" name="full" value="<?= $user->FullName ?>" />
            </div>
            <div class="form-group">
                <label for="exampleInputNumber1">Số điện thoại</label>
                <input type="text" class="form-control" id="Phone" value="<?= $user->Phone ?>" />
            </div>
            <!-- Name Bank -->
            <div class="form-group">
                <label for="exampleInputNumber1">Chọn ngân hàng</label>
                <select class="form-control" id="NameBank" onchange="changeBank()">
                    <?php foreach ($banks as $bank) : ?>
                        <option data-image="<?= $bank->Logo ?>" <?= $bank->Code == $user->NameBank ? 'selected' : '' ?> value="<?= $bank->Code ?>">
                            <?= $bank->Name ?>@<?= $bank->NameVPBank ?>
                        </option>
                    <?php endforeach; ?>

                </select>
                <img src="" alt="<?= $user->NameBank ?>" id="imageBank" class="img-fluid" style="max-width: 100px;">
            </div>
            <!-- Number Bank -->
            <div class="form-group">
                <label for="exampleInputNumber1">Số tài khoản ngân hàng</label>
                <input type="text" class="form-control" id="NumberBank" name="NumberBank" value="<?= $user->NumberBank ?>" />
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Email</label>
                <input disabled type="email" class="form-control" id="exampleInputEmail1" value="<?= $user->Email ?>" />
            </div>
            <div class="text-center">
                <button type="button" onclick="changeInfo()" class="btn btn-success btn-block btn-lg">
                    Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
    <div class="additional mt-3">
        <div class="change_password mb-1">
            <a href="/profile/change-password" class="p-3 btn-light border btn d-flex align-items-center">Đổi mật khẩu
                <i class="icofont-rounded-right ml-auto"></i></a>
        </div>
        <div class="deactivate_account">
            <a href="/profile/cancel" class="p-3 btn-light border btn d-flex align-items-center">Đóng tài khoản
                <i class="icofont-rounded-right ml-auto"></i></a>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // set image bank
        changeBank();

        function formatBank(bank) {
            if (!bank.id) {
                return bank.text;
            }
            let logo = $(bank.element).data('image');
            let name = bank.text.split('@');

            var $bank = $(
                `<span class="d-flex">
                    <img src="${logo}" class="img-flag" />
                    <div style="display: grid">
                        <span class="text-muted">${name[1]}</span>
                        <span>${name[0]}</span>
                    </div>
                </span>`
            );
            return $bank;
        };
        // selection template
        function formatBankSelection(bank) {
            let name = bank.text.split('@');
            return name[0];
        }

        $('#NameBank').select2({
            templateResult: formatBank,
            templateSelection: formatBankSelection
        });

    });

    function changeBank() {

        let logo = $('#NameBank').find(':selected').data('image');
        $('#imageBank').attr('src', logo);
    }
</script>