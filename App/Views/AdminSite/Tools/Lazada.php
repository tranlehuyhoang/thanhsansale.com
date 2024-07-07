<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Lấy đơn hàng lazada
            </div>
            <div class="card-body table-responsive">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Số đơn cần lấy</label>
                            <input type="number" class="form-control" id="pageSize" value="10">
                        </div>
                        <div class="form-group">
                            <label for="">Số trang (1,2,3...)</label>
                            <input type="number" class="form-control" id="pageNum" value="1">
                        </div>
                        <!-- startDate -->
                        <div class="form-group">
                            <label for="">Ngày bắt đầu</label>
                            <input type="text" class="form-control datepicker" id="dateStart" value="<?= $startDate ?>">
                        </div>
                        <!-- endDate -->
                        <div class="form-group">
                            <label for="">Ngày kết thúc</label>
                            <input type="text" class="form-control datepicker" id="dateEnd" value="<?= $endDate ?>">
                        </div>
                        <!-- alert -->
                        <div class="alert alert-info mt-2">
                            <strong>Lưu ý!</strong> Chỉ cho phép lọc trong tháng
                        </div>

                        <button class="btn btn-primary mt-2" onclick="loadData()">Lấy dữ liệu</button>
                        <button class="btn btn-info mt-2" onclick="loadData(true)">Xem tiếp</button>
                    </div>

                    <table class="table table-hover table-nowrap">
                        <thead>
                            <tr>
                                <th>ORDER ID</th>
                                <th>Trạng Thái</th>
                                <th>Tên sản phẩm</th>
                                <th>Ngày hoàn tất</th>
                                <th>Người Mua (SubId)</th>
                                <th>Tiền hoàn của Lazada</th>
                                <th>Tiền hoàn nhận được (<?= $category->Discount * 100 ?>%) </th>
                            </tr>
                        </thead>
                        <tbody id="dataTable">
                            <tr>
                                <td colspan="6" class="text-center">Không có dữ liệu</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-12">

        </div>
    </div>
    <script>

        let data = [];

        // document ready
        $(document).ready(function () {
            loadData();
        });

        function remove(id) {
            // remove element tbody
            $(`#${id}`).remove();
        }


        function loadData(isNext = false) {
            const params = {
                pageSize: $('#pageSize').val(),
                pageNum: isNext ? Number($('#pageNum').val()) + 1 : $('#pageNum').val(),
                dateStart: $('#dateStart').val(),
                dateEnd: $('#dateEnd').val()
            }
            $.ajax({
                url:  '<?=ADMIN_PATH?>/tools/lazada',
                method: 'POST',
                data: params,
                beforeSend: function () {
                    $('#dataTable').html(`
                    <tr>
                        <td colspan="8" class="text-center">Đang tải dữ liệu...</td>
                    </tr>
                    `);
                },
                success: function (res) {
                    console.log(res);
                    if (res.data != null) {
                        $('#pageNum').val(data.page_num);
                        let html = '';
                        if (res.data.length > 0) {
                            res.data.forEach((item, index) => {
                                html += `
                                <tr id="order-${index}">
                                    <td>${item.offerId}</td>
                                    <td>${item.status}</td>
                                    <td><span style="width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;">${item.skuName}</span></td>
                                    <td>${item.fulfilledTime}</td>

                                    <td>${item.subId1}</td>
                                    <td>${item.lazadaCommission}</td>
                                    <td><span class="text-success">${item.commissionWebsite}</span></td>
                                </tr>
                                `;
                            });
                            $('#dataTable').html(html);

                        } else {
                            html = `
                            <tr>
                                <td colspan="8" class="text-center">Không có dữ liệu</td>
                            </tr>
                            `;
                        }
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

        function renderStatus(status) {
            switch (status) {
                case 1:
                    return '<span class="badge rounded-pill bg-info">Đang xử lý</span>';
                case 2:
                    return '<span class="badge rounded-pill bg-success">Hoàn thành</span>';
                case 3:
                    return '<span class="badge rounded-pill bg-danger">Đã hủy</span>';
                case 4:
                    return '<span class="badge rounded-pill bg-warning">Chưa thanh toán</span>';
                default:
                    return '<span class="badge rounded-pill bg-dark">Không xác định</span>';
            }
        }

    </script>