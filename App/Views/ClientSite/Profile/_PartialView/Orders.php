<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Danh Sách Đơn Hàng
            </div>
            <div class="card-body table-responsive">
                <span class="badge badge-info mb-1 text-danger">
                    * Chú ý: Dữ liệu được cập nhật hàng ngày vào lúc 20h00
                </span>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="Username">Mã đơn</label>
                        <input type="text" class="form-control" id="Code" name="Code" placeholder="Lọc mã đơn">
                    </div>
                    <!-- Type -->
                    <div class="form-group col-md-4">
                        <label for="Type">Loại Đơn</label>
                        <select class="form-control" id="Type" name="Type">
                            <option value="">Chọn loại đơn</option>
                            <option value="0">Shopee</option>
                            <option value="1">Lazada</option>
                            <option value="2">Tiktok Shop</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4" style="align-items: center;display: flex;">
                        <button onclick="search()" class="btn btn-primary btn-sm mr-2">Tìm kiếm</button>
                        <button onclick="search(true)" class="btn btn-info btn-sm mr-2">Xem tiếp</button>
                    </div>
                </div>
                <table class="table table-hover table-nowrap">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Loại</th>
                            <th>Mã đơn hàng</th>
                            <th>Tiền hoa hồng</th>
                            <th>Ngày mua</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody id="tableData">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-12">

    </div>
</div>
<script>
    // document ready javascript not jquery
    document.addEventListener('DOMContentLoaded', function () {
        search();
    });
    let pageIndex = 1;
    function search(isNext = false) {
        const params = {
            PageIndex: pageIndex,
            Code: $('#Code').val(),
            Type: $('#Type').val()
        }
        $.ajax({
            url: '/profile/orders',
            method: 'POST',
            data: params,
            beforeSend: function () {
                $('#tableData').html('<tr><td colspan="7">Đang tìm kiếm...</td></tr>');
            },
            success: function (res) {
                console.log(res);
                if (res.success == true) {
                    let html = '';
                    pageIndex = isNext ? pageIndex + 1 : pageIndex;
                    if (res.data.length == 0) {
                        html = '<tr><td colspan="7">Không tìm thấy dữ liệu</td></tr>';
                    } else {
                        res.data.forEach(item => {
                            html += `
                                <tr>
                                    <td>${item.Index*(-1)}</td>
                                    <td>${item.Type}</td>
                                    <td>${item.Code}</td>
                                    <td>${item.Discount}</td>
                                    <td>${item.CreatedAt}</td>
                                    <td>${item.Status}</td>
                                </tr>
                            `;
                        });
                    }
                    $('#tableData').html(html);

                }
            },
            error: function (err) {
                console.log(err);
            }
        });
    }
</script>