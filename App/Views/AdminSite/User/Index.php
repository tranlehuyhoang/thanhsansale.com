<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Danh Sách Tài Khoản
                <a href="<?= ADMIN_PATH ?>/user/create" class="btn btn-success btn-sm float-right">
                    <i class="fa fa-plus"></i>
                    Thêm Mới
                </a>
                <button onclick="resetAllMoney(null)" class="btn btn-danger btn-sm float-right mr-2">Reset All
                    Money</button>
            </div>
            <div class="card-body table-responsive">
                <div class="row">
                    <div class="form-group col-md-2">
                        <label for="Username">Loại tài khoản</label>
                        <input type="text" class="form-control" id="Username" name="Username" placeholder="Lọc tên tài khoản">
                    </div>
                    <!-- Email -->
                    <div class="form-group col-md-2">
                        <label for="Email">Lọc Email</label>
                        <input type="text" class="form-control" id="Email" name="Email" placeholder="Lọc Email">
                    </div>
                    <!-- Role -->
                    <div class="form-group col-md-2">
                        <label for="Role">Lọc Phân Quyền</label>
                        <select class="form-control" id="Role" name="Role">
                            <option value="">Chọn phân quyền</option>
                            <option value="0">Member</option>
                            <option value="1">Mod</option>
                            <option value="2">Admin</option>
                        </select>
                    </div>
                    <!-- Money -->
                    <div class="form-group col-md-2">
                        <label for="Money">Lọc Số Dư</label>
                        <input type="number" class="form-control" id="Money" name="Money" placeholder="Lọc Số Dư">
                    </div>
                    <div class="form-group col">
                        <button onclick="search()" class="btn btn-primary btn-sm">Tìm
                            kiếm</button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="form-group col">
                        <button onclick="exportExcel()" class="btn btn-primary btn-sm ">Xuất Excel All User</button>
                        <button onclick="exportExcelVPBank()" class="btn btn-primary btn-sm ">Xuất Excel Mẫu
                            VPBank</button>
                        <button onclick="exportExcelBIDV()" class="btn btn-primary btn-sm ">Xuất Excel Mẫu
                            BIDV</button>
                        <button onclick="exportExcelTCB()" class="btn btn-primary btn-sm ">Xuất Excel Mẫu
                            TCB</button>
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
                            <th>Số Tài Khoản</th>
                            <th>Tên Ngân Hàng</th>

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
                                    <button onclick="resetAllMoney(<?= $user->Id ?>)" class="btn btn-warning btn-sm">Reset
                                        Money</button>
                                    <button onclick="addMoney(<?= $user->Id ?>)" class="btn btn-success btn-sm">Add Money</button>
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
                                <td><?= $user->NumberBank; ?></td>
                                <td title="<?= $user->NameBank; ?>" style="width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;">
                                    <?= $user->NameBank; ?>
                                </td>
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

    function search() {
        const params = {
            Username: $('#Username').val(),
            Email: $('#Email').val(),
            Role: $('#Role').val(),
            Money: $('#Money').val()
        }
        $.ajax({
            url: '<?= ADMIN_PATH ?>/user/search',
            method: 'POST',
            data: params,
            success: function(res) {
                if (res.success == true) {

                    let html = '';
                    res.data.forEach(user => {
                        html += `<tr>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="<?= ADMIN_PATH ?>/user/edit/${user.Id}">Edit</a>
                                    <button onclick="remove(${user.Id})" class="btn btn-danger btn-sm">Delete</button>
                                    <button onclick="resetAllMoney(${user.Id})" class="btn btn-warning btn-sm">Reset Money</button>
                                    <button onclick="addMoney(${user.Id})" class="btn btn-success btn-sm">Add Money</button>
                                </td>
                                <td>${user.Id}</td>
                                <td>${user.Username}</td>
                                <td>${user.Email}</td>
                                <td>${user.FullName}</td>
                                <td>${renderRole(user.Role)}</td>
                                <td class="text-success">${user.Money}</td>
                                <td>${user.NumberBank}</td>
                                <td title="${user.NameBank}" style="width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;">
                                    ${user.NameBank}
                                </td>

                                <td>${user.CreatedAt}</td>
                                <td>${user.CreatedBy}</td>
                                <td>${user.UpdatedAt}</td>
                                <td>${user.UpdatedBy}</td>
                                <td>${user.IsActive}</td>
                            </tr>`;
                    });
                    $('#tableData').html(html);
                }
            },
        });
    }

    function exportExcel() {
        const params = {
            Username: $('#Username').val(),
            Email: $('#Email').val(),
            Role: $('#Role').val()
        }
        $.ajax({
            url: '<?= ADMIN_PATH ?>/user/export-excel',
            method: 'POST',
            data: params,
            xhrFields: {
                responseType: 'blob'
            },
            beforeSend: function() {
                Swal.fire({
                    icon: 'info',
                    title: 'Đang xuất file...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                });
            },
            success: function(data) {
                console.log(data);
                // check data is Blob
                if (!(data instanceof Blob)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Không có dữ liệu để xuất file',
                    });
                    return;
                }
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(data);
                a.href = url;
                a.download = 'DanhSachTaiKhoan_' + new Date().getTime() + '.xlsx';
                document.body.append(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
                Swal.close();
            },
        });
    }

    function exportExcelVPBank() {
        const params = {
            Username: $('#Username').val(),
            Email: $('#Email').val(),
            Role: $('#Role').val()
        }
        // alert ask
        Swal.fire({
            title: 'Bạn có chắc chắn muốn xuất file mẫu VPBank?',
            html: "Sẽ chỉ xuất ra các Tài khoản có số dư lớn hơn 10.000đ <br> Đảm bảo rằng tất cả các User phải có đầy đủ thông tin thanh toán.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xuất',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= ADMIN_PATH ?>/user/export-excel-vpbank',
                    method: 'POST',
                    data: params,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    beforeSend: function() {
                        Swal.fire({
                            icon: 'info',
                            title: 'Đang xuất file...',
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                        });
                    },
                    success: function(data) {
                        console.log(data);
                        // check data is Blob
                        if (!(data instanceof Blob)) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Không có dữ liệu để xuất file',
                            });
                            return;
                        }

                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(data);
                        a.href = url;
                        a.download = 'DanhSachTaiKhoanVPBank_' + new Date().getTime() + '.xls';
                        document.body.append(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                        Swal.close();
                    },
                });
            }
        })
    }


    function exportExcelBIDV() {
        const params = {
            Username: $('#Username').val(),
            Email: $('#Email').val(),
            Role: $('#Role').val()
        }
        // alert ask
        Swal.fire({
            title: 'Bạn có chắc chắn muốn xuất file mẫu BIDV Bank?',
            html: " <br><strong style='color:red;'>BIDV Bank Không ổn định</strong> <br> Sẽ chỉ xuất ra các Tài khoản có số dư lớn hơn 10.000đ <br> Đảm bảo rằng tất cả các User phải có đầy đủ thông tin thanh toán.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xuất',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= ADMIN_PATH ?>/user/export-excel-bidv',
                    method: 'POST',
                    data: params,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    beforeSend: function() {
                        Swal.fire({
                            icon: 'info',
                            title: 'Đang xuất file...',
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                        });
                    },
                    success: function(data) {
                        console.log(data);
                        // check data is Blob
                        if (!(data instanceof Blob)) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Không có dữ liệu để xuất file',
                            });
                            return;
                        }

                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(data);
                        a.href = url;
                        a.download = 'DanhSachTaiKhoanBIDV_' + new Date().getTime() + '.xlsx';
                        document.body.append(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                        Swal.close();
                    },
                });
            }
        })
    }

    function exportExcelTCB() {
        const params = {
            Username: $('#Username').val(),
            Email: $('#Email').val(),
            Role: $('#Role').val()
        }
        // alert ask
        Swal.fire({
            title: 'Bạn có chắc chắn muốn xuất file mẫu TCB Bank?',
            html: "Sẽ chỉ xuất ra các Tài khoản có số dư lớn hơn 10.000đ <br> Đảm bảo rằng tất cả các User phải có đầy đủ thông tin thanh toán.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xuất',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= ADMIN_PATH ?>/user/export-excel-tcb',
                    method: 'POST',
                    data: params,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    beforeSend: function() {
                        Swal.fire({
                            icon: 'info',
                            title: 'Đang xuất file...',
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                        });
                    },
                    success: function(data) {
                        console.log(data);
                        // check data is Blob
                        if (!(data instanceof Blob)) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Không có dữ liệu để xuất file',
                            });
                            return;
                        }

                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(data);
                        a.href = url;
                        a.download = 'DanhSachTaiKhoanTCB_' + new Date().getTime() + '.xlsx';
                        document.body.append(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                        Swal.close();
                    },
                });
            }
        })
    }


    function resetAllMoney(userId) {
        // alert ask
        let message = userId == null ? 'Bạn có chắc chắn muốn reset số dư của tất cả tài khoản Có số dư lớn hơn 10.000đ? và Thông tin đã điền đầy đủ' : 'Bạn có chắc chắn muốn reset số dư của tài khoản này?';
        Swal.fire({
            title: 'Bạn có chắc chắn muốn reset số dư?',
            html: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Reset',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= ADMIN_PATH ?>/user/reset-money',
                    method: 'POST',
                    data: {
                        userId: userId
                    },
                    success: function(res) {
                        console.log(res);
                        if (res.success == true) {
                            Swal.fire(
                                'Đã reset!',
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


    // add-money
    function addMoney(userId) {
        //swal input
        Swal.fire({
            title: 'Nhập số tiền cần cộng',
            input: 'number',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Cộng',
            showLoaderOnConfirm: true,
            preConfirm: (money) => {
                return $.ajax({
                    url: '<?= ADMIN_PATH ?>/user/add-money',
                    method: 'POST',
                    data: {
                        userId: userId,
                        money: money
                    },
                    success: function(res) {
                        console.log(res);
                        if (res.success == true) {
                            Swal.fire(
                                'Đã cộng!',
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
            },
            allowOutsideClick: () => !Swal.isLoading()
        })
    }


    renderRole = (role) => {
        // convert role to int
        role = parseInt(role);
        switch (role) {
            case 0:
                return 'Member';
            case 1:
                return 'Mod';
            case 2:
                return 'Admin';
            default:
                return 'Unknown';
        }
    }
</script>