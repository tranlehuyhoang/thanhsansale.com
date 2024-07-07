<?php

use App\Services\Common\AlertSession;
use App\Services\Common\Session;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="keywords" content="<?= $settingClient->Keyword ?>" />
    <meta name="description" content="<?= $settingClient->Description ?>" />
    <meta name="author" content="<?= $settingClient->Copyright ?>" />
    <link rel="icon" type="image/png" href="<?= $settingClient->Favicon ?>" />
    <title><?= $title ?></title>

    <link rel="stylesheet" type="text/css" href="/client/vendor/slick/slick.min.css" />
    <link rel="stylesheet" type="text/css" href="/client/vendor/slick/slick-theme.min.css" />

    <link href="/client/vendor/icons/icofont.min.css" rel="stylesheet" type="text/css" />

    <link href="/client/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <link href="/client/css/style.css" rel="stylesheet" />

    <link href="/client/vendor/sidebar/demo.css" rel="stylesheet" />
    <script src="/client/vendor/jquery/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="/client/vendor/sweetalert2@11.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body class="fixed-bottom-padding">
    <div class="border-bottom p-3 d-none mobile-nav">
        <div class="title d-flex align-items-center nav-mobile">
            <a href="/" class="text-decoration-none text-white d-flex align-items-center">
                <img class="osahan-logo mr-2" src="<?= $settingClient->Logo ?>" />
                <h4 class="font-weight-bold text-white m-0" style="font-size:20px"><?= $settingClient->SiteName ?></h4>
            </a>
            <p class="ml-auto m-0 mr-1">
                <a href="/" class="text-decoration-none bg-white p-1 rounded shadow-sm d-flex align-items-center">
                    <span class="badge badge-danger p-1 ml-1 small" style="font-size:14px">Hoàn tiền đến 30%</span>
                </a>
            </p>

            <?php if (isset(Session::get('user')->Username)) { ?>
                <a class="text-decoration-none bg-white p-1 rounded shadow-sm d-flex align-items-center" href="/profile">
                    <img width="30" height="30" class="mr-1" src="https://cdn-icons-png.freepik.com/512/219/219986.png" alt="<?= Session::get('user')->Username ?>">
                    <span title="<?= Session::get('user')->Username ?>" style=" padding 1px; width: 50px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;"><?= Session::get('user')->Username ?></span>
                </a>
            <?php } else { ?>
                <a class="text-decoration-none bg-white p-1 rounded shadow-sm d-flex align-items-center" href="/auth/login">
                    <span style="padding: 1px;">Đăng nhập</span>
                </a>
            <?php } ?>
            <a class="toggle ml-3" href="#"><i class="icofont-navigation-menu"></i></a>
        </div>
        <div class="row m-2 d-flex">
            <div class="form-group col-8">
                <input type="text" id="txtLinkMobile" class="form-control" placeholder="Dán link sản phẩm" />
            </div>
            <div class="col-4">
                <button class="btn btn-info rounded-right" onclick="search(true)">
                    <i class="icofont-search"></i> Tìm kiếm
                </button>
            </div>
        </div>
    </div>


    <a href="#" target="_blank" rel="noopener noreferrer">
        <div class="theme-switch-wrapper" style="bottom: 100px;">
            <span class="badge badge-danger p-1 ml-1 small" style="font-size:14px; white-space: nowrap; margin-right: 50px;">Hỗ trợ 24/7</span>
        </div>
    </a>
    <!-- Mua hang hoan tien -->
    <!-- <a href="https://www.messenger.com/t/195838256942518" target="_blank" rel="noopener noreferrer">
        <div class="theme-switch-wrapper" style="bottom: 70px;">
            <label class="theme-switch" for="checkbox">
                <img width="30" height="30" src="https://upload.wikimedia.org/wikipedia/commons/6/6c/Facebook_Logo_2023.png" alt="">
            </label>
        </div>
    </a> -->
    <!-- thanhsansale -->
    <a href="https://www.messenger.com/t/296832190183614" target="_blank" rel="noopener noreferrer">
        <div class="theme-switch-wrapper" style="bottom: 70px;">

            <label class="theme-switch" for="checkbox">
                <img width="30" height="30" src="https://upload.wikimedia.org/wikipedia/commons/6/6c/Facebook_Logo_2023.png" alt="">
            </label>
        </div>
    </a>

    <!-- <a href="https://zalo.me/g/odqcwk307" target="_blank" rel="noopener noreferrer">
        <div class="theme-switch-wrapper" style="bottom: 70px;">
            <label class="theme-switch" for="checkbox">
                <img width="30" height="30" src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/91/Icon_of_Zalo.svg/2048px-Icon_of_Zalo.svg.png" alt="">
            </label>
        </div>
    </a> -->
    <!-- telgram -->
    <!-- <a href="https://t.me/joinchat/AAAAAFZ9J9Z9ZjQw" target="_blank" rel="noopener noreferrer">
        <div class="theme-switch-wrapper" style="bottom: 25px;">
            <label class="theme-switch" for="checkbox">
                <img width="30" height="30" src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/82/Telegram_logo.svg/2048px-Telegram_logo.svg.png" alt="">
            </label>
        </div>
    </a> -->


    <?php include_once "ClientLayout/_Header.php"; ?>

    <nav aria-label="breadcrumb" class="breadcrumb mb-0 d-none">
        <div class="container">
            <ol class="d-flex align-items-center mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="#" class="text-success">Trang chủ</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"></li>
            </ol>
        </div>
    </nav>

    <section class="py-4 osahan-main-body">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <?php
                    if (isset($alerts)) {
                        foreach ($alerts as $alert) {
                            echo '<div class="alert alert-' . $alert['type'] . '">' . $alert['message'] . '</div>';
                        }
                        AlertSession::clearAlerts();
                    }
                    ?>
                </div>
            </div>
            <?php include_once "../App/Views/ClientSite/" . $viewName . ".php"; ?>
        </div>
    </section>

    <?php include_once "ClientLayout/_NavMobile.php"; ?>

    <?php include_once "ClientLayout/_Footer.php"; ?>

    <?php include_once "ClientLayout/_Modal.php"; ?>
    <?php include_once "ClientLayout/_ModalAlert.php"; ?>


    <script>
        var element = document.getElementById("UserAvatarText");
        if (element) {
            element.addEventListener("click", function() {
                if (this.getAttribute("aria-expanded") === "true") {
                    this.setAttribute("aria-expanded", "false");
                } else {
                    this.setAttribute("aria-expanded", "true");
                }
            });
        }
    </script>

    <script src="/client/vendor/bootstrap/js/bootstrap.bundle.min.js" type="text/javascript"></script>

    <script type="text/javascript" src="/client/vendor/slick/slick.min.js"></script>

    <script type="text/javascript" src="/client/vendor/sidebar/hc-offcanvas-nav.js"></script>

    <script src="/client/js/osahan.js" type="text/javascript"></script>
    <script src="/client/js/hmz.js" type="text/javascript"></script>
    <script src="/client/vendor/rocket-loader.min.js" data-cf-settings="51b6a609a0b7a5559ed6bb15-|49" defer=""></script>
</body>

</html>