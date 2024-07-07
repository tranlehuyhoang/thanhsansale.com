<?php

use App\Services\Common\Helper;

?>
<div class="row" style="margin: 10px;">
    <div class="col-lg-6">
        <div class="recommend-slider mb-3">
            <?php foreach ($product->pictures as $item) { ?>
                <div class="osahan-slider-item">
                    <img src="<?= $item ?>" class="img-fluid mx-auto shadow-sm rounded" alt="Responsive image" />
                </div>
            <?php } ?>
        </div>
        <div class="pd-f d-flex align-items-center mb-3">
            <a class="btn btn-warning p-3 rounded btn-block d-flex align-items-center justify-content-center mr-3 btn-lg" onclick="shareLink('<?= $product->productId; ?>')">
                <i class="icofont-plus m-0 mr-2"></i>Chia Sẻ Link
            </a>
            <a class="btn btn-success p-3 rounded btn-block d-flex align-items-center justify-content-center btn-lg m-0" onclick="getLink('<?= $product->productId; ?>')">
                <i class="icofont-cart m-0 mr-2"></i>Mua Ngay
            </a>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="p-4 bg-white rounded shadow-sm">
            <div class="pt-0">
                <h5 class="font-weight-bold"><?= $product->productName ?></h5>
                <p class="font-weight-light text-dark m-0  align-items-center">
                    Giá KM <b class="h6 text-dark m-0"> <?= ': ' . Helper::formatCurrencyVND($product->discountPrice) ?></b>
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
                    </div>
                </a>
            </div>
            <style>
                .badge {
                    font-size: 18px;
                }
            </style>
            <div class="details">
                <span class="badge badge-success mb-1" tyle="font-size: 18px;">
                    Nhãn hàng: <?= $product->brandName ?>
                </span>

                <span class="badge badge-success mb-1" tyle="font-size: 18px;">
                    Hoàn tiền tối đa: <?= $product->totalCommissionAmount ?>
                </span>
            </div>
        </div>
    </div>
</div>
<script>
    // Copy link
    function shareLink(productId) {
        getLink(productId, true);
    }

    // get link shoppe
    function getLink(productId, isCopy = false) {
        $.ajax({
            url: '/api/lazada/tracking-link/' + productId,
            method: 'GET',
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
                    console.log(res);
                    if (isCopy) {
                        var dummy = document.createElement('textarea');
                        document.body.appendChild(dummy);
                        dummy.value = res.data.trackingLink;
                        dummy.select();
                        document.execCommand('copy');
                        document.body.removeChild(dummy);

                        Swal.fire({
                            icon: 'success',
                            title: 'Bôi đen và copy link',
                            html: '<input type="text" value="' + res.data.trackingLink + '" id="myInput" class="form-control"></input>',
                            showConfirmButton: true,
                        });
                        return;
                    }
                    window.location.href = res.data.trackingLink;
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