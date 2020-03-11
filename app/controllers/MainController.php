<?php

    namespace app\controllers;

    use ishop\App;
    use RedBeanPHP\R as R;
    use ishop\Cache;

    class MainController extends AppController {

//    public $layout = 'test';

        public function indexAction(){
//        $this->layout = 'test';
//        echo __METHOD__;

            $brands = R::find('brand', 'LIMIT 3');

            $hits = R::find('product', "hit = '1' AND status = '1' LIMIT 8");

            //current currency
            $curr = App::$app->getProperty('currency');

            //debug($hits);
            $this->set(compact('brands', 'hits', 'curr'));

           // $this->setMeta(App::$app->getProperty("shop_name"), "Описание", "Ключевики");

          /*  $names = array('Mike', 'john');
            $name = 'Andrey';
            $age = 33;

            $cache = Cache::instance();
           // $cache->set('test', $names);
            $data = $cache->get('test');
           // debug($data);
*/
            //$this->set(compact('name','age', 'names'));
        }

    }