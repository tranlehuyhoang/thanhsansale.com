<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">Sửa thông báo</div>
            <div class="card-body">
                <form method="post" action="<?=ADMIN_PATH?>/notification/edit/<?= $notification->Id ?>" class="row">
                    <div class="form-group col-6">
                        <label for="Title">Tiêu đề</label>
                        <input type="text" class="form-control" id="Title" name="Title"
                            value="<?= $notification->Title ?>" placeholder="Ví dụ: Thông báo về ...">
                    </div>
                    <div class="form-group col-6">
                        <label for="Image">Chọn loại thông báo</label>
                        <select class="form-control" id="Type" name="Type">
                            <option value="0" <?= $notification->Type == 0 ? 'selected' : '' ?>>Thông báo All</option>
                            <option value="1" <?= $notification->Type == 1 ? 'selected' : '' ?>>Thông báo cho user</option>
                        </select>
                    </div>
                    <!-- Chon user -->
                    <div class="form-group col-6">
                        <label for="UserId">Chọn người nhận thông báo</label>
                        <select class="form-control" id="UserId" name="UserId">
                            <option value="0">Tất cả</option>
                            <?php foreach ($users as $item): ?>
                                <option value="<?= $item->Id ?>" <?= $notification->UserId == $item->Id ? 'selected' : '' ?>>
                                    <?= $item->Username ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- content -->
                    <div class="form-group col-12">
                        <label for="Content">Nội dung</label>
                        <textarea class="form-control" id="editor" name="Content" rows="5"><?=$notification->Content?></textarea>
                    </div>

                    <div class="form-group mt-2">
                        <a href="<?=ADMIN_PATH?>/notification" class="btn btn-primary">Quay về</a>
                        <button type="submit" class="btn btn-success">Lưu lại</button>
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
        selector: 'textarea#editor',
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