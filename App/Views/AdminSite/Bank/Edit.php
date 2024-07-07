<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">Sửa Mới Ngân Hàng</div>
            <div class="card-body">
                <form method="post" action="<?= ADMIN_PATH ?>/bank/edit/<?= $bank->Id ?>" class="row">
                    <div class="form-group col-12">
                        <label for="Code">Mã</label>
                        <input disabled type="text" class="form-control" id="Code" name="Code" value="<?= $bank->Code ?>">
                    </div>
                    <div class="form-group col-12">
                        <label for="Name">Tên Đầy Đủ</label>
                        <input type="text" class="form-control" id="Name" name="Name" value="<?= $bank->Name ?>">
                    </div>
                    <div class="form-group col-12">
                        <label for="NameTCB">Tên Đầy TCB</label>
                        <input type="text" class="form-control" id="NameTCB" name="NameTCB" value="<?= $bank->NameTCB ?>">
                    </div>
                    <div class="form-group col-12">
                        <label for="NameVPBank">Tên Đầy VPBank</label>
                        <input type="text" class="form-control" id="NameVPBank" name="NameVPBank" value="<?= $bank->NameVPBank ?>">
                    </div>
                    <div class="form-group col-12">
                        <label for="Logo">Logo</label>
                        <input type="text" class="form-control" id="Logo" name="Logo" value="<?= $bank->Logo ?>">
                    </div>
                    <div class="form-group">
                        <?php if ($bank->Logo) : ?>
                            <img src="<?= $bank->Logo ?>" alt="<?= $bank->Name ?>" class="img-fluid" style="max-width: 100px;">
                        <?php endif; ?>
                    </div>
                    <div class="form-group mt-2">
                        <a href="<?= ADMIN_PATH ?>/bank" class="btn btn-primary">Quay về</a>
                        <button type="submit" class="btn btn-success">Lưu lại</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>