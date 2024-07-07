<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">Thêm Mới Tin</div>
            <div class="card-body">
                <form method="post" action="<?=ADMIN_PATH?>/blog/edit/<?=$blog->Id?>" class="row">
                    <div class="form-group col-12">
                        <label for="Title">Tiêu đề</label>
                        <input type="text" class="form-control" id="Title" name="Title" placeholder="" value="<?=$blog->Title?>">
                    </div>
                    <div class="form-group col-6">
                        <label for="Image">Link Image</label>
                        <input type="text" class="form-control" id="Image" name="Image" placeholder="Hình có dạng JPG|PNG và là hình chữ nhật" value="<?= $blog->Image ?>">
                        <div class="image">
                            <img width="100" height="100" src="<?= $blog->Image ?>" alt="<?= $blog->Title ?>" class="img-thumbnail">
                        </div>
                    </div>
                    
                    <div class="form-group col-12">
                        <label for="Content">Nôi dung</label>
                        <textarea class="form-control" rows="10" id="editor" name="Content"><?=$blog->Content?></textarea>
                    </div>

                    <div class="form-group mt-2">
                        <a href="<?=ADMIN_PATH?>/blog" class="btn btn-primary">Quay về</a>
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