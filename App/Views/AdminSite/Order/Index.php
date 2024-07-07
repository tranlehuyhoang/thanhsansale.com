<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Danh Sách Đơn Hàng
            </div>
            <div class="card-body table-responsive">
                <div class="row">
                    <div class="form-group col-md-2">
                        <label for="Username">Loại mã đơn</label>
                        <input type="text" class="form-control" id="Code" name="Code" placeholder="Lọc mã đơn">
                    </div>
                    <!-- Username -->
                    <div class="form-group col-md-2">
                        <label for="Username">Lọc người mua</label>
                        <input type="text" class="form-control" id="Username" name="Username" placeholder="Lọc người mua">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="ProductName">Lọc tên sản phẩm</label>
                        <input type="text" class="form-control" id="ProductName" name="ProductName" placeholder="Lọc tên sản phẩm">
                    </div>
                    <!-- Status -->
                    <div class="form-group col-md-2">
                        <label for="Status">Lọc trạng thái</label>
                        <select class="form-control" id="Status" name="Status">
                            <option value="">Chọn trạng thái</option>
                            <option value="1">Đang xử lý</option>
                            <option value="2">Hoàn thành</option>
                            <option value="3">Đã hủy</option>
                            <option value="4">Chưa thanh toán</option>
                        </select>
                    </div>

                    <!-- Type -->
                    <div class="form-group col-md-2">
                        <label for="Type">Lọc Loại Đơn</label>
                        <select class="form-control" id="Type" name="Type">
                            <option value="">Chọn loại đơn</option>
                            <option value="0">Shopee</option>
                            <option value="1">Lazada</option>
                            <option value="2">Tiktok Shop</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2" style="align-items: center;display: flex;">
                        <button onclick="search()" class="btn btn-primary btn-sm">Tìm kiếm</button>
                    </div>

                </div>

                <div class="row">
                    <!-- startDate -->
                    <div class="form-group">
                        <label class="text-danger" for="StartDate">Lọc theo ngày cập nhật (không tích thì là theo: Ngày Tạo)</label>
                        <input type="checkbox" id="checkDate" name="checkDate" value="1" style="margin-top: 10px;">
                    </div>
                    <div class="form-group col-2">
                        <label for="StartDate">Từ ngày</label>
                        <input type="date" class="form-control" id="FromDate" name="FromDate" value="<?= date('Y-m-01') ?>">
                    </div>
                    <!-- endDate -->
                    <div class="form-group col-2">
                        <label for="EndDate">Đến ngày</label>
                        <input type="date" class="form-control" id="ToDate" name="ToDate" value="<?= date('Y-m-d') ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group mt-2 ">
                        <button id="btnRefund" onclick="refund()" disabled class="btn btn-success btn-sm mt-2">Hoàn lại tiền
                            đơn hủy</button>
                        <small class="mr-2">(Chọn lọc đơn hủy để hoàn)</small>

                        <button id="btnCheckOrderShopee" onclick="checkOrderShopee()" class="btn btn-info btn-sm mt-2">Cập nhật đơn hàng Shopee</button>
                        <small class="mr-2">(Dùng trong trường hợp đơn hàng không tự động cập nhật)</small>

                        <button id="btnCheckOrderLazada" onclick="checkOrderLazada()" class="btn btn-info btn-sm mt-2">Cập nhật đơn hàng Lazada</button>
                        <small class="mr-2">(Dùng trong trường hợp đơn hàng không tự động cập nhật)</small>
                    </div>
                </div>
                <table class="table table-hover table-nowrap">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Id</th>

                            <th>Loại</th>
                            <th>Mã đơn hàng</th>
                            <th>Tên sản phẩm</th>
                            <th>Tiền hoàn Của Sàn</th>
                            <th>Hoa hồng nhận được</th>
                            <th>Người mua</th>
                            <th>Trạng thái</th>
                            <th>Đã hoàn</th>


                            <th>Ngày tạo</th>
                            <th>Người tạo</th>
                            <th>Ngày cập nhập</th>
                            <th>Người cập nhập</th>
                        </tr>
                    </thead>
                    <tbody id="tableData">
                        <?php foreach ($orders as $item) : ?>
                            <tr>
                                <td>
                                    <button onclick="remove(<?= $item->Id ?>)" class="btn btn-danger btn-sm">Xóa</button>
                                </td>
                                <td><?= $item->Id ?></td>

                                <td><?= $item->Type ?></td>
                                <td title="<?= $item->Code ?>" style="width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;">
                                    <?= $item->Code ?></td>
                                <td>
                                    <span title="<?= $item->ProductName ?>" style="width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;">
                                        <?= $item->ProductName ?>
                                    </span>
                                </td>
                                <td><?= $item->Price ?></td>
                                <td class="text-success"><?= $item->Discount ?></td>
                                <td><?= $item->Username ?? "" ?></td>
                                <td><?= $item->Status ?></td>
                                <td><?php
                                    if ($item->StatusCode == 3)
                                        echo $re = $item->Refund == 0 ? 'Chưa' : 'Đã hoàn'
                                    ?></td>



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
                    url: '<?= ADMIN_PATH ?>/order/delete/' + id,
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
        let isUpdatedAt = $('#checkDate').is(':checked');

        const params = {
            Code: $('#Code').val(),
            Username: $('#Username').val(),
            ProductName: $('#ProductName').val(),
            Type: $('#Type').val(),
            Status: $('#Status').val(),
            FromCreatedAt: isUpdatedAt ? null : $('#FromDate').val(),
            ToCreatedAt: isUpdatedAt ? null : $('#ToDate').val(),
            FromUpdatedAt: isUpdatedAt ? $('#FromDate').val() : null,
            ToUpdatedAt: isUpdatedAt ? $('#ToDate').val() : null
        }
        $.ajax({
            url: '<?= ADMIN_PATH ?>/order/search',
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
                                    </td>
                                    <td>${item.Id}</td>
    
                                    <td>${item.Type}</td>
                                    <td title="${item.Code}" style="width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;">${item.Code}</td>
                                    <td>
                                        <span title="${item.ProductName}" style="width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;">
                                            ${item.ProductName}
                                        </span>
                                    </td>
                                    <td>${item.Price}</td>
                                    <td class="text-success">${item.Discount}</td>
                                    <td>${item.Username ?? ""}</td>
                                    <td>${item.Status}</td>
                                    <td>${item.StatusCode == 3 ? item.Refund == 0 ? 'Chưa' : 'Đã hoàn' : ''}</td>

                                    <td>${item.CreatedAt}</td>
                                    <td>${item.CreatedBy}</td>
                                    <td>${item.UpdatedAt}</td>
                                    <td>${item.UpdatedBy}</td>
                                </tr>
                            `;
                    });
                    $('#tableData').html(html);
                    if (params.Status == 3) {
                        $('#btnRefund').prop('disabled', false);
                    }
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    }

    function refund() {
        const params = {
            Code: $('#Code').val(),
            Username: $('#Username').val(),
            ProductName: $('#ProductName').val(),
            Type: $('#Type').val(),
            Status: $('#Status').val()
        }
        Swal.fire({
            title: 'Bạn có chắc chắn muốn hoàn tiền?',
            text: "Bạn sẽ không thể khôi phục lại dữ liệu này!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hoàn',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= ADMIN_PATH ?>/order/refund',
                    method: 'POST',
                    data: params,
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Đang xử lý...',
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            },
                            allowOutsideClick: false
                        })
                    },
                    success: function(res) {
                        console.log(res);
                        if (res.success == true) {
                            Swal.fire(
                                'Đã hoàn!',
                                res.message,
                                'success'
                            )
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                            return;
                        } else {
                            Swal.fire(
                                'Thông báo!',
                                res.message,
                                'info'
                            )
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);

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

    function checkOrderShopee() {
        Swal.fire({
            title: 'Bạn có chắc chắn muốn cập nhật đơn hàng Shopee?',
            text: "Bạn sẽ không thể khôi phục lại dữ liệu này!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Cập nhật',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/api/shopee/auto-check-order/1',
                    method: 'GET',
                    beforeSend: function() {
                        Swal.fire({
                            icon: 'info',
                            // hide buttons
                            showConfirmButton: false,
                            html: '<div class="spinner-border text-info" role="status"><span class="visually-hidden">Loading...</span></div>',
                            title: 'Đang xử lý.... Sẽ mất khá lâu! vui lòng kiên nhẫn! Shopee trả về khá chậm nên. nhiều lúc server không tự update đơn hàng được. Bạn nhớ vô đây ấn nhé.',
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            },
                            allowOutsideClick: false
                        })
                    },
                    success: function(res) {
                        console.log(res);
                        if (res.success == true) {
                            Swal.fire(
                                'Đã cập nhật!',
                                res.message,
                                'success'
                            )
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                            return;
                        } else {
                            Swal.fire(
                                'Thông báo!',
                                res.message,
                                'info'
                            )
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);

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

    function checkOrderLazada() {
        // input FromDate and ToDate
        // Tạo ngày đầu tiên của tháng hiện tại
        const firstDay = new Date(new Date().getFullYear(), new Date().getMonth(), 1);

        // Tạo ngày cuối cùng của tháng hiện tại
        const lastDay = new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0);

        // Định dạng ngày thành chuỗi theo định dạng YYYY-MM-DD
        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const day = date.getDate().toString().padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        Swal.fire({
            title: 'Bạn có chắc chắn muốn cập nhật đơn hàng Lazada?',
            text: "Bạn sẽ không thể khôi phục lại dữ liệu này!",
            icon: 'warning',
            html: `
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-danger">Lưu ý: Lazada chỉ cho phép lọc trong tháng, Ví dụ tháng 05 thì chỉ lọc từ ngày 01/05 đến 31/05</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="FromDate">Từ ngày</label>
                        <input type="date" class="form-control" id="FromDate" name="FromDate" value="${formatDate(firstDay)}">
                    </div>
                    <div class="col-md-6">
                        <label for="ToDate">Đến ngày</label>
                        <input type="date" class="form-control" id="ToDate" name="ToDate" value="${formatDate(lastDay)}">
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Cập nhật',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                let fromDate = $('#FromDate').val();
                let toDate = $('#ToDate').val();
                $.ajax({
                    url: '/api/lazada/auto-check-order?FromDate=' + fromDate + '&ToDate=' + toDate,
                    method: 'GET',
                    beforeSend: function() {
                        Swal.fire({
                            icon: 'info',
                            // hide buttons
                            showConfirmButton: false,
                            html: '<div class="spinner-border text-info" role="status"><span class="visually-hidden">Loading...</span></div>',
                            title: 'Đang xử lý...! Sẽ mất khá lâu! vui lòng kiên nhẫn',
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            },
                            allowOutsideClick: false
                        })
                    },
                    success: function(res) {
                        console.log(res);
                        if (res.success == true) {
                            Swal.fire(
                                'Đã cập nhật!',
                                res.message,
                                'success'
                            )
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                            return;
                        } else {
                            Swal.fire(
                                'Thông báo!',
                                res.message,
                                'error'
                            )
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