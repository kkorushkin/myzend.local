
$(document).ready(function(){
// NOTICE: SORT COLLECTION - RETURN IT SORTED //////////////////////////////////////////////////////////////////////////
    $('.type-filters').click(function () {
        $('.type-filters-active').css('font-size','').attr('class','type-filters');
        $(this).css('font-size','medium').attr('class','type-filters-active');
        var method = $('.price-filters-active').attr('id');
        var category = $('.type-filters-active').attr('id');
        var more_then = $('#label-output-min').data('num');
        var less_then = $('#label-output-max').data('num');
        var color = $('.color-filters-active').data('color');
        $.ajax({
            type: "POST",
            url: "/collection/sort-collection-ajax",
            data: {
                method: method,
                category: category,
                more_then: more_then,
                less_then: less_then,
                color: color
            },
            async: false,
            success: function (data) {
                $('#collection-foreach-collection')
                    .html(data);
            }
        });
        $('#sort-info').text($('.type-filters-active').data('bread') + $('.price-filters-active').data('bread') +  $('.color-filters-active').data('bread'));
    });
// NOTICE:
    $('.price-filters').click(function () {
        $('.price-filters-active').css('font-size','').attr('class','price-filters');
        $(this).css('font-size','medium').attr('class','price-filters-active');
        var method = $('.price-filters-active').attr('id');
        var category = $('.type-filters-active').attr('id');
        var more_then = $('#label-output-min').data('num');
        var less_then = $('#label-output-max').data('num');
        var color = $('.color-filters-active').data('color');
        $('.price-like-filters input').val('');
        $.ajax({
            type: "POST",
            url: "/collection/sort-collection-ajax",
            data: {
                method: method,
                category: category,
                more_then: more_then,
                less_then: less_then,
                color: color
            },
            async: false,
            success: function (data) {
                $('#collection-foreach-collection')
                    .html(data);
            }
        });
        $('#sort-info').text($('.type-filters-active').data('bread') + $('.price-filters-active').data('bread') +  $('.color-filters-active').data('bread'));
    });
// NOTICE:
    $('#price-slider-submit').click(function(){
        var method = $('.price-filters-active').attr('id');
        var category = $('.type-filters-active').attr('id');
        var more_then = $('#label-output-min').data('num');
        var less_then = $('#label-output-max').data('num');
        var color = $('.color-filters-active').data('color');
//console.log(more_then);
//console.log(less_then);
        $.ajax({
            type: "POST",
            url: "/collection/sort-collection-ajax",
            data: {
                method: method,
                category: category,
                more_then: more_then,
                less_then: less_then,
                color: color
            },
            async: false,
            success: function (data) {
                $('#collection-foreach-collection')
                    .html(data);
            }
        });
        $('#sort-info').text($('.type-filters-active').data('bread') + $('.price-filters-active').data('bread') +  $('.color-filters-active').data('bread'));
    });
    $('.color-filters').click(function(){
        $('.color-filters-active').attr('class','color-filters').text('');
        $(this).attr('class','color-filters-active');
        var method = $('.price-filters-active').attr('id');
        var category = $('.type-filters-active').attr('id');
        var more_then = $('#label-output-min').data('num');
        var less_then = $('#label-output-max').data('num');
        var color = $('.color-filters-active').data('color');
        $.ajax({
            type: "POST",
            url: "/collection/sort-collection-ajax",
            data: {
                method: method,
                category: category,
                more_then: more_then,
                less_then: less_then,
                color: color
            },
            async: false,
            success: function (data) {
//console.log(data);
                $('#collection-foreach-collection')
                    .html(data);
            }
        });
        $('#sort-info').text($('.type-filters-active').data('bread') + $('.price-filters-active').data('bread') +  $('.color-filters-active').data('bread'));
    });
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $(".color-picker-color").click(function () {
        //$('.coll-filters').css('font-size','').removeAttr('data-this');
        //$(this).css('font-size','medium').attr('data-this','1');
        var value = $(this).attr('id');
        var type = $(this).find('span').text();
        $.ajax({
            type: "POST",
            url: "/collection/sort-collection-ajax",
            data: {
                value: value,
                type: type
            },
            async: false,
            success: function (data) {
//alert(data);
                $('#all-about-collection-wrapper')
                    .html(data);
            }
        });
        $('#sort-info').text($('.type-filters-active').data('bread') + $('.price-filters-active').data('bread') +  $('.color-filters-active').data('bread'));
    });
// NOTICE:  'TO CART' BUTTON APPEAR ACTION
    /*
    $('.coll-table').on({
        mouseenter: function () {
            var item_id= $(this).data('num');
            $('#this-to-cart-' + item_id)
                //.fadeIn()
                .css('display', 'block');
        },
        mouseleave: function () {
            var item_id= $(this).data('num');
            $('#this-to-cart-' + item_id)
                //.fadeOut()
                .css('display', 'none');
        }
    });
    */
// NOTICE: SHOW 'TO-CART' CONFIRMATION DIALOG
    $("[id^=this-to-cart-], #item-details-to-cart").click(function () {
        $('#collection-overlay')
            .fadeIn()
            .css('display', 'block');
        $.post("/collection/to-cart-confirm", {value: $(this).data('num')}, function (data) {
            $('#to-cart-confirm')
                .html(data)
                .fadeIn()
                .css('display', 'block');
            $('#collection-main-wrapper')
                .css('filter', 'blur(3px)');
            // CHANGE PRICE DEPENDING OF QUANTITY WITH KEYBOARD
            $('#to-cart-confirm-item_quantity').keyup(function(){
                var i = $('#to-cart-confirm-item_price').data('num');
                var j = $(this).val();
                if(j == 0){
                    alert('can\'t order zero qty.');
                    $('#to-cart-confirm-item_quantity').val(1);
                }else if(j > 10000){
                    alert('can\'t order more then 10000 per order.');
                    $('#to-cart-confirm-item_quantity').val(10000);
                }
                //alert(i + ' and ' + j)
                $('#to-cart-confirm-item_price').text((i * j) + ' $');
            });
            // CHANGE PRICE DEPENDING OF QUANTITY WITH '- +' FORM BUTTON
            $('.math-sign').click(function(){
                var i = parseInt($('#to-cart-confirm-item_price').data('num'));
                var j = parseInt($('#to-cart-confirm-item_quantity').val());
                switch ($(this).text()){
                    case '-':
                        j--;
                        $('#to-cart-confirm-item_quantity').val(j);
                        if(j < 1){
                            j = 1;
                            alert('can\'t order zero qty.'); $('#to-cart-confirm-item_quantity').val(1);
                        }
                        $('#to-cart-confirm-item_price').text('$' + (i * j));
                        break;
                    case '+':
                        j++;
                        $('#to-cart-confirm-item_quantity').val(j);
                        if(j > 10000){
                            j = 10000;
                            alert('can\'t order more then 10000 per order.');
                            $('#to-cart-confirm-item_quantity').val(10000);
                        }
                        $('#to-cart-confirm-item_price').text('$' + (i * j));
                        break;
                    default:
                        alert('default');
                }
                //alert(i + ' and ' + j)
                //$('#to-cart-confirm-item_price').text((i * j) + ' $');
            });
// NOTICE: ADD COLLECTION ITEM TO DB_CARTS
            // FADE OUT BLUR EFFECT, HIDE OVERLAY, HIDE CONFIRMATION DIALOG
            $('#to-cart-confirm-btn').click(function(){
                $('#shopping-cart-img').attr('src','/img/cart-header-full.png');
                $('#to-cart-confirm')
                    .fadeOut()
                    .css('display', 'none');
                $('#collection-overlay')
                    .fadeOut()
                    .css('display', 'none');
                $('#collection-main-wrapper')
                    .css('filter', 'blur(0px)');
                $.post("/collection/to-cart", {
                    item_id: $('#to-cart-confirm-item_id').val(),
                    item_quantity: $('#to-cart-confirm-item_quantity').val().trim(),
                    item_price: $('#to-cart-confirm-item_price').text()
                },function(data){
                    alert(data);
                });
            });
            // FADE OUT BLUR EFFECT, HIDE OVERLAY, HIDE CONFIRMATION DIALOG
            $('#to-cart-cancel-btn').click(function(){
                $('#to-cart-confirm')
                    .fadeOut()
                    .css('display', 'none');
                $('#collection-overlay')
                    .fadeOut()
                    .css('display', 'none');
                $('#collection-main-wrapper')
                    .css('filter', 'blur(0px)');
            });
        });
    });
// NOTICE: SHOW CART DETAILS IN MODAL VIEW + SHOW CART'S CLOSE ICON
    $('#shopping-cart').click(function() {
        $.post("/cart", function(data) {
            $('#cart-view-modal-k')
                .html(data)
                .toggle('slow')
        });
    });
// NOTICE: FILTER CATEGORIES ARROWS
    $('.col-opt').click(function(){
        $(this).next('.col-opt-child, .col-opt-child-active').toggle();
        $(this).children('.popup-arrow-down').toggle();
        $(this).children('.popup-arrow-right').toggle();
    });
// NOTICE: ADD COLOR TO DIV BACKGROUND VIA DATA-COLOR ATTRIBUTE
    $('.color-filters').each(function(){
        $(this).css('background-color',$(this).data('color'));
    });
});