<?php
namespace Admin\Controller;
use Think\Controller;

class IndexController extends Controller{

    public function index(){
        echo C('DB_TYPE');
    }
}
?>