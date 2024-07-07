<div class="title d-flex align-items-center py-3">
    <h5 class="m-0">Lazada</h5>
    <!-- <a class="ml-auto btn btn-outline-success btn-sm" href="/san-pham">Xem thêm</a> -->
</div>

<div class="pick_today">
    <div class="row" id="products_lazada">

    </div>
    <!-- pagination -->
    <div class="row">
        <div class="col-12">
            <!-- next -->
            <!-- <div class="text-center">
                <button class="btn btn-success mr-2" id="btnBack" onclick="backPage()">Trở lại</button>
                <button class="btn btn-success" id="btnNext" onclick="nextPage()">Xem thêm</button>
            </div> -->
        </div>
    </div>

</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $before = document.getElementById('products_lazada');
        $before.innerHTML = `<div class="col-12 text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>`;
        loadDataLazada();
    });

    function loadDataLazada() {
        fetch('/api/lazada/product-feed', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: {
                    pageIndex: 1
                }
            })
            .then(response => response.json())
            .then(res => {
                let html = ``;
                res.data.forEach(item => {
                    html += `<div class="col-6 col-md-3 mb-3" >
                            <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                                <div class="list-card-image-shoppe">
                                    <a href="/san-pham/lazada/${item.productId}" class="text-dark">
                                        <div class="member-plan position-absolute">
                                            
                                            <span class="badge mr-3 ml-3 mt-1 badge-danger-shoppe">Hoàn tiền đến: ${item.totalCommissionAmount}</span>
                                        </div>
                                        <div class="p-3">
                                            <img src="${item.pictures[0]}" class="img-fluid item-img w-100 mb-3" />
                                            <h6 title="${item.productName}"
                                            style="
                                                display: -webkit-box;-webkit-line-clamp: 2;
                                                -webkit-box-orient: vertical;
                                                overflow: hidden;
                                                text-overflow: ellipsis;
                                            "
                                            >${item.productName}</h6>
                                            <div class="d-flex align-items-center">
                                                <h6 class="price m-0 text-success">${item.discountPrice}</h6>
                                                
                                            </div>

                                            <a role="button"  class="btn btn-success btn-sm mt-2" onclick="getLink('${item.productId}')" target="_blank">
                                                Mua ngay
                                            </a>
                                            <button class="btn btn-info btn-sm mt-2" onclick="shareLink('${item.productId}')" >
                                                Chia Sẻ Link
                                            </button>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>`;
                });
                $('#products_lazada').html(html);
            })
            .catch(error => console.log(error));
    }


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
                }else{
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