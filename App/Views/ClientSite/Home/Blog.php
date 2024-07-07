<style>
    .item {
        display: flex;
        margin-right: 10px;
        padding: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
    }

    /*
    .item-image {
        width: 300px;
        height: 200px;
        overflow: hidden;
    }

    .item-image img {
        width: 300px;
        height: 200px;
        object-fit: cover;
    }

    .item-content {
        padding: 0 20px;
    }

    .item-title {
        font-size: 20px;
        font-weight: bold;
    }

    */
</style>
<div class="row">
    <h4>Các bài đăng</h4>
    <div class="col-12">
        <!-- card -->
        <?php foreach ($blogs as $item) : ?>
            <div class="item mb-2 row">
                <div class="item-image col-md-4">
                    <img src="<?= $item->Image ?>" alt="<?= $item->Title ?>" class="img-thumbnail">
                </div>
                <div class="item-content col-md-8">
                    <h5 class="item-title"><?= $item->Title ?></h5>
                    <div class="item-summary">
                        <?php
                        // get the first 100 characters of the content
                        $content = substr($item->Content, 0, 500) . '...';
                        echo $content;
                        ?>
                    </div>
                    <a href="/blog/<?= $item->Slug ?>-<?= $item->Id ?>" class="btn btn-primary">Xem chi tiết</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>