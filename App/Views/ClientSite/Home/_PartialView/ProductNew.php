<div class="title d-flex align-items-center py-3">
    <h5 class="m-0">Shopee</h5>
    <!-- <a class="ml-auto btn btn-outline-success btn-sm" href="/san-pham">Xem thêm</a> -->
</div>

<div class="pick_today">
    <div class="row" id="products">

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
        loadDataShopee();
    });

    function loadDataShopee() {
        fetch('/api/shopee/products')
            .then(response => response.json())
            .then(res => {
                let html = ``;
                res.data.list.forEach(item => {
                    html += `<div class="col-6 col-md-3 mb-3" >
                            <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                                <div class="list-card-image-shoppe">
                                    <a href="/san-pham/${item.item_id}" class="text-dark">
                                        <div class="member-plan position-absolute">
                                           
                                            <span class="badge mr-3 ml-3 mt-1 badge-danger-shoppe">Hoàn tiền đến: ${item.commission}</span>
                                        </div>
                                        <div class="p-3">
                                            <img src="https://down-bs-vn.img.susercontent.com/${item.image}" class="img-fluid item-img w-100 mb-3" />
                                            <h6 title="${item.name}"
                                            style="
                                                display: -webkit-box;-webkit-line-clamp: 2;
                                                -webkit-box-orient: vertical;
                                                overflow: hidden;
                                                text-overflow: ellipsis;
                                            "
                                            >${item.name}</h6>
                                            <div class="d-flex align-items-center">
                                                <h6 class="price m-0 text-success">${item.price}</h6>
                                                <del class="price-old m-1">${item.price_discount}</del>
                                            </div>
                                            <div  class="d-flex align-items-center">
                                                <h6 ${item.discount !='' ? '' : 'hidden'} class="badge badge-danger text-uppercase"> ${item.discount !='' ? 'Giảm '+item.discount : ''}</h6>
                                            </div>
                                            <a role="button"  class="btn btn-success btn-sm mt-2" onclick="getLink('${item.product_link}')" target="_blank">
                                                Mua ngay
                                            </a>
                                            <button class="btn btn-info btn-sm mt-2" onclick="shareLink('${item.product_link}')" >
                                                Chia Sẻ Link
                                            </button>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>`;
                });
                document.getElementById('products').innerHTML = html;

            })
            .catch(error => console.log(error));
    }


    // Chia Sẻ Link
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
                    console.log(res);
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