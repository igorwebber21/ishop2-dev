<?php

    namespace app\models;

    use ishop\App;

    class Cart extends AppModel {

        public function addToCart($product, $qty = 1, $mod = null){
            //session_unset();

            // при первом добавлении в корзину, фиксируем валюту как базовую
            // дальнейшие добавления в корзину будут пересчитываться в этой валюте
            if(!isset($_SESSION['Cart.currency'])){
                $_SESSION['Cart.currency'] = App::$app->getProperty('currency');
            }

            // если есть модификатор (цвет, размер и т.д.)
            if($mod){
                $ID = "{$product->id}-{$mod->id}";
                $title = "{$product->title} ({$mod->title})";
                $price = $mod->price;
            }else{
                $ID = $product->id;
                $title = $product->title;
                $price = $product->price;
            }

            // если товар уже добавлен в козину, добавить количество +qty
            if(isset($_SESSION['Cart'][$ID])){
                $_SESSION['Cart'][$ID]['qty'] += $qty;
            }
            else{   // если товара еще нет в корзине
                $_SESSION['Cart'][$ID] = [
                    'qty' => $qty,
                    'title' => $title,
                    'alias' => $product->alias,
                    'price' => $price * $_SESSION['Cart.currency']['value'],
                    'img' => $product->img,
                ];
            }
            // суммарное количество и сумма
            $_SESSION['Cart.qty'] = isset($_SESSION['Cart.qty']) ? $_SESSION['Cart.qty'] + $qty : $qty;
            $_SESSION['Cart.sum'] = isset($_SESSION['Cart.sum']) ? $_SESSION['Cart.sum'] + $qty * ($price * $_SESSION['Cart.currency']['value']) : $qty * ($price * $_SESSION['Cart.currency']['value']);
            debug($_SESSION);
        }

    }



    /*Array
    (
        [1] => Array
            (
                [qty] => QTY
                [name] => NAME
                [price] => PRICE
                [img] => IMG
            )
        [10] => Array
            (
                [qty] => QTY
                [name] => NAME
                [price] => PRICE
                [img] => IMG
            )
        )
        [qty] => QTY,
        [sum] => SUM
    */
