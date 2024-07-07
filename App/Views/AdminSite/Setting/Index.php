<div class="row">
    <div class="col-12">
        <div class="alert alert-warning" role="alert">
            <strong>Chú ý:</strong> Bạn chỉ được phép cho 1 setting hoạt động, tương ứng với 1 loại type.
            <strong>Vui lòng không xóa. 2 record này. để tránh lỗi phát sinh ngoài ý muốn. </strong>
        </div>
        <div class="card">
            <div class="card-header">
                Danh Sách Cài Đặt
                <a href="<?=ADMIN_PATH?>/setting/create" class="btn btn-success btn-sm float-right">
                    <i class="fa fa-plus"></i>
                    Thêm Mới
                </a>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover table-nowrap">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Id</th>

                            <th>Logo</th>
                            <th>Favicon</th>
                            <th>Loại</th>
                            <th>Tên site</th>
                            <th>Bản quyền</th>
                            <th>Mô tả</th>
                            <th>Từ khóa</th>
                            <th>Địa chỉ</th>


                            <th>Ngày tạo</th>
                            <th>Người tạo</th>
                            <th>Ngày cập nhập</th>
                            <th>Người cập nhập</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($settings as $item) : ?>
                            <tr>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="<?=ADMIN_PATH?>/setting/edit/<?= $item->Id ?>">Edit</a>
                                    <button onclick="remove(<?= $item->Id ?>)" class="btn btn-danger btn-sm">Delete</button>
                                </td>
                                <td><?= $item->Id ?></td>
                                <td><?= ($item->Logo != null) ? '<img src="' . $item->Logo . '" width="30px" height="30px" alt="Logo">' : 'Chưa có logo' ?></td>
                                <td><?= ($item->Favicon != null) ? '<img src="' . $item->Favicon . '" width="30px" height="30px" alt="Favicon">' : 'Chưa có favicon' ?></td>
                                <td><?= ($item->Type == 1) ? 'Admin Site' : 'Client Site' ?></td>
                                <td><?= $item->SiteName ?></td>
                                <td><?= $item->Copyright ?></td>
                                <td><?= $item->Description ?></td>
                                <td><?= $item->Keyword ?></td>
                                <td><?= $item->Address ?></td>

                                <td><?= $item->CreatedAt; ?></td>
                                <td><?= $item->CreatedBy; ?></td>
                                <td><?= $item->UpdatedAt; ?></td>
                                <td><?= $item->UpdatedBy; ?></td>
                                <td><?= ($item->IsActive == true) ? 'Active' : 'Inactive'; ?></td>


                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <?= $pagination ?>
    </div>
</div>
<script>
    function remove(id) {
        Swal.fire({
            title: 'Bạn có chắc chắn muốn xóa?',
            text: "Bạn sẽ không thể khôi phục lại dữ liệu này!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url:  '<?=ADMIN_PATH?>/setting/delete/' + id,
                    method: 'DELETE',
                    contentType: 'application/json',
                    success: function(res) {
                        console.log(res);
                        if (res.success == true) {
                            Swal.fire(
                                'Đã xóa!',
                                res.message,
                                'success'
                            )
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                            return;
                        }
                    },
                    error: function(err) {
                        console.log(err);
                        Swal.fire(
                            'Error!',
                            'Something went wrong!',
                            'error'
                        )
                    }
                });
            }
        })
    }
</script>