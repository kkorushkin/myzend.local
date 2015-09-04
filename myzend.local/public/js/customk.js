// NOTICE: SORT COLLECTION - RETURN IT SORTED BY DESC
$(document).ready(function() {
    $(".coll-filters").click(function () {
        $('.coll-filters').css('font-size','').removeAttr('data-this');
        $(this).css('font-size','medium').attr('data-this','1');
        var value = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: "/collection/sort-collection-ajax",
            data: {
                value: value,
                type: $(this).find('span').text()
            },
            async: false,
            success: function (data) {
                //alert(data);
                $('#collection-main-wrapper')
                    .html(data);
            }
        });
    });
// NOTICE:  'TO CART' BUTTON APPEAR ACTION
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
                    alert('can\'t order more then 10000 per order.')
                    $('#to-cart-confirm-item_quantity').val(10000);
                }
                //alert(i + ' and ' + j)
                $('#to-cart-confirm-item_price').text((i * j) + ' $');
            });
            // CHANGE PRICE DEPENDING OF QUANTITY WITH '- +' FORM BUTTON
            $('.math-sign').click(function(){
                var i = $('#to-cart-confirm-item_price').data('num');
                var j = $('#to-cart-confirm-item_quantity').val();
                var n = (j + $(this).val() + 1);
                alert (n);
                if(j == 0){
                    alert('can\'t order zero qty.');
                    $('#to-cart-confirm-item_quantity').val(1);
                }else if(j > 10000){
                    alert('can\'t order more then 10000 per order.')
                    $('#to-cart-confirm-item_quantity').val(10000);
                }
                //alert(i + ' and ' + j)
                $('#to-cart-confirm-item_price').text((i * j) + ' $');
            });
// NOTICE: ADD COLLECTION ITEM TO DB_CART
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
// NOTICE:
    $('.col-opt').click(function(){
        $(this).next('.col-opt-child').toggle();
        $(this).children('.popup-arrow-down').toggle();
        $(this).children('.popup-arrow-right').toggle();
    });
// NOTICE:
    /*
    $('.col-opt-child ul li').click(function(){
        alert($(this).attr('class'));
    });
    */
});