<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Danh Sách Các thông báo
                <a href="<?=ADMIN_PATH?>/notification/create" class="btn btn-success btn-sm float-right">
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
                            <th>Loại thông báo</th>
                            <th>Tên người nhận</th>
                            <th>Đã xem</th>
                            <!-- <th>Nội dung</th> -->

                            <th>Ngày tạo</th>
                            <th>Người tạo</th>
                            <th>Ngày cập nhập</th>
                            <th>Người cập nhập</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($notifications as $item): ?>
                            <tr>
                                <td>
                                    <a class="btn btn-primary btn-sm"
                                        href="<?=ADMIN_PATH?>/notification/edit/<?= $item->Id ?>">Edit</a>
                                    <button onclick="remove(<?= $item->Id ?>)" class="btn btn-danger btn-sm">Delete</button>
                                </td>
                                <td><?= $item->Id ?></td>
                                <td><?= $item->Title; ?></td>
                                <td>
                                    <?= $item->Type == 0
                                        ? '<span class="text-danger">Thông báo All</span>' :
                                        '<span class="text-success">Thông báo cho user</span>'; ?>
                                </td>
                                <td><?= ($item->UserId == '0' || $item->UserId == null) ? "Tất cả": $item->UserId?></td>
                                <td><?= $item->IsRead == 0
                                    ? '<span class="text-success">Chưa xem</span>' :
                                    '<span class="text-danger">Đã xem</span>'; ?></td>
                                <!-- <td>
                                    <span
                                        style="width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;">
                                        <?= $item->Content ?>
                                    </span>
                                </td> -->


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
                    url:  '<?=ADMIN_PATH?>/notification/delete/' + id,
                    method: 'DELETE',
                    contentType: 'application/json',
                    beforeSend: function () {
                        Swal.fire({
                            title: 'Đang xóa...',
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            },
                            allowOutsideClick: false
                        });
                    },
                    success: function (res) {
                        console.log(res);
                        if (res.success == true) {
                            Swal.fire(
                                'Đã xóa!',
                                res.message,
                                'success'
                            )
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);
                            return;
                        }
                    },
                    error: function (err) {
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