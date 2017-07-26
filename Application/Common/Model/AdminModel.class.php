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
		$ret=$this->_db->where("username='{$username}'")->find();
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
		return $this->_db->where("admin_id={$id}")->save($data);
	}

	public function insert($data=array()){
		if(!$data || !is_array($data)){
			return 0;
		}
		return $this->_db->add($data);
	}

	public function getAdmins(){
		$data=array(
			'status'=>array('neq',-1)
		);
		return $this->_db->where($data)->order('admin_id desc')->select();
	}

	public function updateStatusById($id,$status){
		if(!is_numeric($status)){
			throw_exception('status不能为非数字');
		}
		if(!$id || !is_numeric($id)){
			throw_exception('ID不合法');
		}
		$data['status']=$status;
		return $this->_db->where('admin_id='.$id)->save($data);
	}

	public function getLastLoginUsers(){
		$thime=mktime(0,0,0,date('m'),date('d'),date('Y'));
		$data=array(
			'status'=>1,
			'lastlogintime'=>array('gt',$time)
		);
		$res=$this->_db->where($data)->count();
		return $res['tp_count'];
	}

	public function updatePassword($username,$password){
		$data=array(
			'password'=>$password
		);
		return $this->_db->where("username='{$username}'")->save($data);
	}
}
?>