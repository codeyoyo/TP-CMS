<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;

class AdminController extends CommonController{

    public function index(){
        echo C('DB_TYPE');
    }
}
?>