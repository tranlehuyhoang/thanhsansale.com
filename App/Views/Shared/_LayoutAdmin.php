<?php

use App\Services\Common\AlertSession; ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>
        <?php echo $title; ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?= $setting->Description ?>" name="description" />
    <meta content="<?= $setting->Copyright ?>" name="<?= $setting->Copyright ?>" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="/assets/images/favicon.ico">
    <!-- Sweet Alert-->
    <link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <!-- plugin css -->
    <link href="/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />

    <!-- preloader css -->
    <link rel="stylesheet" href="/assets/css/preloader.min.css" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <!-- jquery -->
    <script src="/assets/libs/jquery/jquery.min.js"></script>
    <!-- cdk edit 5 -->
    <script src="/assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js"></script>
</head>

<body>
    <!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        <header id="page-topbar">
            <?php include_once "Layout/_Header.php" ?>
        </header>

        <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">

            <?php include_once "Layout/_Sidebar.php" ?>

        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <!-- <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">Dashboard</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Dashboard</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div> -->
                    <!-- end page title -->
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
                    <?php

                    include_once "../App/Views/AdminSite/" . $viewName . ".php";
                    ?>
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © <?= $setting->Copyright ?>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Design & Develop by <a href="/" class="text-decoration-underline"><?= $setting->Copyright ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->


    <!-- Right Sidebar -->

    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>
    <!-- JAVASCRIPT -->
    <script src="/assets/libs/jquery/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(function() {
            $(".datepicker").datepicker({
                dateFormat: "dd/mm/yy"
            });
        });
    </script>
    <script src="https://themesbrand.com/minia/layouts/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="/assets/libs/simplebar/simplebar.min.js"></script>
    <script src="/assets/libs/node-waves/waves.min.js"></script>
    <script src="/assets/libs/feather-icons/feather.min.js"></script>
    <!-- pace js -->
    <script src="/assets/libs/pace-js/pace.min.js"></script>
    <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="/assets/js/app.js"></script>

</body>

</html>