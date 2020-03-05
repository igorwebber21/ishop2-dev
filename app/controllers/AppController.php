<?php

    namespace app\controllers;

    use app\models\AppModel;
    use ishop\base\Controller;
    use RedBeanPHP\R as R;

    class AppController extends Controller{

        public function __construct($route)
        {
            parent::__construct($route);
            new AppModel();
        }
    }