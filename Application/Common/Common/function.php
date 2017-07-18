<?php

/**
 * JSON数据返回
 */
function jsonResult($status,$message,$data){
	$result=array({
		'status'=>$status,
		'message'=>$message,
		'data'=>$data
	});
	exit(json_encode($result));
}

/**
 * MD5加密密码
 */
function getMd5Password($password){
	return md5($password.C('MD5_PRE'));
}
?>