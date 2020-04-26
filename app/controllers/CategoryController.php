<?php


    namespace app\controllers;


    use app\models\Breadcrumbs;
    use app\models\Category;
    use ishop\App;
    use RedBeanPHP\R;
    use ishop\libs\Pagination;

    class CategoryController extends  AppController
    {
        public function viewAction(){

            $alias = $this->route['alias'];
            $category = R::findOne('category', 'alias = ?', [$alias]);
           // debug($category);

            if(!$category){
                throw new \Exception('Страница не найдена', 404);

            }


            // breadcrumbs
            $breadcrumbs = Breadcrumbs::getBreadcrumbs($category->id);
            $cat_model = new Category();
            $ids = $cat_model->getIds($category->id);
            $ids = $ids ? ($ids . $category->id) : $category->id;

            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perpage = App::$app->getProperty('pagination');
            $total = R::count('product', "category_id IN ($ids)");
            $pagination = new Pagination($page, $perpage, $total);
            $start =  $pagination->getStart();

            $products = R::find('product', "category_id IN ($ids) LIMIT $start, $perpage");
            $this->setMeta($category->title, $category->description, $category->keywords);
            $this->set(compact('products', 'breadcrumbs', 'pagination', 'total'));
           //die();

        }
    }