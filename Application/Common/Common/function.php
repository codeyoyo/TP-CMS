<?php

/**
 * JSON数据返回
 */
function jsonResult($status,$message,$data){
	$result=array(
		'status'=>$status,
		'message'=>$message,
		'data'=>$data
	);
	exit(json_encode($result));
}

/**
 * MD5加密密码
 */
function getMd5Password($password){
	return md5($password.C('MD5_PRE'));
}

/**
*获取导航菜单
*/
function getMenuType($type){
	return $type==1?'后台菜单':'前端导航';
}

/**
*获取状态
*/
function status($status){
	if($status==0){
		$str='关闭';
	}elseif($status==1){
		$str='正常';
	}elseif($status==-1){
		$str='删除';
	}
	return $str;
}

/**
*获取后台菜单URL地址
*/
function getAdminMenuUrl($nav){
	$url='/admin.php?c='.$nav['c'].'&a='.$nav['a'];
	if($nav['f']=='index'){
		$url="/admin.php?c=".$nav['c'];
	}
	return $url;
}

/**
*获取控制器
*/
function getActive($nav_controller){
	$controller=strtolower(CONTROLLER_NAME);
	if(strtolower($nav_controller)==$controller){
		return 'class="active"';
	}
	return '';
}

/**
*文件上传结果返回
*/
function showKind($status,$data){
	header('Content-type:application/json;charset=UTF-8');
	if($status==0){
		exit(json_encode(array('error'=>0,'url'=>$data)));
	}
	exit(json_encode(array('error'=>1,'message'=>'上传失败')));
}

/**
*获取登录用户名
*/
function getLoginUsername(){
	return $_SESSION['adminUser']['username']?$_SESSION['adminUser']['username']:'';
}

/**
*获取菜单名
*/
function getCatName($navs,$id){
	foreach($navs as $nav){
		$navList[$nav['menu_id']]=$nav['name'];
	}
	return isset($navList[$id])?$navList[$id]:'';
}


function getCopyFromById($id){
	$copyFrom=C("COPY_FORM");
	return $copyFrom[$id]?$copyFrom[$id]:'';
}

function isThumb($thumb){
	if($thumb){
		return '<span style="color:red">有</span>';
	}
	return '无';
}

/**
*文章截取预览
*/
function msubstr($str,$start=0,$length,$charset='utf-8',$suffix=true){
	$len=strlen($str);
	if(function_exists('mb_substr')){
		if($suffix){
			return mb_substr($str,$start,$length,$charset).'...';
		}else{
			return mb_substr($str,$start,$length,$charset);
		}
	}elseif(function_exists('iconv_substr')){
			if($suffix && $len>$length){
				return mb_substr($str,$start,$length,$charset).'...';
			}else{
				return mb_substr($str,$start,$length,$charset);
			}
	}
	$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset],$str,$match);
	$slice=join("",array_slice($match[0],$start,$length));
	if($suffix){
		return $slice.'...';
	}
	return $slice;
}
?>