$(function(){

    /* Filters */
    $('body').on('change', '.w_sidebar input', function(){
        var checked = $('.w_sidebar input:checked');
        var data = '';
        checked.each(function(){
            data += this.value + ',';
        });

        if(data){
            $.ajax({
                url: location.href,
                data: {filter: data},
                type: 'GET',
                beforeSend: function(){

                    $('.preloader').fadeIn(300, function(){
                        $('.product-one').hide();
                    });
                },
                success: function (res) {
                    $('.preloader').delay(500).fadeOut('slow', function () {
                        $('.product-one').html(res).fadeIn();
                        var url = location.search.replace(/filter(.+?)(&|$)/g, ''); // $2
                        var newURL = location.pathname + url + (location.search ? "&" : "?") + "filter=" + data;
                        newURL = newURL.replace('&&', '&');
                        newURL = newURL.replace('?&', '?');
                        history.pushState({}, '', newURL);
                    });
                },
                error: function () {
                    alert('Ошибка');
                }
            })
        }
        else{
            window.location = location.pathname;
        }

    });


    /* Search */
    var products = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            wildcard: '%QUERY',
            url: path + '/search/typeahead?query=%QUERY'
        }
    });

    products.initialize();

    $("#typeahead").typeahead({
        // hint: false,
        highlight: true
    },{
        name: 'products',
        display: 'title',
        limit: 10,
        source: products
    });

    $('#typeahead').bind('typeahead:select', function(ev, suggestion) {
        // console.log(suggestion);
        window.location = path + '/search/?s=' + encodeURIComponent(suggestion.title);
    });


    /* Cart */
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


    $('#cart .modal-body').on('click', '.del-item', function(){
        var id = $(this).data('id');
        $.ajax({
            url: '/cart/delete',
            data: {id: id},
            type: 'GET',
            success: function(res){
                showCart(res);
            },
            error: function(){
                alert('Error!');
            }
        });
    });

    function showCart(cart){
        if($.trim(cart) == '<h3>Корзина пуста</h3>'){
            $('#cart .modal-footer a, #cart .modal-footer .btn-danger').css('display', 'none');
        }else{
            $('#cart .modal-footer a, #cart .modal-footer .btn-danger').css('display', 'inline-block');
        }
        $('#cart .modal-body').html(cart);
        $('#cart').modal();

        if($('.cart-sum').text()){
            $('.simpleCart_total').html($('#cart .cart-sum').text());
        }else{
            $('.simpleCart_total').text('Empty Cart');
        }
    }

    $("#get-cart").click(function(){
        getCart();
        return false;
    });

    function getCart() {
        $.ajax({
            url: '/cart/show',
            type: 'GET',
            success: function(res){
                showCart(res);
            },
            error: function(){
                alert('Ошибка! Попробуйте позже');
            }
        });


    }

    $("#clear-cart").click(function(){
        clearCart();
    });

    function clearCart() {
        $.ajax({
            url: '/cart/clear',
            type: 'GET',
            success: function(res){
                showCart(res);
            },
            error: function(){
                alert('Ошибка! Попробуйте позже');
            }
        });
        return false;
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