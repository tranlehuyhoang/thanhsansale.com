<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">Sửa Shop - <?= $category->Name ?></div>
            <div class="card-body">
                <form method="post" action="<?=ADMIN_PATH?>/category/edit/<?= $category->Id ?>" class="row">
                    <div class="form-group col-6">
                        <label for="Name">Tên shop</label>
                        <input type="text" class="form-control" id="Name" name="Name" placeholder="Ví dụ: Shopee" value="<?= $category->Name ?>">
                    </div>
                    <!-- Discount -->
                    <div class="form-group col-6">
                        <label for="Discount">Chiết Khấu (0.1 => 1% )</label>
                        <input type="text" class="form-control" id="Discount" name="Discount" placeholder="Chiết khấu" value="<?= $category->Discount ?>">
                    </div>
                    <div class="form-group col-6">
                        <label for="Image">Link Image</label>
                        <input type="text" class="form-control" id="Image" name="Image" placeholder="Hình đại diện có dạng JPG|PNG và là hình chữ nhật" value="<?= $category->Image ?>">
                        <div class="image">
                            <img width="100" height="100" src="<?= $category->Image ?>" alt="<?= $category->Name ?>" class="img-thumbnail">
                        </div>
                    </div>
                    <div class="form-group col-12">
                        <label for="Config">Cấu hình</label>
                        <textarea class="form-control" rows="10" id="Config" name="Config" placeholder="Cấu hình shop | Token | Cookie"><?= $category->Config ?></textarea>
                    </div>
                    <div class="form-group col-12">
                        <label for="Content">Nôi dung</label>
                        <textarea class="form-control" rows="10" id="editor" name="Content"><?=$category->Content?></textarea>
                    </div>

                    <div class="form-group mt-2">
                        <a href="<?=ADMIN_PATH?>/category" class="btn btn-primary">Quay về</a>
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