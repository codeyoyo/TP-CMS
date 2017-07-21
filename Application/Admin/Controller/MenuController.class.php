<?php
namespace Admin\Controller;
use Think\Controller;

class MenuController extends  CommonController{
	
	public function index(){
		$data=array();
		if(isset($_REQUEST['type']) && (in_array(0,$_REQUEST) || in_array(1,$_REQUEST))){
			$data['type']=intval($_REQUEST['type']);
			$this->assign('type',$data['type']);
		}else{
			$this->assign('type',-100);
		}
		$page=$_REQUEST['p']?$_REQUEST['p']:1;
		$pageSize=$_REQUEST['pageSize']?$_REQUEST['pageSize']:3;
		$menus=D('Menu')->getMenus($data,$page,$pageSize);
		$menusCount=D('Menu')->getMenusCount($data);
		$res=new \Think\Page($menusCount,$pageSize);
		$pageRes=$res->show();
		$this->assign('pageRes',$pageRes);
		$this->assign('menus',$menus);
		$this->display();
	}
	
	public function add(){
		if($_POST){
			if(!isset($_POST['name']) || !$_POST['name']){
				return jsonResult(0, '菜单名不能为空');
			}
			if(!isset($_POST['m']) || !$_POST['m']){
				return jsonResult(0, '模块名不能为空');
			}
			if(!isset($_POST['c']) || !$_POST['c']){
				return jsonResult(0, '控制器不能为空');
			}
			if(!isset($_POST['f']) || !$_POST['f']){
				return jsonResult(0, '方法名不能为空');
			}
			if($_POST['menu_id']){
				return $this->save($_POST);
			}
			$menuId=D("Menu")->insert($_POST);
			if($menuId){
				return jsonResult(1, '新增成功', $menuId);
			}
			return jsonResult(0, '新增失败', $menuId);
		}else{
			$this->display();
		}
	}
	
	public function edit(){
		$menuId=$_REQUEST['id'];
		$menu=D("Menu")->find($menuId);
		$this->assign('menu',$menu);
		$this->display();		
	}
	
	public function save($data){
		$menuId=$data['menu_id'];
		unset($data['menu_id']);
		
		try{
			$id=D("Menu")->updateMenuById($menuid,$data);
			if($id===FALSE){
				return jsonResult(0, '保存失败');
			}
			return jsonResult(0,'保存成');
		}catch(Exception $ex){
			return jsonResult(0,$ex->getMessage());
		}
	}
	
	public function setStatus(){
		try{
			if($_POST){
				$id=$_POST['id'];
				$status=$_POST['status'];
				$ret=D("Menu")->updateStatusById($id,$status);
				if($ret){
					return jsonResult(1,'操作成功');
				}else{
					return jsonResult(0,'操作失败');
				}
			}
		}catch(Exception $ex){
			return jsonResult(0,$ex->getMessage());
		}
		return jsonResult(0,'没有提交数据');
	}
	
	/**
	 * 数据排序
	 */
	public function listorder(){
		$listoreder=$_POST['listorder'];
		$data =array('jump_url'=> $_SERVER['HTTP_REFERER']);
		$errors=array();
		if($listoreder){
			try{
				foreach($listorder as $emnuId=>$v){
					$id=D("Menu")->updateMenuListorderById($menuId,$v);
					if($id===false){
						$errors[]=$menuId;
					}
				}
			}catch(Exception $ex){
				return jsonResult(0, $ex->getMessage(), $data);
			}
			if($errors){
				return jsonResult(0,"排序失败-".implode(',', $errors), $data);
			}
			return jsonResult(1, '排序成功', $data);
		}
		return jsonResult(0,'数据排序失败', $data);
	}
}
?>