<link href="node_modules/froala-editor/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="node_modules/froala-editor/js/froala_editor.pkgd.min.js"></script>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">Sửa Trang - <?= $page->Title ?></div>
            <div class="card-body">
                <form method="post" action="<?= ADMIN_PATH ?>/trang/edit/<?= $page->Id ?>" class="row">
                    <div class="form-group col-12">
                        <label for="Title">Tiêu đề</label>
                        <input type="text" class="form-control" id="Title" name="Title" value="<?= $page->Title ?>" placeholder="Ví dụ: Shopee">
                    </div>
                    <div class="form-group col-12">
                        <label for="Code">Mã CODE</label>
                        <input type="text" class="form-control" id="Code" name="Code" value="<?= $page->Code ?>" placeholder="Ví dụ: Shopee">
                    </div>
                    <!-- IsMenu -->
                    <div class="form-group col-12">
                        <label for="IsMenu">Hiển thị trên Menu</label>
                        <select class="form-control" id="IsMenu" name="IsMenu">
                            <option value="1" <?= $page->IsMenu == 1 ? 'selected' : '' ?>>Có</option>
                            <option value="0" <?= $page->IsMenu == 0 ? 'selected' : '' ?>>Không</option>
                        </select>
                    </div>
                    <div class="form-group col-12">
                        <label for="Content">Nội dung</label>
                        <textarea type="text" class="form-control" rows="10" id="editor" name="Content"><?= $page->Content ?></textarea>
                    </div>
                    <div class="form-group mt-2">
                        <a href="<?= ADMIN_PATH ?>/trang" class="btn btn-primary">Quay về</a>
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