<?php


    namespace ishop\base;

    use ishop\DB;

    abstract class Model
    {
        public $attributes = [];
        public $errors = [];
        public $rules = [];

        public function __construct()
        {
            DB::instance();
        }
    }