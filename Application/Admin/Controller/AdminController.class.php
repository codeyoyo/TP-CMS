<?php
namespace Admin\Controller;
use Think\Controller;

class AdminController extends Controller{

    public function index(){
        echo C('DB_TYPE');
    }
}
?>