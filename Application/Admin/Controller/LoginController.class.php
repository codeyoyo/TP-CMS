<?php
namespace Admin\Controller;
use Think\Controller;

class LoginController extends Controller{
	public function index(){
		if(session('adminUser')){
			$this->redirect('/admin.php?c=index');
		}
		$this->display();
	}
	
	/**
	 * 检测登录信息
	 */
	public function check(){
		$username=$_POST['username'];
		$password=$_POST['password'];
		if(!trim($username)){
			return jsonResult(0, '用户名不能为空');
		}
		if(!trim($password)){
			return jsonResult(0, '密码不能为空');
		}
		
		$ret=D('Admin')->getAdminByUsername($username);
		if(!ret || $ret['status']!=1){
			return jsonResult(0, '该用户不存在');
		}
		
		if($ret['password']!=getMd5Password($password)){
			return jsonResult(0, '用户名或密码错误');
		}
		
		D("Admin")->updateByAdminId($ret['admin_id'],array('last_login_time'=>time()));
		session('adminUser',$ret);
		return jsonResult(1, '登录成功');
	}
	
	/**
	 * 退出登录
	 */
	public function loginout(){
		session('adminUser',null);
		$this->redirect('/admin.php?c=login');
	}
}
?>