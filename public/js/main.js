$(function(){

    // добавить в корзину (делегируем событие для нового контента на странице, которое загружается динамически AJAX-ом)
    $('body').on('click', '.add-to-cart-link', function(e){
        e.preventDefault();
        var id = $(this).data('id'),
            qty = $('.quantity input').val() ? $('.quantity input').val() : 1,
            mod = $('.available select').val();

        //console.log(id + qty + mod);
        $.ajax({
            url: '/cart/add',
            data: {id: id, qty: qty, mod: mod},
            type: 'GET',
            success: function(res){
                showCart(res);
            },
            error: function(){
                alert('Ошибка! Попробуйте позже');
            }
        });
    });

    function showCart(cart){
        console.log(cart);
    }
    /*Cart*/


    // переключение валюты
    $('#currency').change(function(){

        var currency =  $(this).val();
        window.location = 'currency/change?curr=' + currency;
        console.log();
    });


    // поменять цвет в карточке товара
    $('.available select').on('change', function(){

        var modeId = $(this).val(),
            color = $(this).find('option').filter(':selected').data('title'),
            price = $(this).find('option').filter(':selected').data('price'),
            basePrice = $('#base-price').data('price');

        if(price){
            $('#base-price').text(symbolLeft + price + symbolRight);
        }
        else{
            $('#base-price').text(symbolLeft + basePrice + symbolRight);
        }

        console.log(modeId + price + color);
    });





});