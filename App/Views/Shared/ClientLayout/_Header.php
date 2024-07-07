<?php

use App\Services\Common\Session;

?>
<div class="bg-white shadow-sm osahan-main-nav">
    <nav class="navbar navbar-expand-lg navbar-light bg-white osahan-header py-0 container">
        <a class="navbar-brand mr-0" href="/">
            <img class="img-fluid logo-img rounded-pill border shadow-sm" src="<?= $settingClient->Logo ?>" />
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="ml-3 d-flex align-items-center">
            <div class="dropdown mr-3">
                <a class="text-dark dropdown-toggle d-flex align-items-center osahan-location-drop" href="/"
                    id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div>
                        <i
                            class="icofont-location-pin d-flex align-items-center bg-light rounded-pill p-2 icofont-size border shadow-sm mr-2"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small">Vị trí</p>
                        Viet Nam
                    </div>
                </a>

            </div>

            <div class="input-group mr-sm-2 col-lg-12">
                <input type="text" class="form-control" id="txtLink" placeholder="Dán link sản phẩm" />
                <div class="input-group-prepend" onclick="search(false)">
                    <div class="btn btn-info rounded-right">
                        <i class="icofont-search"></i>
                        Tìm kiếm
                    </div>
                </div>
            </div>

        </div>
        <div class="ml-auto d-flex align-items-center">
            <!-- <div class="dropdown mr-2">
                <a href="#" class="text-dark dropdown-toggle not-drop" id="dropdownMenuNotification"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i
                        class="icofont-notification d-flex align-items-center bg-light rounded-pill p-2 icofont-size border shadow-sm">
                    </i>
                </a>
                <div class="dropdown-menu dropdown-menu-right p-0 osahan-notifications-main"
                    aria-labelledby="dropdownMenuNotification">

                    <div class="osahan-notifications bg-white p-2">
                        <a href="/" class="text-decoration-none text-muted">
                            <div class="notifiction small">
                                <div class="ml-3">
                                    <p class="font-weight-bold mb-1">New Promos Coming</p>
                                    <p class="small m-0">
                                        <i class="icofont-ui-calendar"></i> Sunday, 10:30 AM
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div> -->
            <?php
            if (isset(Session::get('user')->Username)) { ?>
                <a href="/profile" class="mr-2 text-success bg-white  p-2 icofont-size border shadow-sm">
                    <img width="30" height="30" class="mr-1" src="https://cdn-icons-png.freepik.com/512/219/219986.png"
                        alt="<?= Session::get('user')->Username ?>">
                    <span style="max-width: 40px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;"></span><?= Session::get('user')->Username ?>
                </a>
                <!-- Button logout -->
                <a href="/auth/logout" class="mr-2 text-danger bg-danger  p-2 icofont-size border shadow-sm">
                    Logout <i class="icofont-logout"></i>
                </a>
            <?php } else { ?>
                <a href="#" data-toggle="modal" data-target="#login"
                    class="mr-2 text-dark bg-light  p-2 icofont-size border shadow-sm">
                    <i class="icofont-login"></i>
                    Đăng nhập
                </a>
            <?php } ?>
        </div>
    </nav>

    <div class="bg-color-head">
        <div class="container menu-bar d-flex align-items-center">
            <ul class="list-unstyled form-inline mb-0">
                <li class="nav-item active">
                    <a class="nav-link text-white pl-0" href="/">Trang chủ <span class="sr-only">(current)</span></a>
                </li>
                <?php foreach ($pages as $page) { ?>
                    <li class="nav-item">
                        <a class="nav-link text-white"
                            href="/trang/<?= $page->Slug ?>-<?= $page->Id ?>"><?= $page->Title ?></a>
                    </li>
                <?php } ?>

            </ul>
            <!-- <div class="list-unstyled form-inline mb-0 ml-auto">
                <a href="/" class="text-white px-3 py-2">Chiếu khấu hot</a>
                <a href="/chieu-khau" class="text-white bg-offer px-3 py-2">
                    <i class="icofont-sale-discount h6"></i>Lên tới 30%
                </a>
            </div> -->
        </div>
    </div>
</div>
<script>
    function search(isMobile = false) {
        let link = isMobile ? document.getElementById('txtLinkMobile').value : document.getElementById('txtLink').value;
        // check link is shopee.vn or lazada.vn
        // if (!link.includes('https://') && !link.includes('shopee.vn') || !link.includes('lazada.vn')) {
        //     Swal.fire({
        //         icon: 'info',
        //         title: 'Lỗi',
        //         text: 'Link không hợp lệ',
        //     });
        //     return;
        // }
        console.log('Link', link);

        if (link.includes('shp.ee') || link.includes('shope.ee')) {
            // Call API to get link
            GetFinalLink(link);
        } else if (link.includes('shopee.vn')) {
            // Call API to get link
            renderLink(link);
        }
        else if (link.includes('lazada.vn') || link.includes('s.lazada.vn')) {
            // Call API to search product
            renderLinkLazada(link);
        }

        else {
            // Call API to search product
            renderProduct(link);
        }
    }

    function renderProduct(link) {
        // Your code to render product
        console.log("Search Product: " + link);
    }

    function renderLink(link) {
        $.ajax({
            url: '/api/shopee/render-link',
            method: 'POST',
            data: {
                link: link
            },
            success: function (res) {
                if (res.success) {
                    window.location.href = '/san-pham/' + res.data;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: res.message,
                    });
                }
            },
            error: function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Có lỗi xảy ra',
                });
            }
        });
    }

    function renderLinkLazada(link) {
        $.ajax({
            url: '/api/lazada/get-by-link',
            method: 'POST',
            data: {
                link: link
            },
            beforeSend: function () {
                Swal.fire({
                    title: 'Đang tìm kiếm sản phẩm...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                });
            },
            success: function (res) {
                if (res.success) {
                    if (res.data.length == 0) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Thống báo',
                            text: 'Sản phẩm này không được hoàn tiền! Vui lòng chọn sản phẩm khác',
                        });
                        return;
                    }
                    window.location.href = '/san-pham/lazada/' + res.data[0].productId;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: response.message,
                    });
                }
            },
            error: function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Có lỗi xảy ra',
                });
            }
        });
    }


    function GetFinalLink(link) {
        $.ajax({
            url: '/api/shopee/get-final-link',
            method: 'POST',
            data: {
                link: link
            },
            success: function (res) {
                if (res.success) {
                    renderLink(res.data);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: response.message,
                    });
                }
            },
            error: function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Có lỗi xảy ra',
                });
            }
        });
    }
</script>