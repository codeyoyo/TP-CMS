<?php
namespace Admin\Controller;
use Think\Controller;

class IndexController extends CommonController{

    public function index(){
        echo C('DB_TYPE');
    }
}
?>