<div class="row">

    <div class="col-lg-12">
        <div class="osahan-home-page">
            <div class="osahan-body">
                <div class="py-3 osahan-promos">
                    <div class="d-flex align-items-center mb-3">
                        <h5 class="m-0">Sàn thương mại điện tử</h5>
                        <!-- <a href="/mall" class="ml-auto btn btn-outline-success btn-sm">Xem thêm</a> -->
                    </div>
                </div>
                <div class="row">
                    <?php
                    // sort by id asc
                    usort($categories, function ($a, $b) {
                        return $a->Id <=> $b->Id;
                    });
                    foreach ($categories as $category): ?>
                        <?php
                        // check slug = blog
                    
                        if ($category->Slug == 'blog'): ?>
                            <div class="col-6 mb-2" title="<?= $category->Name ?>">
                                <a href="/<?= $category->Slug ?>">
                                    <img style="height: 100%;width: 100%;object-fit: cover;" src="<?= $category->Image ?>"
                                        class="img-fluid mx-auto rounded" alt="<?= $category->Name ?>" />
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="col-6 mb-2" title="<?= $category->Name ?>">
                                <a href="/danh-muc/<?= $category->Slug ?>-<?= $category->Id ?>">
                                    <img style="height: 100%;width: 100%;object-fit: cover;" src="<?= $category->Image ?>"
                                        class="img-fluid mx-auto rounded" alt="<?= $category->Name ?>" />
                                </a>
                            </div>
                        <?php
                        endif;
                    endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <style>
        .col-lg-12 ul {
            list-style-type: none;
            display: contents;
            /* Bỏ dấu chấm đầu của <li> */
        }

        .col-lg-12 ul li a span {
            font-size: large;
            /* Chữ to */
            font-weight: bold;
            /* In đậm */
            color: #2bba81;
            /* Màu cam */
            transition: all 0.3s ease;
            /* Hiệu ứng chuyển đổi mượt mà */
        }

        .col-lg-12 ul li a span:hover {
            color: #ffd700;
            /* Màu chữ khi hover */
            text-shadow: 0px 0px 10px rgba(255, 215, 0, 0.7);
            /* Viền shadow khi hover */
        }
    </style>
    <div class="col-lg-12 mt-4 card" style="padding: 10px;box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
        <ul>
            <?php foreach ($pagesHome as $page): ?>
                <li>
                    <i class="icofont-arrow-right" style="color: orange;"></i>
                    <a href="/trang/<?= $page->Slug ?>-<?= $page->Id ?>">
                        <span><?= $page->Title ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
            <!-- <li>
                <i class="icofont-arrow-right" style="color: orange;"></i>
                <span>[Hot] Ma video shoppe giam 50% lít video áp mã</span>
            </li>
            <li>
                <i class="icofont-arrow-right" style="color: orange;"></i>
                <span>Lịch tung mã shoppe tháng 5</span>
            </li> -->
        </ul>
    </div>

    <?php
    $settingClient->ShowTop == 1 ?
        include_once "_PartialView/TopUser.php" : '';
    ?>

    <div class="col-lg-12">
        <?php include_once "_PartialView/ProductNew.php"; ?>
        <?php //include_once "_PartialView/ProductLazada.php"; ?>
    </div>


</div>