<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Danh Sách Giao Dịch
            </div>
            <div class="card-body table-responsive">
                <div class="row">
                    <!-- Username -->
                    <div class="form-group col-md-2">
                        <label for="Username">Lọc người mua</label>
                        <input type="text" class="form-control" id="Username" name="Username" placeholder="Lọc người mua">
                    </div>
                    <!-- Status -->
                    <div class="form-group col-md-2">
                        <label for="Status">Lọc Trạng Thái</label>
                        <select class="form-control" id="Status" name="Status">
                            <option value="">Chọn trạng thái</option>
                            <option value="0">Chờ xác nhận</option>
                            <option value="1">Đã xác nhận</option>
                            <option value="2">Đã hủy</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4" style="align-items: center;display: flex;">
                        <button onclick="search()" class="btn btn-primary btn-sm" style="margin-right: 10px;">Tìm
                            kiếm</button>
                        <button onclick="approveAll()" class="btn btn-danger btn-sm" style="margin-right: 10px;">
                            Duyệt tất cả</button>


                    </div>
                </div>
                <table class="table table-hover table-nowrap">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Id</th>
                            <th>Mã</th>

                            <th>Tên người nhận</th>
                            <th>Loại</th>
                            <th>Tiền</th>
                            <th>Ghi chú</th>
                            <th>Trạng thái</th>


                            <th>Ngày tạo</th>
                            <th>Người tạo</th>
                            <th>Ngày cập nhập</th>
                            <th>Người cập nhập</th>
                        </tr>
                    </thead>
                    <tbody id="tableData">
                        <?php
                        foreach ($paymentTransactions as $item) : ?>
                            <tr>
                                <td>
                                    <button onclick="remove(<?= $item->Id ?>)" class="btn btn-danger btn-sm">Xóa</button>
                                    <?php if ($item->Status == 0) : ?>
                                        <button onclick="approve(<?= $item->Id ?>)" class="btn btn-primary btn-sm">Duyệt</button>
                                    <?php endif; ?>
                                </td>
                                <td><?= $item->Id ?></td>
                                <td><?= $item->Code ?></td>

                                <td><?= $item->Username ?></td>
                                <td>Rút tiền</td>
                                <td class="text-success"><?= $item->Price ?></td>
                                <td>
                                    <span style="width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;">
                                        <?= $item->Note ?>
                                    </span>
                                </td>
                                <td><?= $item->StatusString ?></td>


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
                    url: '<?= ADMIN_PATH ?>/payment-transaction/delete/' + id,
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


    function approve(id) {
        Swal.fire({
            title: 'Bạn có chắc chắn muốn duyệt?',
            text: "Bạn sẽ không thể khôi phục lại dữ liệu này!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Duyệt',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= ADMIN_PATH ?>/payment-transaction/approve',
                    method: 'POST',
                    data: {
                        Id: Number(id)
                    },
                    beforeSend: function(xhr) {
                        // loading
                        Swal.fire({
                            title: 'Đang xử lý...',
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            },
                            onAfterClose: () => {
                                Swal.hideLoading()
                            }
                        })
                    },
                    success: function(res) {
                        console.log(res);
                        if (res.success == true) {
                            Swal.fire(
                                'Đã duyệt!',
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


    function approveAll() {
        Swal.fire({
            title: 'Bạn có chắc chắn muốn duyệt tất cả?',
            text: "Bạn sẽ không thể khôi phục lại dữ liệu này!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Duyệt',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= ADMIN_PATH ?>/payment-transaction/approve-all',
                    method: 'POST',
                    beforeSend: function(xhr) {
                        // loading
                        Swal.fire({
                            title: 'Đang xử lý...',
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            },
                            onAfterClose: () => {
                                Swal.hideLoading()
                            }
                        })
                    },
                    success: function(res) {
                        console.log(res);
                        if (res.success == true) {
                            Swal.fire(
                                'Đã duyệt!',
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
            Status: $('#Status').val(),
            Type: 1
        }
        $.ajax({
            url: '<?= ADMIN_PATH ?>/payment-transaction/search',
            method: 'POST',
            data: params,
            success: function(res) {
                console.log(res);
                if (res.success == true) {
                    let html = '';
                    res.data.forEach(item => {
                        html += `
                                <tr>
                                    <td>
                                        <button onclick="remove(${item.Id})" class="btn btn-danger btn-sm">Xóa</button>
                                        ${item.Status == 0 ? `<button onclick="approve(${item.Id})" class="btn btn-primary btn-sm">Duyệt</button>` : ''}
                                    </td>
                                    <td>${item.Id}</td>
                                    <td>${item.Code}</td>
    
                                    <td>${item.Username}</td>
                                    <td>Rút tiền</td>
                                    <td>${formatCurrency(item.Price)}</td>
                                    <td>
                                        <span style="width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;">
                                            ${item.Note}
                                        </span>
                                    </td>
                                    <td>${renderStatus(item.Status)}</td>

                                    <td>${item.CreatedAt}</td>
                                    <td>${item.CreatedBy}</td>
                                    <td>${item.UpdatedAt}</td>
                                    <td>${item.UpdatedBy}</td>
                                </tr>
                            `;
                    });
                    $('#tableData').html(html);
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    }

    function renderStatus(status) {
        switch (status) {
            case 0:
                return '<span class="text-warning">Chờ xác nhận</span>';
            case 1:
                return '<span class="text-success">Đã xác nhận</span>';
            case 2:
                return '<span class="text-danger">Đã hủy</span>';
            default:
                return '<span class="text-warning">Chờ xác nhận</span>';
        }
    }


    function formatCurrency(n, separate = ".") {
        return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, separate) + ' VNĐ';
    }
</script>