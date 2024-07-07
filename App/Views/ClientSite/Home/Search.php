<div class="row" style="margin: 10px;">
    <div class="col-lg-6">
        <div class="recommend-slider mb-3">
            <?php foreach ($product['images'] as $item) { ?>
                <div class="osahan-slider-item">
                    <img src="https://down-tx-vn.img.susercontent.com/<?= $item ?>.webp" class="img-fluid mx-auto shadow-sm rounded" alt="Responsive image" />
                </div>
            <?php } ?>
        </div>
        <div class="pd-f d-flex align-items-center mb-3">
            <a class="btn btn-warning p-3 rounded btn-block d-flex align-items-center justify-content-center mr-3 btn-lg" onclick="shareLink('<?= $product['product_link'] ?>')">
                <i class="icofont-plus m-0 mr-2"></i>Chia Sẻ Link
            </a>
            <a class="btn btn-success p-3 rounded btn-block d-flex align-items-center justify-content-center btn-lg m-0" onclick="getLink('<?= $product['product_link'] ?>')">
                <i class="icofont-cart m-0 mr-2"></i>Mua Ngay
            </a>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="p-4 bg-white rounded shadow-sm">
            <div class="pt-0">
                <h5 class="font-weight-bold"><?= $product['name'] ?></h5>
                <p class="font-weight-light text-dark m-0  align-items-center">
                    Giá KM <b class="h6 text-dark m-0"> <?= ': ' . $product['price'] ?></b>
                    <?php if ($product['discount'] != null) { ?>
                      Giá gốc:  <span class="ml-2"><?= $product['discount'] ?> OFF</span>
                    <?php }
                    if ($product['price_discount'] != null) { ?>
                        <del class="badge badge-dark ml-2">
                            <?= $product['price_discount'] ?>
                        </del>
                    <?php } ?>
                </p>
                <a href="#">
                    <div class="rating-wrap d-flex align-items-center mt-2" style=" display: flex; align-items: center;">
                        <ul class="rating-stars list-unstyled">
                            <li>
                                <i class="icofont-star text-warning"></i>
                                <i class="icofont-star text-warning"></i>
                                <i class="icofont-star text-warning"></i>
                                <i class="icofont-star text-warning"></i>
                                <i class="icofont-star"></i>
                            </li>
                        </ul>
                        <p class="label-rating text-muted ml-2 small mr-2">
                            (<?= count($product['item_rating']['rating_count']) ?> Đánh giá)
                        </p>
                        <p class="badge badge-success"><?= round($product['shop_rating'], 1); ?> <i class="icofont-star"></i></p>
                    </div>
                </a>
            </div>
            <style>
                .badge {
                    font-size: 18px;
                }
            </style>
            <div class="details">
                <span class="badge badge-success mb-1" style="font-size: 18px;">
                    Vị trí Shop: <?= $product['shop_location'] ?>
                </span>

                <h3 class="badge badge-success mb-1" style="font-size: 18px;">
                    Hoàn tiền đến: <?= $product['commission'] ?>
                </h3>
            </div>
        </div>
    </div>
</div>



<script>
    // Copy link
    function shareLink(link) {
        getLink(link, true);
    }

    // get link shoppe
    function getLink(link, isCopy = false) {
        $.ajax({
            url: '/api/shopee/create-link',
            method: 'POST',
            data: {
                link: link
            },
            success: function(res) {
                if (res.statusCode == 401) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: res.data
                    });
                    return;
                }
                if (res.success) {
                    if (isCopy) {
                        var dummy = document.createElement('textarea');
                        document.body.appendChild(dummy);
                        dummy.value = res.data.shortLink;
                        dummy.select();
                        document.execCommand('copy');
                        document.body.removeChild(dummy);

                        Swal.fire({
                            icon: 'success',
                            title: 'Bôi đen và copy link',
                            html: '<input type="text" value="' + res.data.shortLink + '" id="myInput" class="form-control"></input>',
                            showConfirmButton: true,
                        });
                        return;
                    }
                    window.location.href = res.data.shortLink;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: res.message
                    });
                }
            },
            error: function(err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: err.message
                });
            }
        });
    }
</script>