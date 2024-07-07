<style>
    .timeline-steps {
        display: flex;
        justify-content: center;
        flex-wrap: wrap
    }

    .timeline-steps .timeline-step {
        align-items: center;
        display: flex;
        flex-direction: column;
        position: relative;
        margin: 1rem
    }

    @media (min-width:768px) {
        .timeline-steps .timeline-step:not(:last-child):after {
            content: "";
            display: block;
            border-top: .25rem dotted #3b82f6;
            width: 3.46rem;
            position: absolute;
            left: 7.5rem;
            top: .3125rem
        }

        .timeline-steps .timeline-step:not(:first-child):before {
            content: "";
            display: block;
            border-top: .25rem dotted #3b82f6;
            width: 3.8125rem;
            position: absolute;
            right: 7.5rem;
            top: .3125rem
        }
    }

    .timeline-steps .timeline-content {
        width: 10rem;
        text-align: center
    }

    .timeline-steps .timeline-content .inner-circle {
        border-radius: 1.5rem;
        height: 1rem;
        width: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #3b82f6
    }

    .timeline-steps .timeline-content .inner-circle:before {
        content: "";
        background-color: #3b82f6;
        display: inline-block;
        height: 3rem;
        width: 3rem;
        min-width: 3rem;
        border-radius: 6.25rem;
        opacity: .5
    }

    .question {

        margin-bottom: 1rem;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        padding: 10px;
        border-radius: 5px;
        display: grid;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="container">
            <div class="row text-center justify-content-center mb-5">
                <div class="col-xl-6 col-lg-8">
                    <h3 class="font-weight-bold">Hỗ trợ đơn hàng <?php 
                        $route = $_SERVER['REQUEST_URI'];
                        if (strpos($route, 'shoppe') !== false) {
                            echo "Shopee";
                        } else if (strpos($route, 'lazada') !== false) {
                            echo "Lazada";
                        }
                    ?></h3>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="timeline-steps aos-init aos-animate" data-aos="fade-up">
                        <div class="timeline-step">
                            <div class="timeline-content" data-toggle="popover" data-trigger="hover"
                                data-placement="top" title=""
                                data-content="And here's some amazing content. It's very engaging. Right?"
                                data-original-title="today">
                                <div class="inner-circle"></div>
                                <p class="h6 mt-3 mb-1">Hôm nay</p>
                                <p class="h6 text-muted mb-0 mb-lg-0">Mua</p>
                            </div>
                        </div>
                        <div class="timeline-step">
                            <div class="timeline-content" data-toggle="popover" data-trigger="hover"
                                data-placement="top" title=""
                                data-content="And here's some amazing content. It's very engaging. Right?"
                                data-original-title="1day">
                                <div class="inner-circle"></div>
                                <p class="h6 mt-3 mb-1">1 Ngày</p>
                                <p class="h6 text-muted mb-0 mb-lg-0">Ghi nhận đơn</p>
                            </div>
                        </div>

                        <div class="timeline-step mb-0">
                            <div class="timeline-content" data-toggle="popover" data-trigger="hover"
                                data-placement="top" title=""
                                data-content="And here's some amazing content. It's very engaging. Right?"
                                data-original-title="45day">
                                <div class="inner-circle"></div>
                                <p class="h6 mt-3 mb-1">30 ngày</p>
                                <p class="h6 text-muted mb-0 mb-lg-0">Sẽ được trả tiền</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cau hoi thuong gap -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <?= $category->Content ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php
        $route = $_SERVER['REQUEST_URI'];
        // contains shopee
        if (strpos($route, 'shoppe') !== false) {
            include_once "_PartialView/ProductNew.php";
        } else if (strpos($route, 'lazada') !== false) {
            include_once "_PartialView/ProductLazada.php";
        }
        ?>
    </div>
</div>