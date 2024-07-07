<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">Thêm Mới Cài Đặt</div>
            <div class="card-body">
                <form method="post" action="<?= ADMIN_PATH ?>/setting/create" class="row">
                    <div class="form-group col-6">
                        <label for="Logo">Logo</label>
                        <input type="text" class="form-control" id="Logo" name="Logo" placeholder="Enter Logo">
                    </div>
                    <div class="form-group col-6">
                        <label for="Favicon">Favicon</label>
                        <input type="text" class="form-control" id="Favicon" name="Favicon" placeholder="Enter Favicon">
                    </div>
                    <div class="form-group col-6">
                        <label for="SiteName">Tên site</label>
                        <input type="text" class="form-control" id="SiteName" name="SiteName" placeholder="Enter SiteName">
                    </div>
                    <div class="form-group col-6">
                        <label for="Copyright">Bản quyền</label>
                        <input type="text" class="form-control" id="Copyright" name="Copyright" placeholder="Enter Copyright">
                    </div>
                    <div class="form-group col-6">
                        <label for="Description">Mô tả</label>
                        <input type="text" class="form-control" id="Description" name="Description" placeholder="Enter Description">
                    </div>
                    <div class="form-group col-6">
                        <label for="Keyword">Từ kóa</label>
                        <input type="text" class="form-control" id="Keyword" name="Keyword" placeholder="Enter Keyword">
                    </div>
                    <div class="form-group col-6">
                        <label for="Address">Địa chỉ</label>
                        <input type="text" class="form-control" id="Address" name="Address" placeholder="Enter Address">
                    </div>
                    <!-- Active -->
                    <div class="form-group col-6">
                        <label for="IsActive">Trạng thái</label>
                        <select class="form-control" id="IsActive" name="IsActive">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label for="Type">Loại</label>
                        <select class="form-control" id="Type" name="Type">
                            <option value="1">Admin Site</option>
                            <option value="0">Client Site</option>
                        </select>
                    </div>
                    <!-- DatePayment -->
                    <div class="form-group col-6">
                        <label for="DatePayment">Ngày thanh toán</label>
                        <input type="text" class="form-control" id="DatePayment" name="DatePayment" placeholder="Enter DatePayment">
                    </div>
                    <!-- Select ShowTop -->
                    <div class="form-group col-6">
                        <label for="ShowTop">Hiển thị đua TOP</label>
                        <select class="form-control" id="ShowTop" name="ShowTop">
                            <option value="1">Hiện thị</option>
                            <option value="0">Ẩn</option>
                        </select>
                    </div>
                    <!-- DescriptionTop -->
                    <div class="form-group col-12">
                        <label for="DescriptionTop">Mô tả đua TOP</label>
                        <textarea class="form-control" id="editorDescriptionTop" name="DescriptionTop" placeholder="Enter DescriptionTop"></textarea>
                    </div>

                    <div class="form-group mt-2">
                        <a href="<?= ADMIN_PATH ?>/setting" class="btn btn-primary">Back</a>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Place the first <script> tag in your HTML's <head> -->
<script src="https://cdn.tiny.cloud/1/auhd56uqm9j1zfwbmr8atwuad9lgzd7bf0guugfkylwmd29z/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script>
    tinymce.init({
        selector: 'textarea#editorDescriptionTop',
        height: 500,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
    });
</script>