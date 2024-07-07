<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Danh Sách Tài Khoản Chưa Kích Hoạt

            </div>
            <div class="card-body table-responsive">
                <div class="row">
                    <div class="form-group col" style="align-items: center;display: flex; justify-content: space-between;">
                        <button onclick="removeAll()" class="btn btn-primary btn-sm">Xóa tất cả</button>
                    </div>
                </div>
                <table class="table table-hover table-nowrap">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Id</th>
                            <th>Tên tài khoản</th>
                            <th>Email</th>
                            <th>Tên đầy đủ</th>
                            <th>Phân quyền</th>
                            <th>Số dư</th>

                            <th>Ngày tạo</th>
                            <th>Người tạo</th>
                            <th>Ngày cập nhập</th>
                            <th>Người cập nhập</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody id="tableData">
                        <?php foreach ($users as $user) : ?>
                            <tr>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="<?= ADMIN_PATH ?>/user/edit/<?= $user->Id ?>">Edit</a>
                                    <button onclick="remove(<?= $user->Id ?>)" class="btn btn-danger btn-sm">Delete</button>
                                </td>
                                <td><?= $user->Id ?></td>
                                <td><?= $user->Username; ?></td>
                                <td><?= $user->Email; ?></td>
                                <td><?= $user->FullName; ?></td>
                                <td><?php
                                    switch ($user->Role) {
                                        case Member:
                                            echo 'Member';
                                            break;
                                        case Mod:
                                            echo 'Mod';
                                            break;
                                        case Admin:
                                            echo 'Admin';
                                            break;
                                        default:
                                            echo 'Unknown';
                                            break;
                                    }
                                    ?></td>
                                <td class="text-success"><?= $user->Money; ?></td>
                                <td><?= $user->CreatedAt; ?></td>
                                <td><?= $user->CreatedBy; ?></td>
                                <td><?= $user->UpdatedAt; ?></td>
                                <td><?= $user->UpdatedBy; ?></td>
                                <td><?= ($user->IsActive == true) ? 'Active' : 'Inactive'; ?></td>


                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-12" style="  display: flex;
                                    align-items: center;
                                    justify-content: space-between;
                                    margin: 10px;">
        <?= $showing ?>
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
                    url: '<?= ADMIN_PATH ?>/user/delete/' + id,
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

    // remove all
    function removeAll() {
        Swal.fire({
            title: 'Bạn có chắc chắn muốn xóa tất cả?',
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
                    url: '<?= ADMIN_PATH ?>/user/delete-account-not-active',
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