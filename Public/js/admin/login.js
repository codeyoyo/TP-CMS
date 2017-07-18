var login={
	check:function(){
		//获取登录页面中的用户名 和 密码
		var username=$('input[name="username"]').val(),
			password=$('input[name="password"]').val();
		if(!username){
			dialog.error('用户名不能为空');
		}
		if(!password){
			dialog.error('密码不能为空');
		}
		
		var url="/index.php?m=admin&c=login&a=check",
			data={
				"username":username,
				"password":password
			};
		var load = dialog.load();
		$.post(url,data,function(result){
			layer.close(load);
			if(result.status==0){
				return dialog.error(result.message);
			}
			if(result.status==1){
				return dialog.success(result.message,'/admin.php?c=index');
			}
		},'JSON');
	}
}
