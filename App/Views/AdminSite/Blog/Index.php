<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Danh Sách tin
                <a href="<?=ADMIN_PATH?>/blog/create" class="btn btn-success btn-sm float-right">
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

                            <th>Tiêu đề</th>
                            <th>Hình ảnh</th>

                            <th>Ngày tạo</th>
                            <th>Người tạo</th>
                            <th>Ngày cập nhập</th>
                            <th>Người cập nhập</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($blogs as $item) : ?>
                            <tr>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="<?=ADMIN_PATH?>/blog/edit/<?= $item->Id ?>">Edit</a>
                                    <button onclick="remove(<?= $item->Id ?>)" class="btn btn-danger btn-sm">Delete</button>
                                </td>
                                <td><?= $item->Id ?></td>
                                <td><span style="width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;">
                                        <?= $item->Title ?>
                                </span></td>
                                <td><?= ($item->Image != null) ? '<img src="' . $item->Image . '" width="30px" height="30px" alt="Logo">' : 'Chưa có hình' ?></td>


                                <td><?= $item->CreatedAt; ?></td>
                                <td><?= $item->CreatedBy; ?></td>
                                <td><?= $item->UpdatedAt; ?></td>
                                <td><?= $item->UpdatedBy; ?></td>

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
                    url:  '<?=ADMIN_PATH?>/blog/delete/' + id,
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