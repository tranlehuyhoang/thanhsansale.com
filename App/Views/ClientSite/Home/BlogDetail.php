<style>
    .item-content img {
        width: 100%;
        height: auto;
        object-fit: cover;
    }

    .item-image img{
        width: 100%;
        height: auto;
        object-fit: cover;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="item mb-2 container">
            <h4><?= $blog->Title ?></h4>
            <div class="item-image">
                <img src="<?= $blog->Image ?>" alt="<?= $blog->Title ?>" class="img-thumbnail">
            </div>
            <div class="item-content row">
                <div class="item-summary">
                    <?= $blog->Content ?>
                </div>
            </div>
        </div>
    </div>
</div>