<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>
        <?= $title; ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<? $settingClient->Name ?>" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<? $settingClient->Favicon ?>">
    <!-- preloader css -->
    <link rel="stylesheet" href="/assets/css/preloader.min.css" type="text/css" />
    <!-- Sweet Alert-->
    <link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <!-- Bootstrap Css -->
    <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body>

    <!-- <body data-layout="horizontal"> -->
    <div class="auth-page">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-12">
                    <?php

                    use App\Services\Common\AlertSession;

                    if (isset($alerts)) {
                        foreach ($alerts as $alert) {
                            echo '<div class="alert alert-' . $alert['type'] . '">' . $alert['message'] . '</div>';
                        }
                        AlertSession::clearAlerts();
                    }
                    ?>
                </div>
            </div>
            <?php
            include_once "../App/Views/" . $viewName . ".php";
            ?>
        </div>
        <!-- end container fluid -->
    </div>


    <!-- JAVASCRIPT -->
    <script src="/assets/libs/jquery/jquery.min.js"></script>
    <script src="https://themesbrand.com/minia/layouts/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="/assets/libs/simplebar/simplebar.min.js"></script>
    <script src="/assets/libs/node-waves/waves.min.js"></script>
    <script src="/assets/libs/feather-icons/feather.min.js"></script>
    <!-- pace js -->
    <script src="/assets/libs/pace-js/pace.min.js"></script>
    <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <!-- password addon init -->
    <script src="/assets/js/pages/pass-addon.init.js"></script>


</body>

</html>