<?php
namespace Common\Model;
use Think\Model;

class MenuModel extends Model{
	private $_db='';
	
	public function __construct(){
		$this->_db=M("menu");
	}
	
	/**
	 * 插入菜单数据
	 */
	public function insert($data=array()){
		if(!data || !is_array($data)){
			return 0;
		}
		
		return $this->_db->add($data);
	}
	
	/**
	 * 获取菜单数据
	 */
	public function getMenus($data,$pageIndex,$pageSize=10){
		$data['status']=array('neq',-1);
		$offset=($pageIndex-1)*$pageSize;
		$list=$this->_db->where($data)->order('listorder desc,menu_id desc')->limit($offset,$pageSize);
		return $list;
	}
	
	/**
	 * 获取菜单总数
	 */
	public function getMenusCount($data=array()){
		$data['status']=array('neq',-1);
		return $this->_db->where($data)->count();
	}
	
	/**
	 * 根据ID获取菜单ID
	 */
	public function find($id){
		if(!$id || !is_numeric($id)){
			return array();
		}
		return $this->_db->where("menu_id={}$id")->find();
	}
	
	/**
	 * 根据ID更新菜单
	 */
	public function updateMenuById($id,$data){
		if(!$id || !is_numeric($id)){
			throw_exception("ID不合法");
		}
		
		if(!$data || !is_array($data)){
			throw_exception('更新的数据不合法');
		}
		
		return $this->_db->where("menu_id={$id}")->save($data);
	}
	
	/**
	 * 更新排队序号
	 */
	public function updateMenuListOrderById($id,$listorder){
		if(!$id || !is_numeric($id)){
			throw_exception('ID不合法');
		}
		$data=array(
			'listorder'=>intval($listorder);
		);
		
		return $this->_db->where("menu_id={$id}")->save($data);
	}
	
	/**
	 * 获取后台菜单
	 */
	public function getAdminMenus(){
		$data=array(
			'status'=>array('neq',-1),
			'type'=>1
		);
		
		return $this->_db->where($data)->order('listorder desc,menu_id desc')->select();
	}
	
	/**
	 * 获取前台菜单
	 */
	public function getBarMenus(){
		$data=array(
			'status'=>1,
			'type'=>0
		);
		return $this->_db->where($data)->order('listordre desc,menu_id desc')->select();
	}
}
?>