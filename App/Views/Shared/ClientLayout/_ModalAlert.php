<!-- Modal Alert -->
<style>
    .modal-alert {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
        padding-top: 60px;
    }

    .modal-alert-content {
        position: relative;
        background-color: #fefefe;
        margin: 10% auto;
        padding: 0;
        border: 1px solid #888;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    .modal-alert-header {
        padding: 2px 16px;
        background-color: #5cb85c;
        color: white;
    }

    .modal-alert-body {
        padding: 2px 16px;
    }

    .close-modal {
        color: #aaa;
        position: absolute;
        top: -14px;
        right: 0;
        padding: 12px 16px;
        font-size: 30px;
        font-weight: bold;
    }

    .close-modal:hover,
    .close-modal:focus {
        color: red;
        text-decoration: none;
        cursor: pointer;
    }
    .modal-alert-body .row .col img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
<?php

$url = $_SERVER['REQUEST_URI'];


if (isset($notificationsAll) && $url == '/'):
    ?>
    <div id="myModal" class="modal-alert row">
        <div class="container">
            <div class="modal-alert-content col-lg-8">
                <div class="modal-alert-header">
                    <span class="close-modal">&times;</span>
                    <h4><?= $notificationsAll->Title ?></h4>
                </div>
                <div class="modal-alert-body">
                    <div class="row">
                        <div class="col">
                            <?= $notificationsAll->Content ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<script>
    var modal = document.getElementById("myModal");

    if (modal) {

        modal.style.display = "block";

        var span = document.getElementsByClassName("close-modal")[0];


        span.onclick = function () {
            modal.style.display = "none";
        }

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // block click outside modal
        modal.onclick = function (event) {
            event.stopPropagation();
        }
    }
</script>