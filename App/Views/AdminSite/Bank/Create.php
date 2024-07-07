<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">Thêm Mới Ngân Hàng</div>
            <div class="card-body">
                <form method="post" action="<?= ADMIN_PATH ?>/bank/create" class="row">
                    <div class="form-group col-12">
                        <label for="Code">Mã</label>
                        <input type="text" class="form-control" id="Code" name="Code" placeholder="">
                    </div>
                    <div class="form-group col-12">
                        <label for="Name">Tên Đầy Đủ</label>
                        <input type="text" class="form-control" id="Name" name="Name">
                    </div>
                    <div class="form-group col-12">
                        <label for="NameTCB">Tên Đầy TCB</label>
                        <input type="text" class="form-control" id="NameTCB" name="NameTCB">
                    </div>
                    <div class="form-group col-12">
                        <label for="NameVVPBank">Tên Đầy VPBank</label>
                        <input type="text" class="form-control" id="NameVVPBank" name="NameVVPBank">
                    </div>
                    <!-- logo -->
                    <div class="form-group col-12">
                        <label for="Logo">Logo</label>
                        <input type="text" class="form-control" id="Logo" name="Logo">
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
