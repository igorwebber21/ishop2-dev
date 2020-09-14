<?php


namespace app\controllers\admin;


use app\models\admin\Product;
use app\models\AppModel;
use ishop\libs\Pagination;
use RedBeanPHP\R;

class ProductController extends AppController
{
    public function indexAction()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perpage = 15;
        $count = R::count('product');
        $pagination = new Pagination($page, $perpage, $count);
        $start = $pagination->getStart();
        $products = R::getAll("SELECT product.*, category.title AS cat FROM product 
                                     JOIN category ON category.id = product.category_id 
                                     ORDER BY product.id DESC LIMIT $start, $perpage");
        $this->setMeta('Список товаров');
        $this->set(compact('products', 'pagination', 'count'));
    }

    public function addAction()
    {
        if(!empty($_POST))
        {
            $product = new Product();
            $data = $_POST;
            $product->load($data);
            $product->attributes['status'] = $product->attributes['status'] ? 'visible' : 'hidden';
            $product->attributes['hit'] = $product->attributes['hit'] ? 'yes' : 'no';

            if(!$product->validate($data)){
                $product->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }

            if($id = $product->save('product'))
            {
                $alias = AppModel::createAlias('product', 'alias', $data['title'], $id);
                $loadedProduct = R::load('product', $id);
                $loadedProduct->alias = $alias;
                R::store($loadedProduct);
                $product->editFilter($id, $data);
                $product->editRelatedProduct($id, $data);
                $_SESSION['success'] = 'Товар добавлен';
            }
            redirect();
        }

        $this->setMeta('Новый товар');
    }

    public function deleteAction()
    {
        $product_id = $this->getRequestID();
        $product = R::load('product', $product_id);
        R::trash($product);
        R::exec('DELETE FROM attribute_product WHERE product_id = ?', [$product_id]);
        R::exec('DELETE FROM related_product WHERE product_id = ?', [$product_id]);

        $_SESSION['success'] = 'Товар удален';
        redirect(ADMIN . '/product');
    }

    public function relatedProductAction()
    {
        /*$data = [
            'items' => [
                [
                    'id' => 1,
                    'text' => 'Товар 1',
                ],
                [
                    'id' => 2,
                    'text' => 'Товар 2',
                ],
            ]
        ];*/

        $q = isset($_GET['q']) ? $_GET['q'] : '';
        $data['items'] = [];
        $products = R::getAssoc('SELECT id, title FROM product WHERE title LIKE ? LIMIT 10', ["%{$q}%"]);
        if($products)
        {
            $i = 0;
            foreach($products as $id => $title){
                $data['items'][$i]['id'] = $id;
                $data['items'][$i]['text'] = $title;
                $i++;
            }
        }
        echo json_encode($data);
        die;
    }

}