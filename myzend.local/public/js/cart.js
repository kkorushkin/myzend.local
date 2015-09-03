$(document).ready(function() {
// NOTICE: SHOW/HIDE REMOVE ITEM BUTTON
    $('.cart-item-wrapper').on({
        mouseenter: function(){
            var item_id = $(this).data('num');
            //alert(item_id);
            $('#change-quantity-' + item_id)
                .fadeIn('fast')
                .css('display', 'block');
            $('#cart-item-del-' + item_id)
                .fadeIn('fast')
                .css('display', 'block');
        },
        mouseleave: function(){
            var item_id = $(this).data('num');
            //alert(item_id);
            $('#change-quantity-' + item_id)
                .fadeOut()
                .css('display', 'none');
            $('#cart-item-del-' + item_id)
                .fadeOut()
                .css('display', 'none');
        }
    });
// NOTICE: DELETE ITEM FROM CART (ACTION)
    $('.cart-item-del').click(function(){
        var item_id = $(this).attr('data-num');
        $.ajax({type: 'post', url: '/cart/cart-remove', data: "item_id=" + item_id, async: false, success: function () {
                $.ajax({type: 'post', url: "/cart/index", async: false, success: function(data) {
                    $('#cart-view-modal-k').html(data);
                }
                });
            }
        });
    });
// NOTICE: CLOSE CART DETAILS MODAL
    $('.cart-informer').click(function(){
        $('#cart-view-modal-k').fadeOut();
    });
});
