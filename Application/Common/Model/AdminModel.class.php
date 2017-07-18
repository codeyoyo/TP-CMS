<?php
namespace Common\Model;
use Think\Model;

/**
 * 后台用户操作类
 */
class AdminModel extends Model{
	private $_db=null;
	
	public function __construct(){
		$this->_db=M('admin');
	}
	
	/**
	 * 根据用户名获取用户信息
	 * $username string 用户名
	 */
	public function getAdminByUserName($username=''){
		$ret=$this->_db->where("user_name='{$username}'")->find();
		return $ret;
	}
	
	/**
	 * 根据adminid更新数据
	 * $id int id
	 * $data object 需更新的数据
	 */
	public function updateByAdminId($id,$data){
		if(!$id || !is_numeric($id)){
			throw_exception("ID不合法");
		}
		if(!$data || !is_array($data)){
			throw_exception('更新的数据不合法');
		}
		return $this->_db->where("admin_id={$id}").save($data);
	}
}
?>