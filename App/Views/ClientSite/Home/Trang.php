<style>
    .page-content img {
        max-width: 100%;
        height: auto;
        object-fit: cover;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- boostrap card -->
            <div class="card">
                <div class="card-header">
                    <?= $trang->Title ?>
                </div>
                <div class="card-body">
                    <div class="page-content">
                        <?= $trang->Content ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>