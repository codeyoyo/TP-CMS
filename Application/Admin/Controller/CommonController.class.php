<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * 后台管理公共控制器
 */
class CommonController extends Controller{
	public function __construct(){
		parent::__construct();
		$this->_init();
	}
	
	/**
	 * 初始化
	 */
	private function _init(){
		// 如果已经登录
		$isLogin=$this->isLogin();
		if(!$isLogin){
			//跳转到登录页面
			$this->redirect('/admin.php?c=login');
		}
	}
	
	/**
	 * 获取当前登录用户信息
	 */
	public function getLoginUser(){
		return session('adminUser');
	}
	
	/**
	 * 判断是否登录
	 */
	public function isLogin(){
		$user=$this->getLoginUser();
		return ($user && is_array($user));
	}
	
	/**
	 * 更新数据状态
	 */
	public function setStatus($data,$models){
		try{
			if($_POST){
				$id=$data['id'];
				$status=$data['status'];
				if(!$id){
					return jsonResult(0, 'ID不存在');
				}
				$ret=D($models)->updateStatusById($id,$status);
				if($ret){
					return jsonResult(1, '操作成功');
				}else{
					return jsonResult(0, '操作失败');
				}
			}
			return jsonResult(0,'没有提交的内容');
		}catch(Exception $ex){
			return jsonResult(0, $e->getMessage());
		}
	}
	
	/**
	 * 数据排序
	 */
	public function listorder($model=''){
		$listorder=$_POST['listorder'];
		$jumpUrl=$_SERVER['HTTP_REFERER'];
		$errors=array();
		$resultData=array('jump_url'=>$jumpUrl);
		try{
			if($listorder){
				foreach($listorder as $id=>$v){
					$id=D($model)->updateListorderById($id,$v);
					if($id===FALSE){
						$errors[]=$id;
					}
				}
				if(array_count_values($errors)>0){
					$group=implode(',', $errors);
					return jsonResult(0, "排序失败-{$group}", $data,$resultData);
				}
				return jsonResult(1, '排序成功', $resultData);
			}
		}catch(Exception $ex){
			return jsonResult(0, $ex->getMessage());
		}
		return jsonResult(0, '排序失败', $resultData);
	}
}
?>