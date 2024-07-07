<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Lịch Sử Trả tiền
            </div>
            <div class="card-body table-responsive">
                <div class="row">
                    <!-- Status -->
                    <div class="form-group col-md-4">
                        <label for="Status">Trạng thái</label>
                        <select class="form-control" id="Status" name="Status">
                            <option value="">Chọn loại trạng thái</option>
                            <option value="0">Pending</option>
                            <option value="1">Success</option>
                            <option value="2">Cancel</option>
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
                            <th>Mã</th>
                            <th>Tiền</th>
                            <th>Ngày trả</th>
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
    document.addEventListener('DOMContentLoaded', function() {
        search();
    });
    let pageIndex = 1;

    function search(isNext = false) {
        const params = {
            PageIndex: pageIndex,
            Code: $('#Code').val(),
            Status: $('#Status').val(),
            Type: 1
        }
        $.ajax({
            url: '/profile/transactions',
            method: 'POST',
            data: params,
            beforeSend: function() {
                $('#tableData').html('<tr><td colspan="7">Đang tìm kiếm...</td></tr>');
            },
            success: function(res) {
                if (res.success == true) {
                    let html = '';
                    pageIndex = isNext ? pageIndex + 1 : pageIndex;
                    if (res.data.length == 0) {
                        html = '<tr><td colspan="7">Không tìm thấy dữ liệu</td></tr>';
                    } else {
                        res.data.forEach(item => {
                            html += `
                                <tr>
                                    <td>${item.Id}</td>
                                    <td>${item.Code}</td>
                                    <td>${item.Price}</td>
                                    <td>${item.CreatedAt}</td>
                                    <td>${item.Status}</td>
                                </tr>
                            `;
                        });
                    }
                    $('#tableData').html(html);

                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    }
</script>