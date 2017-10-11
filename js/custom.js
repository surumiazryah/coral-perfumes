$(document).ready(function () {
    getrecentproduct();
//    getwishlistproduct();
    getcartcount();
    getcarttotal();
    getcartdata();
    $(".cart_items").mouseover(function () {
        $('.shoper-cart').removeClass('hide');
    });
//    $(".shoper-content").mouseout(function(){
//        $('.shoper-cart').addClass('hide');
//    });

    $("body").on("click", ".shoper-content-inner>.shoper-con-det>.remove_item", function () {
        var cartid = $(this).attr('cartid');
        var canname = $(this).attr('canname');
        removecart(cartid, canname);


    });
    $(".add_to_cart").click(function () {

        showLoader();

        $("html, body").animate({
            scrollTop: 0
        }, 1500);
        var canname = $(this).attr('id');
//        var canname = $("#cano_name_" + id).val();
        var qty = $('.q_ty').val();
        addtocart(canname, qty, 'add_to');
    });

    $(".buy_now").click(function () {
        var canname = $(this).attr('id');
//        var canname = $("#cano_name_" + id).val();
        var qty = $('.q_ty').val();
        addtocart(canname, qty, 'buy_now');

    });

    $(".add-cart").click(function () {
        var canname = $(this).attr('id');
        var list_id = $(this).attr('data-val');
        var qty = $('.q_ty').val();
        $.ajax({
            type: "POST",
            url: homeUrl + 'cart/buynow',
            data: {cano_name: canname, qty: qty}
        }).done(function (data) {
            if (data == 9) {

                $('.option_errors').html('<p>Invalid Product.Please try again</p>').show();
            } else {
                removewishlist(list_id, canname);
                getcartcount();
                getcarttotal();
                $(".shopping-cart").fadeToggle("fast");
                $(".shopping-cart-items").html(data);
            }
            hideLoader();
        });
    });

    $(".remove-wish-list").click(function () {
        var canname = $(this).attr('id');
        var list_id = $(this).attr('data-val');
        removewishlist(list_id, canname);
    });

    $(".add_to_wish_list").click(function () {
        var id = $(this).attr('id');
        var div_id = $(this).parent().closest('div').attr('class').split(' ');
        //$(this).closest(".gp_products_inner").prepend($('<div> new div </div>'));

        addwishlist($(this), id, $(this).closest(".gp_products_inner"));
    });
    $('.cart_quantity').on('click', function () {
        showLoader();
        var id = $(this).attr('id');
//        var price = $('.price_' + id).html();
        var quantity = $('#quantity_' + id).val();
        findstock(id, quantity);
        updatecart(id, quantity);
        $("#quantity2_" + id).val(parseInt(quantity));

    });
    $(document).click(function () {
        $(".quantity").each(function () {
            var quantity = $(this).val();
            if (quantity == '') {
                var ids = $(this).attr('id');
                $('#' + ids).val('1');
                var $ids = $(this).attr('id');
                var ids = $ids.split('_');
                var id = ids['1'];
                findstock(id, quantity);
                updatecart(id, quantity);
            }
        });
    });

    $('.quantity').on('change keyup', function () {
        var quantity = this.value
        var $ids = $(this).attr('id');
        var ids = $ids.split('_');
        var id = ids['1'];
        var price = $('.price_' + id).html();
//        var max = $(this).attr('max');
        if (quantity != '' && parseInt(quantity) > '0') {
            findstock(id, quantity);
            var total = (parseInt(price) * parseInt(quantity));
            $('#quantity_' + id).val(parseInt(quantity));
            $("#quantity2_" + id).val(parseInt(quantity));
            updatecart(id, quantity);
        } else if (quantity != '') {
            $('#quantity_' + id).val('1');
        }
    });

    $(".gender-select").click(function () {
        var type = $(this).attr('id');
        var pro_cat = $(this).attr('pro_cat');
        var main_categ = $(this).attr('main-categ');
        var featured = $(this).attr('featured');
        var keyword = $(this).attr('keyword');

        $.ajax({
            type: 'POST',
            cache: false,
            async: false,
            data: {gender: type},
            url: homeUrl + 'product/gender-search',

            success: function (data) {


                if (featured !== "") {
                    if (data == 1) {

                        window.location = homeUrl + 'product/index?id=' + pro_cat + '&type=' + type + '&featured=' + featured;
                    } else if (data == 0) {
                        window.location = homeUrl + 'product/index?id=' + pro_cat + '&featured=' + featured;
                    }

                } else if (keyword !== "") {
                    if (data == 1) {
                        window.location = homeUrl + 'product/index?id=' + pro_cat + '&type=' + type + '&keyword=' + keyword;
                    } else if (data == 0) {
                        window.location = homeUrl + 'product/index?id=' + pro_cat + '&keyword=' + keyword;
                    }

                } else {
                    var url = "'.Url::toRoute('site/index').'";
                    if (data == 1) {
                        window.location = homeUrl + 'product/index?id=' + pro_cat + '&type=' + type;
                    } else if (data == 0) {
                        window.location = homeUrl + 'product/index?id=' + pro_cat;
                    }

                }
            }
        });

    });


    /************ Serach ****************/
    $('.search-keyword').on('keyup', function (e) {
        if ($(this).val()[0] === " ") {


        } else {

            if (e.keyCode != 40 && e.keyCode != 38 && e.keyCode != 27) {
                $.ajax({
                    url: homeUrl + 'product/search-keyword',
                    type: "POST",
                    data: {keyword: $(this).val()},
                    success: function (data) {
                        $('.search-keyword-dropdown').html(data);
                    }
                });
            }
        }
    });

    /********* selected li value to textbox **********/
    $(document).on('click', '.search-dropdown li', function () {
        $('.search-dropdown').hide();
        $('.search-keyword').val($(this).attr('id'));
        $('form#serach-formm').submit();
    });

    /********************li navigation keys ***************/
    $('.search-keyword').on('keydown', function (e) {

        if (e.keyCode == 40) { //down

            var selected = $(".search-selected");
            $('.search-dropdown li').removeClass('search-selected');
            if (selected.next().length == 0) {
                selected.siblings().first().addClass('search-selected');
            } else {
                selected.next().addClass('search-selected');
            }
        } else if (e.keyCode == 38) { //up

            var selected = $(".search-selected");
            $('.search-dropdown li').removeClass('search-selected');
            if (selected.prev().length == 0) {
                selected.siblings().last().addClass('search-selected');
            } else {
                selected.prev().addClass('search-selected');
            }
        } else if (e.keyCode == 27) { //escape

            $('.search-dropdown').hide();
            $('.search-keyword').val('');

        } else if (e.keyCode == 13) { //enter

            var value = $('.search-selected').attr('id');
            $('.search-dropdown').hide();
            $('.search-keyword').val(value);
            $('form#serach-formm').submit();
            e.preventDefault();
        }

        $(".search-dropdown").scrollTop(0);//set to top
        $(".search-dropdown").scrollTop($('.search-selected:first').offset().top - $(".search-dropdown").height())

    });

    /********************Promotion Codes ***************/
    $('.add-promotion').on('click', function (e) {
        var code = $('#promotions-promotion_code').val();
        $.ajax({
            url: homeUrl + 'checkout/promotion-check',
            type: "POST",
            data: {code: code},
            success: function (data) {

                if (data == 1) {
                    $(".promotion-code").after('<div class="help-block" style="color:red">Invalid Code! Please try another one.</div>');
                } else if (data == 2) {
                    $(".promotion-code").after('<div class="help-block" style="color:red"> Code validity expired !</div>');

                } else if (data == 3) {
                    $(".promotion-code").after('<div class="help-block" style="color:red"> Sorry!! You are already used this code!</div>');

                } else if (data == 4) {
                    $(".promotion-code").after('<div class="help-block" style="color:red"> Invalid Code! Please try another one.</div>');

                } else if (data == 5) {
                    $(".promotion-code").after('<div class="help-block" style="color:red"> Sorry!! You are already used this code!</div>');

                } else {
                    var res = $.parseJSON(data);
                    if (!$(".cart-summary-promotion-discount")[0]) {
                        $(".cart-summary-subtotal").after('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 label cart-summary-promotion-discount">Promotion Code Added</div>');
                    }
                    if (!$(".cart-summary-promotion-amount")[0]) {
                        $(".cart-summary-subtotal-amount").after('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 price cart-summary-promotion-amount">' + res.result['total_amount'] + '</div>');

                    } else {
                        $('.cart-summary-promotion-amount').empty();
                        $('.cart-summary-promotion-amount').append(res.result['total_amount']);
                    }

                    $('#id-promotion-table').append('<p id=disc_' + res.result['discount_id'] + '> Promotion code ' + res.result['code'] + ' is added with AED. ' + res.result['amount'] + ' /- off <a class="promotion-remove" title="Remove" id=' + res.result['discount_id'] + '>x</a></p>');
                    $('#promotions-promotion_code').val('');
                    location.reload();
                }

            }
        });

    });


    $(document).on('click', '.promotion-remove', function () {
        var id = $(this).attr('id');
        $.ajax({
            url: homeUrl + 'checkout/promotion-remove',
            type: "POST",
            data: {id: id},
            success: function (data) {
                $('#disc_' + id).remove();
                location.reload();
            }
        });
    });



});
/******/
function removewishlist(list_id, canname) {
    $.ajax({
        url: homeUrl + 'cart/remove-wishlist',
        type: "POST",
        data: {wish_list_id: list_id},
        success: function (data) {
            $('#' + canname).remove();
            location.reload();
        }
    });
}

function findstock(id, quantity) {
    $.ajax({
        type: "POST",
        url: homeUrl + 'cart/findstock',
        data: {cartid: id, quantity: quantity},
        success: function (data) {
            var $data = JSON.parse(data);
            if ($data.msg === "success") {
                $('.total_' + id).html('AED ' + $data.total);
                $('#quantity_' + id).val($data.quantity);
            }
//
        }
    });
}
function updatecart(id, quantity) {
    $.ajax({
        type: "POST",
        url: homeUrl + 'cart/updatecart',
        data: {cartid: id, quantity: quantity},
        success: function (data) {
            var $data = JSON.parse(data);
            if ($data.msg === "success") {
                $('.subtotal').html('AED ' + $data.subtotal);
//                $('.total_' + id).html('AED ' + total);
            }
//
        }
    });
    hideLoader();
}
/******/
function addwishlist(button, id, closest_div) {

    $.ajax({
        type: "POST",
        cache: 'false',
        async: false,
        url: homeUrl + 'ajax/savewishlist',
        data: {product_id: id}
    }).done(function (data) {
        if (data == 0) {
            window.location.href = homeUrl + "site/login-signup";
        } else {
            ShowWishlistPopup(button, id, data, closest_div);
        }
        hideLoader();
    });
}

function ShowWishlistPopup(button, id, flag, closest_div) {
    var offset = button.offset();

    if (flag == 2) {
        closest_div.prepend('<div class="wish-list-popup"><i class="fa fa-check" aria-hidden="true"></i>Already Added to Wishlist</div>');

//		$('#wish-list-popup-' + id).html('<i class="fa fa-check" aria-hidden="true"></i>Already Added to Wishlist');
    } else {
        closest_div.prepend('<div class="wish-list-popup"><i class="fa fa-check" aria-hidden="true"></i>Added to Your Wishlist</div>').delay(500).remove(".wish-list-popup");
//		$('#wish-list-popup-' + id).html('<i class="fa fa-check" aria-hidden="true"></i>Added to Your Wishlist');

    }
    setTimeout(function () {
        $('.wish-list-popup').remove();
    }, 2000);
    $('#wish-list-popup-' + id).fadeIn('fast').delay(1500).fadeOut('slow');
}


function getcarttotal() {

    $.ajax({
        type: "POST",
        cache: 'false',
        async: false,
        url: homeUrl + 'cart/getcarttotal',
        data: {}
    }).done(function (data) {

        $(".cart_amount").html(data);
        hideLoader();
    });
}
function getcartdata() {
    $.ajax({
        type: "POST",
        cache: 'false',
        async: false,
        url: homeUrl + 'cart/selectcart',
        data: {}
    }).done(function (data) {
        $(".shopping-cart-items").html(data);
        //$(".cart_box").show('fast');
        hideLoader();
    });
}

function getcartcount() {

    $.ajax({
        type: "POST",
        cache: 'false',
        async: false,
        url: homeUrl + 'cart/getcartcount',
        data: {}
    }).done(function (data) {
        $(".cart_count").html('(' + data + ')');
        hideLoader();
    });
}
function getrecentproduct() {

    $.ajax({
        type: "POST",
        cache: 'false',
        async: false,
        url: homeUrl + 'product/getrecentproduct',
        data: {}
    }).done(function (data) {
    });
}

function getwishlistproduct() {

    $.ajax({
        type: "POST",
        cache: 'false',
        async: false,
        url: homeUrl + 'product/getwishlistproduct',
        data: {}
    }).done(function (data) {
    });
}

function removecart(cartid, canname) {

    $.ajax({
        type: "POST",
        cache: 'false',
        async: false,
        url: homeUrl + 'cart/removecart',
        data: {cartid: cartid, cano_name: canname}
    }).done(function (data) {
        getcartcount();
        getcarttotal();
        $(".shoper-cart").html(data);
//        $(".shoper-content").html('').html(data);
//        //alert(data);
//        if (data == 'Cart box is Empty') {
//            window.location.href = baseurl + "cart/mycart";
//        } else {
//            location.reload();
//        }
        hideLoader();
    });

}
/****/
function addtocart(canname, qty, action) {
    $.ajax({
        type: "POST",
        url: homeUrl + 'cart/buynow',
        data: {cano_name: canname, qty: qty}
    }).done(function (data) {
        if (data == 9) {

            $('.option_errors').html('<p>Invalid Product.Please try again</p>').show();
        } else {
            getcartcount();
            getcarttotal();
//            $('.option_errors').html("").hide();
            if (action == 'add_to') {
                $(".shopping-cart").fadeToggle("slow");
                $(".shopping-cart-items").html(data);


            } else {
                window.location.href = homeUrl + "cart/mycart";
            }
        }
        hideLoader();

    });
}
function showLoader() {
    $('.page-loading-overlay').removeClass('loaded');
}
function hideLoader() {
    $('.page-loading-overlay').addClass('loaded');
}

