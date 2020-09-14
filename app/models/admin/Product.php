<?php

namespace app\models\admin;

use app\models\AppModel;
use RedBeanPHP\R;

class Product extends AppModel {

    public $attributes = [
      'title' => '',
      'category_id' => '',
      'keywords' => '',
      'description' => '',
      'price' => '',
      'old_price' => '',
      'content' => '',
      'status' => '',
      'hit' => '',
      'alias' => ''
    ];

    public $rules = [
      'required' => [
          ['title'],
          ['category_id'],
          ['price']
      ],
      'integer' => [
          ['category_id']
      ]
    ];

    public function editRelatedProduct($id, $data)
    {
        $related_product = R::getCol('SELECT related_id FROM related_product WHERE product_id = ?', [$id]);

        // если менеджер убрал связанные товары - удаляем их
        if(empty($data['related']) && !empty($related_product))
        {
            R::exec("DELETE FROM related_product WHERE product_id = ?", [$id]);
            return;
        }
        // если добавляются связанные товары
        if(empty($related_product) && !empty($data['related']))
        {
            $sql_part = '';
            foreach($data['related'] as $v){
                $v = (int)$v;
                $sql_part .= "($id, $v),";
            }
            $sql_part = rtrim($sql_part, ',');
            R::exec("INSERT INTO related_product (product_id, related_id) VALUES $sql_part");
            return;
        }
        // если изменились связанные товары - удалим и запишем новые
        if(!empty($data['related']))
        {
            $result = array_diff($related_product, $data['related']);
            if(!empty($result) || count($related_product) != count($data['related']))
            {
                R::exec("DELETE FROM related_product WHERE product_id = ?", [$id]);
                $sql_part = '';
                foreach($data['related'] as $v)
                {
                    $v = (int)$v;
                    $sql_part .= "($id, $v),";
                }
                $sql_part = rtrim($sql_part, ',');
                R::exec("INSERT INTO related_product (product_id, related_id) VALUES $sql_part");
            }
        }
    }

    public function editFilter($id, $data)
    {
        $filter = R::getCol('SELECT attr_id FROM attribute_product WHERE product_id = ?', [$id]);

        // 1. if there are no filters in form
        if(empty($data['attrs']) && !empty($filter))
        {
            R::exec('DELETE FROM attribute_product WHERE product_id = ?', [$id]);
            return;
        }

        // 2. if added new filters
        if(empty($filter) && !empty($data['attrs']))
        {
            $sql_part = '';
            foreach ($data['attrs'] as $attr)
            {
                $sql_part .= "($attr, $id),";
            }

            $sql_part = rtrim($sql_part, ',');
            R::exec("INSERT INTO attribute_product (attr_id, product_id) VALUES {$sql_part}");
            return;
        }

        // 3. if filters changed in form
        if(!empty($data['attrs']))
        {
            $result = array_diff($filter, $data['attrs']);

            if($result)
            {
                R::exec('DELETE FROM attribute_product WHERE product_id = ?', [$id]);
                $sql_part = '';
                foreach ($data['attrs'] as $attr)
                {
                    $sql_part .= "($attr, $id),";
                }

                $sql_part = rtrim($sql_part, ',');
                R::exec("INSERT INTO attribute_product (attr_id, product_id) VALUES {$sql_part}");
                return;
            }
        }

    }
}